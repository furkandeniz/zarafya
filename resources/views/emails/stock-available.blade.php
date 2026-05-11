<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
        .wrap { max-width:560px; margin:40px auto; background:#fff; border-radius:8px; overflow:hidden; }
        .header { background:#3d5ee1; padding:28px 32px; }
        .header h1 { color:#fff; margin:0; font-size:20px; }
        .body { padding:32px; color:#333; line-height:1.7; }
        .product-name { font-size:18px; font-weight:700; color:#3d5ee1; }
        .variant { display:inline-block; background:#f0f4ff; color:#3d5ee1; padding:2px 10px; border-radius:20px; font-size:13px; margin-top:4px; }
        .btn { display:inline-block; margin-top:24px; padding:12px 28px; background:#3d5ee1; color:#fff; text-decoration:none; border-radius:6px; font-weight:600; }
        .footer { padding:16px 32px; background:#f9f9f9; color:#999; font-size:12px; border-top:1px solid #eee; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>Stok Bildirimi</h1>
    </div>
    <div class="body">
        <p>Merhaba,</p>
        <p>Takip ettiğiniz ürün tekrar stoka girdi:</p>
        <p class="product-name">{{ $product->name }}</p>
        @if ($variantLabel)
            <span class="variant">{{ $variantLabel }}</span>
        @endif
        <br>
        <a href="{{ route('shop.product', $product->slug) }}" class="btn">Ürünü İncele</a>
        <p style="margin-top:24px;font-size:13px;color:#888;">
            Bu e-postayı stok takibi için kayıt olduğunuzda aldınız.
        </p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} — Stok bildirimi
    </div>
</div>
</body>
</html>
