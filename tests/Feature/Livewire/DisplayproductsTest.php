<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('displayproducts');

    $component->assertSee('');
});
