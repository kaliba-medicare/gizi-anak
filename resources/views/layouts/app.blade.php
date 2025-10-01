<!DOCTYPE html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="/assets/js/layout.js"></script>
        <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <title>{{ $title ?? 'Dinas Kesehatan Lombok Utara' }}</title>
        @stack('styles')
        @livewireStyles()
    </head>
    <body data-topbar="colored"  data-layout="horizontal">
        <div id="layout-wrapper">
            @include('layouts.header')
            @include('layouts.topnav')
            <div class="main-content">
                <div class="page-content">
                    {{ $slot }}
                </div>
            </div>
            @include('layouts.footer')
        </div>
        <script src="/assets/libs/jquery/jquery.min.js"></script>
        <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="/assets/libs/node-waves/waves.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        @stack('scripts')
        <script src="/assets/js/app.js"></script>
        @livewireScripts()
    </body>
    </html>
    