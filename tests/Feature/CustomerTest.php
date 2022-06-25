<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

use function PHPUnit\Framework\assertNull;
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
    public function test_get_customer_with_groups()
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
    public function test_get_customer_without_groups()
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
     * GET api/customers/id of nonexisting user returns 404
     *
     * @return void
     */
    public function test_get_customer_that_doesnt_exist()
    {
        $response = $this->get('api/customers/1');

        $response->assertStatus(404);
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
     * GET api/customers/id/groups for nonexisting user returns 404
     *
     * @return void
     */
    public function test_get_non_existing_users_groups()
    {
        $response = $this->get('api/customers/1/groups');

        $response->assertStatus(404);
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
    
    /**
     * Test delete customer deletes it from db and returns 204
     *
     * @return void
     */
    public function test_delete_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->delete("api/customers/{$customer->id}");

        $response->assertStatus(204);
        $deletedCustomer = Customer::find($customer->id);
        assertNull($deletedCustomer);
    }

    /**
     * Test delete non existent customer returns 404
     *
     * @return void
     */
    public function test_delete_customer_with_non_existent_customer()
    {
        $response = $this->delete('api/customers/1');

        $response->assertStatus(404);
    }

    /**
     * PUT api/customers/id/groups/group_id adds user to a group and returns 204
     *
     * @return void
     */
    public function test_add_customer_to_group()
    {
        $customer = Customer::factory()->create();
        $group = CustomerGroup::factory()->create();

        $response = $this->put("api/customers/{$customer->id}/groups/{$group->id}");

        $response->assertStatus(204);

        $customerFromDb = Customer::findOrFail($customer->id);
        $groups = $customerFromDb->groups->toArray();
        
        assertTrue(
            $this->given_keys_contains_same_values(
                $groups[0],
                $group->toArray(),
                ['id', 'name']
            ));
    }

    /**
     * PUT api/customers/id/groups/group_id returns 404 if group doesn't exist
     *
     * @return void
     */
    public function test_add_customer_to_non_existing_group()
    {
        $customer = Customer::factory()->create();

        $response = $this->put("api/customers/{$customer->id}/groups/1");

        $response->assertStatus(404);
    }

    /**
     * DELETE api/customers/id/groups/group_id removes customer from a group
     *
     * @return void
     */
    public function test_delete_customer_from_group()
    {
        $customer = Customer::factory()->create();
        $group = CustomerGroup::factory()->create();
        $customer->groups()->attach($group);

        $response = $this->delete("api/customers/{$customer->id}/groups/{$group->id}");

        $response->assertStatus(204);

        assertTrue(count($customer->groups->toArray()) == 0);
    }
}
