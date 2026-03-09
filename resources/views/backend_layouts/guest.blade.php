<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="" type="image/x-icon">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    @yield('title')

    <link rel="icon" href="{{ asset($setting->favicon) }}">
    @include('backend_layouts.partials.styles')
    @stack('css')
</head>

<body>
    <div id="app">
        @yield('content')
    </div>

    {{-- end admin logout form --}}
    @include('backend_layouts.partials.javascripts')
    @stack('js')
</body>

</html>
