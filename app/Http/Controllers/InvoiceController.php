<?php

namespace App\Http\Controllers;

use App\Actions\Invoice\SaveInvoice;
use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Support\InvoiceHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceController extends Controller
{
    public function invoiceCount(Request $request, InvoiceHelper $invoiceHelper)
    {
        $month = Carbon::now()->month;
        $shopId = auth()->user()->defaultShop()->id;

        return response()->json([
            'count' => (int) $invoiceHelper->getLatestInvoiceCount($month, $shopId)
        ]);
    }

    public function index()
    {
        $shopId = auth()->user()->default_shop_id;
        $invoices = QueryBuilder::for(Invoice::class)
            ->where('shop_id', $shopId)
            ->allowedFilters([
                AllowedFilter::scope('from_date'),
                AllowedFilter::scope('to_date'),
                AllowedFilter::scope('search'),
                AllowedFilter::scope('customer_type'),
            ])
            ->latest()
            ->paginate(request('limit', 10));
        return InvoiceResource::collection($invoices);
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        return new InvoiceResource($invoice);
    }

    public function store(InvoiceRequest $request, SaveInvoice $saveInvoice)
    {
        $attributes = $request->validated();
        $invoice = $saveInvoice->handle($attributes);

        return new InvoiceResource($invoice);
    }

    public function update(InvoiceRequest $request, Invoice $invoice, SaveInvoice $saveInvoice)
    {
        $this->authorize('update', $invoice);
        $attributes = $request->validated();
        $invoice = $saveInvoice->handle($attributes, $invoice);

        return new InvoiceResource($invoice);
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);
        try {
            $invoice->invoiceItems()->delete();
            $invoice->delete();
            return $this->successResponse('Invoice successfully deleted');
        } catch (\Exception $exception) {
            return $this->failureResponse($exception->getMessage(), null, 400);
        }
    }
}
