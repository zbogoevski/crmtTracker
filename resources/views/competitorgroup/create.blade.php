@extends('layouts.dashboard')


@section('title', 'Create CompetitorGroup')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Create New CompetitorGroup</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('competitorgroup.store') }}" method="POST">
            @csrf
            @include('competitorgroup._form')
        </form>
    </div>
</div>
@endsection