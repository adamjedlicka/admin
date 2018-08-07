<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Admin</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css', 'vendor/admin') }}">

    </head>
    <body>
        <div id="app">
            <router-view />
        </div>

        <!-- Scripts -->
        <script src="{{ mix('js/app.js', 'vendor/admin') }}"></script>
    </body>
</html>
