<?php

namespace App\Repositories;

use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use App\Models\CustomerGroup;
use Nette\NotImplementedException;

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
        Customer::destroy($customerId);
    }

    public function createCustomer(array $customerDetails)
    {
        return Customer::create($customerDetails);
    }

    public function updateCustomer($customerId, array $newDetails)
    {
        return Customer::whereId($customerId)->update($newDetails);
    }
}