<?php

namespace App\Repositories;

use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use App\Models\CustomerGroup;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function getAllCustomers()
    {
        return Customer::all();
    }

    public function getCustomerById($customerId)
    {
        return Customer::findOrFail($customerId);
    }

    public function getCustomerByIdWithGroups($customerId)
    {
        return Customer::findOrFail($customerId)->load('groups');
    }

    public function getCustomerGroups($customerId)
    {
        return Customer::findOrFail($customerId)->groups;
    }

    public function deleteCustomer($customerId)
    {
        return Customer::destroy($customerId);
    }

    public function createCustomer(array $customerDetails)
    {
        return Customer::create($customerDetails);
    }

    public function updateCustomer($customerId, array $newDetails)
    {
        return Customer::whereId($customerId)->update($newDetails);
    }

    public function attachGroup($customerId, $groupId)
    {
        $customer = Customer::findOrFail($customerId);
        CustomerGroup::findOrFail($groupId);
        $customer->groups()->attach($groupId);
    }

    public function detachGroup($customerId, $groupId)
    {
        $customer = Customer::findOrFail($customerId);
        $customer->groups()->detach($groupId);
    }
}