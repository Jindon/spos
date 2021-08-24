<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'invoice_date' => 'date'
    ];

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function scopeFromDate(Builder $query, $fromDate)
    {
        return $query->whereDate('invoice_date', '>=', Carbon::parse($fromDate));
    }

    public function scopeToDate(Builder $query, $toDate)
    {
        return $query->whereDate('invoice_date', '<=', Carbon::parse($toDate));
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where('invoice_no', 'LIKE', '%' . $search . '%')
            ->orWhere('customer_name', 'LIKE', '%' . $search . '%');
    }

    public function scopeCustomerType(Builder $query, $customer_type)
    {
        $types = [
            'all' => [0,1],
            'retail' => [1],
            'b2b' => [0]
        ];
        return $query->whereIn('retail', $types[$customer_type]);
    }

    public function getAdditionalTaxInfoAttribute()
    {
        return $this->invoiceItems()
            ->selectRaw("tax, SUM(tax_amount) as tax_total")
            ->selectRaw("SUM(taxable_amount) as taxable_total")
            ->selectRaw("SUM(total) as total_sum")
            // ->where('tax', '>', 0)
            ->groupBy('tax')
            ->get()?->toArray();
    }
}
