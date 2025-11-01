<?php

namespace App\Models\Master;

use App\Models\Purchase\PurchaseOrder;
use App\Models\Traits\HasCachedOptions;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounting\SupplierLedger;

class Supplier extends Model
{
    use HasCachedOptions;
    protected $fillable = [
        'name',
        'email',
        'contact',
        'address',
        'agreed_upon_rate_per_unit',
    ];

    // 🧾 A supplier can have multiple purchase orders
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function ledgers()
    {
        return $this->hasMany(SupplierLedger::class);
    }


    // 🔁 (Optional) If you later have a Supplier Ledger / Payments table
    // public function transactions()
    // {
    //     return $this->hasMany(SupplierTransaction::class);
    // }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    public function getTotalDebitAttribute()
    {
        return $this->ledgers()->sum('debit');
    }

    public function getTotalCreditAttribute()
    {
        return $this->ledgers()->sum('credit');
    }

    public function getBalanceAttribute()
    {
        return $this->total_credit - $this->total_debit;
    }


    // Display name with rate for dropdowns or summaries
    public function getDisplayNameAttribute(): string
    {
        $rate = number_format($this->agreed_upon_rate_per_unit, 2);
        return "{$this->name} (Rate: {$rate})";
    }

    // Handy short contact summary
    public function getContactSummaryAttribute(): string
    {
        $email = $this->email ?: 'No email';
        $phone = $this->contact ?: 'No contact';
        return "{$phone} / {$email}";
    }
}
