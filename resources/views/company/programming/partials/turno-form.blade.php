{{-- Formulario de turnos (se muestra dentro del modal) --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
    <div class="space-y-1">
        <label class="text-gray-400 text-xs uppercase tracking-wide">Turno</label>
        <input id="turn-name" type="text" name="name" class="w-full bg-gray-800 border border-gray-700 rounded-md px-3 py-2 text-white focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Ej: D, N" required>
    </div>
    <div class="space-y-1 md:col-span-2">
        <label class="text-gray-400 text-xs uppercase tracking-wide">Descripcion</label>
        <input id="turn-description" type="text" name="description" class="w-full bg-gray-800 border border-gray-700 rounded-md px-3 py-2 text-white focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Ej: 08:00 a 17:00">
    </div>
    <div class="space-y-1">
        <label class="text-gray-400 text-xs uppercase tracking-wide">Color</label>
        <input id="turn-color" type="color" name="color" value="#22d3ee" class="w-full h-10 bg-gray-800 border border-gray-700 rounded-md p-1">
    </div>
</div>
