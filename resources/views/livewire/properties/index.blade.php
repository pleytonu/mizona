
<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Predios
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Administra terrenos, propiedades y sus relaciones territoriales.
            </p>
        </div>

        <button
            wire:click="openCreateModal"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
        >
            <span class="text-lg">+</span>
            Nuevo predio
        </button>
    </div>

    {{-- Buscador --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="relative">
            <input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nombre, código catastral o tipo..."
                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
            >
        </div>
    </div>

    {{-- Listado --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/60">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Predio
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Zona territorial
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Superficie
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Elementos asociados
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Estado
                        </th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($properties as $property)
                        <tr wire:key="property-{{ $property->id }}"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            <td class="px-5 py-4">
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $property->name ?: 'Predio sin nombre' }}
                                </div>
                                <div class="mt-1 text-xs text-gray-500">
                                    Código:
                                    {{ $property->cadastral_code ?: 'Sin código' }}
                                </div>
                                <div class="mt-1 text-xs text-gray-500">
                                    Tipo:
                                    {{ $propertyTypes[$property->property_type] ?? ucfirst(str_replace('_', ' ', $property->property_type)) }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $property->territorialZone?->name ?? 'Sin zona asignada' }}
                                @if ($property->territorialZone?->location)
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $property->territorialZone->location->name }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                @if ($property->area_m2 !== null)
                                    {{ number_format((float) $property->area_m2, 2, ',', '.') }} m²
                                @else
                                    <span class="text-gray-400">No informada</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                <div>{{ $property->addresses_count }} dirección(es)</div>
                                <div class="mt-1">{{ $property->structures_count }} estructura(s)</div>
                            </td>

                            <td class="px-5 py-4">
                                @if ($property->active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        wire:click="openDetails({{ $property->id }})"
                                        class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                                    >
                                        Ver
                                    </button>

                                    <button
                                        wire:click="openEditModal({{ $property->id }})"
                                        class="rounded-lg border border-blue-200 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-50 dark:border-blue-800 dark:text-blue-300 dark:hover:bg-blue-900/30"
                                    >
                                        Editar
                                    </button>

                                    <button
                                        wire:click="openStatusModal({{ $property->id }})"
                                        class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                                    >
                                        {{ $property->active ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    No se encontraron predios
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Puedes registrar uno nuevo o cambiar el criterio de búsqueda.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
            {{ $properties->links() }}
        </div>
    </div>

    {{-- Modal: crear --}}
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-950/50 p-4">
            <div class="my-8 w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-800"
                 wire:key="create-property-modal">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Registrar predio</h2>
                        <p class="mt-1 text-sm text-gray-500">Ingresa los datos principales de la propiedad.</p>
                    </div>
                    <button wire:click="closeCreateModal" class="text-2xl text-gray-400 hover:text-gray-700" aria-label="Cerrar">&times;</button>
                </div>
                    @include('livewire.properties.partials.form', [
                        'zones' => $zones,
                    ])
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="closeCreateModal" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 dark:border-gray-600 dark:text-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="save" wire:loading.attr="disabled" class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">Guardar predio</span>
                        <span wire:loading wire:target="save">Guardando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal: editar --}}
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-950/50 p-4">
            <div class="my-8 w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-800"
                 wire:key="edit-property-modal">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Editar predio</h2>
                        <p class="mt-1 text-sm text-gray-500">Modifica la información registrada.</p>
                    </div>
                    <button wire:click="closeEditModal" class="text-2xl text-gray-400 hover:text-gray-700" aria-label="Cerrar">&times;</button>
                </div>
                    @include('livewire.properties.partials.form', [
                        'zones' => $zones,
                    ])
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="closeEditModal" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 dark:border-gray-600 dark:text-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="update" wire:loading.attr="disabled" class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50">
                        <span wire:loading.remove wire:target="update">Guardar cambios</span>
                        <span wire:loading wire:target="update">Guardando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal: detalles --}}
    @if ($showDetailsModal && $selectedProperty)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-950/50 p-4">
            <div class="my-8 w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-800">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $selectedProperty->name ?: 'Predio sin nombre' }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">Ficha del predio</p>
                    </div>
                    <button wire:click="closeDetailsModal" class="text-2xl text-gray-400 hover:text-gray-700" aria-label="Cerrar">&times;</button>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs text-gray-500">Código catastral</div>
                        <div class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $selectedProperty->cadastral_code ?: 'No informado' }}</div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs text-gray-500">Tipo de predio</div>
                        <div class="mt-1 font-semibold text-gray-900 dark:text-white">
                            {{ ucfirst(str_replace('_', ' ', $selectedProperty->property_type)) }}
                        </div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs text-gray-500">Zona territorial</div>
                        <div class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $selectedProperty->territorialZone?->name ?? 'Sin zona asignada' }}</div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs text-gray-500">Superficie</div>
                        <div class="mt-1 font-semibold text-gray-900 dark:text-white">
                            @if ($selectedProperty->area_m2 !== null)
                                {{ number_format((float) $selectedProperty->area_m2, 2, ',', '.') }} m²
                            @else
                                No informada
                            @endif
                        </div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs text-gray-500">Direcciones vinculadas</div>
                        <div class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $selectedProperty->addresses->count() }}</div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900">
                        <div class="text-xs text-gray-500">Estructuras</div>
                        <div class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $selectedProperty->structures->count() }}</div>
                    </div>
                </div>

                @if ($selectedProperty->description)
                    <div class="mt-4 rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <div class="text-xs text-gray-500">Descripción</div>
                        <p class="mt-2 whitespace-pre-line text-sm text-gray-700 dark:text-gray-300">{{ $selectedProperty->description }}</p>
                    </div>
                @endif

                <div class="mt-6 flex justify-end">
                    <button wire:click="closeDetailsModal" class="rounded-xl bg-gray-900 px-5 py-2 text-sm font-semibold text-white dark:bg-gray-700">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal: activar o desactivar --}}
    @if ($showStatusModal && $selectedProperty)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-800">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ $selectedProperty->active ? 'Desactivar predio' : 'Activar predio' }}
                </h2>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                    ¿Confirmas que deseas {{ $selectedProperty->active ? 'desactivar' : 'activar' }}
                    el predio <strong>{{ $selectedProperty->name ?: 'sin nombre' }}</strong>?
                </p>
                <p class="mt-2 text-xs text-gray-500">
                    El registro se conservará en la base de datos; no se eliminará.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="closeStatusModal" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 dark:border-gray-600 dark:text-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="confirmToggleActive" wire:loading.attr="disabled" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
