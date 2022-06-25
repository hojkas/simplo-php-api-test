<?php

namespace App\Interfaces;

interface CustomerRepositoryInterface
{
    public function getAllCustomers();
    public function getCustomerById($customerId);
    public function getCustomerByIdWithGroups($customerId);
    public function getCustomerGroups($customerId);
    public function deleteCustomer($customerId);
    public function createCustomer(array $customerDetails);
    public function updateCustomer($customerId, array $newDetails);
    public function attachGroup($customerId, $groupId);
    public function detachGroup($customerId, $groupId);
}