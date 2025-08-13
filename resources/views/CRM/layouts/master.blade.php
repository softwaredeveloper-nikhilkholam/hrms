<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Meta Tags --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Page Title (optional dynamic) --}}
    <title>@yield('title', 'CRM System')</title>

    {{-- Styles and other head elements --}}
    @include('CRM.partials.head')
</head>
<body>
    <div class="main-wrapper">
        {{-- Header --}}
        <header class="header">
            @include('CRM.partials.header')
        </header>

        {{-- Sidebar --}}
        <aside class="sidebar" id="sidebar">
            @include('CRM.partials.sidebar')
        </aside>

        {{-- Page Content --}}
        <div class="page-wrapper cardhead">
			<div class="content container-fluid">
                @include('partials.messages')
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @include('CRM.partials.script')
</body>
</html>
