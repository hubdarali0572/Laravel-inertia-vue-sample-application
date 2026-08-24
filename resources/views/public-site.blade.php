<!DOCTYPE html>
<html lang="en" dir="ltr" class="ps-html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title inertia>Bright Future Educational Institute</title>

    <script>
        (function () {
            try {
                document.documentElement.classList.remove('dark');
            } catch (e) {}
        })();
    </script>

    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/logo.jpeg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpeg') }}">

    @routes
    @vite(['resources/js/public.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="ps-body">
    @inertia
</body>
</html>
