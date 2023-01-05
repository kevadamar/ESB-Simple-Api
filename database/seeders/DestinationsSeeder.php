<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DestinationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_destinations')->insert([
            [
                'code' => "D1",
                'name' => 'Dicovery Designs',
                'description' => "41 St Vincent Place Glasglow G1 2ER Scotland",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'code' => "D2",
                'name' => 'Barrington Publishers',
                'description' => "17 Great Suffolk Street London SE1 0NS United Kingdom",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()

            ]
        ]);
    }
}
