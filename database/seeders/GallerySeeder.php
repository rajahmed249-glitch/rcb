<?php

namespace Database\Seeders;

use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        if (GalleryPhoto::count() > 0) {
            return;
        }

        GalleryPhoto::query()->delete();

        $photos = [
            ['title' => 'RCB on the field', 'title_bn' => 'মাঠে আরসিবি', 'image' => 'images/banner1.png', 'sort_order' => 1],
            ['title' => 'RCB team photo', 'title_bn' => 'আরসিবি দলের ছবি', 'image' => 'images/banner2.webp', 'sort_order' => 2],
            ['title' => 'RCB celebration', 'title_bn' => 'আরসিবি উদযাপন', 'image' => 'images/banner3.webp', 'sort_order' => 3],
            ['title' => 'Team group photo', 'title_bn' => 'দলের দলগত ছবি', 'image' => 'images/news/team-photo-2.webp', 'sort_order' => 4],
            ['title' => 'Team group photo', 'title_bn' => 'দলের দলগত ছবি', 'image' => 'images/news/team-photo-3.webp', 'sort_order' => 5],
            ['title' => 'RCB logo', 'title_bn' => 'আরসিবি লোগো', 'image' => 'images/logo.png', 'sort_order' => 6],
        ];

        foreach ($photos as $photo) {
            GalleryPhoto::create($photo);
        }
    }
}
