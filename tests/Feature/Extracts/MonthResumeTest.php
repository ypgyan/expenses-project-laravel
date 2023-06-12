<?php

use App\Models\User;
use function Pest\Laravel\actingAs;

it('should work resume endpoint', function () {
    $user = User::factory()->create();
    actingAs($user)->get('/api/summary/2023/05', ['Accept' => 'application/json'])
        ->assertStatus(200);
});
