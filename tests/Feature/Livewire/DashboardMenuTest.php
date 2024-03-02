<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('dashboard_menu');

    $component->assertSee('');
});
