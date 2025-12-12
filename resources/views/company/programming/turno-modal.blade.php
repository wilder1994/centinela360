<div id="turno-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-2xl w-full max-w-sm mx-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
            <div>
                <p id="turno-modal-title" class="text-xs uppercase text-gray-400">Gestionar turno</p>
                <h3 id="turno-modal-heading" class="text-lg font-semibold text-white">Crear turno</h3>
            </div>
            <button id="turno-modal-close" class="text-gray-400 hover:text-white text-xl px-2" aria-label="Cerrar">&times;</button>
        </div>
        <form id="turno-modal-form" class="px-5 py-4 space-y-3 text-sm text-gray-200">
            <div id="turno-select-wrapper" class="space-y-1 hidden">
                <label class="text-gray-300">Selecciona un turno</label>
                <select id="turno-modal-select" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary"></select>
            </div>
            <div class="space-y-1">
                <label class="text-gray-300">C&oacute;digo</label>
                <input id="turno-modal-code" type="text" maxlength="2" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Ej: D, N, R">
            </div>
            <div class="space-y-1">
                <label class="text-gray-300">Descripci&oacute;n</label>
                <input id="turno-modal-desc" type="text" class="w-full rounded-md bg-slate-800 border border-slate-700 px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary" placeholder="Ej: 08:00 a 17:00">
            </div>
            <div class="space-y-2">
                <label class="text-gray-300">Color</label>
                <div class="flex items-center gap-3">
                    <input id="turno-modal-color" type="color" value="#22d3ee" class="h-10 w-16 rounded-md bg-slate-800 border border-slate-700 p-1">
                    <div class="flex flex-wrap gap-2">
                        @foreach (['#22d3ee', '#38bdf8', '#a855f7', '#f97316', '#f43f5e', '#84cc16', '#cbd5e1'] as $color)
                            <button type="button" class="turno-color-pill h-8 w-8 rounded-md border border-slate-700" data-color="{{ $color }}" style="background: {{ $color }};"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
        <div class="px-5 py-4 border-t border-slate-800 flex justify-end gap-2">
            <button id="turno-modal-cancel" type="button" class="px-4 py-2 rounded-md border border-slate-700 text-gray-200 hover:bg-slate-800 transition">Cancelar</button>
            <button id="turno-modal-submit" type="button" class="px-4 py-2 rounded-md bg-primary text-white hover:brightness-110 transition">Guardar</button>
        </div>
    </div>
</div>
