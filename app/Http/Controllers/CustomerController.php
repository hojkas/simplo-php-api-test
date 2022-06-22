<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Interfaces\CustomerRepositoryInterface;
use App\Repositories\CustomerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(): JsonResponse
    {
        $customers = $this->customerRepository->getAllCustomers();
        return response()->json(CustomerResource::collection($customers));
    }

    public function show(Request $request): JsonResponse
    {
        $customerId = $request->route('id');
        $show_groups = $request->query('showGroups');

        $customer = $this->customerRepository->getCustomerById($customerId);

        if($show_groups) $customer->load('groups');

        return response()->json(new CustomerResource($customer));
    }
}
