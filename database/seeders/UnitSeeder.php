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

        Unit::create([
            'name' => 'Bundle',
            'symbol' => 'bndl',
            'conversion_operator' => '*',
            'base_unit_id' => Unit::where('name', 'Kilograms')->first()->id,
            'conversion_value' => '4.5'
        ], );

        Unit::create([
            'name' => 'Bag',
            'symbol' => 'bag',
            'conversion_operator' => '*',
            'base_unit_id' => Unit::where('name', 'Bundle')->first()->id,
            'conversion_value' => '10'
        ]);

    }
}
