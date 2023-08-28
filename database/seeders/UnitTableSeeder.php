<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitRecords = [
            ['unit_name' => 'kg'],
            ['unit_name' => 'pcs'],
            ['unit_name' => 'pack'],
        ];
        Unit::insert($unitRecords);
    }
}
