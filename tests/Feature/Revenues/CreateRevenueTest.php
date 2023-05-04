<?php

use App\Models\Revenue;
use Illuminate\Testing\Fluent\AssertableJson;

it('should create a revenue', function (array $body) {
    $this->post('/api/revenues', $body)
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('description', $body['description'])
            ->where('value', $body['value'])
            ->where('received_at', $body['received_at'])
            ->etc()
        );
})->with('revenueBody');

it('should fail all fields', function () {
    $this->post('/api/revenues', [], ['Accept' => 'application/json'])
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
    Revenue::factory()->create([
        'description' => $body['description'],
    ]);

    $this->post('/api/revenues', $body, ['Accept' => 'application/json'])
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

it('test value field greater than zero rule', function (array $body) {
    $body['value'] = 0;
    $this->post('/api/revenues', $body, ['Accept' => 'application/json'])
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
    $body['received_at'] = '03/10/1995';
    $this->post('/api/revenues', $body, ['Accept' => 'application/json'])
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
