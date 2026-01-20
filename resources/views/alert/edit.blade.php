@extends('layouts.dashboard')


@section('title', 'Edit Alert')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit Alert</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('alert.update', $alert->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('alert._form')
        </form>
    </div>
</div>
@endsection