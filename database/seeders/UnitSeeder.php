<?php

namespace Database\Seeders;

use App\Models\Master\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'name' => 'Kilograms',
                'symbol' => 'Kg',
            ],
            [
                'name' => 'Piece',
                'symbol' => 'pc',
            ],
        ];

        collect($units)->each(function ($unit) {
            Unit::create($unit);
        });

    }
}
