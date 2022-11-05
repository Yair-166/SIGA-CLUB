<!doctype html>
<html lang="sp" data-topbar="light">

    <head>
    <meta charset="utf-8" />
    <title>@yield('title') | SIGA CLUB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="SIGA-CLUB" name="description" />
    <meta content="TT" name="2021-B004" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico')}}">
    {{-- <Link rel="stylesheet" href="{{ URL::asset('assets/css/switch.min.css') }}"> --}}
        @include('layouts.head-css')
  </head>

    @yield('body')

    @yield('content')

    @include('layouts.vendor-scripts')
    </body>
</html>
