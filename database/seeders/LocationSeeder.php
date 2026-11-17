<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    private array $districts = [
        'Dhaka' => ['bn' => 'ঢাকা', 'thanas' => [['Dhamrai', 'ধামরাই'], ['Dohar', 'দোহার'], ['Keraniganj', 'কেরানীগঞ্জ'], ['Nawabganj', 'নবাবগঞ্জ'], ['Savar', 'সাভার']]],
        'Faridpur' => ['bn' => 'ফরিদপুর', 'thanas' => [['Alfadanga', 'আলফাডাঙ্গা'], ['Bhanga', 'ভাঙ্গা'], ['Boalmari', 'বোয়ালমারী'], ['Charbhadrasan', 'চরভদ্রাসন'], ['Faridpur Sadar', 'ফরিদপুর সদর'], ['Madhukhali', 'মধুখালী'], ['Nagarkanda', 'নগরকান্দা'], ['Sadarpur', 'সদরপুর'], ['Saltha', 'সালথা']]],
        'Gazipur' => ['bn' => 'গাজীপুর', 'thanas' => [['Gazipur Sadar', 'গাজীপুর সদর'], ['Kaliakair', 'কালিয়াকৈর'], ['Kaliganj', 'কালীগঞ্জ'], ['Kapasia', 'কাপাসিয়া'], ['Sreepur', 'শ্রীপুর']]],
        'Gopalganj' => ['bn' => 'গোপালগঞ্জ', 'thanas' => [['Gopalganj Sadar', 'গোপালগঞ্জ সদর'], ['Kashiani', 'কাশিয়ানী'], ['Kotalipara', 'কোটালীপাড়া'], ['Muksudpur', 'মুকসুদপুর'], ['Tungipara', 'টুঙ্গিপাড়া']]],
        'Kishoreganj' => ['bn' => 'কিশোরগঞ্জ', 'thanas' => [['Austagram', 'অষ্টগ্রাম'], ['Bajitpur', 'বাজিতপুর'], ['Bhairab', 'ভৈরব'], ['Hossainpur', 'হোসেনপুর'], ['Itna', 'ইটনা'], ['Karimganj', 'করিমগঞ্জ'], ['Katiadi', 'কটিয়াদী'], ['Kishoreganj Sadar', 'কিশোরগঞ্জ সদর'], ['Kuliarchar', 'কুলিয়ারচর'], ['Mithamain', 'মিথামইন'], ['Nikli', 'নিকলী'], ['Pakundia', 'পাকুন্দিয়া'], ['Tarail', 'তাড়াইল']]],
        'Madaripur' => ['bn' => 'মাদারীপুর', 'thanas' => [['Kalkini', 'কালকিনি'], ['Madaripur Sadar', 'মাদারীপুর সদর'], ['Rajoir', 'রাজৈর'], ['Shibchar', 'শিবচর']]],
        'Manikganj' => ['bn' => 'মানিকগঞ্জ', 'thanas' => [['Daulatpur', 'দৌলতপুর'], ['Ghior', 'ঘিওর'], ['Harirampur', 'হরিরামপুর'], ['Manikganj Sadar', 'মানিকগঞ্জ সদর'], ['Saturia', 'সাটুরিয়া'], ['Shivalaya', 'শিবালয়'], ['Singair', 'সিঙ্গাইর']]],
        'Munshiganj' => ['bn' => 'মুন্সিগঞ্জ', 'thanas' => [['Gazaria', 'গজারিয়া'], ['Lohajang', 'লোহাজং'], ['Munshiganj Sadar', 'মুন্সিগঞ্জ সদর'], ['Sirajdikhan', 'শিরাজদিখান'], ['Srinagar', 'শ্রীনগর'], ['Tongibari', 'টঙ্গীবাড়ী']]],
        'Narayanganj' => ['bn' => 'নারায়ণগঞ্জ', 'thanas' => [['Araihazar', 'আড়াইহাজার'], ['Bandar', 'বন্দর'], ['Narayanganj Sadar', 'নারায়ণগঞ্জ সদর'], ['Rupganj', 'রূপগঞ্জ'], ['Sonargaon', 'সোনারগাঁও']]],
        'Narsingdi' => ['bn' => 'নরসিংদী', 'thanas' => [['Belabo', 'বেলাবো'], ['Monohardi', 'মনোহরদী'], ['Narsingdi Sadar', 'নরসিংদী সদর'], ['Palash', 'পলাশ'], ['Raipura', 'রায়পুরা'], ['Shibpur', 'শিবপুর']]],
        'Rajbari' => ['bn' => 'রাজবাড়ী', 'thanas' => [['Baliakandi', 'বালিয়াকান্দি'], ['Goalandaghat', 'গোয়ালন্দ ঘাট'], ['Kalukhali', 'কালুখালী'], ['Pangsha', 'পাংশা'], ['Rajbari Sadar', 'রাজবাড়ী সদর']]],
        'Shariatpur' => ['bn' => 'শরীয়তপুর', 'thanas' => [['Bhedarganj', 'ভেদারগঞ্জ'], ['Damudya', 'ডামুড্যা'], ['Gosairhat', 'গোসাইরহাট'], ['Naria', 'নড়িয়া'], ['Shariatpur Sadar', 'শরীয়তপুর সদর']]],
        'Tangail' => ['bn' => 'টাঙ্গাইল', 'thanas' => [['Basail', 'বাসাইল'], ['Bhuapur', 'ভুয়াপুর'], ['Delduar', 'দেলদুয়ার'], ['Dhanbari', 'ধনবাড়ী'], ['Ghatail', 'ঘাটাইল'], ['Gopalpur', 'গোপালপুর'], ['Kalihati', 'কালিহাতী'], ['Madhupur', 'মধুপুর'], ['Mirzapur', 'মির্জাপুর'], ['Nagarpur', 'নাগরপুর'], ['Sakhipur', 'সখীপুর'], ['Tangail Sadar', 'টাঙ্গাইল সদর']]],

        'Bandarban' => ['bn' => 'বান্দরবান', 'thanas' => [['Ali Kadam', 'আলীকদম'], ['Bandarban Sadar', 'বান্দরবান সদর'], ['Lama', 'লামা'], ['Naikhongchhari', 'নাইক্ষ্যংছড়ি'], ['Rowangchhari', 'রোয়াংছড়ি'], ['Ruma', 'রুমা'], ['Thanchi', 'থানচি']]],
        'Brahmanbaria' => ['bn' => 'ব্রাহ্মণবাড়িয়া', 'thanas' => [['Akhaura', 'আখাউড়া'], ['Ashuganj', 'আশুগঞ্জ'], ['Bancharampur', 'বাঞ্ছারামপুর'], ['Bijoynagar', 'বিজয়নগর'], ['Brahmanbaria Sadar', 'ব্রাহ্মণবাড়িয়া সদর'], ['Kasba', 'কসবা'], ['Nabinagar', 'নবীনগর'], ['Nasirnagar', 'নাসিরনগর'], ['Sarail', 'সরাইল']]],
        'Chandpur' => ['bn' => 'চাঁদপুর', 'thanas' => [['Chandpur Sadar', 'চাঁদপুর সদর'], ['Faridganj', 'ফরিদগঞ্জ'], ['Haimchar', 'হাইমচর'], ['Haziganj', 'হাজীগঞ্জ'], ['Kachua', 'কচুয়া'], ['Matlab Dakshin', 'মতলব দক্ষিণ'], ['Matlab Uttar', 'মতলব উত্তর'], ['Shahrasti', 'শাহরাস্তি']]],
        'Chattogram' => ['bn' => 'চট্টগ্রাম', 'thanas' => [['Anwara', 'আনোয়ারা'], ['Banshkhali', 'বাঁশখালী'], ['Boalkhali', 'বোয়ালখালী'], ['Chandanaish', 'চন্দনাইশ'], ['Fatikchhari', 'ফটিকছড়ি'], ['Hathazari', 'হাটহাজারী'], ['Karnaphuli', 'কর্ণফুলী'], ['Lohagara', 'লোহাগাড়া'], ['Mirsharai', 'মীরসরাই'], ['Patiya', 'পটিয়া'], ['Rangunia', 'রাঙ্গুনিয়া'], ['Raozan', 'রাউজান'], ['Satkania', 'সাতকানিয়া'], ['Sitakunda', 'সীতাকুণ্ড']]],
        'Cumilla' => ['bn' => 'কুমিল্লা', 'thanas' => [['Barura', 'বরুড়া'], ['Brahmanpara', 'ব্রাহ্মণপাড়া'], ['Burichang', 'বুড়িচং'], ['Chandina', 'চান্দিনা'], ['Chauddagram', 'চৌদ্দগ্রাম'], ['Cumilla Adarsha Sadar', 'কুমিল্লা আদর্শ সদর'], ['Cumilla Sadar Dakshin', 'কুমিল্লা সদর দক্ষিণ'], ['Daudkandi', 'দাউদকান্দি'], ['Debidwar', 'দেবিদ্বার'], ['Homna', 'হোমনা'], ['Laksam', 'লাকসাম'], ['Meghna', 'মেঘনা'], ['Monohorgonj', 'মনোহরগঞ্জ'], ['Muradnagar', 'মুরাদনগর'], ['Nangalkot', 'নাঙ্গলকোট'], ['Titas', 'তিতাস']]],
        "Cox's Bazar" => ['bn' => 'কক্সবাজার', 'thanas' => [['Chakaria', 'চকরিয়া'], ["Cox's Bazar Sadar", 'কক্সবাজার সদর'], ['Kutubdia', 'কুতুবদিয়া'], ['Maheshkhali', 'মহেশখালী'], ['Pekua', 'পেকুয়া'], ['Ramu', 'রামু'], ['Teknaf', 'টেকনাফ'], ['Ukhia', 'উখিয়া']]],
        'Feni' => ['bn' => 'ফেনী', 'thanas' => [['Chhagalnaiya', 'ছাগলনাইয়া'], ['Daganbhuiyan', 'দাগনভূঞা'], ['Feni Sadar', 'ফেনী সদর'], ['Fulgazi', 'ফুলগাজী'], ['Parshuram', 'পরশুরাম'], ['Sonagazi', 'সোনাগাজী']]],
        'Khagrachhari' => ['bn' => 'খাগড়াছড়ি', 'thanas' => [['Dighinala', 'দীঘিনালা'], ['Khagrachhari Sadar', 'খাগড়াছড়ি সদর'], ['Lakshmichhari', 'লক্ষ্মীছড়ি'], ['Mahalchhari', 'মহালছড়ি'], ['Manikchhari', 'মানিকছড়ি'], ['Matiranga', 'মাটিরাঙ্গা'], ['Panchhari', 'পানছড়ি'], ['Ramgarh', 'রামগড়']]],
        'Lakshmipur' => ['bn' => 'লক্ষ্মীপুর', 'thanas' => [['Kamalnagar', 'কামালনগর'], ['Lakshmipur Sadar', 'লক্ষ্মীপুর সদর'], ['Raipur', 'রায়পুর'], ['Ramganj', 'রামগঞ্জ'], ['Ramgati', 'রামগতি']]],
        'Noakhali' => ['bn' => 'নোয়াখালী', 'thanas' => [['Begumganj', 'বেগমগঞ্জ'], ['Chatkhil', 'চাটখিল'], ['Companiganj', 'কোম্পানীগঞ্জ'], ['Hatiya', 'হাতিয়া'], ['Kabirhat', 'কবিরহাট'], ['Noakhali Sadar', 'নোয়াখালী সদর'], ['Senbagh', 'সেনবাগ'], ['Sonaimuri', 'সোনাইমুড়ী'], ['Subarnachar', 'সুবর্ণচর']]],
        'Rangamati' => ['bn' => 'রাঙ্গামাটি', 'thanas' => [['Bagaichhari', 'বাঘাইছড়ি'], ['Barkal', 'বরকল'], ['Belaichhari', 'বিলাইছড়ি'], ['Juraichhari', 'জুরাইছড়ি'], ['Kaptai', 'কাপ্তাই'], ['Kawkhali', 'কাউখালী'], ['Langadu', 'লংগদু'], ['Naniarchar', 'নানিয়ারচর'], ['Rajasthali', 'রাজস্থলী'], ['Rangamati Sadar', 'রাঙ্গামাটি সদর']]],

        'Bogura' => ['bn' => 'বগুড়া', 'thanas' => [['Adamdighi', 'আদমদিঘী'], ['Bogura Sadar', 'বগুড়া সদর'], ['Dhunat', 'ধুনট'], ['Dhupchanchia', 'ধুপচাঁচিয়া'], ['Gabtali', 'গাবতলী'], ['Kahaloo', 'কাহালু'], ['Nandigram', 'নন্দীগ্রাম'], ['Sariakandi', 'সারিয়াকান্দি'], ['Shajahanpur', 'শাজাহানপুর'], ['Sherpur', 'শেরপুর'], ['Shibganj', 'শিবগঞ্জ'], ['Sonatala', 'সোনাতলা']]],
        'Chapainawabganj' => ['bn' => 'চাঁপাইনবাবগঞ্জ', 'thanas' => [['Bholahat', 'ভোলাহাট'], ['Chapainawabganj Sadar', 'চাঁপাইনবাবগঞ্জ সদর'], ['Gomastapur', 'গোমস্তাপুর'], ['Nachole', 'নাচোল'], ['Shibganj', 'শিবগঞ্জ']]],
        'Joypurhat' => ['bn' => 'জয়পুরহাট', 'thanas' => [['Akkelpur', 'আক্কেলপুর'], ['Joypurhat Sadar', 'জয়পুরহাট সদর'], ['Kalai', 'কালাই'], ['Khetlal', 'ক্ষেতলাল'], ['Panchbibi', 'পাঁচবিবি']]],
        'Naogaon' => ['bn' => 'নওগাঁ', 'thanas' => [['Atrai', 'আত্রাই'], ['Badalgachhi', 'বদলগাছী'], ['Dhamoirhat', 'ধামইরহাট'], ['Manda', 'মান্দা'], ['Mohadevpur', 'মহাদেবপুর'], ['Naogaon Sadar', 'নওগাঁ সদর'], ['Niamatpur', 'নিয়ামতপুর'], ['Patnitala', 'পত্নীতলা'], ['Porsha', 'পোরশা'], ['Raninagar', 'রাণীনগর'], ['Sapahar', 'সাপাহার']]],
        'Natore' => ['bn' => 'নাটোর', 'thanas' => [['Bagatipara', 'বাগাতিপাড়া'], ['Baraigram', 'বড়াইগ্রাম'], ['Gurudaspur', 'গুরুদাসপুর'], ['Lalpur', 'লালপুর'], ['Naldanga', 'নলডাঙ্গা'], ['Natore Sadar', 'নাটোর সদর'], ['Singra', 'সিংড়া']]],
        'Pabna' => ['bn' => 'পাবনা', 'thanas' => [['Atgharia', 'আটঘরিয়া'], ['Bera', 'বেড়া'], ['Bhangura', 'ভাঙুরা'], ['Chatmohar', 'চাটমোহর'], ['Faridpur', 'ফরিদপুর'], ['Ishwardi', 'ঈশ্বরদী'], ['Pabna Sadar', 'পাবনা সদর'], ['Santhia', 'সাঁথিয়া'], ['Sujanagar', 'সুজানগর']]],
        'Rajshahi' => ['bn' => 'রাজশাহী', 'thanas' => [['Bagha', 'বাঘা'], ['Bagmara', 'বাগমারা'], ['Charghat', 'চারঘাট'], ['Durgapur', 'দুর্গাপুর'], ['Godagari', 'গোদাগাড়ী'], ['Mohanpur', 'মোহনপুর'], ['Paba', 'পবা'], ['Puthia', 'পুঠিয়া'], ['Tanore', 'তানোর']]],
        'Sirajganj' => ['bn' => 'সিরাজগঞ্জ', 'thanas' => [['Belkuchi', 'বেলকুচি'], ['Chauhali', 'চৌহালি'], ['Kamarkhanda', 'কামারখান্দ'], ['Kazipur', 'কাজিপুর'], ['Raiganj', 'রায়গঞ্জ'], ['Shahjadpur', 'শাহজাদপুর'], ['Sirajganj Sadar', 'সিরাজগঞ্জ সদর'], ['Tarash', 'তাড়াশ'], ['Ullapara', 'উল্লাপাড়া']]],

        'Bagerhat' => ['bn' => 'বাগেরহাট', 'thanas' => [['Bagerhat Sadar', 'বাগেরহাট সদর'], ['Chitalmari', 'চিতলমারী'], ['Fakirhat', 'ফকিরহাট'], ['Kachua', 'কচুয়া'], ['Mollahat', 'মোল্লাহাট'], ['Mongla', 'মোংলা'], ['Morrelganj', 'মোড়েলগঞ্জ'], ['Rampal', 'রামপাল'], ['Sarankhola', 'সরণখোলা']]],
        'Chuadanga' => ['bn' => 'চুয়াডাঙ্গা', 'thanas' => [['Alamdanga', 'আলমডাঙ্গা'], ['Chuadanga Sadar', 'চুয়াডাঙ্গা সদর'], ['Damurhuda', 'দামুড়হুদা'], ['Jibannagar', 'জীবননগর']]],
        'Jashore' => ['bn' => 'যশোর', 'thanas' => [['Abhaynagar', 'অভয়নগর'], ['Bagherpara', 'বাঘেরপাড়া'], ['Chaugachha', 'চৌগাছা'], ['Jashore Sadar', 'যশোর সদর'], ['Jhikargachha', 'ঝিকরগাছা'], ['Keshabpur', 'কেশবপুর'], ['Manirampur', 'মনিরামপুর'], ['Sharsha', 'শার্শা']]],
        'Jhenaidah' => ['bn' => 'ঝিনাইদহ', 'thanas' => [['Harinakunda', 'হরিণাকুণ্ডু'], ['Jhenaidah Sadar', 'ঝিনাইদহ সদর'], ['Kaliganj', 'কালীগঞ্জ'], ['Kotchandpur', 'কোটচাঁদপুর'], ['Maheshpur', 'মহেশপুর'], ['Shailkupa', 'শৈলকুপা']]],
        'Khulna' => ['bn' => 'খুলনা', 'thanas' => [['Batiaghata', 'বটিয়াঘাটা'], ['Dacope', 'ডাকোপ'], ['Dighalia', 'দিঘলিয়া'], ['Dumuria', 'ডুমুরিয়া'], ['Koyra', 'কয়রা'], ['Paikgachha', 'পাইকগাছা'], ['Phultala', 'ফুলতলা'], ['Rupsa', 'রূপসা'], ['Terokhada', 'তেরোখাদা']]],
        'Kushtia' => ['bn' => 'কুষ্টিয়া', 'thanas' => [['Bheramara', 'ভেড়ামারা'], ['Daulatpur', 'দৌলতপুর'], ['Khoksa', 'খোকসা'], ['Kumarkhali', 'কুমারখালী'], ['Kushtia Sadar', 'কুষ্টিয়া সদর'], ['Mirpur', 'মিরপুর']]],
        'Magura' => ['bn' => 'মাগুরা', 'thanas' => [['Magura Sadar', 'মাগুরা সদর'], ['Mohammadpur', 'মহম্মদপুর'], ['Shalikha', 'শালিখা'], ['Sreepur', 'শ্রীপুর']]],
        'Meherpur' => ['bn' => 'মেহেরপুর', 'thanas' => [['Gangni', 'গাংনী'], ['Meherpur Sadar', 'মেহেরপুর সদর'], ['Mujibnagar', 'মুজিবনগর']]],
        'Narail' => ['bn' => 'নড়াইল', 'thanas' => [['Kalia', 'কালিয়া'], ['Lohagara', 'লোহাগড়া'], ['Narail Sadar', 'নড়াইল সদর']]],
        'Satkhira' => ['bn' => 'সাতক্ষীরা', 'thanas' => [['Assasuni', 'আশাশুনি'], ['Debhata', 'দেভহাটা'], ['Kalaroa', 'কালারোয়া'], ['Kaliganj', 'কালীগঞ্জ'], ['Satkhira Sadar', 'সাতক্ষীরা সদর'], ['Shyamnagar', 'শ্যামনগর'], ['Tala', 'তালা']]],

        'Barguna' => ['bn' => 'বরগুনা', 'thanas' => [['Amtali', 'আমতলী'], ['Bamna', 'বামনা'], ['Barguna Sadar', 'বরগুনা সদর'], ['Betagi', 'বেতাগী'], ['Patharghata', 'পাথরঘাটা'], ['Taltali', 'তালতলী']]],
        'Barishal' => ['bn' => 'বরিশাল', 'thanas' => [['Agailjhara', 'আগৈলঝারা'], ['Babuganj', 'বাবুগঞ্জ'], ['Bakerganj', 'বাকেরগঞ্জ'], ['Banaripara', 'বানারীপাড়া'], ['Barishal Sadar', 'বরিশাল সদর'], ['Gaurnadi', 'গৌরনদী'], ['Hizla', 'হিজলা'], ['Mehendiganj', 'মেহেন্দীগঞ্জ'], ['Muladi', 'মুলাদী'], ['Wazirpur', 'ওয়াজিরপুর']]],
        'Bhola' => ['bn' => 'ভোলা', 'thanas' => [['Bhola Sadar', 'ভোলা সদর'], ['Burhanuddin', 'বুরহানউদ্দিন'], ['Char Fasson', 'চর ফ্যাশন'], ['Daulatkhan', 'দৌলতখান'], ['Lalmohan', 'লালমোহন'], ['Manpura', 'মনপুরা'], ['Tazumuddin', 'তজুমদ্দিন']]],
        'Jhalokati' => ['bn' => 'ঝালকাঠি', 'thanas' => [['Jhalokati Sadar', 'ঝালকাঠি সদর'], ['Kathalia', 'কাঠালিয়া'], ['Nalchity', 'নলচিটি'], ['Rajapur', 'রাজাপুর']]],
        'Patuakhali' => ['bn' => 'পটুয়াখালী', 'thanas' => [['Bauphal', 'বাউফল'], ['Dashmina', 'দশমিনা'], ['Dumki', 'ডুমকি'], ['Galachipa', 'গলাচিপা'], ['Kalapara', 'কালাপাড়া'], ['Mirzaganj', 'মির্জাগঞ্জ'], ['Patuakhali Sadar', 'পটুয়াখালী সদর'], ['Rangabali', 'রাঙ্গাবালী']]],
        'Pirojpur' => ['bn' => 'পিরোজপুর', 'thanas' => [['Bhandaria', 'ভাণ্ডারিয়া'], ['Kawkhali', 'কাউখালী'], ['Mathbaria', 'মঠবাড়িয়া'], ['Nazirpur', 'নাজিরপুর'], ['Nesarabad', 'নেছারাবাদ'], ['Pirojpur Sadar', 'পিরোজপুর সদর'], ['Zianagar', 'জিয়ানগর']]],

        'Habiganj' => ['bn' => 'হবিগঞ্জ', 'thanas' => [['Ajmiriganj', 'আজমিরিগঞ্জ'], ['Bahubal', 'বাহুবল'], ['Baniyachong', 'বানিয়াচং'], ['Chunarughat', 'চুনারুঘাট'], ['Habiganj Sadar', 'হবিগঞ্জ সদর'], ['Lakhai', 'লাখাই'], ['Madhabpur', 'মাধবপুর'], ['Nabiganj', 'নবীগঞ্জ'], ['Shayestaganj', 'শায়েস্তাগঞ্জ']]],
        'Moulvibazar' => ['bn' => 'মৌলভীবাজার', 'thanas' => [['Barlekha', 'বড়লেখা'], ['Juri', 'জুড়ী'], ['Kamalganj', 'কমলগঞ্জ'], ['Kulaura', 'কুলাউড়া'], ['Moulvibazar Sadar', 'মৌলভীবাজার সদর'], ['Rajnagar', 'রাজনগর'], ['Sreemangal', 'শ্রীমঙ্গল']]],
        'Sunamganj' => ['bn' => 'সুনামগঞ্জ', 'thanas' => [['Bishwamvarpur', 'বিশ্বম্ভরপুর'], ['Chhatak', 'ছাতক'], ['Derai', 'দিরাই'], ['Dharampasha', 'ধর্মপাশা'], ['Dowarabazar', 'দোয়ারাবাজার'], ['Jagannathpur', 'জগন্নাথপুর'], ['Jamalganj', 'জামালগঞ্জ'], ['Shantiganj', 'শান্তিগঞ্জ'], ['Sullah', 'সুল্লা'], ['Sunamganj Sadar', 'সুনামগঞ্জ সদর'], ['Tahirpur', 'তাহিরপুর']]],
        'Sylhet' => ['bn' => 'সিলেট', 'thanas' => [['Balaganj', 'বালাগঞ্জ'], ['Beanibazar', 'বিয়ানীবাজার'], ['Bishwanath', 'বিশ্বনাথ'], ['Companiganj', 'কোম্পানীগঞ্জ'], ['Dakshin Surma', 'দক্ষিণ সুরমা'], ['Fenchuganj', 'ফেঞ্চুগঞ্জ'], ['Golapganj', 'গোলাপগঞ্জ'], ['Gowainghat', 'গোয়াইনঘাট'], ['Jaintiapur', 'জৈন্তিয়াপুর'], ['Kanaighat', 'কানাইঘাট'], ['Osmani Nagar', 'ওসমানী নগর'], ['Sylhet Sadar', 'সিলেট সদর'], ['Zakiganj', 'জকিগঞ্জ']]],

        'Dinajpur' => ['bn' => 'দিনাজপুর', 'thanas' => [['Birampur', 'বিরামপুর'], ['Birganj', 'বিরগঞ্জ'], ['Biral', 'বীরল'], ['Bochaganj', 'বোচাগঞ্জ'], ['Chirirbandar', 'চিরিরবন্দর'], ['Dinajpur Sadar', 'দিনাজপুর সদর'], ['Ghoraghat', 'ঘোড়াঘাট'], ['Hakimpur', 'হাকিমপুর'], ['Kaharole', 'কাহারোল'], ['Khansama', 'খানসামা'], ['Nawabganj', 'নবাবগঞ্জ'], ['Parbatipur', 'পার্বতীপুর'], ['Phulbari', 'ফুলবাড়ী']]],
        'Gaibandha' => ['bn' => 'গাইবান্ধা', 'thanas' => [['Gaibandha Sadar', 'গাইবান্ধা সদর'], ['Gobindaganj', 'গোবিন্দগঞ্জ'], ['Palashbari', 'পালাশবাড়ী'], ['Phulchhari', 'ফুলছড়ি'], ['Sadullapur', 'সদুল্লাপুর'], ['Saghata', 'সাঘাটা'], ['Sundarganj', 'সুন্দরগঞ্জ']]],
        'Kurigram' => ['bn' => 'কুড়িগ্রাম', 'thanas' => [['Bhurungamari', 'ভুরুঙ্গামারী'], ['Char Rajibpur', 'চর রাজীবপুর'], ['Chilmari', 'চিলমারী'], ['Kurigram Sadar', 'কুড়িগ্রাম সদর'], ['Nageshwari', 'নাগেশ্বরী'], ['Phulbari', 'ফুলবাড়ী'], ['Rajarhat', 'রাজারহাট'], ['Raumari', 'রাউমারী'], ['Ulipur', 'উলিপুর']]],
        'Lalmonirhat' => ['bn' => 'লালমনিরহাট', 'thanas' => [['Aditmari', 'আদিতমারী'], ['Hatibandha', 'হাতীবান্ধা'], ['Kaliganj', 'কালীগঞ্জ'], ['Lalmonirhat Sadar', 'লালমনিরহাট সদর'], ['Patgram', 'পাটগ্রাম']]],
        'Nilphamari' => ['bn' => 'নীলফামারী', 'thanas' => [['Dimla', 'ডিমলা'], ['Domar', 'ডোমার'], ['Jaldhaka', 'জলঢাকা'], ['Kishoreganj', 'কিশোরগঞ্জ'], ['Nilphamari Sadar', 'নীলফামারী সদর'], ['Saidpur', 'সৈয়দপুর']]],
        'Panchagarh' => ['bn' => 'পঞ্চগড়', 'thanas' => [['Atwari', 'আটোয়ারী'], ['Boda', 'বোদা'], ['Debiganj', 'দেবীগঞ্জ'], ['Panchagarh Sadar', 'পঞ্চগড় সদর'], ['Tetulia', 'তেতুলিয়া']]],
        'Rangpur' => ['bn' => 'রংপুর', 'thanas' => [['Badarganj', 'বদরগঞ্জ'], ['Gangachara', 'গাংগাচড়া'], ['Kaunia', 'কাউনিয়া'], ['Mithapukur', 'মিঠাপুকুর'], ['Pirgachha', 'পীরগাছা'], ['Pirganj', 'পীরগঞ্জ'], ['Rangpur Sadar', 'রংপুর সদর'], ['Taraganj', 'তারাগঞ্জ']]],
        'Thakurgaon' => ['bn' => 'ঠাকুরগাঁও', 'thanas' => [['Baliadangi', 'বালিয়াডাঙ্গী'], ['Haripur', 'হরিপুর'], ['Pirganj', 'পীরগঞ্জ'], ['Ranisankail', 'রাণীসংকৈল'], ['Thakurgaon Sadar', 'ঠাকুরগাঁও সদর']]],

        'Jamalpur' => ['bn' => 'জামালপুর', 'thanas' => [['Bakshiganj', 'বকশীগঞ্জ'], ['Dewanganj', 'দেওয়ানগঞ্জ'], ['Islampur', 'ইসলামপুর'], ['Jamalpur Sadar', 'জামালপুর সদর'], ['Madarganj', 'মাদারগঞ্জ'], ['Melandaha', 'মেলান্দহ'], ['Sarishabari', 'সরিষাবাড়ী']]],
        'Mymensingh' => ['bn' => 'ময়মনসিংহ', 'thanas' => [['Bhaluka', 'ভালুকা'], ['Dhobaura', 'ধোবাউড়া'], ['Fulbaria', 'ফুলবাড়িয়া'], ['Gaffargaon', 'গফরগাঁও'], ['Gauripur', 'গৌরীপুর'], ['Haluaghat', 'হালুয়াঘাট'], ['Ishwarganj', 'ঈশ্বরগঞ্জ'], ['Muktagachha', 'মুক্তাগাছা'], ['Mymensingh Sadar', 'ময়মনসিংহ সদর'], ['Nandail', 'নান্দাইল'], ['Phulpur', 'ফুলপুর'], ['Tarakanda', 'তারাকান্দা'], ['Trishal', 'ত্রিশাল']]],
        'Netrokona' => ['bn' => 'নেত্রকোণা', 'thanas' => [['Atpara', 'আটপাড়া'], ['Barhatta', 'বারহাট্টা'], ['Durgapur', 'দুর্গাপুর'], ['Kalmakanda', 'কলমাকান্দা'], ['Kendua', 'কেন্দুয়া'], ['Khaliajuri', 'খালিয়াজুরি'], ['Madan', 'মদন'], ['Mohanganj', 'মোহনগঞ্জ'], ['Netrokona Sadar', 'নেত্রকোণা সদর'], ['Purbadhala', 'পূর্বধলা']]],
        'Sherpur' => ['bn' => 'শেরপুর', 'thanas' => [['Jhenaigati', 'ঝিনাইগাতী'], ['Nakla', 'নকলা'], ['Nalitabari', 'নালিতাবাড়ী'], ['Sherpur Sadar', 'শেরপুর সদর']]],
    ];

    public function run(): void
    {
        $sort = 0;

        foreach ($this->districts as $name => $info) {
            $sort++;

            $district = Location::updateOrCreate(
                ['name' => $name, 'type' => 'district'],
                ['name_bn' => $info['bn'], 'sort_order' => $sort]
            );

            foreach ($info['thanas'] as $index => $thana) {
                Location::updateOrCreate(
                    ['name' => $thana[0], 'type' => 'thana', 'parent_id' => $district->id],
                    ['name_bn' => $thana[1], 'sort_order' => $index + 1]
                );
            }
        }
    }
}
