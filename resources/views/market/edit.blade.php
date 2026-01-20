@extends('layouts.dashboard')


@section('title', 'Edit Market')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit Market</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('market.update', $market->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('market._form')
        </form>
    </div>
</div>
@endsection