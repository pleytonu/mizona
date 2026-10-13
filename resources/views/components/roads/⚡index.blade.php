<div class="mx-auto max-w-7xl space-y-6 p-4 sm:p-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Maestro de Vías
        </h1>
        <p class="mt-1 text-sm text-gray-600">
            Administración de calles, avenidas, pasajes y caminos de Mizona.
        </p>
    </div>

    @if (session()->has('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-gray-900">
            {{ $editingId ? 'Editar vía' : 'Registrar nueva vía' }}
        </h2>

        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="current_name" class="mb-1 block text-sm font-medium">
                        Nombre de la vía *
                    </label>
                    <input
                        id="current_name"
                        type="text"
                        wire:model="current_name"
                        maxlength="180"
                        required
                        class="w-full rounded-lg border-gray-300"
                        placeholder="Ej.: Avenida Concha y Toro"
                    >
                    @error('current_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="road_type" class="mb-1 block text-sm font-medium">
                        Tipo de vía *
                    </label>
                    <select
                        id="road_type"
                        wire:model="road_type"
                        class="w-full rounded-lg border-gray-300"
                    >
                        <option value="calle">Calle</option>
                        <option value="avenida">Avenida</option>
                        <option value="pasaje">Pasaje</option>
                        <option value="camino">Camino</option>
                        <option value="carretera">Carretera</option>
                        <option value="autopista">Autopista</option>
                        <option value="ruta">Ruta</option>
                        <option value="callejon">Callejón</option>
                        <option value="otro">Otro</option>
                    </select>
                    @error('road_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="official_code" class="mb-1 block text-sm font-medium">
                        Código oficial
                    </label>
                    <input
                        id="official_code"
                        type="text"
                        wire:model="official_code"
                        maxlength="100"
                        class="w-full rounded-lg border-gray-300"
                        placeholder="Opcional"
                    >
                    @error('official_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-1 block text-sm font-medium">
                        Descripción
                    </label>
                    <textarea
                        id="description"
                        wire:model="description"
                        rows="2"
                        class="w-full rounded-lg border-gray-300"
                        placeholder="Observaciones de la vía"
                    ></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="active" class="rounded border-gray-300">
                Vía activa
            </label>

            <div class="flex flex-wrap gap-2">
                <button
                    type="submit"
                    class="rounded-lg bg-blue-700 px-5 py-2.5 font-medium text-white hover:bg-blue-800"
                >
                    {{ $editingId ? 'Guardar cambios' : 'Registrar vía' }}
                </button>

                @if ($editingId)
                    <button
                        type="button"
                        wire:click="resetForm"
                        class="rounded-lg border border-gray-300 px-5 py-2.5"
                    >
                        Cancelar edición
                    </button>
                @endif
            </div>
        </form>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <h2 class="text-lg font-semibold text-gray-900">Vías registradas</h2>

            <input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nombre o código..."
                class="w-full rounded-lg border-gray-300 sm:max-w-sm"
            >
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-3 py-3">Nombre</th>
                        <th class="px-3 py-3">Tipo</th>
                        <th class="px-3 py-3">Código oficial</th>
                        <th class="px-3 py-3">Estado</th>
                        <th class="px-3 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($roads as $road)
                        <tr wire:key="road-{{ $road->id }}">
                            <td class="px-3 py-3 font-medium text-gray-900">
                                {{ $road->current_name }}
                            </td>
                            <td class="px-3 py-3">{{ ucfirst($road->road_type) }}</td>
                            <td class="px-3 py-3">{{ $road->official_code ?: '—' }}</td>
                            <td class="px-3 py-3">
                                @if ($road->active)
                                    <span class="text-green-700">Activa</span>
                                @else
                                    <span class="text-gray-500">Inactiva</span>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex flex-wrap gap-3">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $road->id }})"
                                        class="font-medium text-blue-700 hover:underline"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="toggleActive({{ $road->id }})"
                                        class="font-medium text-gray-700 hover:underline"
                                    >
                                        {{ $road->active ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-8 text-center text-gray-500">
                                No hay vías registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $roads->links() }}
        </div>
    </section>
</div>
