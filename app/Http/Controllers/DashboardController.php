<?php

namespace App\Http\Controllers;

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
            'invoice_count' => $this->getInvoiceCount($shopId),
            'top_customers' => $this->getTopCustomers($shopId)
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
            ->where('walk_in_customer', 0)
            ->select('customer_name', 'customer_gstin', 'customer_pan')
            ->selectRaw("SUM(total) as sold_total, SUM(tax) as tax_total")
            ->orderByDesc('sold_total')
            ->groupBy('customer_name', 'customer_gstin', 'customer_pan')
            ->take(5)
            ->get();
    }

    public function getInvoiceCount($shopId)
    {
        return QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::scope('from_date'),
                AllowedFilter::scope('to_date'),
            ])
            ->where('shop_id', $shopId)
            ->count();
    }
}
