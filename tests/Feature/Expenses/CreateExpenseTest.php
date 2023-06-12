<?php

use App\Enums\Expenses\Categories;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\actingAs;

it('should create a expense', function (array $body) {
    $user = User::factory()->create();
    actingAs($user)->post('/api/expenses', $body)
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('description', $body['description'])
            ->where('value', $body['value'])
            ->where('paid_at', $body['paid_at'])
            ->where('category', Categories::OUTRAS)
            ->etc()
        );
})->with('expenseBody');

it('should fail all fields', function () {
    $user = User::factory()->create();
    actingAs($user)->post('/api/expenses', [], ['Accept' => 'application/json'])
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
                "paid_at" => [
                    "The paid at field is required."
                ]
            ]
        ]);
});

it('test duplicated description rule', function (array $body) {
    Expense::factory()->create([
        'description' => $body['description'],
        'paid_at' => now()
    ]);

    $user = User::factory()->create();
    actingAs($user)->post('/api/expenses', $body, ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertExactJson([
            "message" => "Expense already registered this month!",
            "errors" => [
                "description" => [
                    "Expense already registered this month!"
                ],
            ]
        ]);
})->with('expenseBody');

it('test value field greater than zero rule', function (array $body) {
    $body['value'] = 0;
    $user = User::factory()->create();
    actingAs($user)->post('/api/expenses', $body, ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertExactJson([
            "message" => "The value field must be greater than 0.",
            "errors" => [
                "value" => [
                    "The value field must be greater than 0."
                ],
            ]
        ]);
})->with('expenseBody');

it('test paid at date format', function (array $body) {
    $body['paid_at'] = '03/10/1995';
    $user = User::factory()->create();
    actingAs($user)->post('/api/expenses', $body, ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertExactJson([
            "message" => "The paid at field must match the format d-m-Y.",
            "errors" => [
                "paid_at" => [
                    "The paid at field must match the format d-m-Y."
                ],
            ]
        ]);
})->with('expenseBody');

it('test invalid category', function (array $body) {
    $body['category'] = 'Não registrado';
    $user = User::factory()->create();
    actingAs($user)->post('/api/expenses', $body, ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertExactJson([
            "message" => "The selected category is invalid.",
            "errors" => [
                "category" => [
                    "The selected category is invalid."
                ],
            ]
        ]);
})->with('expenseBody');
