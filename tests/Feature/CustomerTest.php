<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

use function PHPUnit\Framework\assertTrue;

class CustomerTest extends TestCase
{
    use DatabaseMigrations;

    private function given_keys_contains_same_values($array1, $array2, $keyArray) : bool
    {
        foreach($keyArray as $key)
        {
            if( !isset($array1[$key]) ||
                !isset($array2[$key]) ||
                $array1[$key] != $array2[$key])
                    return false;
            return true;
        }
    }
    
    /**
     * Test if basic request to endpoint api/customers/ returns 200.
     *
     * @return void
     */
    public function test_get_customers_returns_http_ok_response()
    {
        $response = $this->get('api/customers/');

        $response->assertStatus(200);
    }

    /**
     * Test GET api/customers/ returns correct list of customers
     *
     * @return void
     */
    public function test_all_two_customers_are_returned()
    {
        $customer1 = Customer::factory()->create();
        $customer2 = Customer::factory()->create();

        $response = $this->get('api/customers/');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment($customer1->toArray())
            ->assertJsonFragment($customer2->toArray());
    }

    /**
     * Test GET api/customers/id?showGroups=true returns customer with group shown
     *
     * @return void
     */
    public function test_correct_get_customer_with_groups()
    {
        $customer = Customer::factory()->create();
        $group = CustomerGroup::factory()->create();
        $customer->groups()->attach($group->id);

        $response = $this->get("api/customers/{$customer->id}?showGroups=true");

        $response
            ->assertStatus(200)
            ->assertJsonFragment($customer->toArray())
            ->assertJsonFragment(['name' => $group->name]);
    }

    /**
     * Test GET api/customers/id returns customer without group shown
     *
     * @return void
     */
    public function test_correct_get_customer_without_groups()
    {
        $customer = Customer::factory()->create();
        $group = CustomerGroup::factory()->create();
        $customer->groups()->attach($group->id);

        $response = $this->get("api/customers/{$customer->id}");

        $response
            ->assertStatus(200)
            ->assertJsonFragment($customer->toArray())
            ->assertJsonMissing(['name' => $group->name]);
    }
    
    /**
     * Tets GET api/customers/id/groups returns customers groups properly
     *
     * @return void
     */
    public function test_get_customer_groups_only()
    {
        $customer = Customer::factory()->create();
        $group1 = CustomerGroup::factory()->create();
        $group2 = CustomerGroup::factory()->create();
        $nonCustomerGroup = CustomerGroup::factory()->create();

        $customer->groups()->attach([$group1->id, $group2->id]);

        $response = $this->get("api/customers/{$customer->id}/groups");
        
        $response
            ->assertStatus(200)
            ->assertJsonFragment($group1->toArray())
            ->assertJsonFragment($group2->toArray())
            ->assertJsonMissing(['name' => $nonCustomerGroup->name]);
    }
    
    /**
     * Test POST api/customers/ saves new customer and returns it
     *
     * @return void
     */
    public function test_post_customer()
    {
        $newCustomerDetails = Customer::factory()->make()->toArray();

        $response = $this->post('api/customers/', $newCustomerDetails);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($newCustomerDetails);

        $customerFromDb = Customer::findOrFail($response->json('id'))->toArray();

        assertTrue(
            $this->given_keys_contains_same_values(
                $newCustomerDetails,
                $customerFromDb,
                ['name', 'surname', 'email', 'phone_number']));
    }

    /**
     * Test put customer returns 204 and updates existing customer
     *
     * @return void
     */
    public function test_put_customer()
    {
        $customer = Customer::factory()->create();
        $editedCustomer = $customer->toArray();
        $editedCustomer['name'] = 'new name';

        $response = $this->put("api/customers/{$customer->id}", $editedCustomer);

        $response->assertStatus(204);

        $customerFromDb = Customer::findOrFail($customer->id)->toArray();
        assertTrue(
            $this->given_keys_contains_same_values(
                $editedCustomer,
                $customerFromDb,
                ['name', 'surname', 'email', 'phone_number']));
    }

    /**
     * Test put customer with non existing id returns 404
     *
     * @return void
     */
    public function test_put_customer_with_non_existent_customer()
    {
        $response = $this->put('api/customers/1');

        $response->assertStatus(404);
    }
}
