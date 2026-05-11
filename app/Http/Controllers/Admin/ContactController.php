<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $messages = $query->paginate(15)->withQueryString();
        $types = ['Soru', 'Öneri', 'Şikayet', 'Bilgi Talebi', 'Diğer'];

        return view('admin.pages.contacts.index', compact('messages', 'types'));
    }

    public function show(ContactMessage $contact)
    {
        $contact->markAsRead();
        return view('admin.pages.contacts.show', compact('contact'));
    }

    public function update(Request $request, ContactMessage $contact)
    {
        $request->validate([
            'status' => ['required', 'in:open,closed'],
        ]);

        $contact->update(['status' => $request->status]);

        return back()->with('success', 'Durum güncellendi.');
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Mesaj silindi.');
    }
}
