<?php

namespace App\Filament\Support\Traits;

use App\Filament\Resources\Inventory\RawMaterialInventories\RawMaterialInventoryResource;
use App\Filament\Resources\Inventory\TwisterInventories\TwisterInventoryResource;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;
use App\Filament\Resources\Purchase\StockTransferRecords\StockTransferRecordResource;
use Illuminate\Support\Facades\Cache;
use App\Models\Purchase\StockTransferRecord;
use App\Filament\Resources\Master\Units\UnitResource;
use App\Filament\Resources\Master\Brands\BrandResource;
use App\Filament\Resources\Master\Suppliers\SupplierResource;
use App\Filament\Resources\Master\RawMaterials\RawMaterialResource;
use App\Filament\Resources\Purchase\DeliveryOrders\DeliveryOrderResource;
use App\Filament\Resources\Purchase\PurchaseOrders\PurchaseOrderResource;
use App\Models\Purchase\GoodsReceivedNote;

trait NavigationGroup
{
    /**
     * Create a new class instance.
     */
    protected static function getResources(): array
    {
        return [
            'Master' => [
                RawMaterialResource::class,
                BrandResource::class,
                UnitResource::class,
                SupplierResource::class,
            ],
            'Purchase' => [
                PurchaseOrderResource::class,
                StockTransferRecordResource::class,
                DeliveryOrderResource::class,
                GoodsReceivedNoteResource::class,

            ],
            'Stock Management' => [
                RawMaterialInventoryResource::class,
                TwisterInventoryResource::class
            ],
        ];
    }

    public static function getOrder(): array
    {
        $cacheKey = 'navigationGroup:getOrder';

        return Cache::rememberForever($cacheKey, function () {
            return [
                'Master',
                'Purchase',
                'Stock Management',
                'Settings'
            ];
        });
    }

    protected static function getCachedResources(): array
    {
        $cacheKey = 'navigationGroup:resources';

        return Cache::rememberForever($cacheKey, fn() => self::getResources());
    }

    public static function getNavigationGroup(): ?string
    {
        self::refreshNavigationCache();
        $resources = self::getCachedResources();

        foreach ($resources as $group => $classes) {
            if (in_array(self::class, $classes)) {
                return $group;
            }
        }

        return null;
    }

    public static function refreshNavigationCache()
    {
        Cache::forget('navigationGroup:resources');
        Cache::forget('navigationGroup:getOrder');
    }
}
