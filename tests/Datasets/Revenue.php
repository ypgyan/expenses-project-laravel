<?php


dataset('revenueBody', [
    fn() => [
        "description" => fake()->text(),
        "value" => 55.55,
        "received_at" => now()->format('d-m-Y')
    ]
]);
