@extends('layouts.dashboard')


@section('title', 'Edit License')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit License</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('license.update', $license->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('license._form')
        </form>
    </div>
</div>
@endsection