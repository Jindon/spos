<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaveInvoice
{
    public function handle(array $attributes, Invoice $updateInvoice = null)
    {
        try {
            DB::beginTransaction();

            $invoiceData = $this->getAdditionInvoiceData($attributes);

            if ($updateInvoice) {
                $updateInvoice->invoiceItems()->delete();
                $updateInvoice->update($invoiceData);
                $invoice = $updateInvoice->fresh();
            } else {
                $invoice = Invoice::create($invoiceData);
            }

            $items = $attributes['items'];
            foreach ($items as $item) {
                $itemData = $this->getAdditionInvoiceItemData($item);
                $invoice->invoiceItems()->create($itemData);
            }

            $invoice->paid = 1;
            $invoice->save();
            DB::commit();

            $invoice->fresh();
            $invoice->load('invoiceItems');
            return $invoice;
        } catch (\Exception $error) {
            DB::rollBack();
            return $error;
        }
    }

    protected function getAdditionInvoiceData(array $attributes)
    {
        $invoiceData = Arr::except($attributes, ['items']);
        $items = $attributes['items'];
        $itemsCollection = collect($items);
        $taxAmount = $itemsCollection->sum('tax_amount');
        $cgst = $sgst = round($taxAmount / 2, 2);

        return array_merge($invoiceData, [
            'total' => $itemsCollection->sum('total') - (float) $attributes['discount'],
            'taxable' => $itemsCollection->sum('taxable_amount'),
            'sgst' => $sgst,
            'tax' => $taxAmount,
            'cgst' => $cgst,
            'user_id' => auth()->id(),
            'shop_id' => auth()->user()->defaultShop()->id,
        ]);
    }

    protected function getAdditionInvoiceItemData(array $item)
    {
        if (array_key_exists('inclusive', $item)) {
            $item['inclusive'] = $item['inclusive'] ?? false;
        }
        $data = [
            'user_id' => auth()->id(),
            'shop_id' => auth()->user()->defaultShop()?->id
        ];
        return array_merge($item, $data);
    }
}
