<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ReturnRequest::with(['order', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returnRequests = $query->paginate(20)->withQueryString();

        return view('admin.pages.return-requests.index', compact('returnRequests'));
    }

    public function show(ReturnRequest $returnRequest)
    {
        $returnRequest->load(['order.items.product', 'user', 'items.orderItem']);

        return view('admin.pages.return-requests.show', compact('returnRequest'));
    }

    public function question(Request $request, ReturnRequest $returnRequest)
    {
        abort_if(! in_array($returnRequest->status, ['pending', 'questioning']), 409, 'Bu talep zaten sonuçlandırıldı.');

        $data = $request->validate([
            'admin_note' => ['required', 'string', 'max:1000'],
        ]);

        $returnRequest->update([
            'status'     => 'questioning',
            'admin_note' => $data['admin_note'],
        ]);

        return back()->with('success', 'Soru müşteriye iletildi.');
    }

    public function approve(Request $request, ReturnRequest $returnRequest)
    {
        abort_if(! in_array($returnRequest->status, ['pending', 'questioning']), 409, 'Bu talep zaten işleme alındı.');

        $data = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $returnRequest->update([
            'status'      => 'approved',
            'admin_note'  => $data['admin_note'] ?? null,
            'resolved_at' => now(),
        ]);

        $returnRequest->order->update(['shipping_status' => 'returned']);

        return back()->with('success', 'İade talebi onaylandı, sipariş durumu "İade Edildi" olarak güncellendi.');
    }

    public function reject(Request $request, ReturnRequest $returnRequest)
    {
        abort_if(! in_array($returnRequest->status, ['pending', 'questioning']), 409, 'Bu talep zaten işleme alındı.');

        $data = $request->validate([
            'admin_note' => ['required', 'string', 'max:1000'],
        ]);

        $returnRequest->update([
            'status'      => 'rejected',
            'admin_note'  => $data['admin_note'],
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'İade talebi reddedildi.');
    }
}
