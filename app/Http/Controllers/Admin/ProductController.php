<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StockAvailableMail;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\StockNotification;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user    = auth()->user();
        $query   = Product::with('firstImage', 'category', 'store', 'variants')->latest();

        if ($user->isSeller()) {
            $query->where('store_id', $user->store_id);
        } else {
            if ($request->filled('store_id')) {
                $query->where('store_id', $request->store_id);
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products   = $query->paginate(15)->withQueryString();
        $stores     = $user->isSeller() ? collect() : Store::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.pages.products.index', compact('products', 'stores', 'categories'));
    }

    public function create()
    {
        $user       = auth()->user();
        $categories = Category::orderBy('name')->get();
        $stores     = $user->isSeller()
            ? Store::where('id', $user->store_id)->get()
            : Store::orderBy('name')->get();
        $commissionRate = (float) Setting::get('commission_rate', 15) / 100;
        return view('admin.pages.products.create', compact('categories', 'stores', 'commissionRate'));
    }

    public function store(Request $request)
    {
        $user        = auth()->user();
        $hasVariants = $request->boolean('has_variants');

        $request->validate([
            'name'            => 'required|string|max:255|unique:products,name',
            'category_id'     => 'nullable|exists:categories,id',
            'store_id'        => 'nullable|exists:stores,id',
            'description'     => 'nullable|string',
            'cost_price'      => 'nullable|numeric|min:0',
            'expected_price'  => $hasVariants ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'stock'           => $hasVariants ? 'nullable' : 'required|integer|min:0',
            'images'          => 'nullable|array|max:10',
            'images.*'        => 'image|mimes:jpg,jpeg,png,webp',
            'attributes_json' => $hasVariants ? 'required|json' : 'nullable',
            'variants_json'   => $hasVariants ? 'required|json' : 'nullable',
        ]);

        $commissionPrice = $this->resolveCommissionPrice($hasVariants, $request);

        $product = Product::create([
            'name'           => $request->name,
            'slug'           => Str::slug($request->name),
            'category_id'    => $request->category_id,
            'store_id'       => $user->isSeller() ? $user->store_id : $request->store_id,
            'description'    => $request->description,
            'cost_price'     => $hasVariants ? null : $request->cost_price,
            'expected_price' => $hasVariants ? null : $request->expected_price,
            'price'          => $commissionPrice,
            'stock'          => $hasVariants ? null : $request->stock,
        ]);

        if ($hasVariants) {
            $this->saveVariants($product, $request);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $product->images()->create([
                    'image' => $this->storeResized($file),
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', '"' . $product->name . '" ürünü başarıyla oluşturuldu.');
    }

    public function edit(string $id)
    {
        $user    = auth()->user();
        $product = Product::with('images', 'attributes', 'variants')->findOrFail($id);

        if ($user->isSeller() && $product->store_id !== $user->store_id) {
            abort(403);
        }

        $categories = Category::orderBy('name')->get();
        $stores     = $user->isSeller()
            ? Store::where('id', $user->store_id)->get()
            : Store::orderBy('name')->get();

        $existingAttributes = $product->attributes->map(fn ($a) => [
            'name'   => $a->name,
            'values' => $a->values,
        ])->values();

        $existingVariants = $product->variants->map(fn ($v) => [
            'combination'    => $v->combination,
            'label'          => $v->label,
            'stock'          => $v->stock,
            'cost_price'     => $v->cost_price,
            'expected_price' => $v->expected_price,
            'price'          => $v->price,
        ])->values();

        $commissionRate = (float) Setting::get('commission_rate', 15) / 100;
        return view('admin.pages.products.edit', compact(
            'product', 'categories', 'stores',
            'existingAttributes', 'existingVariants', 'commissionRate'
        ));
    }

    public function update(Request $request, string $id)
    {
        $user        = auth()->user();
        $product     = Product::findOrFail($id);
        $hasVariants = $request->boolean('has_variants');

        if ($user->isSeller() && $product->store_id !== $user->store_id) {
            abort(403);
        }

        $request->validate([
            'name'            => 'required|string|max:255|unique:products,name,' . $product->id,
            'category_id'     => 'nullable|exists:categories,id',
            'store_id'        => 'nullable|exists:stores,id',
            'description'     => 'nullable|string',
            'cost_price'      => 'nullable|numeric|min:0',
            'expected_price'  => $hasVariants ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'stock'           => $hasVariants ? 'nullable' : 'required|integer|min:0',
            'images'          => 'nullable|array|max:10',
            'images.*'        => 'image|mimes:jpg,jpeg,png,webp',
            'attributes_json' => $hasVariants ? 'required|json' : 'nullable',
            'variants_json'   => $hasVariants ? 'required|json' : 'nullable',
        ]);

        $commissionPrice = $this->resolveCommissionPrice($hasVariants, $request);

        $product->update([
            'name'           => $request->name,
            'slug'           => Str::slug($request->name),
            'category_id'    => $request->category_id,
            'store_id'       => $user->isSeller() ? $user->store_id : $request->store_id,
            'description'    => $request->description,
            'cost_price'     => $hasVariants ? null : $request->cost_price,
            'expected_price' => $hasVariants ? null : $request->expected_price,
            'price'          => $commissionPrice,
            'stock'          => $hasVariants ? null : $request->stock,
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
                    'image' => $this->storeResized($file),
                    'order' => $nextOrder + $index,
                ]);
            }
        }

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', '"' . $product->name . '" ürünü başarıyla güncellendi.');
    }

    private function resolveCommissionPrice(bool $hasVariants, Request $request): float
    {
        $rate = (float) Setting::get('commission_rate', 15) / 100;

        if (!$hasVariants) {
            return round((float) $request->expected_price * (1 + $rate), 2);
        }

        $variants = json_decode($request->variants_json ?? '[]', true) ?? [];
        $prices   = array_filter(
            array_map(fn ($v) => isset($v['expected_price']) && $v['expected_price'] !== '' ? (float) $v['expected_price'] : null, $variants),
            fn ($p) => $p !== null
        );

        return !empty($prices)
            ? round(min($prices) * (1 + $rate), 2)
            : 0;
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
            $label         = $variant['label'] ?? implode(' / ', $variant['combination']);
            $stock         = max(0, (int) ($variant['stock'] ?? 0));
            $costPrice     = isset($variant['cost_price']) && $variant['cost_price'] !== '' ? (float) $variant['cost_price'] : null;
            $expectedPrice = isset($variant['expected_price']) && $variant['expected_price'] !== '' ? (float) $variant['expected_price'] : null;
            $rate          = (float) Setting::get('commission_rate', 15) / 100;
            $commPrice     = $expectedPrice !== null ? round($expectedPrice * (1 + $rate), 2) : null;

            $product->variants()->create([
                'combination'    => $variant['combination'],
                'label'          => $label,
                'stock'          => $stock,
                'cost_price'     => $costPrice,
                'expected_price' => $expectedPrice,
                'price'          => $commPrice,
            ]);

            if ($stock > 0) {
                $this->dispatchStockNotifications($product, $label);
            }
        }
    }

    private function storeResized(\Illuminate\Http\UploadedFile $file): string
    {
        $filename = 'products/' . uniqid() . '.jpg';
        $encoded  = (new ImageManager(new Driver()))
            ->decode($file->getRealPath())
            ->scaleDown(width: 1920, height: 1920)
            ->encode(new JpegEncoder(82));
        Storage::disk('public')->put($filename, (string) $encoded);
        return $filename;
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
        $user    = auth()->user();
        $product = Product::findOrFail($productId);

        if ($user->isSeller() && $product->store_id !== $user->store_id) {
            abort(403);
        }

        $image = ProductImage::where('product_id', $productId)->findOrFail($imageId);
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Fotoğraf silindi.');
    }

    public function destroy(string $id)
    {
        $user    = auth()->user();
        $product = Product::with('images')->findOrFail($id);

        if ($user->isSeller() && $product->store_id !== $user->store_id) {
            abort(403);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', '"' . $name . '" ürünü başarıyla silindi.');
    }
}
