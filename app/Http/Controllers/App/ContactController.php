<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname'   => ['required', 'string', 'max:100'],
            'lname'   => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create([
            'name'    => trim($validated['fname'] . ' ' . $validated['lname']),
            'email'   => $validated['email'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('contact')->with('success', 'Mesajınız başarıyla iletildi. En kısa sürede geri dönüş yapacağız.');
    }
}
