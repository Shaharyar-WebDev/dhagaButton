<?php

namespace App\Models\Purchase;

use App\Models\Master\Supplier;
use App\Models\Master\RawMaterial;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory\DyerInventory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\TwisterInventory;
use App\Services\StockTransferRecordService;
use App\Models\Inventory\RawMaterialInventory;
use App\Filament\Support\Traits\NavigationGroup;
use App\Models\Purchase\StockTransferRecordItem;

class StockTransferRecord extends Model
{
    use NavigationGroup;
    protected $fillable = [
        'str_number',
        'raw_material_id',
        'from_supplier_id',
        'to_supplier_id',
        'challan_no',
        'challan_date',
        'status',
        'locked',
        'remarks',
    ];

    /**
     * Each stock transfer record belongs to one raw material.
     */
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    /**
     * Each stock transfer record may belong to one supplier.
     */
    public function fromSupplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function toSupplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(StockTransferRecordItem::class);
    }

    public static function generateStrNumber(): string
    {
        //  $datePart = now()->format('y-m-d-Hi'); // like PO-25-10-13-2235
        $datePart = now()->format('y-m-d-h:iA'); // like PO-25-10-13-10:35PM
        return 'STR-' . $datePart;
    }

    public function inventories()
    {
        return $this->morphMany(RawMaterialInventory::class, 'referenceable', 'reference_type', 'reference_id');
    }

    public function lock()
    {
        if (!$this->locked) {
            $this->updateQuietly(['locked' => true]);
        }
    }

    protected static function booted()
    {
        static::creating(function ($str) {
            if (!$str->str_number) {
                $str->str_number = self::generateStrNumber();
            }
        });

        static::saved(function ($str) {
            // Only trigger when STR is verified
            StockTransferRecordService::recordStr($str);
        });

        static::deleting(fn($record) => $record->inventories()->delete());

    }
}
