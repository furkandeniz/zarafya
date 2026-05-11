<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── Kategoriler ──────────────────────────────────────────────
        $categoryNames = [
            'Elektronik', 'Giyim', 'Ayakkabı', 'Ev & Yaşam',
            'Spor & Outdoor', 'Kozmetik', 'Kitap', 'Oyuncak',
        ];
        $categories = collect($categoryNames)->map(fn ($name) =>
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'slug' => Str::slug($name), 'description' => null]
            )
        );

        // ── 15 Mağaza ────────────────────────────────────────────────
        $storeData = [
            ['Teknosa',      'teknosa@ornek.com',   '0212 555 01 01'],
            ['Koton',        'koton@ornek.com',     '0212 555 02 02'],
            ['Adidas TR',    'adidas@ornek.com',    '0212 555 03 03'],
            ['İkea TR',      'ikea@ornek.com',      '0212 555 04 04'],
            ['Decathlon TR', 'decathlon@ornek.com', '0212 555 05 05'],
            ['Gratis',       'gratis@ornek.com',    '0212 555 06 06'],
            ['D&R',          'dr@ornek.com',        '0212 555 07 07'],
            ['Toyzz Shop',   'toyzz@ornek.com',     '0212 555 08 08'],
            ['LC Waikiki',   'lcw@ornek.com',       '0212 555 09 09'],
            ['Samsung TR',   'samsung@ornek.com',   '0212 555 10 10'],
            ['Apple TR',     'apple@ornek.com',     '0212 555 11 11'],
            ['Nike TR',      'nike@ornek.com',      '0212 555 12 12'],
            ['Zara TR',      'zara@ornek.com',      '0212 555 13 13'],
            ['Migros Shop',  'migros@ornek.com',    '0212 555 14 14'],
            ['Hepsiburada',  'hepsi@ornek.com',     '0212 555 15 15'],
        ];

        $stores = collect($storeData)->map(fn ($d) =>
            Store::firstOrCreate(
                ['slug' => Str::slug($d[0])],
                [
                    'name'      => $d[0],
                    'slug'      => Str::slug($d[0]),
                    'email'     => $d[1],
                    'phone'     => $d[2],
                    'is_active' => true,
                ]
            )
        );

        // ── 40 Ürün ──────────────────────────────────────────────────
        $productNames = [
            'Akıllı Telefon Pro X', 'Kablosuz Kulaklık', 'Laptop Ultra 15',
            'Akıllı Saat Series 5', 'Tablet 10 inç', 'Bluetooth Hoparlör',
            'Erkek Slim Fit Gömlek', 'Kadın Denim Ceket', 'Çocuk Spor Ayakkabı',
            'Koşu Ayakkabısı Air', 'Deri Cüzdan', 'Seyahat Valizi 28"',
            'Yoga Matı', 'Dambıl Seti 10 kg', 'Yüzme Gözlüğü',
            'Vitamin C Serum', 'Nemlendirici Krem SPF50', 'Saç Bakım Şampuanı',
            'Roman: Sessiz Ev', 'Programlama Kitabı Python', 'Çocuk Lego Seti',
            'Uzaktan Kumandalı Araba', 'Kupa Takımı 6\'lı', 'Yemek Takımı 24 Parça',
            'Kahve Makinesi Dolce', 'Robot Süpürge', 'Hava Fritözü XL',
            'Erkek Spor Tişört', 'Kadın Yoga Taytı', 'Erkek Şort 3\'lü Paket',
            'Outdoor Çadır 3 Kişilik', 'Trekking Botu', 'Bisiklet Kaskı',
            'Parfüm 100ml EDP', 'Ruj Seti 5 Renk', 'Göz Farı Paleti',
            'Çizgi Roman Seti', 'Akıllı Bebek Monitörü', 'Oyuncak Mutfak Seti',
            'Mini Projeksiyonu',
        ];

        foreach ($productNames as $i => $name) {
            $store    = $stores->random();
            $category = $categories->random();
            $slug     = Str::slug($name) . '-' . ($i + 1);

            Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name'        => $name,
                    'slug'        => $slug,
                    'category_id' => $category->id,
                    'store_id'    => $store->id,
                    'description' => "Bu ürün hakkında detaylı açıklama buraya gelecek.",
                    'price'       => rand(49, 4999) + 0.99,
                    'stock'       => rand(5, 200),
                ]
            );
        }

        // ── 80 Kupon ─────────────────────────────────────────────────
        $adjectives = ['SUPER', 'MEGA', 'INDIRIM', 'KAMPANYA', 'FIRSАТ', 'OZEL', 'YAZ', 'KIS', 'BAHAR', 'SONBAHAR'];
        $used = [];

        for ($i = 0; $i < 80; $i++) {
            $type  = fake()->randomElement(['fixed', 'percent']);
            $value = $type === 'percent'
                ? fake()->randomElement([5, 10, 15, 20, 25, 30, 40, 50])
                : fake()->randomElement([25, 50, 75, 100, 150, 200, 300, 500]);

            // Benzersiz kod üret
            do {
                $code = $adjectives[array_rand($adjectives)] . rand(10, 999);
            } while (in_array($code, $used));
            $used[] = $code;

            Coupon::create([
                'store_id'        => $stores->random()->id,
                'code'            => $code,
                'discount_type'   => $type,
                'discount_value'  => $value,
                'min_order_amount'=> fake()->randomElement([null, 100, 200, 500]),
                'max_uses'        => fake()->randomElement([null, 10, 25, 50, 100]),
                'used_count'      => rand(0, 10),
                'expires_at'      => fake()->randomElement([
                    null,
                    now()->addDays(rand(7, 90)),
                    now()->subDays(rand(1, 30)),
                ]),
                'is_active'       => fake()->boolean(80),
            ]);
        }

        $this->command->info('✓ 15 mağaza, 40 ürün, 80 kupon oluşturuldu.');
    }
}
