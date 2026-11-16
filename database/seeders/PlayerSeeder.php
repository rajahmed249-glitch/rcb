<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $players = [
            ['name' => 'Raj Ahmed', 'role' => 'Captain', 'is_captain' => true, 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium'],
            ['name' => 'Fazle Rabbi Nayan', 'role' => 'Vice Captain', 'is_vice_captain' => true, 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm offbreak'],
            ['name' => 'Soloyman Hossain', 'role' => 'Batsman', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Mohidul Haq Jony', 'role' => 'All-rounder', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium-fast'],
            ['name' => 'Ebadul Sheikh', 'role' => 'Bowler', 'bowling_style' => 'Right-arm fast'],
            ['name' => 'NMS Sadi', 'role' => 'Wicketkeeper', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Sufiyan Akash', 'role' => 'Bowler', 'bowling_style' => 'Left-arm orthodox'],
            ['name' => 'Syed Adib Rahman', 'role' => 'Batsman', 'batting_style' => 'Left-hand bat'],
            ['name' => 'Tanver Ahmad', 'role' => 'All-rounder', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium'],
            ['name' => 'Saiful Islam Shoron', 'role' => 'Bowler', 'bowling_style' => 'Right-arm legbreak'],
            ['name' => 'Akash Mollah', 'role' => 'Batsman', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Masum Rana Shamim', 'role' => 'All-rounder', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium'],
            ['name' => 'Mohammad Rahul', 'role' => 'Bowler', 'bowling_style' => 'Right-arm fast-medium'],
            ['name' => 'Al Shahria', 'role' => 'Batsman', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Ashraful Islam Rahat', 'role' => 'Wicketkeeper', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Naem Islam', 'short_name' => 'MD', 'role' => 'All-rounder', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm offbreak'],
            ['name' => 'Md. Ashikur Rahman Ashik', 'short_name' => 'MD', 'role' => 'Bowler', 'bowling_style' => 'Left-arm fast'],
            ['name' => 'Md. Dulal Hossain Dulal', 'role' => 'Batsman', 'batting_style' => 'Right-hand bat'],
            ['name' => 'Shehzad Robin', 'short_name' => 'SA', 'role' => 'All-rounder', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium'],
            ['name' => 'Sabbir Hossain Rabby Sabbir', 'short_name' => 'SA', 'role' => 'Bowler', 'bowling_style' => 'Right-arm legbreak'],
            ['name' => 'Saiful Islam Bijoy Saiful', 'short_name' => 'HU', 'role' => 'Batsman', 'batting_style' => 'Left-hand bat'],
            ['name' => 'Humayun Ahmed Zishan Zishan', 'role' => 'Bowler', 'bowling_style' => 'Right-arm medium'],
            ['name' => 'Siam Hossain Siam', 'short_name' => 'MD', 'role' => 'All-rounder', 'batting_style' => 'Right-hand bat', 'bowling_style' => 'Right-arm medium-fast'],
            ['name' => 'Md. Jahidul Islam Jahid', 'role' => 'Batsman', 'batting_style' => 'Right-hand bat'],
        ];

        foreach ($players as $player) {
            Player::create($player);
        }
    }
}
