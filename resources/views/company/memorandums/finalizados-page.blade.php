{{-- resources/views/company/memorandums/finalizados-page.blade.php --}}
@extends('layouts.company')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 xl:px-16 py-6 space-y-8">
    @livewire('company.memorandums.board', [
        'estadosVisibles' => ['finalizado'],
        'tituloTabla'     => 'Memorandos finalizados',
        'mensajeVacio'    => 'No hay memorandos finalizados en este momento.',
    ])
</div>
@endsection
