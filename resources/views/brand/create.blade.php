@extends('layouts.dashboard')


@section('title', 'Create Brand')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Create New Brand</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('brand.store') }}" method="POST">
            @csrf
            @include('brand._form')
        </form>
    </div>
</div>
@endsection