<?php

use Illuminate\Support\Facades\Validator;

test('pashto_date rule passes', function () {
    $validator = Validator::make(['date' => '1403-02-30'], ['date' => 'pashto_date']);
    expect($validator->passes())->toBeTrue();
});

test('pashto_date rule fails', function () {
    $validator = Validator::make(['date' => '1403-13-01'], ['date' => 'pashto_date']);
    expect($validator->fails())->toBeTrue();
});