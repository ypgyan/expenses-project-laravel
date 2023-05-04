<?php

use App\Models\Revenue;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;

it('has revenues page', function () {
    $response = $this->get('/api/revenues');
    $response->assertStatus(200);
});

it('should return a revenue with valid paremeters', function () {
    $createdRevenue = Revenue::factory()->create();
    $this->get("api/revenues/{$createdRevenue->id}")
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('description', $createdRevenue->description)
            ->where('id', $createdRevenue->id)
            ->where('value', number_format($createdRevenue->value, 2, '.', ''))
            ->where('received_at', Carbon::createFromFormat('Y-m-d', $createdRevenue->received_at)->format('d-m-Y'))
            ->etc()
        );
});

it('should fail if invalid id', function () {
    $this->get("api/revenues/0")
        ->assertStatus(404);
});
