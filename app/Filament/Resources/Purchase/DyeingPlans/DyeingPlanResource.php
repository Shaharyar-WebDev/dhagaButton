<?php

namespace App\Filament\Resources\Purchase\DyeingPlans;

use App\Filament\Resources\Purchase\DyeingPlans\Pages\CreateDyeingPlan;
use App\Filament\Resources\Purchase\DyeingPlans\Pages\EditDyeingPlan;
use App\Filament\Resources\Purchase\DyeingPlans\Pages\ListDyeingPlans;
use App\Filament\Resources\Purchase\DyeingPlans\Schemas\DyeingPlanForm;
use App\Filament\Resources\Purchase\DyeingPlans\Tables\DyeingPlansTable;
use App\Filament\Support\Traits\NavigationGroup;
use App\Models\Purchase\DyeingPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DyeingPlanResource extends Resource
{
    use NavigationGroup;
    protected static ?string $model = DyeingPlan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $recordTitleAttribute = 'plan_number';

    public static function form(Schema $schema): Schema
    {
        return DyeingPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DyeingPlansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDyeingPlans::route('/'),
            'create' => CreateDyeingPlan::route('/create'),
            'edit' => EditDyeingPlan::route('/{record}/edit'),
        ];
    }
}
