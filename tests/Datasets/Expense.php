<?php


dataset('expenseBody', [
    fn() => [
        "description" => fake()->text(),
        "value" => 55.55,
        "paid_at" => now()->format('d-m-Y')
    ]
]);
