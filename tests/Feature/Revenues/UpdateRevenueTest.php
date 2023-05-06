<?php

use App\Models\Revenue;
use Illuminate\Testing\Fluent\AssertableJson;

it('should update a revenue', function (array $body) {
    $revenue = Revenue::factory()->create();
    $body['description'] = fake()->text();

    $this->put('/api/revenues/' . $revenue->id, $body, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('description', $body['description'])
            ->where('value', $body['value'])
            ->where('received_at', $body['received_at'])
            ->etc()
        );
})->with('revenueBody');

it('should fail all fields', function () {
    $revenue = Revenue::factory()->create();
    $this->put('/api/revenues/' . $revenue->id, [], ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertExactJson([
            "message" => "The description field is required. (and 2 more errors)",
            "errors" => [
                "description" => [
                    "The description field is required."
                ],
                "value" => [
                    "The value field is required."
                ],
                "received_at" => [
                    "The received at field is required."
                ]
            ]
        ]);
});

it('test duplicated description rule', function (array $body) {
    $revenueFirst = Revenue::factory()->create([
        'description' => $body['description'],
    ]);
    $revenue = Revenue::factory()->create([
        'description' => fake()->text(),
    ]);

    $this->put('/api/revenues/' . $revenue->id, $body, ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertExactJson([
            "message" => "Revenue already registered this month!",
            "errors" => [
                "description" => [
                    "Revenue already registered this month!"
                ],
            ]
        ]);
})->with('revenueBody');

it('test duplicated description rule with updating same revenue', function (array $body) {
    $revenue = Revenue::factory()->create([
        'description' => $body['description'],
    ]);

    $this->put('/api/revenues/' . $revenue->id, $body, ['Accept' => 'application/json'])
        ->assertStatus(200);
})->with('revenueBody');

it('test value field greater than zero rule', function (array $body) {
    $revenue = Revenue::factory()->create();
    $body['value'] = 0;
    $this->put('/api/revenues/' . $revenue->id, $body, ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertExactJson([
            "message" => "The value field must be greater than 0.",
            "errors" => [
                "value" => [
                    "The value field must be greater than 0."
                ],
            ]
        ]);
})->with('revenueBody');

it('test received at date format', function (array $body) {
    $revenue = Revenue::factory()->create();
    $body['received_at'] = '03/10/1995';
    $this->put('/api/revenues/' . $revenue->id, $body, ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertExactJson([
            "message" => "The received at field must match the format d-m-Y.",
            "errors" => [
                "received_at" => [
                    "The received at field must match the format d-m-Y."
                ],
            ]
        ]);
})->with('revenueBody');

it('should fail to update a revenue that does not exist', function (array $body) {
    $body['description'] = fake()->text();
    $this->put("api/revenues/0", $body, ['Accept' => 'application/json'])
        ->assertStatus(404);
})->with('revenueBody');
