# 🏏 REAL CHALLENGER BOYS (RCB) — Cricket Team Website (Laravel 12)

এটি https://devcorre.rcb.com.bd/ ওয়েবসাইটের একটি Laravel ভার্সন।

## ফিচারসমূহ

- 🏠 হোমপেজ — Latest Result, Upcoming Fixtures, Latest News, Players, Sponsors
- 🏏 Matches — সব ম্যাচ, ফিল্টার (All / Completed / Upcoming), ম্যাচ ডিটেইলস
- 👤 Players — প্লেয়ার লিস্ট, প্রোফাইল, ক্যাপ্টেন/ভাইস-ক্যাপ্টেন ব্যাজ, স্ট্যাটস
- 📰 News — নিউজ লিস্ট ও ডিটেইলস (slug URL)
- 🤝 Sponsors — স্পনসর লিস্ট
- 🔍 Search — প্লেয়ার, ম্যাচ ও নিউজ সার্চ
- 📋 Be a Player — প্লেয়ার ভর্তি ফর্ম (ভ্যালিডেশন সহ, ডাটাবেজে সেভ হয়)
- 📱 সম্পূর্ণ রেসপন্সিভ ডার্ক থিম

## ধাপে ধাপে সেটআপ (Local)

1. PHP (৮.২+), Composer, MySQL ইনস্টল থাকতে হবে।

2. প্রজেক্ট ফোল্ডারে যান:
   ```bash
   cd rcb-website
   ```

3. `.env` ফাইল তৈরি করুন:
   ```bash
   cp .env.example .env
   ```

4. ডিপেন্ডেন্সি ইনস্টল করুন:
   ```bash
   composer install
   ```

5. অ্যাপ কী জেনারেট করুন:
   ```bash
   php artisan key:generate
   ```

6. `.env` ফাইলে ডাটাবেজ তথ্য দিন:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=rcb_website
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```
   (MySQL না থাকলে: `DB_CONNECTION=sqlite` দিন এবং `touch database/database.sqlite` করুন)

7. টেবিল তৈরি ও ডেমো ডাটা ঢোকান:
   ```bash
   php artisan migrate --seed
   ```

8. সার্ভার চালু করুন:
   ```bash
   php artisan serve
   ```

9. ব্রাউজারে খুলুন: http://localhost:8000

## ফোল্ডার স্ট্রাকচার (গুরুত্বপূর্ণ ফাইল)

```
rcb-website/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php            → হোমপেজ
│   │   ├── MatchController.php           → ম্যাচ লিস্ট ও ডিটেইলস
│   │   ├── PlayerController.php          → প্লেয়ার লিস্ট ও প্রোফাইল
│   │   ├── NewsController.php            → নিউজ লিস্ট ও ডিটেইলস
│   │   ├── SponsorController.php         → স্পনসর
│   │   ├── PlayerApplicationController.php → Be a Player ফর্ম
│   │   └── SearchController.php          → সার্চ
│   └── Models/
│       ├── Player.php
│       ├── CricketMatch.php
│       ├── NewsPost.php
│       ├── Sponsor.php
│       └── PlayerApplication.php
├── database/
│   ├── migrations/                        → ৫টি টেবিল
│   └── seeders/                           → ডেমো ডাটা (আসল সাইটের প্লেয়ার/ম্যাচ/নিউজ)
├── resources/views/
│   ├── layouts/app.blade.php              → মূল লেআউট (হেডার/ফুটার)
│   ├── home.blade.php
│   ├── matches/ (index, show)
│   ├── players/ (index, show)
│   ├── news/ (index, show)
│   ├── sponsors/index.blade.php
│   ├── search/results.blade.php
│   ├── be-a-player/create.blade.php
│   └── errors/404.blade.php
├── routes/web.php                         → সব রাউট
├── public/css/app.css                     → পুরো ডিজাইন
└── public/js/app.js                       → মোবাইল মেনু
```

## রাউট লিস্ট

| URL | বিবরণ |
|---|---|
| `/` | হোমপেজ |
| `/matches` | সব ম্যাচ (`?status=completed` / `?status=upcoming`) |
| `/matches/{id}` | ম্যাচ ডিটেইলস |
| `/players` | প্লেয়ার লিস্ট |
| `/players/{id}` | প্লেয়ার প্রোফাইল |
| `/news` | নিউজ লিস্ট |
| `/news/{slug}` | নিউজ ডিটেইলস |
| `/sponsors` | স্পনসর |
| `/be-a-player` | প্লেয়ার আবেদন ফর্ম |
| `/search?q=...` | সার্চ |

## Railway-এ ডিপ্লয় (ফ্রি লাইভ লিংক পেতে)

1. [railway.app](https://railway.app)-এ GitHub দিয়ে সাইন আপ করুন।
2. **New Project → Deploy from GitHub repo** → `rcb` রিপোটি সিলেক্ট করুন।
3. সার্ভিসের **Variables** ট্যাবে যোগ করুন:
   - `DB_CONNECTION` = `sqlite`
   - `APP_ENV` = `production`
   - `APP_DEBUG` = `false`
4. **Settings → Networking → Generate Domain** চাপুন — লাইভ লিংক পেয়ে যাবেন! 🎉

(প্রজেক্টে `railway.json` ও `Procfile` আগেই যোগ করা আছে — ডিপ্লয়ের সময় মাইগ্রেশন ও সিডিং নিজে থেকেই হবে। ডিপ্লয়ের সময় root directory হিসেবে রিপোর যে ফোল্ডারে Laravel কোড আছে সেটি সিলেক্ট করতে হতে পারে।)

## cPanel/হোস্টিং-এ ডিপ্লয়

1. পুরো প্রজেক্ট আপলোড করুন (vendor ছাড়া)।
2. সার্ভারে `composer install --no-dev` চালান।
3. ডোমেইনের document root → `public` ফোল্ডারে সেট করুন।
4. `.env`-এ প্রোডাকশন ডাটাবেজ দিন, `APP_ENV=production`, `APP_DEBUG=false` করুন।
5. `php artisan migrate --seed --force` চালান।
