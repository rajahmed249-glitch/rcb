<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $aboutFields = [
            ['key' => 'about_who_text', 'label' => __('site.about.who_we_are'), 'en' => __('site.about.who_text', [], 'en'), 'bn' => __('site.about.who_text', [], 'bn')],
            ['key' => 'about_believe_text', 'label' => __('site.about.believe'), 'en' => __('site.about.believe_text', [], 'en'), 'bn' => __('site.about.believe_text', [], 'bn')],
            ['key' => 'about_mission_text', 'label' => __('site.about.mission'), 'en' => __('site.about.mission_text', [], 'en'), 'bn' => __('site.about.mission_text', [], 'bn')],
            ['key' => 'about_vision_text', 'label' => __('site.about.vision'), 'en' => __('site.about.vision_text', [], 'en'), 'bn' => __('site.about.vision_text', [], 'bn')],
            ['key' => 'about_home_text', 'label' => __('site.about.home_ground'), 'en' => __('site.about.home_text', [], 'en'), 'bn' => __('site.about.home_text', [], 'bn')],
        ];

        $contactFields = [
            ['key' => 'contact_location', 'label' => __('site.contact.location'), 'en' => 'East Shewrapara, Begum Rokeya Sarani, Mirpur, Dhaka - 1216, Bangladesh', 'bn' => 'পূর্ব শেওড়াপাড়া, বেগম রোকেয়া সরণি, মিরপুর, ঢাকা - ১২১৬, বাংলাদেশ'],
        ];

        return view('admin.settings.form', [
            'aboutFields' => $aboutFields,
            'contactFields' => $contactFields,
            'since' => SiteSetting::get('about_since', '2004'),
            'phone' => SiteSetting::get('contact_phone', '01674297522'),
            'email' => SiteSetting::get('contact_email', 'info@rcb.com.bd'),
            'whatsapp' => SiteSetting::get('contact_whatsapp', 'https://wa.link/c1y139'),
        ]);
    }

    public function update(Request $request)
    {
        $keys = [
            'about_since',
            'contact_phone',
            'contact_email',
            'contact_whatsapp',
        ];

        foreach ($keys as $key) {
            SiteSetting::set($key, $request->input($key));
        }

        foreach (['about_who_text', 'about_believe_text', 'about_mission_text', 'about_vision_text', 'about_home_text', 'contact_location'] as $key) {
            SiteSetting::set($key, $request->input($key));
            SiteSetting::set($key . '_bn', $request->input($key . '_bn'));
        }

        return redirect()->route('admin.settings.edit')->with('success', __('site.admin.saved'));
    }
}
