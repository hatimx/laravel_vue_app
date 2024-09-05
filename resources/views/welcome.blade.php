<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b1353a64a2.js" crossorigin="anonymous"></script>
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <div id="app"></div>
    </div>
</body>

</html>
