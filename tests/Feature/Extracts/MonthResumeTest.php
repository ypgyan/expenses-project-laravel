<?php

it('should work resume endpoint', function () {
    $this->get('/api/summary/2023/05', ['Accept' => 'application/json'])
        ->assertStatus(200);
});
