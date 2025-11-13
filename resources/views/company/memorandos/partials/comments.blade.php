<div class="space-y-4">
    <div class="flex items-start gap-3">
        <img src="{{ asset('images/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover" alt="Avatar">
        <div class="flex-1 bg-gray-50 border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h4 class="font-semibold text-gray-800">María Gómez</h4>
                <span class="text-xs text-gray-500">Hace 2 horas</span>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                Se envió el memorando al supervisor regional y quedó pendiente la firma del gerente general.
            </p>
        </div>
    </div>

    <div class="flex items-start gap-3">
        <img src="{{ asset('images/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover" alt="Avatar">
        <div class="flex-1 bg-gray-50 border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h4 class="font-semibold text-gray-800">Carlos Ruiz</h4>
                <span class="text-xs text-gray-500">Hace 1 día</span>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                Se cargaron los soportes fotográficos en la carpeta compartida y se notificó al área jurídica.
            </p>
        </div>
    </div>

    <form action="#" method="POST" class="bg-white border border-gray-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <img src="{{ asset('images/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover" alt="Avatar">
            <div class="flex-1">
                <label for="comment" class="sr-only">Comentario</label>
                <textarea id="comment" name="comment" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[var(--primary)] focus:ring-[var(--primary)]" placeholder="Escribe una actualización..."></textarea>
                <div class="mt-3 flex justify-end">
                    <button type="submit" class="bg-[var(--primary)] text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition-colors">
                        Publicar comentario
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
