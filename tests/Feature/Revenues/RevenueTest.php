<?php

it('has revenues page', function () {
    $response = $this->get('/api/revenues');

    $response->assertStatus(200);
});
