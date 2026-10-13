<div class="min-h-full space-y-5 bg-slate-50 p-4 sm:p-6">

    {{-- ENCABEZADO --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-blue-700">
                <span>Gestión territorial</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Zonas</span>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Zonas territoriales
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Administra centros urbanos, barrios y sectores de Mizona.
            </p>
        </div>

        <button type="button" wire:click="openCreateModal"
            class="inline-flex items-center justify-center gap-2 rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Nueva zona
        </button>
    </div>

    {{-- MENSAJE --}}
    @if (session()->has('success'))
        <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="m5 12 4 4L19 6"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- BUSCADOR --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-center gap-3 rounded-xl bg-slate-100 px-4 py-3 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100">
            <svg class="h-5 w-5 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="11" cy="11" r="7"/>
                <path d="m16 16 4 4"/>
            </svg>

            <input type="search" wire:model.live.debounce.300ms="search"
                placeholder="Buscar por zona, comuna o tipo..."
                aria-label="Buscar zonas territoriales"
                class="w-full border-0 bg-transparent text-sm text-slate-900 outline-none placeholder:text-slate-500 focus:ring-0">
        </div>

        <div class="mt-3 flex items-center justify-between px-1 text-xs text-slate-500">
            <span>Directorio de zonas</span>
            <span>{{ $zones->total() }} {{ $zones->total() === 1 ? 'registro' : 'registros' }}</span>
        </div>
    </section>

    {{-- LISTADO --}}
    <section class="overflow-visible rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between rounded-t-2xl border-b border-slate-200 px-5 py-4">
            <div>
                <h2 class="font-semibold text-slate-900">Todas las zonas</h2>
                <p class="mt-1 text-xs text-slate-500">Zonas registradas en la plataforma.</p>
            </div>
            <span class="text-xs text-slate-500">Página {{ $zones->currentPage() }}</span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($zones as $zone)
                <div wire:key="zone-{{ $zone->id }}"
                    class="group flex items-center gap-3 px-4 py-4 transition hover:bg-blue-50/50 sm:px-5">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                            <circle cx="12" cy="10" r="2.5"/>
                        </svg>
                    </div>

                    <button type="button" wire:click="openDetails({{ $zone->id }})"
                        class="min-w-0 flex-1 text-left">
                        <div class="truncate text-sm font-semibold text-slate-900 group-hover:text-blue-700">
                            {{ $zone->name }}
                        </div>
                        <div class="mt-1 flex flex-wrap items-center gap-x-2 text-sm text-slate-500">
                            <span>{{ $zone->location?->name ?? 'Sin comuna' }}</span>
                            <span class="text-slate-300">·</span>
                            <span>{{ str($zone->zone_type)->replace('_', ' ')->ucfirst() }}</span>
                        </div>
                    </button>

                    <div class="hidden sm:block">
                        @if ($zone->active)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Activa
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                Inactiva
                            </span>
                        @endif
                    </div>

                    {{-- ACCIONES POR ZONA --}}
                    <div class="relative shrink-0" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                        <button type="button" @click="open = !open"
                            :aria-expanded="open"
                            aria-label="Acciones de {{ $zone->name }}"
                            class="rounded-full p-2 text-slate-500 transition hover:bg-slate-200 hover:text-slate-900">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <circle cx="5" cy="12" r="1.7"/>
                                <circle cx="12" cy="12" r="1.7"/>
                                <circle cx="19" cy="12" r="1.7"/>
                            </svg>
                        </button>

                        <div x-cloak x-show="open" x-transition
                            class="absolute right-0 top-full z-40 mt-1 w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl">

                            <button type="button" @click="open = false"
                                wire:click="openDetails({{ $zone->id }})"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-100">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Ver detalles
                            </button>

                            <button type="button" @click="open = false"
                                wire:click="openEditModal({{ $zone->id }})"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-100">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="m15 5 4 4M4 20l4-.8L19.5 7.7a2.1 2.1 0 0 0-3-3L5 16.2 4 20Z"/>
                                </svg>
                                Editar zona
                            </button>

                            <div class="my-1 border-t border-slate-100"></div>

                            <button type="button" @click="open = false"
                                wire:click="openStatusModal({{ $zone->id }})"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm {{ $zone->active ? 'text-red-700 hover:bg-red-50' : 'text-green-700 hover:bg-green-50' }}">
                                @if ($zone->active)
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M5.6 5.6l12.8 12.8"/>
                                    </svg>
                                    Desactivar zona
                                @else
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="m5 12 4 4L19 6"/>
                                    </svg>
                                    Reactivar zona
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-14 text-center">
                    <p class="font-semibold text-slate-900">No encontramos zonas</p>
                    <p class="mt-1 text-sm text-slate-500">Prueba con otro término o crea una nueva zona.</p>
                    <button type="button" wire:click="openCreateModal"
                        class="mt-4 text-sm font-semibold text-blue-700 hover:text-blue-900">
                        Crear zona
                    </button>
                </div>
            @endforelse
        </div>

        @if ($zones->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $zones->links() }}
            </div>
        @endif
    </section>

    {{-- MODAL CREAR --}}
    @if ($showCreateModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/55 p-3 sm:p-6"
            style="height: 100dvh;" wire:click.self="closeCreateModal"
            x-data x-on:keydown.escape.window="$wire.closeCreateModal()">

            <section class="my-auto flex w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
                style="max-height: calc(100dvh - 2rem);" role="dialog" aria-modal="true">

                <header class="flex shrink-0 items-start justify-between border-b border-slate-200 px-5 py-4 sm:px-6">
                    <div>
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-blue-700">Gestión territorial</p>
                        <h2 class="text-xl font-bold text-slate-900">Crear nueva zona</h2>
                        <p class="mt-1 text-sm text-slate-500">Completa la información territorial.</p>
                    </div>
                    <button type="button" wire:click="closeCreateModal" aria-label="Cerrar"
                        class="rounded-full p-2 text-slate-500 hover:bg-slate-100">✕</button>
                </header>

                <form wire:submit="save" class="flex min-h-0 flex-1 flex-col">
                    <div class="min-h-0 flex-1 space-y-5 overflow-y-auto overscroll-contain px-5 py-5 sm:px-6"
                        x-init="$nextTick(() => { $el.scrollTop = 0 })">

                        <div>
                            <label for="create-name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nombre de la zona *</label>
                            <input id="create-name" type="text" wire:model="name" maxlength="150"
                                placeholder="Ej.: Centro de Puente Alto"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="create-location" class="mb-1.5 block text-sm font-semibold text-slate-700">Comuna *</label>
                                <select id="create-location" wire:model="location_id"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    <option value="">Selecciona una comuna</option>
                                    @foreach ($communes as $commune)
                                        <option value="{{ $commune->id }}">{{ $commune->name }}</option>
                                    @endforeach
                                </select>
                                @error('location_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="create-type" class="mb-1.5 block text-sm font-semibold text-slate-700">Tipo de zona *</label>
                                <select id="create-type" wire:model="zone_type"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    <option value="centro_urbano">Centro urbano</option>
                                    <option value="sector_comercial">Sector comercial</option>
                                    <option value="sector_industrial">Sector industrial</option>
                                    <option value="barrio">Barrio</option>
                                    <option value="localidad">Localidad</option>
                                    <option value="otro">Otro</option>
                                </select>
                                @error('zone_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="create-description" class="mb-1.5 block text-sm font-semibold text-slate-700">Descripción</label>
                            <textarea id="create-description" wire:model="description" rows="3" maxlength="500"
                                placeholder="Describe el sector y sus características..."
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50"></textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 hover:bg-blue-50/40">
                            <input type="checkbox" wire:model="active" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600">
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Zona activa</span>
                                <span class="mt-1 block text-xs text-slate-500">Habilitada para utilizarse en Mizona.</span>
                            </span>
                        </label>
                        @error('active') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <footer class="flex shrink-0 flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <button type="button" wire:click="closeCreateModal"
                            class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Cancelar</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="save">Guardar zona</span>
                            <span wire:loading wire:target="save">Guardando...</span>
                        </button>
                    </footer>
                </form>
            </section>
        </div>
    @endif

    {{-- MODAL DETALLES --}}
    @if ($showDetailsModal && $selectedZone)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/55 p-3 sm:p-6"
            style="height: 100dvh;" wire:click.self="closeDetailsModal"
            x-data x-on:keydown.escape.window="$wire.closeDetailsModal()">
            <section class="w-full max-w-xl overflow-hidden rounded-2xl bg-white shadow-2xl" role="dialog" aria-modal="true">
                <header class="flex items-start justify-between border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Ficha territorial</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-900">Detalles de la zona</h2>
                    </div>
                    <button type="button" wire:click="closeDetailsModal" aria-label="Cerrar"
                        class="rounded-full p-2 text-slate-500 hover:bg-slate-100">✕</button>
                </header>

                <div class="space-y-5 p-6">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Nombre</p>
                        <p class="mt-1 text-lg font-semibold text-slate-900">{{ $selectedZone->name }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Comuna</p>
                            <p class="mt-1 text-sm text-slate-800">{{ $selectedZone->location?->name ?? 'Sin comuna' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Tipo de zona</p>
                            <p class="mt-1 text-sm text-slate-800">{{ str($selectedZone->zone_type)->replace('_', ' ')->ucfirst() }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Estado</p>
                        <p class="mt-2">
                            @if ($selectedZone->active)
                                <span class="rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">Activa</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">Inactiva</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Descripción</p>
                        <p class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $selectedZone->description ?: 'Sin descripción registrada.' }}</p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-3 text-xs text-slate-500">
                        Identificador del registro: {{ $selectedZone->id }}
                    </div>
                </div>

                <footer class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="closeDetailsModal"
                        class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Cerrar</button>
                    <button type="button" wire:click="openEditModal({{ $selectedZone->id }})"
                        class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Editar zona</button>
                </footer>
            </section>
        </div>
    @endif

    {{-- MODAL EDITAR --}}
    @if ($showEditModal && $selectedZone)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/55 p-3 sm:p-6"
            style="height: 100dvh;" wire:click.self="closeEditModal"
            x-data x-on:keydown.escape.window="$wire.closeEditModal()">
            <section class="my-auto flex w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
                style="max-height: calc(100dvh - 2rem);" role="dialog" aria-modal="true">

                <header class="flex shrink-0 items-start justify-between border-b border-slate-200 px-5 py-4 sm:px-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Gestión territorial</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-900">Editar zona</h2>
                        <p class="mt-1 text-sm text-slate-500">Modifica la información del registro.</p>
                    </div>
                    <button type="button" wire:click="closeEditModal" aria-label="Cerrar"
                        class="rounded-full p-2 text-slate-500 hover:bg-slate-100">✕</button>
                </header>

                <form wire:submit="update" class="flex min-h-0 flex-1 flex-col">
                    <div class="min-h-0 flex-1 space-y-5 overflow-y-auto px-5 py-5 sm:px-6"
                        x-init="$nextTick(() => { $el.scrollTop = 0 })">

                        <div>
                            <label for="edit-name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nombre de la zona *</label>
                            <input id="edit-name" type="text" wire:model="name" maxlength="150"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="edit-location" class="mb-1.5 block text-sm font-semibold text-slate-700">Comuna *</label>
                                <select id="edit-location" wire:model="location_id"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    <option value="">Selecciona una comuna</option>
                                    @foreach ($communes as $commune)
                                        <option value="{{ $commune->id }}">{{ $commune->name }}</option>
                                    @endforeach
                                </select>
                                @error('location_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="edit-type" class="mb-1.5 block text-sm font-semibold text-slate-700">Tipo de zona *</label>
                                <select id="edit-type" wire:model="zone_type"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    <option value="centro_urbano">Centro urbano</option>
                                    <option value="sector_comercial">Sector comercial</option>
                                    <option value="sector_industrial">Sector industrial</option>
                                    <option value="barrio">Barrio</option>
                                    <option value="localidad">Localidad</option>
                                    <option value="otro">Otro</option>
                                </select>
                                @error('zone_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="edit-description" class="mb-1.5 block text-sm font-semibold text-slate-700">Descripción</label>
                            <textarea id="edit-description" wire:model="description" rows="3" maxlength="500"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50"></textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 hover:bg-blue-50/40">
                            <input type="checkbox" wire:model="active" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600">
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Zona activa</span>
                                <span class="mt-1 block text-xs text-slate-500">Habilitada para utilizarse en Mizona.</span>
                            </span>
                        </label>
                    </div>

                    <footer class="flex shrink-0 flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <button type="button" wire:click="closeEditModal"
                            class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Cancelar</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="update"
                            class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="update">Guardar cambios</span>
                            <span wire:loading wire:target="update">Guardando...</span>
                        </button>
                    </footer>
                </form>
            </section>
        </div>
    @endif

    {{-- CONFIRMACIÓN DE ESTADO --}}
    @if ($showStatusModal && $selectedZone)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/55 p-4"
            style="height: 100dvh;" wire:click.self="closeStatusModal"
            x-data x-on:keydown.escape.window="$wire.closeStatusModal()">
            <section class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl" role="dialog" aria-modal="true">
                <div class="p-6">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full {{ $selectedZone->active ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                        @if ($selectedZone->active)
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M5.6 5.6l12.8 12.8"/>
                            </svg>
                        @else
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="m5 12 4 4L19 6"/>
                            </svg>
                        @endif
                    </div>

                    <h2 class="text-lg font-bold text-slate-900">
                        {{ $selectedZone->active ? '¿Desactivar esta zona?' : '¿Reactivar esta zona?' }}
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Vas a {{ $selectedZone->active ? 'desactivar' : 'reactivar' }}
                        <strong>{{ $selectedZone->name }}</strong>.
                        @if ($selectedZone->active)
                            El registro se conservará y no se eliminará de la base de datos.
                        @else
                            La zona volverá a quedar marcada como activa.
                        @endif
                    </p>
                </div>

                <footer class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="closeStatusModal"
                        class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Cancelar</button>
                    <button type="button" wire:click="confirmToggleActive" wire:loading.attr="disabled"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60 {{ $selectedZone->active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                        {{ $selectedZone->active ? 'Sí, desactivar' : 'Sí, reactivar' }}
                    </button>
                </footer>
            </section>
        </div>
    @endif

</div>
