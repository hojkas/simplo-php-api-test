<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerGroupResource;
use App\Http\Resources\CustomerResource;
use App\Interfaces\CustomerRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        if($show_groups) $customer = $this->customerRepository->getCustomerByIdWithGroups($customerId);
        else $customer = $this->customerRepository->getCustomerById($customerId);

        return response()->json(new CustomerResource($customer));
    }

    public function showGroups(Request $request): JsonResponse
    {
        $customerId = $request->route('id');
        $groups = $this->customerRepository->getCustomerGroups($customerId);
        return response()->json(CustomerGroupResource::collection($groups));
    }

    public function store(Request $request): JsonResponse
    {
        $customerDetails = $request->only([
            'name',
            'surname',
            'email',
            'phone_number'
        ]);

        $createdCustomer = $this->customerRepository->createCustomer($customerDetails);

        return response()->json(
            new CustomerResource($createdCustomer),
            Response::HTTP_CREATED);
    }

    public function update(Request $request): JsonResponse
    {
        $customerId = $request->route('id');
        $customerDetails = $request->only([
            'name',
            'surname',
            'email',
            'phone_number'
        ]);

        $rowsAffected = $this->customerRepository->updateCustomer($customerId, $customerDetails);
        
        if($rowsAffected === 1) return response()->json(['success' => 'success'], Response::HTTP_OK);
        
        return response()->json(['error' => 'bad request'], Response::HTTP_BAD_REQUEST);
    }
}
