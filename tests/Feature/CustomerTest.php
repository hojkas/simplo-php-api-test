<?php

namespace Tests\Feature;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use DatabaseMigrations;
    
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

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment($customer1->toArray());
        $response->assertJsonFragment($customer2->toArray());
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

        $response->assertStatus(200);
        $response->assertJsonFragment($customer->toArray());
        $response->assertJsonFragment(['name' => $group->name]);
    }

    /**
     * Test GET api/customes/id returns customer without group shown
     *
     * @return void
     */
    public function test_correct_get_customer_without_groups()
    {
        $customer = Customer::factory()->create();
        $group = CustomerGroup::factory()->create();
        $customer->groups()->attach($group->id);

        $response = $this->get("api/customers/{$customer->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment($customer->toArray());
        $response->assertJsonMissing(['name' => $group->name]);
    }
}
