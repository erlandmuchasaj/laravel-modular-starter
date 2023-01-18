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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(session()->has('lang-rtl')) dir="rtl" @endif>
<x-core::hidden-credits />
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

    <x-core::hreflang />
    <x-core::favicon />
    <x-core::mobile-meta />
    <x-core::og-tags />
    <x-core::robots />
    <x-core::seo />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.gstatic.com/">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    @stack('preload')

    {{-- if we want to add any other content on head --}}
    @yield('head')

    <!-- Styles -->
    @yield('css')

    {{-- Global css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    {{-- Here we have global JS configurations --}}
    <script>document.documentElement.className = 'js';</script>
    <x-core::globals />
</head>
<body class="{{ str_replace('.', '-', optional(Route::current())->getName()) }} @yield('body_class')">
<!--[if lt IE 7]>
<p class="chromeframe">
    You are using an outdated browser. <a href="https://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.
</p>
<![endif]-->
@include('core::components.demo')
@include('core::layouts.navigation')
@isset($header)
    <header class="bg-white shadow">
        {{ $header ?? '' }}
    </header>
@endisset
<main>
    {{ $slot }}
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js" integrity="sha512-6UofPqm0QupIL0kzS/UIzekR73/luZdC6i/kXDbWnLOJoqwklBK6519iUnShaYceJ0y4FaiPtX/hRnV/X/xlUQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@stack('script')
</body>
</html>
