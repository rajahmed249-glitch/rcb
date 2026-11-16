<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        Player::query()->delete();

        $players = [
            ['name' => 'Raj Ahmed', 'name_bn' => 'রাজ আহমেদ', 'role' => 'Captain', 'is_captain' => true, 'photo' => 'images/players/raj-ahmed.webp', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium'],
            ['name' => 'Fazle Rabbi Nayan', 'name_bn' => 'ফজলে রব্বি নয়ন', 'role' => 'Vice Captain', 'is_vice_captain' => true, 'photo' => 'images/players/fazle-rabbi-nayan.webp', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm offbreak'],
            ['name' => 'Soloyman Hossain', 'name_bn' => 'সোলায়মান হোসেন', 'role' => 'Batsman', 'photo' => 'images/players/soloyman-hossain.webp', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Mohidul Haq Jony', 'name_bn' => 'মহিদুল হক জনি', 'role' => 'All-rounder', 'photo' => 'images/players/mohidul-haq-jony.webp', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium-fast'],
            ['name' => 'Ebadul Sheikh', 'name_bn' => 'ইবাদুল শেখ', 'role' => 'Bowler', 'photo' => 'images/players/ebadul-sheikh.webp', 'bowling_style' => 'Right-arm fast'],
            ['name' => 'NMS Sadi', 'name_bn' => 'এনএমএস সাদি', 'role' => 'Wicketkeeper', 'photo' => 'images/players/nms-sadi.webp', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Sufiyan Akash', 'name_bn' => 'সুফিয়ান আকাশ', 'role' => 'Bowler', 'photo' => 'images/players/sufiyan-akash.webp', 'bowling_style' => 'Left-arm orthodox'],
            ['name' => 'Syed Adib Rahman', 'name_bn' => 'সৈয়দ আদিব রহমান', 'role' => 'Batsman', 'photo' => 'images/players/syed-adib-rahman.webp', 'batting_style' => 'Left-hand bat'],
            ['name' => 'Tanver Ahmad', 'name_bn' => 'তানভীর আহমদ', 'role' => 'All-rounder', 'photo' => 'images/players/tanver-ahmad.webp', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium'],
            ['name' => 'Saiful Islam Shoron', 'name_bn' => 'সাইফুল ইসলাম শরণ', 'role' => 'Bowler', 'photo' => 'images/players/saiful-islam-shoron.webp', 'bowling_style' => 'Right-arm legbreak'],
            ['name' => 'Akash Mollah', 'name_bn' => 'আকাশ মোল্লা', 'role' => 'Batsman', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Masum Rana Shamim', 'name_bn' => 'মাসুম রানা শামীম', 'role' => 'All-rounder', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium'],
            ['name' => 'Mohammad Rahul', 'name_bn' => 'মোহাম্মদ রাহুল', 'role' => 'Bowler', 'bowling_style' => 'Right-arm fast-medium'],
            ['name' => 'Al Shahria', 'name_bn' => 'আল শাহরিয়া', 'role' => 'Batsman', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Ashraful Islam Rahat', 'name_bn' => 'আশরাফুল ইসলাম রাহাত', 'role' => 'Wicketkeeper', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Naem Islam', 'name_bn' => 'নায়েম ইসলাম', 'short_name' => 'MD', 'role' => 'All-rounder', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm offbreak'],
            ['name' => 'Md. Ashikur Rahman Ashik', 'name_bn' => 'মোঃ আশিকুর রহমান আশিক', 'short_name' => 'MD', 'role' => 'Bowler', 'bowling_style' => 'Left-arm fast'],
            ['name' => 'Md. Dulal Hossain Dulal', 'name_bn' => 'মোঃ দুলাল হোসেন দুলাল', 'role' => 'Batsman', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Shehzad Robin', 'name_bn' => 'শাহজাদ রবিন', 'short_name' => 'SA', 'role' => 'All-rounder', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium'],
            ['name' => 'Sabbir Hossain Rabby Sabbir', 'name_bn' => 'সাব্বির হোসেন রাব্বি সাব্বির', 'short_name' => 'SA', 'role' => 'Bowler', 'bowling_style' => 'Right-arm legbreak'],
            ['name' => 'Saiful Islam Bijoy Saiful', 'name_bn' => 'সাইফুল ইসলাম বিজয় সাইফুল', 'short_name' => 'HU', 'role' => 'Batsman', 'batting_style' => 'Left-hand bat'],
            ['name' => 'Humayun Ahmed Zishan Zishan', 'name_bn' => 'হুমায়ুন আহমেদ জিশান জিশান', 'role' => 'Bowler', 'bowling_style' => 'Right-arm medium'],
            ['name' => 'Siam Hossain Siam', 'name_bn' => 'সিয়াম হোসেন সিয়াম', 'short_name' => 'MD', 'role' => 'All-rounder', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium-fast'],
            ['name' => 'Md. Jahidul Islam Jahid', 'name_bn' => 'মোঃ জাহিদুল ইসলাম জাহিদ', 'role' => 'Batsman', 'batting_style' => 'Right-hand bat'],
        ];

        foreach ($players as $player) {
            Player::create($player);
        }
    }
}
