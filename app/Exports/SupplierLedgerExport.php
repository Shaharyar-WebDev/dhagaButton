<?php

namespace App\Exports;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Models\Purchase\DeliveryOrder;
use App\Models\Accounting\SupplierLedger;
use App\Models\Purchase\GoodsReceivedNote;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, ShouldAutoSize, WithMapping};

class SupplierLedgerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    protected int $supplierId;
    protected string $supplierName;
    protected ?DateTimeInterface $from;
    protected ?DateTimeInterface $to;

    public function __construct(int $supplierId, string $supplierName, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null)
    {
        $this->supplierId = $supplierId;
        $this->supplierName = $supplierName;
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $query = SupplierLedger::where('supplier_id', $this->supplierId)
            ->orderBy('date', 'asc');

        if ($this->from) {
            $query->where('date', '>=', $this->from);
        }
        if ($this->to) {
            $query->where('date', '<=', $this->to);
        }

        return $query->get();
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_00, // Qty Received
            'G' => NumberFormat::FORMAT_NUMBER_00, // Debit
            'H' => NumberFormat::FORMAT_NUMBER_00, // Credit
            'I' => NumberFormat::FORMAT_NUMBER_00, // Balance
        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'Supplier',
            'Transaction Type',
            'Transaction Reference',
            'Reference No',
            // 'Qty Received',
            'Debit (PKR)',
            'Credit (PKR)',
            'Balance (PKR)',
            'Details / Summary',
            'Remarks',
        ];
    }

    public function map($row): array
    {
        $qty = 0.0;
        $debit = $row->debit;
        $credit = $row->credit;
        $balance = $row->balance;
        $transactionRef = ''; // initialize in case source is missing
        $details = '';
        $sourceRemarks = '';
        $sourceType = $row->source_type ? class_basename($row->source_type) : '';

        if ($row->source) {
            $source = $row->source;

            // Transaction Reference (getTitleAttributeName or fallback)
            if (method_exists($source, 'getTitleAttributeName')) {
                $transactionRef = $source->getTitleAttributeName();
            } elseif (property_exists($source, 'reference_no')) {
                $transactionRef = $source->reference_no;
            } else {
                $transactionRef = class_basename($row->source_type) . '-' . $source->id;
            }

        }

        // Qty
        if ($row->source_type === GoodsReceivedNote::class && $row->source) {
            $grn = $row->source;

            if ($grn->relationLoaded('items') || method_exists($grn, 'items')) {
                $qty = (float) $grn->items->sum('quantity'); // keep as float
                $details = $grn->items
                    ->groupBy(fn($item) => $item->brand->name ?? 'Unknown')
                    ->map(fn($items, $brand) => $items->sum('quantity') . ' ' . $brand)
                    ->values()
                    ->join(', ');
            }
            $sourceRemarks = $grn->remarks ?? '';
        }

        // Same for Delivery Order
        if ($row->source_type === DeliveryOrder::class && $row->source) {
            $do = $row->source;

            if ($do->relationLoaded('items') || method_exists($do, 'items')) {
                $qty = (float) $do->items->sum('quantity'); // numeric float only
                $details = $do->items
                    ->groupBy(fn($item) => $item->brand->name ?? 'Unknown')
                    ->map(fn($items, $brand) => $items->sum('quantity') . ' ' . $brand)
                    ->values()
                    ->join(', ');
            } else {
                $qty = (float) ($do->quantity ?? 0); // numeric float
                $brandName = $do->brand->name ?? 'Unknown';
                $details = "{$do->quantity} {$brandName}";
            }
            $sourceRemarks = $do->remarks ?? '';
        }


        return [
            $row->date ? Carbon::parse($row->date)->format('d M Y') : '',
            $this->supplierName,
            $sourceType,
            $transactionRef,
            $row->reference_no ?? '',
            // $qty ?: 0, // ensure numeric 0
            $debit ?: 0.00,
            $credit ?: 0.00,
            $balance ?: 0.00,
            $details,
            $sourceRemarks,
        ];

    }
}
