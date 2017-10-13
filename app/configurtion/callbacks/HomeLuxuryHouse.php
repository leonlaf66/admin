<?php
namespace module\configurtion\callbacks;

class HomeLuxuryHouse
{
    public static function process($path, & $items)
    {
        foreach ($items as $idx => $item) {
            $mlsId = $item['id'];
            $image = $item['image'];

            $base64Pos = strpos($image, 'data:image/jpeg;base64,');
            if ($base64Pos === false) {
                $items[$idx]['image'] = str_replace(media_url().'/', '', $items[$idx]['image']);
                continue;
            }

            if ($base64Pos !== 0) {
                $image = substr($image, $base64Pos + strlen('data:image/jpeg;base64,'));
            }

            $dir = media_file('home-luxury-house');
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
            $file = $dir.'/'.$mlsId.'.jpg';

            $imageData = explode(",", $image);
            $imageData[1] = str_replace(' ', '+', $imageData[1]);
            $imageData = base64_decode($imageData[1]);

            file_put_contents($file, $imageData);

            $items[$idx]['image'] = 'home-luxury-house/'.$mlsId.'.jpg';
        }
    }
}