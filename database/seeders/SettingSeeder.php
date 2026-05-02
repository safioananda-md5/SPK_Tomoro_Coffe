<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate([
            'id' => 1
        ], [
            'main_title' => 'Rekomendasi Terbaik',
            'main_desc_1' => 'Eksplorasi rasa kopi kelas dunia dengan harga yang bersahabat. Pilih menu favoritmu sekarang dan rasakan standar baru dalam menikmati kopi berkualitas tinggi.',
            'main_desc_2' => 'Dinikmati lebih dari penikmat Tomoro Coffee di Indonesia sampai detik ini',
            'second_title' => 'Rekomendasi Menu Terbaik',
            'second_desc' => 'Pilihan terbaik yang telah teruji. Kami menyeleksi setiap rasa dengan kriteria yang ketat untuk memastikan hanya rasa yang paling dicintai pelanggan yang sampai ke tangan Anda. Dijamin ketagihan!',
        ]);
    }
}
