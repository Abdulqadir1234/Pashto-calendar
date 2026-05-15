<?php

test('to_gregorian helper works', function () {
    $gregorian = to_gregorian(1403, 1, 1);
    expect($gregorian)->toBeString();
    expect($gregorian)->toBe('2024-03-20');
});

test('pashto_month_name helper returns correct name', function () {
    expect(pashto_month_name(1))->toBe('وری');
    expect(pashto_month_name(2))->toBe('غویی');
});

test('pashto_number helper converts numerals', function () {
    expect(pashto_number(123))->toBe('۱۲۳');
});

test('to_shamsi_pashto helper returns a string', function () {
    $result = to_shamsi_pashto(\Carbon\Carbon::parse('2024-03-20'));
    expect($result)->toBeString();
});