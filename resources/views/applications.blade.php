@extends('layouts.master2')
@section('title', 'Management')

@section('styles')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        cursor: pointer;
    }
    .icon1 {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        color: white;
        font-size: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }
    .card:hover .icon1 {
        transform: scale(1.15);
    }
    .fs-14 {
        font-size: 14px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1.1px;
        font-weight: 600;
    }
    .font-weight-semibold {
        font-weight: 600;
    }
    a {
        text-decoration: none;
        color: inherit;
    }
</style>
@endsection

@section('content') 
<div class="container py-4">
    <div class="row">
        @php
        $applications = [
            ['id' => 1, 'name' => 'Landing Page', 'icon' => 'fa-home', 'bg' => 'bg-success'],
            ['id' => 2, 'name' => 'HRMS', 'icon' => 'fa-user-circle', 'bg' => 'bg-danger'],
            ['id' => 3, 'name' => 'Stock Management', 'icon' => 'fa-cog', 'bg' => 'bg-primary'],
            ['id' => 4, 'name' => 'CRM', 'icon' => 'fa-cog', 'bg' => 'bg-info'],
        ];
        @endphp

        @foreach ($applications as $app)
            <div class="col-xl-3 col-lg-6 col-md-12 mb-4">
                <a href="{{ url('/selectApplication/' . $app['id']) }}">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="fs-14 font-weight-semibold">{{ $app['name'] }}</span>
                                <h3 class="mt-2 mb-0"></h3>
                            </div>
                            <div class="icon1 {{ $app['bg'] }}">
                                <i class="fa {{ $app['icon'] }}" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
