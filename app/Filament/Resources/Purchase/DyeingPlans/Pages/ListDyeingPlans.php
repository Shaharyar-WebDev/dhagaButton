<?php

namespace App\Filament\Resources\Purchase\DyeingPlans\Pages;

use App\Filament\Resources\Purchase\DyeingPlans\DyeingPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDyeingPlans extends ListRecords
{
    protected static string $resource = DyeingPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
