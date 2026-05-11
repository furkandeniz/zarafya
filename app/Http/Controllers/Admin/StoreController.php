<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::latest()->paginate(15);
        return view('admin.pages.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('admin.pages.stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:stores,name',
            'description' => 'nullable|string|max:1000',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:30',
            'address'     => 'nullable|string|max:500',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('stores', 'public');
        }

        $store = Store::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'logo'        => $logoPath,
            'description' => $request->description,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.stores.index')
            ->with('success', '"' . $store->name . '" mağazası başarıyla oluşturuldu.');
    }

    public function edit(string $id)
    {
        $store = Store::findOrFail($id);
        return view('admin.pages.stores.edit', compact('store'));
    }

    public function update(Request $request, string $id)
    {
        $store = Store::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:stores,name,' . $store->id,
            'description' => 'nullable|string|max:1000',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:30',
            'address'     => 'nullable|string|max:500',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $store->logo = $request->file('logo')->store('stores', 'public');
        }

        $store->name        = $request->name;
        $store->slug        = Str::slug($request->name);
        $store->description = $request->description;
        $store->email       = $request->email;
        $store->phone       = $request->phone;
        $store->address     = $request->address;
        $store->is_active   = $request->boolean('is_active');
        $store->save();

        return redirect()->route('admin.stores.index')
            ->with('success', '"' . $store->name . '" mağazası başarıyla güncellendi.');
    }

    public function destroy(string $id)
    {
        $store = Store::findOrFail($id);

        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }

        $name = $store->name;
        $store->delete();

        return redirect()->route('admin.stores.index')
            ->with('success', '"' . $name . '" mağazası başarıyla silindi.');
    }
}
