<x-app-layout>
    <div class="w-full p-6">
        @livewire('company.memorandums.board', [
            'tituloTabla'  => 'Listado de memorandos',
            'mensajeVacio' => 'No hay memorandos registrados.',
        ])
    </div>
</x-app-layout>
