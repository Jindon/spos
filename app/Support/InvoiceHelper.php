<?php

namespace App\Support;

use App\Models\Invoice;
use Illuminate\Support\Carbon;

class InvoiceHelper
{
    public function getLatestInvoiceCount($month, $shopId)
    {
        $latestInvoice = Invoice::whereMonth('invoice_date', $month)
            ->where('shop_id', $shopId)
            ->whereMonth('created_at', Carbon::now())
            ->latest()->first();

        if (!$latestInvoice) {
            return 0;
        }

        $latestInvoiceNo = $latestInvoice->invoice_no;
        $latestCount = substr($latestInvoiceNo, strpos($latestInvoiceNo, "-") + 1);
        return $latestCount;
    }
}
