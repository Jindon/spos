<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaxSummaryResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $shopId = auth()->user()->defaultShop()->id;
        $report = [
            'most_sold_items' => $this->getMostSoldItems($shopId),
            'invoice_counts' => $this->getInvoiceCounts($shopId),
            'top_customers' => $this->getTopCustomers($shopId),
            'tax_summary' => $this->taxSummary($shopId),
        ];

        return new JsonResource($report);
    }

    protected function getMostSoldItems($shopId)
    {
        return QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::scope('from_date'),
                AllowedFilter::scope('to_date'),
            ])
            ->where('invoices.shop_id', $shopId)
            ->join('invoice_items', 'invoice_items.invoice_id', 'invoices.id')
            ->selectRaw("invoice_items.name, invoice_items.tax as tax_rate, SUM(invoice_items.quantity) AS sold_count, SUM(invoice_items.total) as sold_total")
            ->groupBy('name', 'tax_rate')
            ->orderByDesc('sold_count')
            ->take(5)
            ->get();
    }

    protected function getTopCustomers($shopId)
    {
        return QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::scope('from_date'),
                AllowedFilter::scope('to_date'),
            ])
            ->where('shop_id', $shopId)
            ->where('retail', 0)
            ->select('customer_name', 'customer_gstin', 'customer_pan')
            ->selectRaw("SUM(total) as sold_total, SUM(tax) as tax_total")
            ->orderByDesc('sold_total')
            ->groupBy('customer_name', 'customer_gstin', 'customer_pan')
            ->take(5)
            ->get();
    }

    public function getInvoiceCounts($shopId)
    {
        $query = QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::scope('from_date'),
                AllowedFilter::scope('to_date'),
            ])->where('shop_id', $shopId);

        $query1 = clone $query;
        $query2 = clone $query;
        return [
                'total' => $query->count(),
                'retail' => $query1->where('retail', 1)->count(),
                'b2b' => $query2->where('retail', '=', 0)->count(),
            ];
    }

    public function taxSummary($shopId)
    {
        $summary = QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::scope('from_date'),
                AllowedFilter::scope('to_date'),
            ])
            ->where('invoices.shop_id', $shopId)
            ->join('invoice_items', 'invoice_items.invoice_id', 'invoices.id')
            ->select('invoice_items.tax')
            ->selectRaw("SUM(invoice_items.total) as sum_total")
            ->selectRaw("SUM(invoice_items.taxable_amount) as taxable_total, SUM(invoice_items.tax_amount) as tax_total")
            ->groupBy('invoice_items.tax')
            ->take(5)
            ->get();

        return TaxSummaryResource::collection($summary);
    }
}
