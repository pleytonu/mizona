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
                            @if ($segment->length_meters !== null)
                                <span class="inline-flex items-center gap-1 rounded-full bg-cyan-50 px-3 py-1.5 font-semibold text-cyan-800">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M4 7v10M20 7v10M4 12h16"/>
                                    </svg>
                                    {{ number_format((float) $segment->length_meters, 2, ',', '.') }} m
                                </span>
                            @endif
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

            <section class="my-auto flex w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
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

                        
                        {{-- EDITOR CARTOGRÁFICO --}}
                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">
                                        Editor cartográfico del tramo
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Dibuja el recorrido de la vía haciendo clic en el mapa.
                                        Puedes cambiar el mapa base y editar la línea.
                                    </p>
                                </div>

                                <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                    WGS84 · EPSG:4326
                                </span>
                            </div>

                            <div
                                id="road-segment-map"
                                wire:ignore
                                x-init="window.initMizonaRoadSegmentMap($wire, $el)"
                                class="mt-4 h-[360px] w-full overflow-hidden rounded-xl border border-slate-300 bg-slate-100"
                            ></div>

                            <div class="mt-3 rounded-xl bg-slate-50 p-3 text-sm text-slate-600">
                                <p class="font-semibold text-slate-800">Cómo dibujar el tramo</p>

                                <p class="mt-1">
                                    1. Selecciona la herramienta para dibujar una línea.
                                    2. Haz clic en cada vértice del recorrido.
                                    3. Haz doble clic para finalizar.
                                    4. Guarda el formulario para conservar los cambios.
                                </p>

                                <p class="mt-2 text-xs">
                                    Las coordenadas se completan automáticamente. La geometría
                                    se guarda como GeoJSON, con las coordenadas en orden
                                    [longitud, latitud].
                                </p>
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
                        <div class="rounded-xl border border-cyan-100 bg-cyan-50 p-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-cyan-800">Longitud del tramo</p>
                            @if ($selectedSegment->length_meters !== null)
                                <p class="mt-1 text-xl font-bold tabular-nums text-slate-900">
                                    {{ number_format((float) $selectedSegment->length_meters, 2, ',', '.') }} m
                                </p>
                                <p class="mt-1 text-xs text-slate-600">
                                    {{ number_format((float) $selectedSegment->length_meters / 1000, 4, ',', '.') }} km
                                </p>
                            @else
                                <p class="mt-1 text-sm text-slate-500">Pendiente de calcular</p>
                            @endif
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


@assets
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css"
    >

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        defer
    ></script>

    <script
        src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"
        defer
    ></script>
@endassets


