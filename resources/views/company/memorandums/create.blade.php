@extends('layouts.company')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Registrar memorándum</h1>
            </div>

            <div class="flex gap-3">
                 <a href="{{ route('company.memorandums.index') }}"
                     class="inline-flex items-center px-3 py-1.5 rounded-full border border-slate-200 bg-white text-[11px] sm:text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                    ← Volver al listado
                </a>
            </div>
        </div>

        <livewire:memorandums.form />
    </div>
@endsection
