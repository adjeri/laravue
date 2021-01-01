<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FREE Ultimate Guide.</title>

        <!-- Fonts -->


        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/loader.css') }}">
        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
        <script>
            window.Laravel = { csrfToken: '{{ csrf_token() }}' };
        </script>
    </head>

    <body>
        <div id="app" v-cloak>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>

</html>