@script
<script>
    window.initMizonaRoadSegmentMap = function (wire, element) {
        if (!window.L || !L.Control || !L.Control.Draw) {
            element.innerHTML =
                '<div style="padding:16px;color:#b91c1c">' +
                'No se pudo cargar el mapa. Comprueba tu conexión a Internet.' +
                '</div>';
            return;
        }

        // Evitar inicializar dos veces el mismo elemento.
        if (element._mizonaLeafletMap) {
            element._mizonaLeafletMap.remove();
        }

        const numberOrNull = (value) => {
            if (value === null || value === undefined || value === '') {
                return null;
            }

            const number = Number(value);

            return Number.isFinite(number) ? number : null;
        };

        // Centro inicial: Santiago. Si hay coordenadas guardadas,
        // se utilizarán para centrar el mapa.
        const startLat = numberOrNull(wire.start_latitude);
        const startLng = numberOrNull(wire.start_longitude);

        const initialCenter =
            startLat !== null && startLng !== null
                ? [startLat, startLng]
                : [-33.45, -70.66];

        const map = L.map(element, {
            zoomControl: true
        }).setView(initialCenter, startLat !== null ? 16 : 12);

        element._mizonaLeafletMap = map;

        // Mapas base.
        const baseLayers = {
            'Calles — OpenStreetMap': L.tileLayer(
                'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                {
                    maxZoom: 19,
                    attribution:
                        '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
                }
            ),

            'Satélite — Esri': L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                {
                    maxZoom: 19,
                    attribution:
                        'Sources: Esri, Maxar, Earthstar Geographics, and the GIS User Community'
                }
            ),

            'Topográfico — OpenTopoMap': L.tileLayer(
                'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
                {
                    maxZoom: 17,
                    attribution:
                        '&copy; OpenStreetMap contributors, SRTM | Map style: OpenTopoMap'
                }
            ),

            'Claro — CARTO': L.tileLayer(
                'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
                {
                    maxZoom: 20,
                    attribution:
                        '&copy; OpenStreetMap contributors &copy; CARTO'
                }
            ),

            'Oscuro — CARTO': L.tileLayer(
                'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
                {
                    maxZoom: 20,
                    attribution:
                        '&copy; OpenStreetMap contributors &copy; CARTO'
                }
            )
        };

        baseLayers['Calles — OpenStreetMap'].addTo(map);

        L.control.layers(baseLayers, null, {
            collapsed: true,
            position: 'topright'
        }).addTo(map);

        // Grupo obligatorio para editar y eliminar las líneas dibujadas.
        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Interfaz de dibujo en español.
        if (L.drawLocal) {
            L.drawLocal.draw.toolbar.buttons.polyline =
                'Dibujar tramo de vía';

            L.drawLocal.draw.toolbar.actions.text = 'Cancelar';
            L.drawLocal.draw.toolbar.actions.title = 'Cancelar dibujo';

            L.drawLocal.draw.toolbar.finish.text = 'Finalizar';
            L.drawLocal.draw.toolbar.finish.title = 'Finalizar dibujo';

            L.drawLocal.draw.toolbar.undo.text = 'Deshacer último punto';
            L.drawLocal.draw.toolbar.undo.title = 'Deshacer último punto';

            L.drawLocal.edit.toolbar.buttons.edit = 'Editar tramo';
            L.drawLocal.edit.toolbar.buttons.remove = 'Eliminar tramo';

            L.drawLocal.edit.toolbar.actions.save.text = 'Guardar';
            L.drawLocal.edit.toolbar.actions.save.title = 'Guardar cambios';

            L.drawLocal.edit.toolbar.actions.cancel.text = 'Cancelar';
            L.drawLocal.edit.toolbar.actions.cancel.title = 'Cancelar edición';
        }

        new L.Control.Draw({
            position: 'topleft',

            draw: {
                polyline: {
                    shapeOptions: {
                        color: '#2563eb',
                        weight: 5,
                        opacity: 0.9
                    },
                    allowIntersection: false
                },

                polygon: false,
                rectangle: false,
                circle: false,
                marker: false,
                circlemarker: false
            },

            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: true
            }
        }).addTo(map);

        // Convertir una línea dibujada al GeoJSON que espera Mizona.
        function syncLayer(layer) {
            let latlngs = layer.getLatLngs();

            if (!Array.isArray(latlngs)) {
                return;
            }

            latlngs = latlngs.flat(Infinity).filter((point) =>
                point &&
                typeof point.lat === 'number' &&
                typeof point.lng === 'number'
            );

            if (latlngs.length < 2) {
                return;
            }

            const coordinates = latlngs.map((point) => [
                Number(point.lng.toFixed(7)),
                Number(point.lat.toFixed(7))
            ]);

            const first = latlngs[0];
            const last = latlngs[latlngs.length - 1];

            const geometry = {
                type: 'LineString',
                coordinates: coordinates
            };

            // false mantiene las modificaciones locales para enviarlas
            // junto con el formulario al ejecutar save().
            wire.set(
                'geometryJson',
                JSON.stringify(geometry, null, 2),
                false
            );

            wire.set('start_latitude', first.lat.toFixed(7), false);
            wire.set('start_longitude', first.lng.toFixed(7), false);
            wire.set('end_latitude', last.lat.toFixed(7), false);
            wire.set('end_longitude', last.lng.toFixed(7), false);
        }

        function clearGeometry() {
            wire.set('geometryJson', '', false);
            wire.set('start_latitude', '', false);
            wire.set('start_longitude', '', false);
            wire.set('end_latitude', '', false);
            wire.set('end_longitude', '', false);
        }

        
        function buildSegmentPopup() {
            const card = document.createElement('div');

            Object.assign(card.style, {
                width: '280px',
                maxWidth: '100%',
                overflow: 'hidden',
                border: '1px solid #DCE5EF',
                borderRadius: '12px',
                background: '#FFFFFF',
                color: '#1E293B',
                fontFamily: 'Inter, system-ui, sans-serif',
                fontSize: '10px',
                boxShadow: '0 4px 14px rgba(15, 23, 42, 0.08)'
            });

            const active = [
                true, 1, '1', 'true', 'on'
            ].includes(wire.active);

            const directions = {
                one_way: 'Sentido único',
                two_way: 'Doble sentido'
            };

            function displayValue(value, fallback = 'No registrado') {
                if (
                    value === null ||
                    value === undefined ||
                    String(value).trim() === ''
                ) {
                    return fallback;
                }

                return String(value);
            }

            // Calcular longitud provisional desde la geometría dibujada.
            // La longitud definitiva se recalcula y guarda en el modelo Laravel.
            function getGeometryLengthMeters() {
                let geometry = null;

                try {
                    const raw = wire.geometryJson;
                    geometry = raw
                        ? (typeof raw === 'string' ? JSON.parse(raw) : raw)
                        : null;
                } catch (error) {
                    return null;
                }

                if (geometry?.type === 'Feature') {
                    geometry = geometry.geometry;
                }

                if (geometry?.type !== 'LineString' || !Array.isArray(geometry.coordinates)) {
                    return null;
                }

                const points = geometry.coordinates
                    .filter((point) => Array.isArray(point) && point.length >= 2)
                    .map((point) => [Number(point[1]), Number(point[0])])
                    .filter(([lat, lng]) => Number.isFinite(lat) && Number.isFinite(lng));

                if (points.length < 2) {
                    return null;
                }

                const radius = 6371008.8;
                const toRadians = (degrees) => degrees * Math.PI / 180;
                let total = 0;

                for (let i = 1; i < points.length; i++) {
                    const [lat1, lon1] = points[i - 1].map(toRadians);
                    const [lat2, lon2] = points[i].map(toRadians);
                    const dLat = lat2 - lat1;
                    const dLon = lon2 - lon1;
                    const a = Math.sin(dLat / 2) ** 2
                        + Math.cos(lat1) * Math.cos(lat2) * Math.sin(dLon / 2) ** 2;
                    total += radius * 2 * Math.atan2(
                        Math.sqrt(Math.min(1, Math.max(0, a))),
                        Math.sqrt(1 - Math.min(1, Math.max(0, a)))
                    );
                }

                return total;
            }

            function formatMeters(value) {
                return new Intl.NumberFormat('es-CL', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value) + ' m';
            }

            function selectedLabel(id) {
                const select = document.getElementById(id);

                if (!select || !select.value) {
                    return 'No seleccionada';
                }

                return select.selectedOptions?.[0]?.textContent?.trim()
                    || 'No registrada';
            }

            function makeText(text, styles = {}) {
                const element = document.createElement('div');

                element.textContent = text;

                Object.assign(element.style, styles);

                return element;
            }

            // Encabezado ejecutivo.
            const header = document.createElement('div');

            Object.assign(header.style, {
                background: '#142B4A',
                color: '#FFFFFF',
                padding: '10px 11px',
                borderBottom: '3px solid #3B82F6'
            });

            header.appendChild(makeText('MIZONA · GESTIÓN TERRITORIAL', {
                fontSize: '9px',
                fontWeight: '700',
                letterSpacing: '1.0px',
                color: '#B9D8FF',
                marginBottom: '6px'
            }));

            const titleRow = document.createElement('div');

            Object.assign(titleRow.style, {
                display: 'flex',
                alignItems: 'flex-start',
                justifyContent: 'space-between',
                gap: '8px'
            });

            const title = makeText(
                displayValue(wire.segment_name, 'Tramo de vía'),
                {
                    fontSize: '16px',
                    fontWeight: '600',
                    lineHeight: '1.1',
                    color: '#FFFFFF',
                    overflowWrap: 'anywhere',
                    flex: '1'
                }
            );

            const badge = makeText(active ? 'ACTIVO' : 'INACTIVO', {
                flexShrink: '0',
                padding: '4px 7px',
                borderRadius: '20px',
                fontSize: '9px',
                fontWeight: '700',
                letterSpacing: '0.3px',
                background: active ? '#DCFCE7' : '#E2E8F0',
                color: active ? '#166534' : '#475569'
            });

            titleRow.append(title, badge);
            header.appendChild(titleRow);

            header.appendChild(makeText('Ficha técnica del tramo vial', {
                marginTop: '3px',
                fontSize: '11px',
                color: '#D5E1EF'
            }));

            card.appendChild(header);

            // Cuerpo de la ficha.
            const body = document.createElement('div');

            Object.assign(body.style, {
                padding: '13px 15px'
            });

            body.appendChild(makeText('INFORMACIÓN GENERAL', {
                fontSize: '9px',
                fontWeight: '700',
                letterSpacing: '0.8px',
                color: '#64748B',
                marginBottom: '7px'
            }));

            function addRow(label, value) {
                const row = document.createElement('div');

                Object.assign(row.style, {
                    display: 'grid',
                    gridTemplateColumns: '94px minmax(0, 1fr)',
                    gap: '8px',
                    padding: '7px 0',
                    borderBottom: '1px solid #EDF2F7',
                    alignItems: 'start'
                });

                row.appendChild(makeText(label, {
                    color: '#64748B',
                    fontSize: '11px',
                    lineHeight: '1.5'
                }));

                row.appendChild(makeText(value, {
                    color: '#1E293B',
                    fontSize: '11px',
                    fontWeight: '600',
                    lineHeight: '1.5',
                    overflowWrap: 'anywhere'
                }));

                body.appendChild(row);
            }

            addRow('Vía', selectedLabel('segment-road'));
            addRow('Territorio', selectedLabel('segment-location'));

            const geometryLength = getGeometryLengthMeters();
            addRow(
                'Longitud',
                geometryLength === null ? 'Dibuja una línea' : formatMeters(geometryLength)
            );

            if (geometryLength !== null) {
                addRow(
                    'Equivalencia',
                    new Intl.NumberFormat('es-CL', {
                        minimumFractionDigits: 4,
                        maximumFractionDigits: 4
                    }).format(geometryLength / 1000) + ' km'
                );
            }

            addRow(
                'Circulación',
                directions[wire.direction_type] || 'No definida'
            );

            addRow(
                'Válido desde',
                displayValue(wire.valid_from, 'Sin fecha')
            );

            addRow(
                'Válido hasta',
                displayValue(wire.valid_to, 'Sin vencimiento')
            );

            // Coordenadas destacadas.
            const coordinatesBox = document.createElement('div');

            Object.assign(coordinatesBox.style, {
                marginTop: '12px',
                padding: '11px',
                background: '#F8FAFC',
                border: '1px solid #E2E8F0',
                borderRadius: '9px'
            });

            coordinatesBox.appendChild(makeText('COORDENADAS GEOGRÁFICAS', {
                fontSize: '9px',
                fontWeight: '700',
                letterSpacing: '0.5px',
                color: '#475569',
                marginBottom: '9px'
            }));

            function addCoordinate(label, latitude, longitude, color) {
                const item = document.createElement('div');

                Object.assign(item.style, {
                    display: 'flex',
                    alignItems: 'flex-start',
                    gap: '8px',
                    marginTop: '7px'
                });

                const marker = document.createElement('div');

                Object.assign(marker.style, {
                    width: '8px',
                    height: '8px',
                    minWidth: '8px',
                    borderRadius: '50%',
                    background: color,
                    marginTop: '4px'
                });

                const details = document.createElement('div');

                details.style.minWidth = '0';

                details.appendChild(makeText(label, {
                    fontSize: '9px',
                    color: '#64748B',
                    marginBottom: '3px',
                    fontWeight: '600'
                }));

                details.appendChild(makeText(
                    `${displayValue(latitude, '—')}, ${displayValue(longitude, '—')}`,
                    {
                        fontSize: '11px',
                        fontWeight: '600',
                        color: '#0F172A',
                        fontVariantNumeric: 'tabular-nums',
                        overflowWrap: 'anywhere',
                        lineHeight: '1.5'
                    }
                ));

                item.append(marker, details);
                coordinatesBox.appendChild(item);
            }

            addCoordinate(
                'PUNTO INICIAL · LAT, LNG',
                wire.start_latitude,
                wire.start_longitude,
                '#16A34A'
            );

            addCoordinate(
                'PUNTO FINAL · LAT, LNG',
                wire.end_latitude,
                wire.end_longitude,
                '#DC2626'
            );

            coordinatesBox.appendChild(makeText(
                'Sistema de referencia: WGS84 · EPSG:4326',
                {
                    marginTop: '10px',
                    paddingTop: '8px',
                    borderTop: '1px solid #E2E8F0',
                    color: '#64748B',
                    fontSize: '9px'
                }
            ));

            body.appendChild(coordinatesBox);
            card.appendChild(body);

            return card;
        }


        function attachSegmentPopup(layer) {
            layer.bindPopup(
                () => buildSegmentPopup(),
                {
                    maxWidth: 320,
                    maxHeight: 380,
                    autoPan: true,
                    autoPanPadding: [20, 20],
                    closeButton: true
                }
            );
        }

        // Al comenzar un nuevo trazado, se conserva un único tramo.
        map.on(L.Draw.Event.CREATED, function (event) {
            if (event.layerType !== 'polyline') {
                return;
            }

            drawnItems.clearLayers();

            attachSegmentPopup(event.layer);

            drawnItems.addLayer(event.layer);
            syncLayer(event.layer);
        });

        // Actualizar las coordenadas tras editar vértices.
        map.on(L.Draw.Event.EDITED, function (event) {
            event.layers.eachLayer(function (layer) {
                syncLayer(layer);
            });
        });

        // Si se elimina la línea, se limpian también los campos.
        map.on(L.Draw.Event.DELETED, function () {
            if (drawnItems.getLayers().length === 0) {
                clearGeometry();
            } else {
                syncLayer(drawnItems.getLayers()[0]);
            }
        });

        // Cargar la geometría existente cuando se edita un tramo.
        function loadExistingGeometry() {
            let parsedGeometry = null;

            try {
                const raw = wire.geometryJson;

                if (raw) {
                    parsedGeometry =
                        typeof raw === 'string' ? JSON.parse(raw) : raw;
                }
            } catch (error) {
                console.warn('GeoJSON del tramo no válido:', error);
            }

            // Aceptar también geometrías GeoJSON Feature o FeatureCollection.
            if (parsedGeometry?.type === 'Feature') {
                parsedGeometry = parsedGeometry.geometry;
            }

            if (parsedGeometry?.type === 'FeatureCollection') {
                const feature = parsedGeometry.features?.find(
                    (item) => item.geometry?.type === 'LineString'
                );

                parsedGeometry = feature?.geometry ?? null;
            }

            if (
                parsedGeometry?.type === 'LineString' &&
                Array.isArray(parsedGeometry.coordinates) &&
                parsedGeometry.coordinates.length >= 2
            ) {
                // GeoJSON usa [longitud, latitud];
                // Leaflet recibe [latitud, longitud].
                const latlngs = parsedGeometry.coordinates.map(
                    ([longitude, latitude]) => [
                        Number(latitude),
                        Number(longitude)
                    ]
                );

                const line = L.polyline(latlngs, {
                    color: '#2563eb',
                    weight: 5,
                    opacity: 0.9
                });

                attachSegmentPopup(line);
                drawnItems.addLayer(line);
                map.fitBounds(line.getBounds(), {
                    padding: [25, 25],
                    maxZoom: 17
                });

                return;
            }

            // Compatibilidad con tramos antiguos que tienen extremos
            // guardados, pero todavía no cuentan con geometría.
            const endLat = numberOrNull(wire.end_latitude);
            const endLng = numberOrNull(wire.end_longitude);

            if (
                startLat !== null &&
                startLng !== null &&
                endLat !== null &&
                endLng !== null
            ) {
                const line = L.polyline(
                    [[startLat, startLng], [endLat, endLng]],
                    {
                        color: '#2563eb',
                        weight: 5,
                        opacity: 0.9
                    }
                );

                attachSegmentPopup(line);    
                drawnItems.addLayer(line);
                map.fitBounds(line.getBounds(), {
                    padding: [25, 25],
                    maxZoom: 17
                });
            }
        }

        loadExistingGeometry();

        // El modal necesita que el mapa recalcule sus dimensiones.
        requestAnimationFrame(() => {
            map.invalidateSize();
        });
    };
</script>
@endscript