<div class="min-h-full space-y-5 bg-slate-50 px-4 pb-4 pt-2 sm:px-6 sm:pb-6 sm:pt-3">

    {{-- ENCABEZADO --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-blue-700">
                <span>Gestión territorial</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Tramos de vías</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Maestro de tramos</h1>
            <p class="mt-1 text-sm text-slate-500">
                Administra segmentos, coordenadas y sentidos de circulación de Mizona.
            </p>
        </div>

        <button type="button" wire:click="openCreate"
            class="inline-flex items-center justify-center gap-2 rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Nuevo tramo
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

    {{-- CONTADORES --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total de tramos</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalSegments }}</p>
            <p class="mt-1 text-xs text-slate-500">Registros en el maestro</p>
        </section>
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Tramos activos</p>
            <p class="mt-2 text-3xl font-bold text-green-700">{{ $activeSegments }}</p>
            <p class="mt-1 text-xs text-slate-500">Disponibles para utilizarse</p>
        </section>
    </div>

    {{-- BUSCADOR --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-center gap-3 rounded-xl bg-slate-100 px-4 py-3 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100">
            <svg class="h-5 w-5 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="11" cy="11" r="7"/>
                <path d="m16 16 4 4"/>
            </svg>
            <input type="search" wire:model.live.debounce.300ms="search"
                placeholder="Buscar por tramo, vía o ubicación..."
                aria-label="Buscar tramos"
                class="w-full border-0 bg-transparent text-sm text-slate-900 outline-none placeholder:text-slate-500 focus:ring-0">
        </div>
        <div class="mt-3 flex items-center justify-between px-1 text-xs text-slate-500">
            <span>Directorio de tramos</span>
            <span>{{ $segments->total() }} {{ $segments->total() === 1 ? 'registro' : 'registros' }}</span>
        </div>
    </section>

    {{-- LISTADO --}}
    <section class="overflow-visible rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between rounded-t-2xl border-b border-slate-200 px-5 py-4">
            <div>
                <h2 class="font-semibold text-slate-900">Todos los tramos</h2>
                <p class="mt-1 text-xs text-slate-500">Tramos registrados en la plataforma.</p>
            </div>
            <span class="text-xs text-slate-500">Página {{ $segments->currentPage() }}</span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($segments as $segment)
                <div wire:key="segment-{{ $segment->id }}"
                    class="group flex items-center gap-3 px-4 py-4 transition hover:bg-blue-50/50 sm:px-5">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M4 19 9 5l6 14 5-14"/>
                            <path d="M2 12h20" stroke-dasharray="2 3"/>
                        </svg>
                    </div>

                    <button type="button" wire:click="openDetails({{ $segment->id }})"
                        class="min-w-0 flex-1 text-left">
                        <div class="truncate text-sm font-semibold text-slate-900 group-hover:text-blue-700">
                            {{ $segment->segment_name ?: 'Tramo #' . $segment->id }}
                        </div>
                        <div class="mt-1 flex flex-wrap items-center gap-x-2 text-sm text-slate-500">
                            <span>{{ $segment->road?->current_name ?? 'Vía no disponible' }}</span>
                            <span class="text-slate-300">·</span>
                            <span>{{ $segment->location?->name ?? 'Ubicación no disponible' }}</span>
                        </div>
                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                            @if ($segment->active)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 font-medium text-green-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 font-medium text-slate-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Inactivo
                                </span>
                            @endif

                            @if ($segment->direction_type === 'one_way')
                                <span class="rounded-full bg-blue-50 px-3 py-1.5 font-medium text-blue-700">Sentido único</span>
                            @elseif ($segment->direction_type === 'two_way')
                                <span class="rounded-full bg-violet-50 px-3 py-1.5 font-medium text-violet-700">Doble sentido</span>
                            @else
                                <span class="rounded-full bg-amber-50 px-3 py-1.5 font-medium text-amber-700">Circulación desconocida</span>
                            @endif
                        </div>
                    </button>

                    {{-- ACCIONES --}}
                    <div class="relative shrink-0" x-data="{ open: false }"
                        @click.outside="open = false" @keydown.escape.window="open = false">
                        <button type="button" @click="open = !open" :aria-expanded="open"
                            aria-label="Acciones del tramo"
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
                                wire:click="openDetails({{ $segment->id }})"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-100">
                                Ver detalles
                            </button>
                            <button type="button" @click="open = false"
                                wire:click="openEdit({{ $segment->id }})"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-100">
                                Editar tramo
                            </button>
                            <div class="my-1 border-t border-slate-100"></div>
                            <button type="button" @click="open = false"
                                wire:click="toggleActive({{ $segment->id }})"
                                wire:confirm="¿Deseas cambiar el estado de este tramo?"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm {{ $segment->active ? 'text-red-700 hover:bg-red-50' : 'text-green-700 hover:bg-green-50' }}">
                                {{ $segment->active ? 'Desactivar tramo' : 'Reactivar tramo' }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-14 text-center">
                    <p class="font-semibold text-slate-900">No encontramos tramos</p>
                    <p class="mt-1 text-sm text-slate-500">Prueba con otro término o registra un nuevo tramo.</p>
                    <button type="button" wire:click="openCreate"
                        class="mt-4 text-sm font-semibold text-blue-700 hover:text-blue-900">
                        Crear tramo
                    </button>
                </div>
            @endforelse
        </div>

        @if ($segments->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $segments->links() }}
            </div>
        @endif
    </section>

    {{-- MODAL CREAR / EDITAR TRAMO --}}
    @if ($showModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/55 p-3 sm:p-6"
            style="height: 100dvh;"
            wire:click.self="closeModal"
            x-data
            x-on:keydown.escape.window="$wire.closeModal()">

            <section class="my-auto flex w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
                style="max-height: calc(100dvh - 2rem);"
                role="dialog" aria-modal="true" aria-labelledby="segment-modal-title">

                <header class="flex shrink-0 items-start justify-between border-b border-slate-200 px-5 py-4 sm:px-6">
                    <div>
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-blue-700">Gestión territorial</p>
                        <h2 id="segment-modal-title" class="text-xl font-bold text-slate-900">
                            {{ $editing ? 'Editar tramo de vía' : 'Crear nuevo tramo' }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">Completa la información territorial y de circulación.</p>
                    </div>
                    <button type="button" wire:click="closeModal" aria-label="Cerrar"
                        class="rounded-full p-2 text-slate-500 hover:bg-slate-100">✕</button>
                </header>

                <form wire:submit="save" class="flex min-h-0 flex-1 flex-col">
                    <div class="min-h-0 flex-1 space-y-5 overflow-y-auto overscroll-contain px-5 py-5 sm:px-6"
                        x-init="$nextTick(() => { $el.scrollTop = 0 })">

                        {{-- IDENTIFICACIÓN --}}
                        <div>
                            <label for="segment-name" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Nombre descriptivo del tramo
                            </label>
                            <input id="segment-name" type="text" wire:model="segment_name" maxlength="180"
                                placeholder="Ej.: tramo norte entre dos intersecciones"
                                class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                            @error('segment_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="segment-road" class="mb-1.5 block text-sm font-semibold text-slate-700">Vía *</label>
                                <select id="segment-road" wire:model="road_id"
                                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    <option value="">Selecciona una vía</option>
                                    @foreach ($roads as $road)
                                        <option value="{{ $road->id }}">{{ $road->current_name }} ({{ $road->road_type }})</option>
                                    @endforeach
                                </select>
                                @error('road_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="segment-location" class="mb-1.5 block text-sm font-semibold text-slate-700">Ubicación territorial *</label>
                                <select id="segment-location" wire:model="location_id"
                                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    <option value="">Selecciona una ubicación</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                                @error('location_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- VIGENCIA --}}
                        <div class="border-t border-slate-200 pt-4">
                            <h3 class="text-sm font-semibold text-slate-900">Vigencia del tramo</h3>
                            <div class="mt-3 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="segment-valid-from" class="mb-1.5 block text-sm font-semibold text-slate-700">Válido desde</label>
                                    <input id="segment-valid-from" type="date" wire:model="valid_from"
                                        class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    @error('valid_from') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="segment-valid-to" class="mb-1.5 block text-sm font-semibold text-slate-700">Válido hasta</label>
                                    <input id="segment-valid-to" type="date" wire:model="valid_to"
                                        class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    @error('valid_to') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- COORDENADAS --}}
                        <div class="border-t border-slate-200 pt-4">
                            <h3 class="text-sm font-semibold text-slate-900">Coordenadas del tramo</h3>
                            <p class="mt-1 text-xs text-slate-500">Campos opcionales. Latitud: −90 a 90; longitud: −180 a 180.</p>
                            <div class="mt-3 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="segment-start-lat" class="mb-1.5 block text-sm font-semibold text-slate-700">Latitud inicial</label>
                                    <input id="segment-start-lat" type="number" step="any" wire:model="start_latitude"
                                        class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    @error('start_latitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="segment-start-lng" class="mb-1.5 block text-sm font-semibold text-slate-700">Longitud inicial</label>
                                    <input id="segment-start-lng" type="number" step="any" wire:model="start_longitude"
                                        class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    @error('start_longitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="segment-end-lat" class="mb-1.5 block text-sm font-semibold text-slate-700">Latitud final</label>
                                    <input id="segment-end-lat" type="number" step="any" wire:model="end_latitude"
                                        class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    @error('end_latitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="segment-end-lng" class="mb-1.5 block text-sm font-semibold text-slate-700">Longitud final</label>
                                    <input id="segment-end-lng" type="number" step="any" wire:model="end_longitude"
                                        class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                    @error('end_longitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- CIRCULACIÓN --}}
                        <div class="border-t border-slate-200 pt-4">
                            <h3 class="text-sm font-semibold text-slate-900">Sentido de circulación</h3>
                            <div class="mt-3 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="segment-direction-type" class="mb-1.5 block text-sm font-semibold text-slate-700">Tipo de circulación *</label>
                                    <select id="segment-direction-type" wire:model.live="direction_type"
                                        class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                        <option value="unknown">Desconocido</option>
                                        <option value="one_way">Sentido único</option>
                                        <option value="two_way">Doble sentido</option>
                                    </select>
                                    @error('direction_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="segment-circulation" class="mb-1.5 block text-sm font-semibold text-slate-700">Sentidos permitidos *</label>
                                    <select id="segment-circulation" wire:model="circulation_direction"
                                        class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                        <option value="unknown">Desconocido / sin verificar</option>
                                        @if ($direction_type === 'one_way')
                                            <option value="forward">Inicio → fin</option>
                                            <option value="reverse">Fin → inicio</option>
                                        @elseif ($direction_type === 'two_way')
                                            <option value="both">Ambos sentidos</option>
                                        @endif
                                    </select>
                                    @error('circulation_direction') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="mt-5">
                                <label for="segment-circulation-source" class="mb-1.5 block text-sm font-semibold text-slate-700">Fuente de verificación</label>
                                <input id="segment-circulation-source" type="text" wire:model="circulation_source" maxlength="100"
                                    placeholder="Ej.: señalización observada, documento municipal..."
                                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                @error('circulation_source') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- GEOMETRÍA --}}
                        <details class="rounded-xl border border-slate-200">
                            <summary class="cursor-pointer px-4 py-3 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                Geometría del tramo (opcional)
                            </summary>
                            <div class="border-t border-slate-200 p-4">
                                <label for="segment-geometry" class="mb-1.5 block text-sm font-semibold text-slate-700">Geometría JSON / GeoJSON</label>
                                <textarea id="segment-geometry" rows="5" wire:model="geometryJson"
                                    placeholder='{"type":"LineString","coordinates":[[-70.67,-33.45],[-70.66,-33.44]]}'
                                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 font-mono text-xs focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50"></textarea>
                                <p class="mt-2 text-xs text-slate-500">GeoJSON utiliza el orden [longitud, latitud].</p>
                                @error('geometryJson') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </details>

                        {{-- ESTADO --}}
                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 hover:bg-blue-50/40">
                            <input type="checkbox" wire:model="active" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600">
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Tramo activo</span>
                                <span class="mt-1 block text-xs text-slate-500">Habilitado para utilizarse en Mizona.</span>
                            </span>
                        </label>
                        @error('active') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <footer class="flex shrink-0 flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <button type="button" wire:click="closeModal"
                            class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            Cancelar
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="save">{{ $editing ? 'Guardar cambios' : 'Guardar tramo' }}</span>
                            <span wire:loading wire:target="save">Guardando...</span>
                        </button>
                    </footer>
                </form>
            </section>
        </div>
    @endif

    {{-- MODAL DETALLES --}}
    @if ($showDetailsModal && $selectedSegment)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/55 p-3 sm:p-6"
            style="height: 100dvh;"
            wire:click.self="closeDetails"
            x-data
            x-on:keydown.escape.window="$wire.closeDetails()">

            <section class="my-auto flex w-full max-w-xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
                style="max-height: calc(100dvh - 2rem);" role="dialog" aria-modal="true">
                <header class="flex shrink-0 items-start justify-between border-b border-slate-200 px-5 py-4 sm:px-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Ficha territorial</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-900">Detalles del tramo</h2>
                    </div>
                    <button type="button" wire:click="closeDetails" aria-label="Cerrar"
                        class="rounded-full p-2 text-slate-500 hover:bg-slate-100">✕</button>
                </header>

                <div class="min-h-0 flex-1 space-y-5 overflow-y-auto p-5 sm:p-6">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Nombre</p>
                        <p class="mt-1 text-lg font-semibold text-slate-900">{{ $selectedSegment->segment_name ?: 'Tramo #' . $selectedSegment->id }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Vía</p>
                            <p class="mt-1 text-sm text-slate-800">{{ $selectedSegment->road?->current_name ?? 'No disponible' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Ubicación</p>
                            <p class="mt-1 text-sm text-slate-800">{{ $selectedSegment->location?->name ?? 'No disponible' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Tipo de circulación</p>
                            <p class="mt-1 text-sm text-slate-800">
                                @if ($selectedSegment->direction_type === 'one_way')
                                    Sentido único
                                @elseif ($selectedSegment->direction_type === 'two_way')
                                    Doble sentido
                                @else
                                    Desconocido
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Sentidos permitidos</p>
                            <p class="mt-1 text-sm text-slate-800">
                                @if ($selectedSegment->forward_allowed && $selectedSegment->reverse_allowed)
                                    Ambos sentidos
                                @elseif ($selectedSegment->forward_allowed)
                                    Inicio a fin
                                @elseif ($selectedSegment->reverse_allowed)
                                    Fin a inicio
                                @else
                                    Desconocido / sin verificar
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Coordenadas iniciales</p>
                            <p class="mt-1 text-sm text-slate-800">{{ $selectedSegment->start_latitude ?? '—' }}, {{ $selectedSegment->start_longitude ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Coordenadas finales</p>
                            <p class="mt-1 text-sm text-slate-800">{{ $selectedSegment->end_latitude ?? '—' }}, {{ $selectedSegment->end_longitude ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Válido desde</p>
                            <p class="mt-1 text-sm text-slate-800">{{ $selectedSegment->valid_from?->format('d-m-Y') ?? 'Sin fecha' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Válido hasta</p>
                            <p class="mt-1 text-sm text-slate-800">{{ $selectedSegment->valid_to?->format('d-m-Y') ?? 'Sin fecha final' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Fuente de verificación</p>
                        <p class="mt-1 text-sm text-slate-800">{{ $selectedSegment->circulation_source ?: 'No indicada' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Estado</p>
                        <p class="mt-2">
                            @if ($selectedSegment->active)
                                <span class="rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">Activo</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">Inactivo</span>
                            @endif
                        </p>
                    </div>

                    @if ($selectedSegment->geometry)
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Geometría</p>
                            <pre class="mt-2 overflow-x-auto rounded-xl bg-slate-50 p-3 text-xs text-slate-700">{{ json_encode($selectedSegment->geometry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @endif

                    <div class="rounded-xl bg-slate-50 p-3 text-xs text-slate-500">
                        Identificador del registro: {{ $selectedSegment->id }}
                    </div>
                </div>

                <footer class="flex shrink-0 flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                    <button type="button" wire:click="closeDetails"
                        class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                        Cerrar
                    </button>
                    <button type="button" wire:click="openEdit({{ $selectedSegment->id }})"
                        class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Editar tramo
                    </button>
                </footer>
            </section>
        </div>
    @endif

</div>