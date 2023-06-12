<?php

use App\Models\Revenue;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\actingAs;

it('has revenues page', function () {
    $user = User::factory()->create();
    $response = actingAs($user)->get('/api/revenues');
    $response->assertStatus(200);
});

it('should return unauthenticated revenues page', function () {
    $response = $this->json('GET', '/api/revenues', [], ['Accept' => 'application/json']);
    $response->assertStatus(401);
});

it('should return a revenue with valid parameters', function () {
    $createdRevenue = Revenue::factory()->create();
   $user = User::factory()->create();
    actingAs($user)->get("api/revenues/{$createdRevenue->id}")
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('description', $createdRevenue->description)
            ->where('id', $createdRevenue->id)
            ->where('value', number_format($createdRevenue->value, 2, '.', ''))
            ->where('received_at', Carbon::createFromFormat('Y-m-d', $createdRevenue->received_at)->format('d-m-Y'))
            ->etc()
        );
});

it('should fail if invalid id', function () {
   $user = User::factory()->create();
    actingAs($user)->get("api/revenues/0")
        ->assertStatus(404);
});

it('should delete a revenue', function () {
    $createdRevenue = Revenue::factory()->create();
   $user = User::factory()->create();
    actingAs($user)->delete("api/revenues/$createdRevenue->id")
        ->assertStatus(200);
});

it('should fail to delete a revenue that does not exist', function () {
   $user = User::factory()->create();
    actingAs($user)->delete("api/revenues/0")
        ->assertStatus(404);
});

it('should filter revenues', function () {
    $revenue = Revenue::factory(5)->create()->first();
   $user = User::factory()->create();
    actingAs($user)->json('GET', '/api/revenues', ['description' => $revenue->description], ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has(1)->first(fn(Assertablejson $json) => $json->where('id', $revenue->id)
            ->where('description', $revenue->description)
            ->where('value', number_format($revenue->value, 2, '.', ''))
            ->where('received_at', Carbon::createFromFormat('Y-m-d', $revenue->received_at)->format('d-m-Y'))
            ->etc()
        ));
});

it('should return any revenue', function () {
    Revenue::factory(5)->create();
   $user = User::factory()->create();
    actingAs($user)->json('GET', '/api/revenues', ['description' => 'Teste'], ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertExactJson([]);
});
