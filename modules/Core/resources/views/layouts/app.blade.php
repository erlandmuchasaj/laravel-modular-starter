<?php
/**
 * This Software is the property of Erland Muchasaj and is protected
 * by copyright law - it is NOT Freeware.
 * Any unauthorized use of this software without a valid license
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @copyright (C) Erland Muchasaj
 * @author Erland Muchasaj <erland.muchasaj@rgmail.com>
 * @link https://erlandmuchasaj.tech/
 */
?>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @langrtl dir="rtl" @endlangrtl>
@include('common.hiddenCredits')
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ config('app.theme_color', '#ffffff') }}">

    <title>@yield('title_prefix') @yield('title', config('app.name', 'EM Starter')) @yield('title_postfix')</title>

    <meta name="locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}"/>
    <meta name="base_url" content="{{ url('/') }}">
    <meta name="generator" content="{{ config('app.name', 'EM Starter') }} {{ App::VERSION() }}">
    <link rel="canonical" href="@yield('canonical', request()->url())" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,700" rel="stylesheet">

    {{-- if we want to add any other content on head --}}
    @yield('head')

    <!-- Styles -->
    @yield('css')

    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">

    <!--AfterStyles -->
    @stack('afterCss')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{-- Here we have global JS configurations --}}
    @include('common.globals')
</head>
<body class="@langrtl rtl @endlangrtl @yield('body_class', str_replace('.', '-', optional(Route::current())->getName()))">
@include('common.demo')
@include('common.impersonate')

@if (config('app.announcements'))
    @include('common.announcements')
@endif

<main id="app">
    @include('frontend.layouts.includes.nav')
    @include('common.notifications')
    <main>
        @yield('content')
    </main>
</main>

@yield('scriptBottomStart')
<script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/plugins/toastr/build/toastr.min.js') }}"></script>
@yield('scriptBottomEnd')

<!-- Custom Scripts -->
<script src="{{ asset('/js/custom.js') }}"></script>

{{-- Global toastr --}}
@include('common.toastr')

<!-- After Scripts - mainly used to stack js comming from partials-->
@stack('afterJsScripts')
</body>
</html>
