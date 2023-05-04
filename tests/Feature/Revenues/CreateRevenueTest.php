<?php

use Illuminate\Testing\Fluent\AssertableJson;

it('should create a revenue', function (array $body) {
   $this->post('/api/revenues', $body)
       ->assertStatus(200)
       ->assertJson(fn (AssertableJson $json) =>
       $json->where('description', $body['description'])
           ->where('value', $body['value'])
           ->where('received_at', $body['received_at'])
           ->etc()
       );
})->with('revenueBody');
