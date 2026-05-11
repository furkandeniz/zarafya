<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Notifications\AdminCreatedUserVerification;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('store')
            ->where('role', '!=', 'enduser')
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        $users  = $query->paginate(15)->withQueryString();
        $stores = Store::orderBy('name')->get();

        return view('admin.pages.users.index', compact('users', 'stores'));
    }

    public function create()
    {
        $stores = Store::orderBy('name')->get();
        return view('admin.pages.users.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,seller,customer',
            'store_id' => 'nullable|exists:stores,id',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role, // enduser asla admin panelden oluşturulamaz
            'store_id' => $request->store_id,
        ]);

        $user->notify(new AdminCreatedUserVerification());

        return redirect()->route('admin.users.index')
            ->with('success', '"' . $user->name . '" kullanıcısı oluşturuldu. Doğrulama e-postası gönderildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $user   = User::findOrFail($id);
        $stores = Store::orderBy('name')->get();
        return view('admin.pages.users.edit', compact('user', 'stores'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:admin,seller,customer',
            'store_id' => 'nullable|exists:stores,id',
        ]);

        $user->name     = $request->name; // enduser rolü admin panelden güncellenemez
        $user->email    = $request->email;
        $user->role     = $request->role;
        $user->store_id = $request->store_id;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', '"' . $user->name . '" kullanıcısının bilgileri başarıyla güncellendi.');
    }

    public function resendVerification(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('admin.users.index')
                ->with('info', '"' . $user->name . '" kullanıcısının e-postası zaten doğrulanmış.');
        }

        $user->notify(new AdminCreatedUserVerification());

        return redirect()->route('admin.users.index')
            ->with('success', '"' . $user->name . '" kullanıcısına doğrulama maili gönderildi.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', '"' . $name . '" kullanıcısı başarıyla silindi.');
    }
}
