<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('123456');
        $adminRecords = [
            ['name' => 'Admin', 'type' => 'admin', 'mobile' => 911000222, 'email' => 'admin@admin.com', 'password' => $password, 'image' => '', 'status' => 1],
            ['name' => 'staff1', 'type' => 'staff', 'mobile' => 1234567890, 'email' => 'staff@staff.com', 'password' => $password, 'image' => '', 'status' => 1]
        ];
        Admin::insert($adminRecords);
    }
}
