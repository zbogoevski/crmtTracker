@extends('layouts.dashboard')


@section('title', 'Create RegulatoryBody')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Create New RegulatoryBody</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('regulatorybody.store') }}" method="POST">
            @csrf
            @include('regulatorybody._form')
        </form>
    </div>
</div>
@endsection