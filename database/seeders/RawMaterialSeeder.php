<?php

namespace Database\Seeders;

use App\Models\Master\Unit;
use Illuminate\Database\Seeder;
use App\Models\Master\RawMaterial;
use App\Models\Master\RawMaterialType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $yarnType = RawMaterialType::where('name', 'yarns')->first();
        $twistedYarnType = RawMaterialType::where('name', 'twisted_yarn')->first();
        $boxesType = RawMaterialType::where('name', 'boxes')->first();
        $labelsType = RawMaterialType::where('name', 'labels')->first();
        $spoolsType = RawMaterialType::where('name', 'spools')->first();

        $kgUnit = Unit::where('symbol', 'Kg')->first();
        $pcUnit = Unit::where('symbol', 'pc')->first();

        $rawMaterials = [
            [
                'name' => 'Polyester 50/S Yarn',
                'unit_id' => $kgUnit->id,
                'raw_material_type_id' => $yarnType->id,
            ],
            [
                'name' => 'Polyester 50/3 Yarn',
                'unit_id' => $kgUnit->id,
                'raw_material_type_id' => $twistedYarnType->id,
            ],
            [
                'name' => 'Box',
                'unit_id' => $pcUnit->id,
                'raw_material_type_id' => $boxesType->id,
            ],
            [
                'name' => 'Label',
                'unit_id' => $pcUnit->id,
                'raw_material_type_id' => $labelsType->id,
            ],
            [
                'name' => 'Spool',
                'unit_id' => $pcUnit->id,
                'raw_material_type_id' => $spoolsType->id,
            ],
        ];

        collect($rawMaterials)->each(function ($rawMaterial) {
            RawMaterial::create($rawMaterial);
        });
    }
}
