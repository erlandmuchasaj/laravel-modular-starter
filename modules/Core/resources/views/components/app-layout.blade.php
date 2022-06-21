<?php
/**
 * This Software is the property of Erland Muchasaj and is protected
 * by copyright law - it is NOT Freeware.
 * Any unauthorized use of this software without a valid license
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @copyright (C) Erland Muchasaj
 * @author Erland Muchasaj <erland.muchasaj@gmail.com>
 * @link https://erlandmuchasaj.tech/
 */
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @langrtl dir="rtl" @endlangrtl>
{{--@include('common.hiddenCredits')--}}
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ config('app.theme_color', '#ffffff') }}">

    <title>@yield('title_prefix') @yield('title', config('app.name', 'EMCMS Starter')) @yield('title_postfix')</title>

    <meta name="locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}"/>
    <meta name="base_url" content="{{ url('/') }}">
    <meta name="generator" content="{{ config('app.name', 'EMCMS Starter') }} {{ app()->version() }}">
    <link rel="canonical" href="@yield('canonical', request()->url())" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.gstatic.com/">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    {{-- if we want to add any other content on head --}}
    @yield('head')

    <!-- Styles -->
    @yield('css')

    {{-- Global css --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="@langrtl rtl @endlangrtl @yield('body_class', str_replace('.', '-', optional(Route::current())->getName()))">
@include('core::layouts.navigation')
<header class="bg-white shadow">
    {{ $header }}
</header>
<main>
    {{ $slot }}
</main>
</body>
</html>
