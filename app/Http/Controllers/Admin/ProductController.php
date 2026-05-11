<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StockAvailableMail;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\StockNotification;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('firstImage', 'category', 'store', 'variants')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products   = $query->paginate(15)->withQueryString();
        $stores     = Store::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.pages.products.index', compact('products', 'stores', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $stores     = Store::orderBy('name')->get();
        return view('admin.pages.products.create', compact('categories', 'stores'));
    }

    public function store(Request $request)
    {
        $hasVariants = $request->boolean('has_variants');

        $request->validate([
            'name'            => 'required|string|max:255|unique:products,name',
            'category_id'     => 'nullable|exists:categories,id',
            'store_id'        => 'nullable|exists:stores,id',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'stock'           => $hasVariants ? 'nullable' : 'required|integer|min:0',
            'images'          => 'nullable|array|max:10',
            'images.*'        => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'attributes_json' => $hasVariants ? 'required|json' : 'nullable',
            'variants_json'   => $hasVariants ? 'required|json' : 'nullable',
        ]);

        $product = Product::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'category_id' => $request->category_id,
            'store_id'    => $request->store_id,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $hasVariants ? null : $request->stock,
        ]);

        if ($hasVariants) {
            $this->saveVariants($product, $request);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $product->images()->create([
                    'image' => $file->store('products', 'public'),
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', '"' . $product->name . '" ürünü başarıyla oluşturuldu.');
    }

    public function edit(string $id)
    {
        $product    = Product::with('images', 'attributes', 'variants')->findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $stores     = Store::orderBy('name')->get();

        $existingAttributes = $product->attributes->map(fn ($a) => [
            'name'   => $a->name,
            'values' => $a->values,
        ])->values();

        $existingVariants = $product->variants->map(fn ($v) => [
            'combination' => $v->combination,
            'label'       => $v->label,
            'stock'       => $v->stock,
            'price'       => $v->price,
        ])->values();

        return view('admin.pages.products.edit', compact(
            'product', 'categories', 'stores',
            'existingAttributes', 'existingVariants'
        ));
    }

    public function update(Request $request, string $id)
    {
        $product     = Product::findOrFail($id);
        $hasVariants = $request->boolean('has_variants');

        $request->validate([
            'name'            => 'required|string|max:255|unique:products,name,' . $product->id,
            'category_id'     => 'nullable|exists:categories,id',
            'store_id'        => 'nullable|exists:stores,id',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'stock'           => $hasVariants ? 'nullable' : 'required|integer|min:0',
            'images'          => 'nullable|array|max:10',
            'images.*'        => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'attributes_json' => $hasVariants ? 'required|json' : 'nullable',
            'variants_json'   => $hasVariants ? 'required|json' : 'nullable',
        ]);

        $product->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'category_id' => $request->category_id,
            'store_id'    => $request->store_id,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $hasVariants ? null : $request->stock,
        ]);

        $product->attributes()->delete();
        $product->variants()->delete();

        if ($hasVariants) {
            $this->saveVariants($product, $request);
        } else {
            // Varyantsız ürün: stok > 0 ise bekleyen bildirimleri gönder
            if ((int) $request->stock > 0) {
                $this->dispatchStockNotifications($product, null);
            }
        }

        if ($request->hasFile('images')) {
            $nextOrder = $product->images()->max('order') + 1;
            foreach ($request->file('images') as $index => $file) {
                $product->images()->create([
                    'image' => $file->store('products', 'public'),
                    'order' => $nextOrder + $index,
                ]);
            }
        }

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', '"' . $product->name . '" ürünü başarıyla güncellendi.');
    }

    private function saveVariants(Product $product, Request $request): void
    {
        $attributes = json_decode($request->attributes_json, true) ?? [];
        $variants   = json_decode($request->variants_json, true) ?? [];

        foreach ($attributes as $attr) {
            if (empty($attr['name']) || empty($attr['values'])) {
                continue;
            }
            $product->attributes()->create([
                'name'   => trim($attr['name']),
                'values' => array_values(array_filter(array_map('trim', (array) $attr['values']))),
            ]);
        }

        foreach ($variants as $variant) {
            if (!isset($variant['combination']) || !is_array($variant['combination'])) {
                continue;
            }
            $label = $variant['label'] ?? implode(' / ', $variant['combination']);
            $stock = max(0, (int) ($variant['stock'] ?? 0));

            $product->variants()->create([
                'combination' => $variant['combination'],
                'label'       => $label,
                'stock'       => $stock,
                'price'       => (isset($variant['price']) && $variant['price'] !== '' && $variant['price'] !== null)
                    ? (float) $variant['price']
                    : null,
            ]);

            // Varyant stoklu kaydedildiyse bekleyen bildirimleri gönder
            if ($stock > 0) {
                $this->dispatchStockNotifications($product, $label);
            }
        }
    }

    private function dispatchStockNotifications(Product $product, ?string $variantLabel): void
    {
        $query = StockNotification::where('product_id', $product->id)
            ->whereNull('notified_at');

        if ($variantLabel === null) {
            $query->whereNull('variant_label');
        } else {
            $query->where('variant_label', $variantLabel);
        }

        $query->each(function (StockNotification $notification) use ($product, $variantLabel) {
            try {
                Mail::to($notification->email)
                    ->send(new StockAvailableMail($product, $variantLabel));
                $notification->update(['notified_at' => now()]);
            } catch (\Throwable) {
                // Mail gönderilemezse sessizce geç, sonraki denemede tekrar dener
            }
        });
    }

    public function destroyImage(string $productId, string $imageId)
    {
        $image = ProductImage::where('product_id', $productId)->findOrFail($imageId);
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Fotoğraf silindi.');
    }

    public function destroy(string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', '"' . $name . '" ürünü başarıyla silindi.');
    }
}
