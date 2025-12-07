@extends('layouts.company')

@section('content')
<div class="animate-fadeIn space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Clientes</h1>
            <p class="text-sm text-gray-500">Listado de clientes y servicios</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('company.clients.archived') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                Archivados
            </a>
            <button type="button" data-service-types-modal class="px-4 py-2 rounded-lg border border-[var(--primary)] text-[var(--primary)] hover:bg-[var(--primary)]/10">
                Tipos de servicio
            </button>
            <a href="{{ route('company.clients.create') }}" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg shadow">
                + Nuevo cliente
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-4 flex flex-col gap-3">
        <form method="GET" class="flex flex-col md:flex-row gap-3 items-start md:items-center">
            <div class="flex-1 w-full">
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, NIT o ciudad..."
                       class="w-full input rounded-lg border border-gray-200 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
            </div>
            <button type="submit" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg">Buscar</button>
        </form>
        @if (session('status'))
            <div class="text-sm text-green-700 bg-green-50 border border-green-200 px-3 py-2 rounded-lg">{{ session('status') }}</div>
        @endif
    </div>

    <!-- Tabla (escritorio) -->
    <div class="hidden md:block bg-white rounded-xl shadow overflow-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Cliente</th>
                    <th class="px-4 py-3 text-left">NIT</th>
                    <th class="px-4 py-3 text-left">Servicios</th>
                    <th class="px-4 py-3 text-left">Tipo / Horario</th>
                    <th class="px-4 py-3 text-left">Antigüedad</th>
                    <th class="px-4 py-3 text-left">Cuadrante</th>
                    <th class="px-4 py-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($clients as $client)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                
                                <div>
                                    <div class="font-semibold">{{ $client->business_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $client->address }}</div>
                                    <div class="text-xs text-gray-500">{{ $client->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $client->nit }}</td>
                        <td class="px-4 py-3">{{ $client->service_count }}</td>
                        <td class="px-4 py-3">{{ $client->service_summary ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $client->tenure ?: 'N/D' }}</td>
                        <td class="px-4 py-3">{{ $client->quadrant }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <button type="button" class="text-gray-600 hover:underline" data-notes-open data-client="{{ $client->id }}">Historial</button>
                                <a href="{{ route('company.clients.edit', $client) }}" class="text-[var(--primary)] hover:underline">Editar</a>
                                <button type="button" class="text-red-600 hover:underline" data-action-modal data-url="{{ route('company.clients.destroy', $client) }}" data-method="DELETE" data-title="Archivar cliente" data-message="Para archivar deja un comentario.">Archivar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay clientes registrados aún.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Cards (móvil) -->
    <div class="grid md:hidden gap-4">
        @forelse ($clients as $client)
            <div class="bg-white p-4 rounded-xl shadow space-y-2">
                <div class="flex items-start gap-3">
                    <img src="{{ asset('images/default-logo.png') }}" class="w-12 h-12 object-contain" alt="logo">
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-semibold">{{ $client->business_name }}</p>
                                <p class="text-sm text-gray-500">NIT {{ $client->nit }}</p>
                                <p class="text-xs text-gray-500">{{ $client->address }}</p>
                            </div>
                            <div class="text-sm text-gray-500">{{ $client->service_count }} servicios</div>
                        </div>
                        <p class="text-xs text-gray-500">{{ $client->service_summary ?: 'Sin servicios registrados' }}</p>
                        <p class="text-xs text-gray-500">Antigüedad: {{ $client->tenure ?: 'N/D' }}</p>
                        <p class="text-xs text-gray-500">Cuadrante: {{ $client->quadrant }}</p>
                        <p class="text-xs text-gray-500">{{ $client->email }}</p>

                        <div class="mt-3 flex items-center gap-4">
                            <button type="button" class="text-gray-600 text-sm hover:underline" data-notes-open data-client="{{ $client->id }}">Historial</button>
                            <a href="{{ route('company.clients.edit', $client) }}" class="text-[var(--primary)] text-sm hover:underline">Editar</a>
                            <form method="POST" action="{{ route('company.clients.destroy', $client) }}" onsubmit="return confirm('¿Archivar cliente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-sm hover:underline">Archivar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500">No hay clientes registrados aún.</div>
        @endforelse
    </div>

    @foreach ($clients as $client)
        <div id="notes-content-{{ $client->id }}" class="hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $client->business_name }}</h3>
            <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                @forelse ($client->notes->sortByDesc('created_at') as $note)
                    <div class="p-2 rounded-lg bg-gray-50 border">
                        <p class="text-sm text-gray-800">{{ $note->body }}</p>
                        <p class="text-[11px] text-gray-500 mt-1">{{ $note->created_at->format('d/m/Y H:i') }} · {{ $note->user->name ?? 'Usuario' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Sin notas registradas.</p>
                @endforelse
            </div>
            <form method="POST" action="{{ route('company.clients.notes.store', $client) }}" class="mt-3 space-y-2">
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

    <div id="service-types-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-30">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-xl p-6 relative">
            <button type="button" data-service-types-close class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tipos de servicio</h3>
            <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                @forelse ($serviceTypes as $type)
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('company.clients.service_types.update', $type) }}" class="flex-1 flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $type->name }}" class="input w-full rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]">
                            <button type="submit" class="px-3 py-2 bg-[var(--primary)] text-white rounded-lg text-sm">Guardar</button>
                        </form>
                        <form method="POST" action="{{ route('company.clients.service_types.destroy', $type) }}" onsubmit="return confirm('¿Eliminar tipo de servicio?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm">Eliminar</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No hay tipos configurados aún.</p>
                @endforelse
            </div>
            <form method="POST" action="{{ route('company.clients.service_types.store') }}" class="mt-4 space-y-2">
                @csrf
                <label class="block text-sm font-medium text-gray-700">Nuevo tipo</label>
                <div class="flex items-center gap-2">
                    <input type="text" name="name" class="input w-full rounded-lg border-[var(--primary)] focus:ring-[var(--primary)]" required>
                    <button type="submit" class="px-4 py-2 bg-[var(--primary)] text-white rounded-lg">Crear</button>
                </div>
            </form>
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

    <div class="hidden md:flex justify-end">
        {{ $clients->links() }}
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const notesModal = document.getElementById('notes-modal');
        const notesBody = document.getElementById('notes-modal-body');
        const serviceTypesModal = document.getElementById('service-types-modal');
        const shouldOpenServiceTypes = @json(session('open_service_types', false));
        const actionModal = document.getElementById('action-modal');
        const actionForm = document.getElementById('action-modal-form');
        const actionTitle = document.getElementById('action-modal-title');
        const actionMessage = document.getElementById('action-modal-message');

        document.querySelectorAll('[data-notes-open]').forEach(button => {
            button.addEventListener('click', () => {
                const clientId = button.dataset.client;
                const content = document.getElementById(`notes-content-${clientId}`);
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

        document.querySelectorAll('[data-service-types-modal]').forEach(button => {
            button.addEventListener('click', () => {
                serviceTypesModal?.classList.remove('hidden');
                serviceTypesModal?.classList.add('flex');
            });
        });

        document.querySelectorAll('[data-service-types-close]').forEach(button => {
            button.addEventListener('click', () => {
                serviceTypesModal?.classList.add('hidden');
                serviceTypesModal?.classList.remove('flex');
            });
        });

        if (serviceTypesModal) {
            if (shouldOpenServiceTypes) {
                serviceTypesModal.classList.remove('hidden');
                serviceTypesModal.classList.add('flex');
            }
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
