<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        Schema::disableForeignKeyConstraints();

        DB::table('alternatives')->truncate();
        DB::table('alternativecriterias')->truncate();

        Schema::enableForeignKeyConstraints();
    }
}
