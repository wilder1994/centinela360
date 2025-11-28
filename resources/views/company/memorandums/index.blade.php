@extends('layouts.company')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Memorándums</h2>
                <p class="text-sm text-gray-500">Consulta y administra los memorándums de tu empresa.</p>
            </div>
            <a href="{{ route('company.memorandums.create') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded shadow hover:bg-blue-700">
                Nuevo memorándum
            </a>
        </div>

        <div class="bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asunto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Emitido</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($memorandums as $memorandum)
                        <tr>
                            <td class="px-6 py-4 font-mono text-sm text-gray-900">{{ $memorandum->code }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $memorandum->subject }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $memorandum->employee?->full_name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $memorandum->status->label() }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ optional($memorandum->issued_at)->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('company.memorandums.show', $memorandum) }}" class="text-blue-600 hover:underline">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-sm text-gray-500">No hay memorándums registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4">{{ $memorandums->links() }}</div>
        </div>
    </div>
@endsection
