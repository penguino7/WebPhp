<?php
$images = [
    'laptop_default.png' => 'LAPTOP',
    'dell_inspiron_3520.png' => 'Dell Inspiron 3520',
    'dell_xps_13.png' => 'Dell XPS 13 Plus',
    'dell_g15.png' => 'Dell Gaming G15',
    'hp_pavilion_14.png' => 'HP Pavilion 14',
    'hp_envy_x360.png' => 'HP Envy x360',
    'sony_vaio_sx14.png' => 'Sony VAIO SX14',
    'sony_vaio_fe14.png' => 'Sony VAIO FE14',
    'lenovo_thinkpad_x1.png' => 'ThinkPad X1 Carbon',
    'lenovo_legion_5.png' => 'Lenovo Legion 5',
    'acer_nitro_v.png' => 'Acer Nitro V 15',
    'acer_swift_go.png' => 'Acer Swift Go 14',
    'asus_zenbook_14.png' => 'Asus Zenbook 14',
    'asus_tuf_a15.png' => 'Asus TUF A15',
    'samsung_galaxy_book4.png' => 'Galaxy Book4 Pro',
    'macbook_air_m3.png' => 'MacBook Air M3',
    'macbook_pro_14.png' => 'MacBook Pro 14 M3'
];

foreach ($images as $file => $title) {
    $im = imagecreatetruecolor(400, 300);
    $bg = imagecolorallocate($im, 245, 247, 250);
    $border = imagecolorallocate($im, 215, 225, 235);
    $textColor = imagecolorallocate($im, 30, 41, 59);
    $accent = imagecolorallocate($im, 11, 94, 215);
    $screenBg = imagecolorallocate($im, 15, 23, 42);
    $screenInner = imagecolorallocate($im, 30, 58, 138);

    imagefill($im, 0, 0, $bg);
    imagerectangle($im, 0, 0, 399, 299, $border);

    // Screen
    imagefilledrectangle($im, 90, 45, 310, 185, $screenBg);
    imagefilledrectangle($im, 98, 53, 302, 177, $screenInner);

    // Base
    imagefilledrectangle($im, 60, 190, 340, 205, $border);
    imagefilledrectangle($im, 160, 192, 240, 197, $accent);

    // Text
    imagestring($im, 4, max(15, (int)((400 - strlen($title) * 9) / 2)), 230, $title, $textColor);
    imagestring($im, 2, 160, 105, 'LaptopShop', imagecolorallocate($im, 255, 255, 255));

    imagepng($im, __DIR__ . '/' . $file);
    imagedestroy($im);
}
echo "Generated all images!\n";
unlink(__FILE__);
