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
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'main_title'   => 'Rekomendasi Terbaik',
                'main_desc_1'  => 'Eksplorasi rasa kopi...',
                'main_desc_2'  => 'Dinikmati lebih dari <b>40 juta</b>...',
                'second_title' => 'Rekomendasi Menu Terbaik',
                'second_desc'  => 'Pilihan terbaik yang telah teruji...',
            ]
        );
    }
}
