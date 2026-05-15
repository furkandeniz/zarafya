<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user         = auth()->user();
        $recentOrders = $user->orders()->latest()->limit(5)->get();
        $totalOrders  = $user->orders()->count();

        return view('app.account.dashboard', compact('user', 'recentOrders', 'totalOrders'));
    }

    public function orders()
    {
        $orders = auth()->user()->orders()->with('items')->latest()->paginate(10);

        return view('app.account.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('app.account.order-detail', compact('order'));
    }

    public function profile()
    {
        return view('app.account.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        return back()->with('success', 'Bilgileriniz güncellendi.');
    }

    public function password()
    {
        return view('app.account.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mevcut şifreniz hatalı.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Şifreniz başarıyla güncellendi.');
    }
}
