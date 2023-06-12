<?php

use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\actingAs;

it('has expenses page', function () {
    $user = User::factory()->create();
    $response = actingAs($user)->get('/api/expenses');
    $response->assertStatus(200);
});

it('should return unauthenticated', function () {
    $response = $this->json('GET', '/api/expenses', [], ['Accept' => 'application/json']);
    $response->assertStatus(401);
});

it('should return a expense with valid parameters', function () {
    $createdExpense = Expense::factory()->create();
    $user = User::factory()->create();
    actingAs($user)->get("api/expenses/{$createdExpense->id}")
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('description', $createdExpense->description)
            ->where('id', $createdExpense->id)
            ->where('value', number_format($createdExpense->value, 2, '.', ''))
            ->where('paid_at', Carbon::createFromFormat('Y-m-d', $createdExpense->paid_at)->format('d-m-Y'))
            ->etc()
        );
});

it('should fail if invalid id', function () {
    $user = User::factory()->create();
    actingAs($user)->get("api/expenses/0")
        ->assertStatus(404);
});

it('should delete a expense', function () {
    $createdExpense = Expense::factory()->create();
    $user = User::factory()->create();
    actingAs($user)->delete("api/expenses/$createdExpense->id")
        ->assertStatus(200);
});

it('should fail to delete a expense that does not exist', function () {
    $user = User::factory()->create();
    actingAs($user)->delete("api/expenses/0")
        ->assertStatus(404);
});

it('should filter expenses', function () {
    $expense = Expense::factory(5)->create()->first();
    $user = User::factory()->create();
    actingAs($user)->json('GET', '/api/expenses', ['description' => $expense->description], ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has(1)->first(fn(Assertablejson $json) => $json->where('id', $expense->id)
            ->where('description', $expense->description)
            ->where('value', number_format($expense->value, 2, '.', ''))
            ->where('paid_at', Carbon::createFromFormat('Y-m-d', $expense->paid_at)->format('d-m-Y'))
            ->etc()
        ));
});

it('should return any expense', function () {
    Expense::factory(5)->create();
    $user = User::factory()->create();
    actingAs($user)->json('GET', '/api/expenses', ['description' => 'Teste'], ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertExactJson([]);
});
