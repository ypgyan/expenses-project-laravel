<?php

use App\Models\Expense;
use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;

it('should return 200 revenues extract endpoint', function () {
    $this->get('/api/revenues/extract/2023/04', ['Accept' => 'application/json'])
        ->assertStatus(200);
});

it('should return april revenues extract endpoint', function () {
    $revenues = Revenue::factory()->create(['received_at' => '2023-04-20']);
    $this->get('/api/revenues/extract/2023/04', ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->first(callback: fn(Assertablejson $json) => $json->where('id', $revenues->id)
            ->where('description', $revenues->description)
            ->where('value', number_format($revenues->value, 2, '.', ''))
            ->where('received_at', Carbon::createFromFormat('Y-m-d', $revenues->received_at)->format('d-m-Y'))
            ->etc()
        ));
});
