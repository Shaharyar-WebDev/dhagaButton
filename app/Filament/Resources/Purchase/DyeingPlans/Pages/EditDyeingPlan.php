<?php

namespace App\Filament\Resources\Purchase\DyeingPlans\Pages;

use App\Filament\Resources\Purchase\DyeingPlans\DyeingPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDyeingPlan extends EditRecord
{
    protected static string $resource = DyeingPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
