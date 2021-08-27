<?php

namespace App\Actions\Invoice;

use App\Actions\Customer\SaveCustomer;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Setting;
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

            $this->saveCustomer($attributes);

            DB::commit();

            $invoice->fresh();
            $invoice->load('invoiceItems');
            return $invoice;
        } catch (\Exception $error) {
            DB::rollBack();
            throw $error;
        }
    }

    protected function getAdditionInvoiceData(array $attributes)
    {
        $invoiceData = Arr::except($attributes, ['items']);
        $items = $attributes['items'];
        $itemsCollection = collect($items);
        $taxAmount = $itemsCollection->sum('tax_amount');
        $cgst = $sgst = round($taxAmount / 2, 2);

        if ($attributes['retail']) {
            $invoiceData['customer_name'] = null;
            $invoiceData['customer_address'] = null;
            $invoiceData['customer_gstin'] = null;
            $invoiceData['customer_pan'] = null;
        }

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

    protected function saveCustomer(array $attributes)
    {
        $shopId = auth()->user()->defaultShop()->id;
        $settings = Setting::where('shop_id', $shopId)->first();
        if (
            $settings?->save_customers
            && data_get($attributes, 'customer_gstin', null)
        ) {
            if (
                ! Customer::where('shop_id', $shopId)
                    ->where('gstin', $attributes['customer_gstin'])
                    ->exists()
            ) {
                $saveCustomer = new SaveCustomer();
                $saveCustomer->handle([
                    'state_id' => $attributes['state_id'],
                    'name' => $attributes['customer_name'],
                    'address' => data_get($attributes, 'customer_address', null),
                    'gstin' => $attributes['customer_gstin'],
                    'pan' => data_get($attributes, 'customer_pan', null),
                ]);
            }
        }
    }
}
