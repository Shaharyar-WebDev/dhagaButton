<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\RawMaterialType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RawMaterialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materialTypes = [
            [
                'name' => 'yarns'
            ],
            [
                'name' => 'boxes'
            ],
            [
                'name' => 'labels'
            ],
            [
                'name' => 'spools'
            ]
        ];

        collect($materialTypes)->each(function ($type) {
            RawMaterialType::create($type);
        });
    }
}
