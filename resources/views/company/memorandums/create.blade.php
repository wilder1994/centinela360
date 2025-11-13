@extends('layouts.company')

@section('content')
    <div class="space-y-6">
        <header>
            <h1 class="text-2xl font-semibold text-gray-800">Nuevo Memorándum</h1>
            <p class="text-sm text-gray-500">Utiliza el siguiente formulario para registrar un memorándum interno.</p>
        </header>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('company.memorandums.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700">Asunto</label>
                    <input id="subject" name="subject" type="text"
                           class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           value="{{ old('subject') }}" required>
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700">Contenido</label>
                    <textarea id="body" name="body" rows="6"
                              class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              required>{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('company.memorandums.index') }}"
                       class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
