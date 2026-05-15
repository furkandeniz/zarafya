<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ReturnRequestController extends Controller
{
    public function create(Order $order)
    {
        $this->authorizeOrder($order);
        abort_if($order->shipping_status !== 'delivered', 403, 'Yalnızca teslim edilmiş siparişler için iade talebi açılabilir.');

        if (Schema::hasTable('return_requests')) {
            abort_if(
                $order->returnRequest()->whereIn('status', ['pending', 'approved'])->exists(),
                409,
                'Bu sipariş için zaten aktif bir iade talebi bulunuyor.'
            );
        }

        return view('app.account.return-request-create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $this->authorizeOrder($order);
        abort_if($order->shipping_status !== 'delivered', 403);

        if (Schema::hasTable('return_requests')) {
            abort_if(
                $order->returnRequest()->whereIn('status', ['pending', 'approved'])->exists(),
                409
            );
        }

        abort_if(! Schema::hasTable('return_requests'), 503, 'İade sistemi henüz aktif değil.');

        $data = $request->validate([
            'reason'                   => ['required', 'in:' . implode(',', array_keys(ReturnRequest::REASONS))],
            'note'                     => ['nullable', 'string', 'max:1000'],
            'items'                    => ['required', 'array', 'min:1'],
            'items.*.order_item_id'    => ['required', 'integer'],
            'items.*.quantity'         => ['required', 'integer', 'min:1'],
            'items.*.selected'         => ['sometimes', 'boolean'],
        ]);

        $selectedItems = collect($data['items'])->filter(
            fn($i) => !empty($i['selected']) || isset($i['quantity'])
        );

        if ($selectedItems->isEmpty()) {
            return back()->withErrors(['items' => 'En az bir ürün seçmelisiniz.'])->withInput();
        }

        $returnRequest = ReturnRequest::create([
            'order_id' => $order->id,
            'user_id'  => auth()->id(),
            'reason'   => $data['reason'],
            'note'     => $data['note'] ?? null,
        ]);

        foreach ($selectedItems as $itemData) {
            $orderItem = $order->items()->find($itemData['order_item_id']);
            if (!$orderItem) continue;

            $returnRequest->items()->create([
                'order_item_id' => $orderItem->id,
                'quantity'      => min((int) $itemData['quantity'], $orderItem->quantity),
            ]);
        }

        return redirect()->route('app.account.order.detail', $order)
            ->with('success', 'İade talebiniz alındı. En kısa sürede incelenecektir.');
    }

    public function reply(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        $returnRequest = $order->returnRequest;
        abort_if(! $returnRequest || $returnRequest->status !== 'questioning', 409);

        $data = $request->validate([
            'customer_reply' => ['required', 'string', 'max:1000'],
        ]);

        $returnRequest->update([
            'customer_reply' => $data['customer_reply'],
            'status'         => 'pending',
        ]);

        return redirect()->route('app.account.order.detail', $order)
            ->with('success', 'Yanıtınız iletildi. En kısa sürede değerlendirilecektir.');
    }

    private function authorizeOrder(Order $order): void
    {
        abort_if($order->user_id !== auth()->id(), 403);
    }
}
