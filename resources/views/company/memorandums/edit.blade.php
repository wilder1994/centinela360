@extends('layouts.company')

@section('content')
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar memorándum</h1>

        <livewire:memorandums.form :memorandum="$memorandum" />
    </div>
@endsection
