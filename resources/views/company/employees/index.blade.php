@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-6">
    <div class="flex items-center justify-between px-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Empleados</h1>
            <p class="text-sm text-gray-500">Gestión de la base de datos de empleados</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('company.employees.archived') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                Archivados
            </a>
            <button type="button" data-catalogs-modal class="px-4 py-2 rounded-lg border border-[var(--primary)] text-[var(--primary)] hover:bg-[var(--primary)]/10">
                Catálogos
            </button>
            <a href="{{ route('company.employees.create') }}" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg shadow">
                + Nuevo empleado
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow px-4 py-3">
        <form method="GET" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3 w-full md:w-auto">
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, documento o teléfono" class="input w-full md:w-80 rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg">Buscar</button>
            </div>
            @if(session('status'))
                <div class="text-sm text-green-700 bg-green-50 px-3 py-2 rounded-lg">{{ session('status') }}</div>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Empleado</th>
                    <th class="px-4 py-3 text-left">Documento</th>
                    <th class="px-4 py-3 text-left">Teléfono</th>
                    <th class="px-4 py-3 text-left">Cliente</th>
                    <th class="px-4 py-3 text-left">Servicio</th>
                    <th class="px-4 py-3 text-left">Antigüedad</th>
                    <th class="px-4 py-3 text-left">Carnet vence</th>
                    <th class="px-4 py-3 text-left">Estado</th>
                    <th class="px-4 py-3 text-left">Historial</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">
                @forelse($employees as $employee)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $employee->photo_url }}"
                                    class="w-10 h-10 rounded-full object-cover border border-gray-300">

                                <div>
                                    <div class="font-semibold">{{ $employee->fullName }}</div>
                                    <div class="text-xs text-gray-500">{{ $employee->position }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $employee->document_type }} {{ $employee->document_number }}</td>
                        <td class="px-4 py-3">{{ $employee->phone }}</td>
                        <td class="px-4 py-3">{{ $employee->client?->business_name ?? 'Sin asignar' }}</td>
                        <td class="px-4 py-3">{{ $employee->service_type ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $employee->tenure ?: 'N/D' }}</td>
                        <td class="px-4 py-3">{{ optional($employee->badge_expires_at)->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'Activo' => 'bg-green-100 text-green-700',
                                    'En vacaciones' => 'bg-yellow-100 text-yellow-700',
                                    'Incapacitado' => 'bg-orange-100 text-orange-700',
                                    'Desprogramado' => 'bg-gray-100 text-gray-700',
                                    'Calamidad' => 'bg-red-100 text-red-700',
                                    'Despedido' => 'bg-red-100 text-red-700',
                                ];
                                $badgeClass = $statusColors[$employee->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $badgeClass }}">{{ $employee->status }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <button type="button" class="text-gray-600 hover:underline" data-notes-open data-employee="{{ $employee->id }}">Ver historial</button>
                        </td>
                        <td class="px-4 py-3 text-center space-x-2 whitespace-nowrap">
                            <a href="{{ route('company.employees.edit', $employee) }}" class="text-[var(--primary)] hover:underline">Editar</a>
                            <button type="button" class="text-red-600 hover:underline" data-action-modal data-url="{{ route('company.employees.destroy', $employee) }}" data-method="DELETE" data-title="Archivar empleado" data-message="Para archivar deja un comentario.">Archivar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">No hay empleados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3">
            {{ $employees->links() }}
        </div>
    </div>

    @foreach ($employees as $employee)
        <div id="notes-content-{{ $employee->id }}" class="hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $employee->fullName }}</h3>
            <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                @forelse ($employee->activityNotes->sortByDesc('created_at') as $note)
                    <div class="p-2 rounded-lg bg-gray-50 border">
                        <p class="text-sm text-gray-800">{{ $note->body }}</p>
                        <p class="text-[11px] text-gray-500 mt-1">{{ $note->created_at->format('d/m/Y H:i') }} · {{ $note->user->name ?? 'Usuario' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Sin notas registradas.</p>
                @endforelse
            </div>
            <form method="POST" action="{{ route('company.employees.notes.store', $employee) }}" class="mt-3 space-y-2">
                @csrf
                <label class="block text-sm font-medium text-gray-700">Nueva nota</label>
                <textarea name="body" rows="3" class="w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg">Guardar nota</button>
                </div>
            </form>
        </div>
    @endforeach

    <div id="notes-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-40">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6 relative">
            <button type="button" data-notes-close class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
            <div id="notes-modal-body" class="space-y-3"></div>
        </div>
    </div>

    <div id="catalogs-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-30">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl p-6 relative">
            <button type="button" data-catalogs-close class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Catálogos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Tipos de empleado</h4>
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @forelse ($employeeTypes as $type)
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('company.employees.catalogs.update', ['catalog' => 'employee', 'id' => $type->id]) }}" class="flex-1 flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $type->name }}" class="input w-full rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                                    <button type="submit" class="px-3 py-2 bg-[var(--primary)] text-white rounded-lg text-sm">Guardar</button>
                                </form>
                                <form method="POST" action="{{ route('company.employees.catalogs.destroy', ['catalog' => 'employee', 'id' => $type->id]) }}" onsubmit="return confirm('¿Eliminar tipo de empleado?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 text-sm">Eliminar</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay tipos configurados aún.</p>
                        @endforelse
                    </div>
                    <form method="POST" action="{{ route('company.employees.catalogs.store') }}" class="mt-3 space-y-2">
                        @csrf
                        <input type="hidden" name="catalog" value="employee">
                        <label class="block text-sm font-medium text-gray-700">Nuevo tipo de empleado</label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="name" class="input w-full rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                            <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg">Crear</button>
                        </div>
                    </form>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Tipos de documento</h4>
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @forelse ($documentTypesCatalog as $type)
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('company.employees.catalogs.update', ['catalog' => 'document', 'id' => $type->id]) }}" class="flex-1 flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $type->name }}" class="input w-full rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                                    <button type="submit" class="px-3 py-2 bg-[var(--primary)] text-white rounded-lg text-sm">Guardar</button>
                                </form>
                                <form method="POST" action="{{ route('company.employees.catalogs.destroy', ['catalog' => 'document', 'id' => $type->id]) }}" onsubmit="return confirm('¿Eliminar tipo de documento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 text-sm">Eliminar</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay tipos configurados aún.</p>
                        @endforelse
                    </div>
                    <form method="POST" action="{{ route('company.employees.catalogs.store') }}" class="mt-3 space-y-2">
                        @csrf
                        <input type="hidden" name="catalog" value="document">
                        <label class="block text-sm font-medium text-gray-700">Nuevo tipo de documento</label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="name" class="input w-full rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                            <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="action-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-xl p-6 relative">
            <button type="button" data-action-close class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 id="action-modal-title" class="text-lg font-semibold text-gray-800 mb-2"></h3>
            <p id="action-modal-message" class="text-sm text-gray-600 mb-3"></p>
            <form method="POST" id="action-modal-form" class="space-y-3">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <textarea name="comment" rows="3" class="w-full input rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Escribe el comentario" required></textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" data-action-close class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const notesModal = document.getElementById('notes-modal');
        const notesBody = document.getElementById('notes-modal-body');
        const catalogsModal = document.getElementById('catalogs-modal');
        const shouldOpenCatalogs = @json(session('open_employee_catalogs', false));
        const actionModal = document.getElementById('action-modal');
        const actionForm = document.getElementById('action-modal-form');
        const actionTitle = document.getElementById('action-modal-title');
        const actionMessage = document.getElementById('action-modal-message');

        document.querySelectorAll('[data-notes-open]').forEach(button => {
            button.addEventListener('click', () => {
                const employeeId = button.dataset.employee;
                const content = document.getElementById(`notes-content-${employeeId}`);
                if (content && notesBody && notesModal) {
                    notesBody.innerHTML = content.innerHTML;
                    notesModal.classList.remove('hidden');
                    notesModal.classList.add('flex');
                }
            });
        });

        document.querySelectorAll('[data-notes-close]').forEach(button => {
            button.addEventListener('click', () => {
                notesModal?.classList.add('hidden');
                notesModal?.classList.remove('flex');
            });
        });

        if (notesModal) {
            notesModal.addEventListener('click', (event) => {
                if (event.target === notesModal) {
                    notesModal.classList.add('hidden');
                    notesModal.classList.remove('flex');
                }
            });
        }

        document.querySelectorAll('[data-catalogs-modal]').forEach(button => {
            button.addEventListener('click', () => {
                catalogsModal?.classList.remove('hidden');
                catalogsModal?.classList.add('flex');
            });
        });

        document.querySelectorAll('[data-catalogs-close]').forEach(button => {
            button.addEventListener('click', () => {
                catalogsModal?.classList.add('hidden');
                catalogsModal?.classList.remove('flex');
            });
        });

        if (catalogsModal && shouldOpenCatalogs) {
            catalogsModal.classList.remove('hidden');
            catalogsModal.classList.add('flex');
        }

        document.querySelectorAll('[data-action-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const url = button.dataset.url;
                const method = button.dataset.method || 'POST';
                const title = button.dataset.title || '';
                const message = button.dataset.message || '';

                if (actionModal && actionForm) {
                    actionForm.action = url;
                    actionForm.querySelector('input[name="_method"]').value = method;
                    actionTitle.textContent = title;
                    actionMessage.textContent = message;
                    actionModal.classList.remove('hidden');
                    actionModal.classList.add('flex');
                }
            });
        });

        document.querySelectorAll('[data-action-close]').forEach(button => {
            button.addEventListener('click', () => {
                actionModal?.classList.add('hidden');
                actionModal?.classList.remove('flex');
            });
        });

        if (actionModal) {
            actionModal.addEventListener('click', (event) => {
                if (event.target === actionModal) {
                    actionModal.classList.add('hidden');
                    actionModal.classList.remove('flex');
                }
            });
        }
    });
</script>
@endpush
@endsection
