<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Collab EXP - Home</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div style="background-image: url('/images/rural-bg.jpg');  background-position: center;background-repeat: no-repeat;    background-size: cover;" class=" relative min-h-screen px-10 pt-12 text-cente lg:text-left lg:px-60 md:px-10 sm:15 justify-items-start sm:flex bg-dots-darker dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <livewire:welcome.navigation />
            @endif
            
            <div class="flex flex-col justify-center mt-20 lg:mt-0 md:mt-0">
                <h1 class="text-2xl text-white "> Welcome to <span class="text-sky-500 "> {{ __('Rural Shop')}} </span> </h1>
                <h1 class="text-white"> A platform that connect consumers directly with the rural producers. </h1>
                
                <div class="buttons">
                    <x-button black class="mt-5">  Get working experience. </x-button>
                    <x-button sky class="mt-5">  For businesses.    </x-button>
                </div>
            </div>
            
        </div>      
    </body>
</html>
