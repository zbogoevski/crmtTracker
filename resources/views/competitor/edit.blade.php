@extends('layouts.dashboard')


@section('title', 'Edit Competitor')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit Competitor</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('competitor.update', $competitor->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('competitor._form')
        </form>
    </div>
</div>
@endsection