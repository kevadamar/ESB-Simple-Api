<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_services')->insert([
            [
                'code' => "S1",
                'description' => "Design",
                'm_service_type_id' => 1,
                'unit_price' => 230,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'code' => "S2",
                'description' => "Development",
                'm_service_type_id' => 1,
                'unit_price' => 330,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'code' => "S3",
                'description' => "Meetings",
                'm_service_type_id' => 1,
                'unit_price' => 60,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
