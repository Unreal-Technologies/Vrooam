<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style type="text/css">
            html *
            {
                font-family: monospace;
                font-size: 12pt;
            }
            
            #cart-totals table, #cart-totals td, #cart-item table, #cart-item td, #product-editlist table, #product-editlist td, #coupon-list table, #coupon-list td
            {
                width: 100%;
                white-space: nowrap;
                text-align: right;
            }
            
            #cart-item td, #product-editlist td, #coupon-list td
            {
                text-align: left;
                width: 1px;
            }
            
            #cart-item td:last-child, #product-editlist td[rowspan]:not([rowspan='1']), #coupon-list td[rowspan]:not([rowspan='1'])
            {
                text-align: right;
                width: 100%;
            }
            
            #cart-totals td, #cart-item td
            {
                vertical-align: top;
            }
            
            #cart-totals td:first-child
            {
                text-align: left;
            }
            
            #cart-totals tr td:last-child
            {
                font-weight: bold;
            }
            
            #cart-totals td *, #cart-item td *
            {
                margin: 0px;
                white-space: nowrap;
            }
            
            #cart-totals hr
            {
                border-color: black;
            }
            
            input[type='text']
            {
                max-width: 250px;
            }
            
            a
            {
                margin-top: 16px;
            }
        </style>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
