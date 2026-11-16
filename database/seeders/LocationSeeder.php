<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    private array $districts = [
        'Dhaka' => ['bn' => 'ঢাকা', 'thanas' => ['Dhamrai', 'Dohar', 'Keraniganj', 'Nawabganj', 'Savar']],
        'Faridpur' => ['bn' => 'ফরিদপুর', 'thanas' => ['Alfadanga', 'Bhanga', 'Boalmari', 'Charbhadrasan', 'Faridpur Sadar', 'Madhukhali', 'Nagarkanda', 'Sadarpur', 'Saltha']],
        'Gazipur' => ['bn' => 'গাজীপুর', 'thanas' => ['Gazipur Sadar', 'Kaliakair', 'Kaliganj', 'Kapasia', 'Sreepur']],
        'Gopalganj' => ['bn' => 'গোপালগঞ্জ', 'thanas' => ['Gopalganj Sadar', 'Kashiani', 'Kotalipara', 'Muksudpur', 'Tungipara']],
        'Kishoreganj' => ['bn' => 'কিশোরগঞ্জ', 'thanas' => ['Austagram', 'Bajitpur', 'Bhairab', 'Hossainpur', 'Itna', 'Karimganj', 'Katiadi', 'Kishoreganj Sadar', 'Kuliarchar', 'Mithamain', 'Nikli', 'Pakundia', 'Tarail']],
        'Madaripur' => ['bn' => 'মাদারীপুর', 'thanas' => ['Kalkini', 'Madaripur Sadar', 'Rajoir', 'Shibchar']],
        'Manikganj' => ['bn' => 'মানিকগঞ্জ', 'thanas' => ['Daulatpur', 'Ghior', 'Harirampur', 'Manikganj Sadar', 'Saturia', 'Shivalaya', 'Singair']],
        'Munshiganj' => ['bn' => 'মুন্সিগঞ্জ', 'thanas' => ['Gazaria', 'Lohajang', 'Munshiganj Sadar', 'Sirajdikhan', 'Srinagar', 'Tongibari']],
        'Narayanganj' => ['bn' => 'নারায়ণগঞ্জ', 'thanas' => ['Araihazar', 'Bandar', 'Narayanganj Sadar', 'Rupganj', 'Sonargaon']],
        'Narsingdi' => ['bn' => 'নরসিংদী', 'thanas' => ['Belabo', 'Monohardi', 'Narsingdi Sadar', 'Palash', 'Raipura', 'Shibpur']],
        'Rajbari' => ['bn' => 'রাজবাড়ী', 'thanas' => ['Baliakandi', 'Goalandaghat', 'Kalukhali', 'Pangsha', 'Rajbari Sadar']],
        'Shariatpur' => ['bn' => 'শরীয়তপুর', 'thanas' => ['Bhedarganj', 'Damudya', 'Gosairhat', 'Naria', 'Shariatpur Sadar']],
        'Tangail' => ['bn' => 'টাঙ্গাইল', 'thanas' => ['Basail', 'Bhuapur', 'Delduar', 'Dhanbari', 'Ghatail', 'Gopalpur', 'Kalihati', 'Madhupur', 'Mirzapur', 'Nagarpur', 'Sakhipur', 'Tangail Sadar']],

        'Bandarban' => ['bn' => 'বান্দরবান', 'thanas' => ['Ali Kadam', 'Bandarban Sadar', 'Lama', 'Naikhongchhari', 'Rowangchhari', 'Ruma', 'Thanchi']],
        'Brahmanbaria' => ['bn' => 'ব্রাহ্মণবাড়িয়া', 'thanas' => ['Akhaura', 'Ashuganj', 'Bancharampur', 'Bijoynagar', 'Brahmanbaria Sadar', 'Kasba', 'Nabinagar', 'Nasirnagar', 'Sarail']],
        'Chandpur' => ['bn' => 'চাঁদপুর', 'thanas' => ['Chandpur Sadar', 'Faridganj', 'Haimchar', 'Haziganj', 'Kachua', 'Matlab Dakshin', 'Matlab Uttar', 'Shahrasti']],
        'Chattogram' => ['bn' => 'চট্টগ্রাম', 'thanas' => ['Anwara', 'Banshkhali', 'Boalkhali', 'Chandanaish', 'Fatikchhari', 'Hathazari', 'Karnaphuli', 'Lohagara', 'Mirsharai', 'Patiya', 'Rangunia', 'Raozan', 'Satkania', 'Sitakunda']],
        'Cumilla' => ['bn' => 'কুমিল্লা', 'thanas' => ['Barura', 'Brahmanpara', 'Burichang', 'Chandina', 'Chauddagram', 'Cumilla Adarsha Sadar', 'Cumilla Sadar Dakshin', 'Daudkandi', 'Debidwar', 'Homna', 'Laksam', 'Meghna', 'Monohorgonj', 'Muradnagar', 'Nangalkot', 'Titas']],
        "Cox's Bazar" => ['bn' => 'কক্সবাজার', 'thanas' => ['Chakaria', "Cox's Bazar Sadar", 'Kutubdia', 'Maheshkhali', 'Pekua', 'Ramu', 'Teknaf', 'Ukhia']],
        'Feni' => ['bn' => 'ফেনী', 'thanas' => ['Chhagalnaiya', 'Daganbhuiyan', 'Feni Sadar', 'Fulgazi', 'Parshuram', 'Sonagazi']],
        'Khagrachhari' => ['bn' => 'খাগড়াছড়ি', 'thanas' => ['Dighinala', 'Khagrachhari Sadar', 'Lakshmichhari', 'Mahalchhari', 'Manikchhari', 'Matiranga', 'Panchhari', 'Ramgarh']],
        'Lakshmipur' => ['bn' => 'লক্ষ্মীপুর', 'thanas' => ['Kamalnagar', 'Lakshmipur Sadar', 'Raipur', 'Ramganj', 'Ramgati']],
        'Noakhali' => ['bn' => 'নোয়াখালী', 'thanas' => ['Begumganj', 'Chatkhil', 'Companiganj', 'Hatiya', 'Kabirhat', 'Noakhali Sadar', 'Senbagh', 'Sonaimuri', 'Subarnachar']],
        'Rangamati' => ['bn' => 'রাঙ্গামাটি', 'thanas' => ['Bagaichhari', 'Barkal', 'Belaichhari', 'Juraichhari', 'Kaptai', 'Kawkhali', 'Langadu', 'Naniarchar', 'Rajasthali', 'Rangamati Sadar']],

        'Bogura' => ['bn' => 'বগুড়া', 'thanas' => ['Adamdighi', 'Bogura Sadar', 'Dhunat', 'Dhupchanchia', 'Gabtali', 'Kahaloo', 'Nandigram', 'Sariakandi', 'Shajahanpur', 'Sherpur', 'Shibganj', 'Sonatala']],
        'Chapainawabganj' => ['bn' => 'চাঁপাইনবাবগঞ্জ', 'thanas' => ['Bholahat', 'Chapainawabganj Sadar', 'Gomastapur', 'Nachole', 'Shibganj']],
        'Joypurhat' => ['bn' => 'জয়পুরহাট', 'thanas' => ['Akkelpur', 'Joypurhat Sadar', 'Kalai', 'Khetlal', 'Panchbibi']],
        'Naogaon' => ['bn' => 'নওগাঁ', 'thanas' => ['Atrai', 'Badalgachhi', 'Dhamoirhat', 'Manda', 'Mohadevpur', 'Naogaon Sadar', 'Niamatpur', 'Patnitala', 'Porsha', 'Raninagar', 'Sapahar']],
        'Natore' => ['bn' => 'নাটোর', 'thanas' => ['Bagatipara', 'Baraigram', 'Gurudaspur', 'Lalpur', 'Naldanga', 'Natore Sadar', 'Singra']],
        'Pabna' => ['bn' => 'পাবনা', 'thanas' => ['Atgharia', 'Bera', 'Bhangura', 'Chatmohar', 'Faridpur', 'Ishwardi', 'Pabna Sadar', 'Santhia', 'Sujanagar']],
        'Rajshahi' => ['bn' => 'রাজশাহী', 'thanas' => ['Bagha', 'Bagmara', 'Charghat', 'Durgapur', 'Godagari', 'Mohanpur', 'Paba', 'Puthia', 'Tanore']],
        'Sirajganj' => ['bn' => 'সিরাজগঞ্জ', 'thanas' => ['Belkuchi', 'Chauhali', 'Kamarkhanda', 'Kazipur', 'Raiganj', 'Shahjadpur', 'Sirajganj Sadar', 'Tarash', 'Ullapara']],

        'Bagerhat' => ['bn' => 'বাগেরহাট', 'thanas' => ['Bagerhat Sadar', 'Chitalmari', 'Fakirhat', 'Kachua', 'Mollahat', 'Mongla', 'Morrelganj', 'Rampal', 'Sarankhola']],
        'Chuadanga' => ['bn' => 'চুয়াডাঙ্গা', 'thanas' => ['Alamdanga', 'Chuadanga Sadar', 'Damurhuda', 'Jibannagar']],
        'Jashore' => ['bn' => 'যশোর', 'thanas' => ['Abhaynagar', 'Bagherpara', 'Chaugachha', 'Jashore Sadar', 'Jhikargachha', 'Keshabpur', 'Manirampur', 'Sharsha']],
        'Jhenaidah' => ['bn' => 'ঝিনাইদহ', 'thanas' => ['Harinakunda', 'Jhenaidah Sadar', 'Kaliganj', 'Kotchandpur', 'Maheshpur', 'Shailkupa']],
        'Khulna' => ['bn' => 'খুলনা', 'thanas' => ['Batiaghata', 'Dacope', 'Dighalia', 'Dumuria', 'Koyra', 'Paikgachha', 'Phultala', 'Rupsa', 'Terokhada']],
        'Kushtia' => ['bn' => 'কুষ্টিয়া', 'thanas' => ['Bheramara', 'Daulatpur', 'Khoksa', 'Kumarkhali', 'Kushtia Sadar', 'Mirpur']],
        'Magura' => ['bn' => 'মাগুরা', 'thanas' => ['Magura Sadar', 'Mohammadpur', 'Shalikha', 'Sreepur']],
        'Meherpur' => ['bn' => 'মেহেরপুর', 'thanas' => ['Gangni', 'Meherpur Sadar', 'Mujibnagar']],
        'Narail' => ['bn' => 'নড়াইল', 'thanas' => ['Kalia', 'Lohagara', 'Narail Sadar']],
        'Satkhira' => ['bn' => 'সাতক্ষীরা', 'thanas' => ['Assasuni', 'Debhata', 'Kalaroa', 'Kaliganj', 'Satkhira Sadar', 'Shyamnagar', 'Tala']],

        'Barguna' => ['bn' => 'বরগুনা', 'thanas' => ['Amtali', 'Bamna', 'Barguna Sadar', 'Betagi', 'Patharghata', 'Taltali']],
        'Barishal' => ['bn' => 'বরিশাল', 'thanas' => ['Agailjhara', 'Babuganj', 'Bakerganj', 'Banaripara', 'Barishal Sadar', 'Gaurnadi', 'Hizla', 'Mehendiganj', 'Muladi', 'Wazirpur']],
        'Bhola' => ['bn' => 'ভোলা', 'thanas' => ['Bhola Sadar', 'Burhanuddin', 'Char Fasson', 'Daulatkhan', 'Lalmohan', 'Manpura', 'Tazumuddin']],
        'Jhalokati' => ['bn' => 'ঝালকাঠি', 'thanas' => ['Jhalokati Sadar', 'Kathalia', 'Nalchity', 'Rajapur']],
        'Patuakhali' => ['bn' => 'পটুয়াখালী', 'thanas' => ['Bauphal', 'Dashmina', 'Dumki', 'Galachipa', 'Kalapara', 'Mirzaganj', 'Patuakhali Sadar', 'Rangabali']],
        'Pirojpur' => ['bn' => 'পিরোজপুর', 'thanas' => ['Bhandaria', 'Kawkhali', 'Mathbaria', 'Nazirpur', 'Nesarabad', 'Pirojpur Sadar', 'Zianagar']],

        'Habiganj' => ['bn' => 'হবিগঞ্জ', 'thanas' => ['Ajmiriganj', 'Bahubal', 'Baniyachong', 'Chunarughat', 'Habiganj Sadar', 'Lakhai', 'Madhabpur', 'Nabiganj', 'Shayestaganj']],
        'Moulvibazar' => ['bn' => 'মৌলভীবাজার', 'thanas' => ['Barlekha', 'Juri', 'Kamalganj', 'Kulaura', 'Moulvibazar Sadar', 'Rajnagar', 'Sreemangal']],
        'Sunamganj' => ['bn' => 'সুনামগঞ্জ', 'thanas' => ['Bishwamvarpur', 'Chhatak', 'Derai', 'Dharampasha', 'Dowarabazar', 'Jagannathpur', 'Jamalganj', 'Shantiganj', 'Sullah', 'Sunamganj Sadar', 'Tahirpur']],
        'Sylhet' => ['bn' => 'সিলেট', 'thanas' => ['Balaganj', 'Beanibazar', 'Bishwanath', 'Companiganj', 'Dakshin Surma', 'Fenchuganj', 'Golapganj', 'Gowainghat', 'Jaintiapur', 'Kanaighat', 'Osmani Nagar', 'Sylhet Sadar', 'Zakiganj']],

        'Dinajpur' => ['bn' => 'দিনাজপুর', 'thanas' => ['Birampur', 'Birganj', 'Biral', 'Bochaganj', 'Chirirbandar', 'Dinajpur Sadar', 'Ghoraghat', 'Hakimpur', 'Kaharole', 'Khansama', 'Nawabganj', 'Parbatipur', 'Phulbari']],
        'Gaibandha' => ['bn' => 'গাইবান্ধা', 'thanas' => ['Gaibandha Sadar', 'Gobindaganj', 'Palashbari', 'Phulchhari', 'Sadullapur', 'Saghata', 'Sundarganj']],
        'Kurigram' => ['bn' => 'কুড়িগ্রাম', 'thanas' => ['Bhurungamari', 'Char Rajibpur', 'Chilmari', 'Kurigram Sadar', 'Nageshwari', 'Phulbari', 'Rajarhat', 'Raumari', 'Ulipur']],
        'Lalmonirhat' => ['bn' => 'লালমনিরহাট', 'thanas' => ['Aditmari', 'Hatibandha', 'Kaliganj', 'Lalmonirhat Sadar', 'Patgram']],
        'Nilphamari' => ['bn' => 'নীলফামারী', 'thanas' => ['Dimla', 'Domar', 'Jaldhaka', 'Kishoreganj', 'Nilphamari Sadar', 'Saidpur']],
        'Panchagarh' => ['bn' => 'পঞ্চগড়', 'thanas' => ['Atwari', 'Boda', 'Debiganj', 'Panchagarh Sadar', 'Tetulia']],
        'Rangpur' => ['bn' => 'রংপুর', 'thanas' => ['Badarganj', 'Gangachara', 'Kaunia', 'Mithapukur', 'Pirgachha', 'Pirganj', 'Rangpur Sadar', 'Taraganj']],
        'Thakurgaon' => ['bn' => 'ঠাকুরগাঁও', 'thanas' => ['Baliadangi', 'Haripur', 'Pirganj', 'Ranisankail', 'Thakurgaon Sadar']],

        'Jamalpur' => ['bn' => 'জামালপুর', 'thanas' => ['Bakshiganj', 'Dewanganj', 'Islampur', 'Jamalpur Sadar', 'Madarganj', 'Melandaha', 'Sarishabari']],
        'Mymensingh' => ['bn' => 'ময়মনসিংহ', 'thanas' => ['Bhaluka', 'Dhobaura', 'Fulbaria', 'Gaffargaon', 'Gauripur', 'Haluaghat', 'Ishwarganj', 'Muktagachha', 'Mymensingh Sadar', 'Nandail', 'Phulpur', 'Tarakanda', 'Trishal']],
        'Netrokona' => ['bn' => 'নেত্রকোণা', 'thanas' => ['Atpara', 'Barhatta', 'Durgapur', 'Kalmakanda', 'Kendua', 'Khaliajuri', 'Madan', 'Mohanganj', 'Netrokona Sadar', 'Purbadhala']],
        'Sherpur' => ['bn' => 'শেরপুর', 'thanas' => ['Jhenaigati', 'Nakla', 'Nalitabari', 'Sherpur Sadar']],
    ];

    public function run(): void
    {
        $sort = 0;

        foreach ($this->districts as $name => $info) {
            $sort++;

            $district = Location::firstOrCreate(
                ['name' => $name, 'type' => 'district'],
                ['name_bn' => $info['bn'], 'sort_order' => $sort]
            );

            foreach ($info['thanas'] as $index => $thana) {
                Location::firstOrCreate(
                    ['name' => $thana, 'type' => 'thana', 'parent_id' => $district->id],
                    ['sort_order' => $index + 1]
                );
            }
        }
    }
}
