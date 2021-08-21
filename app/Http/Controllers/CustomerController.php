<?php

namespace App\Http\Controllers;

use App\Actions\Customer\SaveCustomer;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CustomerController extends Controller
{
    public function index()
    {
        $shopId = auth()->user()->defaultShop()->id;
        $invoices = QueryBuilder::for(Customer::class)
            ->where('shop_id', $shopId)
            ->allowedFilters([
                AllowedFilter::scope('search'),
            ])
            ->latest()
            ->paginate(request('limit', 10));
        return CustomerResource::collection($invoices);
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    public function store(CustomerRequest $request, SaveCustomer $saveCustomer)
    {
        $attributes = $request->validated();
        $customer = $saveCustomer->handle($attributes);
        return new CustomerResource($customer);
    }

    public function update(CustomerRequest $request, Customer $customer, SaveCustomer $saveCustomer)
    {
        $attributes = $request->validated();
        $customer = $saveCustomer->handle($attributes, $customer);
        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return response()->json([
                'message' => 'Customer successfully deleted'
            ]);
        } catch (\Exception $error) {
            abort(400, $error->getMessage());
        }
    }
}
