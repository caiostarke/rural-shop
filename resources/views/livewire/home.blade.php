<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    @if (Route::has('login'))
        <livewire:welcome.navigation />
    @endif

    <h1> Welcome </h1>
</div>
