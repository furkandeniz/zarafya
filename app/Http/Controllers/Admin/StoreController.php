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
        $user = auth()->user();
        if ($user->isSeller()) {
            return redirect()->route('admin.stores.edit', $user->store_id);
        }
        $stores = Store::latest()->paginate(15);
        return view('admin.pages.stores.index', compact('stores'));
    }

    public function create()
    {
        if (auth()->user()->isSeller()) {
            abort(403);
        }
        return view('admin.pages.stores.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->isSeller()) {
            abort(403);
        }
        $request->validate([
            'name'           => 'required|string|max:255|unique:stores,name',
            'description'    => 'nullable|string|max:1000',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:30',
            'address'        => 'nullable|string|max:500',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'      => 'boolean',
            'bank_name'      => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'iban'           => ['nullable', 'string', 'max:32', 'regex:/^TR[0-9]{24}$/'],
            'account_number' => 'nullable|string|max:50',
            'branch_name'    => 'nullable|string|max:255',
            'branch_code'    => 'nullable|string|max:20',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('stores', 'public');
        }

        $store = Store::create([
            'name'           => $request->name,
            'slug'           => Str::slug($request->name),
            'logo'           => $logoPath,
            'description'    => $request->description,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'is_active'      => $request->boolean('is_active', true),
            'bank_name'      => $request->bank_name,
            'account_holder' => $request->account_holder,
            'iban'           => strtoupper(str_replace(' ', '', $request->iban ?? '')),
            'account_number' => $request->account_number,
            'branch_name'    => $request->branch_name,
            'branch_code'    => $request->branch_code,
        ]);

        return redirect()->route('admin.stores.index')
            ->with('success', '"' . $store->name . '" mağazası başarıyla oluşturuldu.');
    }

    public function edit(string $id)
    {
        $user  = auth()->user();
        $store = Store::findOrFail($id);

        if ($user->isSeller() && $store->id !== $user->store_id) {
            abort(403);
        }

        return view('admin.pages.stores.edit', compact('store'));
    }

    public function update(Request $request, string $id)
    {
        $user  = auth()->user();
        $store = Store::findOrFail($id);

        if ($user->isSeller() && $store->id !== $user->store_id) {
            abort(403);
        }

        $request->validate([
            'name'           => 'required|string|max:255|unique:stores,name,' . $store->id,
            'description'    => 'nullable|string|max:1000',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:30',
            'address'        => 'nullable|string|max:500',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'      => 'boolean',
            'bank_name'      => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'iban'           => ['nullable', 'string', 'max:32', 'regex:/^TR[0-9]{24}$/'],
            'account_number' => 'nullable|string|max:50',
            'branch_name'    => 'nullable|string|max:255',
            'branch_code'    => 'nullable|string|max:20',
        ]);

        if ($request->hasFile('logo')) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
            $store->logo = $request->file('logo')->store('stores', 'public');
        }

        $store->name           = $request->name;
        $store->slug           = Str::slug($request->name);
        $store->description    = $request->description;
        $store->email          = $request->email;
        $store->phone          = $request->phone;
        $store->address        = $request->address;
        $store->is_active      = $request->boolean('is_active');
        $store->bank_name      = $request->bank_name;
        $store->account_holder = $request->account_holder;
        $store->iban           = strtoupper(str_replace(' ', '', $request->iban ?? ''));
        $store->account_number = $request->account_number;
        $store->branch_name    = $request->branch_name;
        $store->branch_code    = $request->branch_code;
        $store->save();

        return redirect()->route('admin.stores.index')
            ->with('success', '"' . $store->name . '" mağazası başarıyla güncellendi.');
    }

    public function destroy(string $id)
    {
        if (auth()->user()->isSeller()) {
            abort(403);
        }

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
