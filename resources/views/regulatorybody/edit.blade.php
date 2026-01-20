@extends('layouts.dashboard')


@section('title', 'Edit RegulatoryBody')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit RegulatoryBody</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('regulatorybody.update', $regulatorybody->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('regulatorybody._form')
        </form>
    </div>
</div>
@endsection