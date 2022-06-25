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
        $customerDetails = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'email:rfc,dns|unique:customers|required',
            'phone_number' => 'required'
        ]);

        $createdCustomer = $this->customerRepository->createCustomer($customerDetails);

        return response()->json(
            new CustomerResource($createdCustomer),
            Response::HTTP_CREATED);
    }

    public function update(Request $request): JsonResponse
    {
        $customerId = $request->route('id');
        $customerDetails = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'email:rfc,dns',
            'phone_number' => 'required'
        ]);

        $rowsAffected = $this->customerRepository->updateCustomer($customerId, $customerDetails);
        
        if($rowsAffected === 1) return response()->json(null, Response::HTTP_NO_CONTENT);
        
        return response()->json(null, Response::HTTP_NOT_FOUND);
    }

    public function destroy(Request $request): JsonResponse
    {
        $customerId = $request->route('id');
        $rowsAffected = $this->customerRepository->deleteCustomer($customerId);

        if($rowsAffected) return response()->json(null, Response::HTTP_NO_CONTENT);
        else return response()->json(null, Response::HTTP_NOT_FOUND);
    }

    public function add_to_group(Request $request): JsonResponse
    {
        $customerId = $request->route('id');
        $groupId = $request->route('group_id');

        $this->customerRepository->attachGroup($customerId, $groupId);
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function remove_from_group(Request $request): JsonResponse
    {
        $customerId = $request->route('id');
        $groupId = $request->route('group_id');

        $this->customerRepository->detachGroup($customerId, $groupId);
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
