<?php

namespace Database\Seeders;

use App\Models\NewsPost;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        NewsPost::query()->delete();

        $posts = [
            [
                'title' => '🏏❤️ আলহামদুলিল্লাহ! ❤️🏏 #বিগ_ম্যাচ_উইন 🔥 বড় দলের বিপক্ষে দুর্দান্ত এক বিশাল জয়! 💪❤️',
                'slug' => 'big-match-win-against-strong-team',
                'excerpt' => 'বড় দলের বিপক্ষে দুর্দান্ত এক বিশাল জয়! 💪❤️ দলের প্রত্যেকে দুর্দান্ত পারফরম্যান্স দিয়েছে।',
                'content' => "আলহামদুলিল্লাহ! বড় দলের বিপক্ষে দুর্দান্ত এক বিশাল জয় পেয়েছে রিয়েল চ্যালেঞ্জার বয়েজ। 💪❤️\n\nম্যাচে দলের প্রত্যেকে দুর্দান্ত পারফরম্যান্স দিয়েছে। ব্যাটিং, বোলিং ও ফিল্ডিং — তিন বিভাগেই ছিল দুর্দান্ত সমন্বয়। ক্যাপ্টেনের দুর্দান্ত ইনিংস আর বোলারদের শৃঙ্খলা ম্যাচের মোড় ঘুরিয়ে দেয়।\n\nইনশাআল্লাহ, এভাবেই এগিয়ে যেতে থাকবে আমরা। সবার সাপোর্ট ও দোয়া চাই! 🏏❤️",
                'published_at' => '2026-06-26 20:00:00',
            ],
            [
                'title' => 'Alhamdulillah ❤️ After losing the last 2 matches, we ended up winning a big match before Eid.',
                'slug' => 'big-win-before-eid',
                'excerpt' => 'Alhamdulillah ❤️ After losing the last 2 matches, we ended with a big match win before Eid ❤️🏏',
                'content' => "Alhamdulillah ❤️ After losing the last 2 matches, we ended with a big match win before Eid ❤️❤️❤️🏏\n\nThe whole team showed great character and bounced back strongly. Everyone in the team performed very well — the batsmen set a solid total and the bowlers defended it brilliantly.\n\nThanks to all our supporters for standing by the team. More wins coming soon, InshaAllah! 🏏",
                'published_at' => '2026-05-22 19:30:00',
            ],
            [
                'title' => 'Alhamdulillah ❤️ Started with a big match win after Eid 🏏',
                'slug' => 'big-win-after-eid',
                'excerpt' => 'Alhamdulillah ❤️ Started with a big match win after Eid ❤️❤️❤️🏏 Everyone in the team performed very well.',
                'content' => "Alhamdulillah ❤️ Started with a big match win after Eid ❤️❤️❤️🏏\n\nEveryone in the team performed very well. It was a complete team effort — from the openers giving a flying start to the bowlers finishing things off in style.\n\nWe aim to keep this momentum going through the rest of the season. Keep supporting REAL CHALLENGER BOYS! 💪",
                'image' => 'images/news/10-apr-2026.webp',
                'published_at' => '2026-04-10 18:45:00',
            ],
            [
                'title' => 'Alhamdulillah; We won the 2nd match of the series by a big margin against a stronger team',
                'slug' => 'second-series-win-big-margin',
                'excerpt' => 'Alhamdulillah first match of the series win by big margin against a stronger team.',
                'content' => "Alhamdulillah; We won the 2nd match of the series by a big margin against a stronger team.\n\nThe boys played with great energy and discipline. Special mention to our young bowlers who kept the pressure on from the very first over.\n\nAlhamdulillah for everything. On to the next one! 🏏❤️",
                'published_at' => '2026-01-24 17:00:00',
            ],
        ];

        foreach ($posts as $post) {
            NewsPost::create($post);
        }
    }
}
