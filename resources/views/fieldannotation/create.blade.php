@extends('layouts.dashboard')


@section('title', 'Create FieldAnnotation')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Create New FieldAnnotation</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('fieldannotation.store') }}" method="POST">
            @csrf
            @include('fieldannotation._form')
        </form>
    </div>
</div>
@endsection