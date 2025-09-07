<?php

namespace Database\Seeders;

use App\Models\Regency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Regency::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $csvFile = fopen(base_path("database/data/regencies.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
        if (!$firstline) {
        Regency::create([
        "name" => $data['0'],
        ]);
        }
        $firstline = false;
        }
        fclose($csvFile);
        }
    
}
