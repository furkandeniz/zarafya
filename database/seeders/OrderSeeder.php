<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    private array $cities = [
        'İstanbul, Kadıköy, Moda Cad. No:12',
        'Ankara, Çankaya, Tunalı Hilmi Cad. No:7',
        'İzmir, Konak, Kıbrıs Şehitleri Cad. No:34',
        'Bursa, Osmangazi, Atatürk Cad. No:55',
        'Antalya, Muratpaşa, Cumhuriyet Cad. No:9',
        'Adana, Seyhan, Ziyapaşa Bulvarı No:21',
        'Gaziantep, Şahinbey, İstasyon Cad. No:3',
        'Konya, Selçuklu, Nalçacı Cad. No:18',
        'Mersin, Yenişehir, Çankaya Cad. No:44',
        'Kayseri, Melikgazi, Sivas Cad. No:6',
    ];

    public function run(): void
    {
        $stores   = Store::where('is_active', true)->get();
        $users    = User::all();
        $statuses = array_keys(Order::STATUSES);

        // Ağırlıklı dağılım: çok sipariş teslim edilmiş, az iade/iptal
        $weightedStatuses = [
            'pending', 'pending',
            'processing', 'processing',
            'shipped', 'shipped', 'shipped',
            'delivered', 'delivered', 'delivered', 'delivered', 'delivered',
            'returned',
            'cancelled',
        ];

        for ($i = 0; $i < 120; $i++) {
            $store    = $stores->random();
            $user     = $users->isNotEmpty() ? $users->random() : null;
            $status   = $weightedStatuses[array_rand($weightedStatuses)];

            // Mağazaya ait ürünler varsa onları, yoksa herhangi bir ürün seç
            $storeProducts = Product::where('store_id', $store->id)->get();
            $pool          = $storeProducts->isNotEmpty() ? $storeProducts : Product::all();

            // 1-4 kalem
            $itemCount = rand(1, 4);
            $items     = $pool->random(min($itemCount, $pool->count()));

            $orderItems  = [];
            $totalPrice  = 0;

            foreach ($items as $product) {
                $qty   = rand(1, 3);
                $price = $product->price;
                $totalPrice += $price * $qty;
                $orderItems[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'quantity'     => $qty,
                    'price'        => $price,
                ];
            }

            // Geçmişe yayılmış tarihler (son 6 ay)
            $createdAt = now()->subDays(rand(0, 180))->subHours(rand(0, 23));

            $order = Order::create([
                'user_id'          => $user?->id,
                'store_id'         => $store->id,
                'total_price'      => round($totalPrice, 2),
                'shipping_status'  => $status,
                'shipping_address' => $this->cities[array_rand($this->cities)],
                'notes'            => null,
                'created_at'       => $createdAt,
                'updated_at'       => $createdAt,
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create(array_merge($item, [
                    'order_id'   => $order->id,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]));
            }
        }

        $this->command->info('✓ 120 sipariş ve kalem verileri oluşturuldu.');
    }
}
