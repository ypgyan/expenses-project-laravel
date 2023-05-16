<?php

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;

it('should return 200 expenses extract endpoint', function () {
    $this->get('/api/expenses/extract/2023/04', ['Accept' => 'application/json'])
        ->assertStatus(200);
});

it('should return april expenses extract endpoint', function () {
    $expense = Expense::factory()->create(['paid_at' => '2023-04-20']);
    $this->get('/api/expenses/extract/2023/04', ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->first(callback: fn(Assertablejson $json) => $json->where('id', $expense->id)
            ->where('description', $expense->description)
            ->where('value', number_format($expense->value, 2, '.', ''))
            ->where('paid_at', Carbon::createFromFormat('Y-m-d', $expense->paid_at)->format('d-m-Y'))
            ->etc()
        ));
});
