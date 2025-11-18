<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $employees = [
            'Ade Sri Utari',
            'Bayu Lingga Imam Hidayat',
            'Andi Fatimah Humaerah',
            'Dirgahayu',
        ];

        foreach ($employees as $index => $name) {
            DB::table('employees')->insert([
                'employee_id' => 'EMP' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name' => $name,
                'email' => Str::slug($name, '.') . '@example.com',
                'address' => 'Makassar',
                'city' => 'Makassar',
                'state' => 'Sulawesi Selatan',
                'postal_code' => '90111',
                'image' => null,
                'sosmed' => json_encode([]),
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
