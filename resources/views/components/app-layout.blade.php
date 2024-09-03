<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Head content -->
</head>
<body>
    <!-- Navigation and header -->

    <main>
        {{ $slot }}
    </main>
</body>
</html>
