<div class="min-h-full space-y-5 bg-slate-50 px-4 pb-4 pt-2 sm:px-6 sm:pb-6 sm:pt-3">

    {{-- ENCABEZADO --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-blue-700">
                <span>Gestión territorial</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Vías</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Maestro de vías</h1>
            <p class="mt-1 text-sm text-slate-500">
                Administra calles, avenidas, pasajes y caminos de Mizona.
            </p>
        </div>

        <button type="button" wire:click="openCreateModal"
            class="inline-flex items-center justify-center gap-2 rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Nueva vía
        </button>
    </div>

    {{-- MENSAJE --}}
    @if (session()->has('success'))
        <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m5 12 4 4L19 6"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- CONTADORES --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total de vías</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalRoads }}</p>
            <p class="mt-1 text-xs text-slate-500">Registros en el maestro</p>
        </section>
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Vías activas</p>
            <p class="mt-2 text-3xl font-bold text-green-700">{{ $activeRoads }}</p>
            <p class="mt-1 text-xs text-slate-500">Disponibles para utilizarse</p>
        </section>
    </div>

    {{-- BUSCADOR --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-center gap-3 rounded-xl bg-slate-100 px-4 py-3 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100">
            <svg class="h-5 w-5 shrink-0 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="7"/>
                <path d="m16 16 4 4"/>
            </svg>
            <input type="search" wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nombre, tipo o código oficial..."
                aria-label="Buscar vías"
                class="w-full border-0 bg-transparent text-sm text-slate-900 outline-none placeholder:text-slate-500 focus:ring-0">
        </div>
        <div class="mt-3 flex items-center justify-between px-1 text-xs text-slate-500">
            <span>Directorio de vías</span>
            <span>{{ $roads->total() }} {{ $roads->total() === 1 ? 'registro' : 'registros' }}</span>
        </div>
    </section>

    {{-- LISTADO --}}
    <section class="overflow-visible rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between rounded-t-2xl border-b border-slate-200 px-5 py-4">
            <div>
                <h2 class="font-semibold text-slate-900">Todas las vías</h2>
                <p class="mt-1 text-xs text-slate-500">Vías registradas en la plataforma.</p>
            </div>
            <span class="text-xs text-slate-500">Página {{ $roads->currentPage() }}</span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($roads as $road)
                <div wire:key="road-{{ $road->id }}"
                    class="group flex items-center gap-3 px-4 py-4 transition hover:bg-blue-50/50 sm:px-5">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 20V4h5l3 4 3-4h5v16h-5l-3-4-3 4H4Z"/>
                            <path d="M9 4v16M15 4v16"/>
                        </svg>
                    </div>

                    <button type="button" wire:click="openDetails({{ $road->id }})"
                        class="min-w-0 flex-1 text-left">
                        <div class="truncate text-sm font-semibold text-slate-900 group-hover:text-blue-700">
                            {{ $road->current_name }}
                        </div>
                        <div class="mt-1 flex flex-wrap items-center gap-x-2 text-sm text-slate-500">
                            <span>{{ str($road->road_type)->replace('_', ' ')->ucfirst() }}</span>
                            <span class="text-slate-300">·</span>
                            <span>{{ $road->official_code ?: 'Sin código oficial' }}</span>
                        </div>
                    </button>

                    <div class="hidden sm:block">
                        @if ($road->active)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>Activa
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Inactiva
                            </span>
                        @endif
                    </div>

                    {{-- ACCIONES --}}
                    <div class="relative shrink-0" x-data="{ open: false }"
                        @click.outside="open = false" @keydown.escape.window="open = false">
                        <button type="button" @click="open = !open"
                            :aria-expanded="open"
                            aria-label="Acciones de {{ $road->current_name }}"
                            class="rounded-full p-2 text-slate-500 transition hover:bg-slate-200 hover:text-slate-900">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="5" cy="12" r="1.7"/>
                                <circle cx="12" cy="12" r="1.7"/>
                                <circle cx="19" cy="12" r="1.7"/>
                            </svg>
                        </button>

                        <div x-cloak x-show="open" x-transition
                            class="absolute right-0 top-full z-40 mt-1 w-52 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl">
                            <button type="button" @click="open = false"
                                wire:click="openDetails({{ $road->id }})"
                                class="w-full rounded-lg px-3 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-100">
                                Ver detalles
                            </button>
                            <button type="button" @click="open = false"
                                wire:click="openEditModal({{ $road->id }})"
                                class="w-full rounded-lg px-3 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-100">
                                Editar vía
                            </button>
                            <div class="my-1 border-t border-slate-100"></div>
                            <button type="button" @click="open = false"
                                wire:click="openStatusModal({{ $road->id }})"
                                class="w-full rounded-lg px-3 py-2.5 text-left text-sm {{ $road->active ? 'text-red-700 hover:bg-red-50' : 'text-green-700 hover:bg-green-50' }}">
                                {{ $road->active ? 'Desactivar vía' : 'Reactivar vía' }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-14 text-center">
                    <p class="font-semibold text-slate-900">No encontramos vías</p>
                    <p class="mt-1 text-sm text-slate-500">Prueba con otro término o registra una nueva vía.</p>
                    <button type="button" wire:click="openCreateModal"
                        class="mt-4 text-sm font-semibold text-blue-700 hover:text-blue-900">
                        Registrar primera vía
                    </button>
                </div>
            @endforelse
        </div>

        @if ($roads->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $roads->links() }}
            </div>
        @endif
    </section>

    {{-- CAMPOS COMPARTIDOS DEL FORMULARIO --}}
    @if ($showCreateModal || ($showEditModal && $selectedRoad))
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/55 p-3 sm:p-6"
            style="height: 100dvh;"
            wire:click.self="{{ $showCreateModal ? 'closeCreateModal' : 'closeEditModal' }}"
            x-data
            x-on:keydown.escape.window="$wire.{{ $showCreateModal ? 'closeCreateModal' : 'closeEditModal' }}()">

            <section class="my-auto flex w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
                style="max-height: calc(100dvh - 2rem);" role="dialog" aria-modal="true"
                x-data="{
                    roadName: @js($current_name ?? ''),
                    fitLogo() {
                        const text = this.$refs.roadLogoText;
                        if (!text) return;
                        const length = (this.roadName || 'NOMBRE DE VÍA').trim().length;
                        text.style.fontSize = (length > 25 ? 8 : length > 20 ? 9 : length > 15 ? 10 : length > 11 ? 12 : 14) + 'px';
                    }
                }"
                x-init="fitLogo()">

                {{-- ENCABEZADO DIVIDIDO: TEXTO A LA IZQUIERDA Y LETRERO A LA DERECHA --}}
                <header class="relative grid shrink-0 grid-cols-2 items-center gap-3 border-b border-slate-200 px-5 py-4 sm:gap-5 sm:px-6">
                    <div class="min-w-0 pr-2">
                        <p class="mb-1 text-[10px] font-semibold uppercase tracking-wider text-blue-700 sm:text-xs">Gestión territorial</p>
                        <h2 class="text-lg font-bold leading-tight text-slate-900 sm:text-xl">
                            {{ $showCreateModal ? 'Nueva vía' : 'Editar vía' }}
                        </h2>
                        <p class="mt-1 text-xs leading-5 text-slate-500 sm:text-sm">Completa la información del registro.</p>
                    </div>

                    <div class="flex min-w-0 flex-col items-center justify-center gap-1 pr-5 sm:pr-6" aria-live="polite">
                        <div class="w-1/2 min-w-0 max-w-[220px] flex-none rounded-md bg-black p-1 shadow-sm">
                            <div class="flex h-10 items-center rounded border border-white px-1">
                                <div class="flex min-w-0 flex-1 items-center justify-center overflow-hidden px-1">
                                    <span x-ref="roadLogoText"
                                        x-text="(roadName || '').trim() || 'NOMBRE DE VÍA'"
                                        class="block w-full overflow-hidden text-ellipsis whitespace-nowrap text-center font-extrabold uppercase leading-none tracking-tight text-white"
                                        style="font-size: 14px;"
                                        title="Vista previa del nombre de la vía"></span>
                                </div>
                                <span class="flex h-full w-6 shrink-0 items-center justify-center border-l border-white pl-1" aria-hidden="true">
                                    <svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M13.2 4.8 20.4 12l-7.2 7.2-1.7-1.7 4.3-4.3H3.6v-2.4h12.2l-4.3-4.3z"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <p class="text-center text-[10px] leading-tight text-slate-400 sm:text-xs">Vista previa del letrero vial</p>
                    </div>

                    <button type="button"
                        wire:click="{{ $showCreateModal ? 'closeCreateModal' : 'closeEditModal' }}"
                        aria-label="Cerrar"
                        class="absolute right-2 top-2 rounded-full p-2 text-slate-500 hover:bg-slate-100 sm:right-3 sm:top-3">✕</button>
                </header>

                <form wire:submit="{{ $showCreateModal ? 'save' : 'update' }}"
                    class="flex min-h-0 flex-1 flex-col">
                    <div class="min-h-0 flex-1 space-y-5 overflow-y-auto overscroll-contain px-5 py-5 sm:px-6">

                        <div>
                            <label for="road-name-modal" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Nombre de la vía *
                            </label>
                            <input id="road-name-modal" type="text" wire:model="current_name"
                                x-on:input="roadName = $event.target.value; fitLogo()"
                                maxlength="180" required
                                placeholder="Ej.: Avenida Concha y Toro"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                            @error('current_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="road-type-modal" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                    Tipo de vía *
                                </label>
                                <select id="road-type-modal" wire:model="road_type"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
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
                                @error('road_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="road-code-modal" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                    Código oficial
                                </label>
                                <input id="road-code-modal" type="text" wire:model="official_code"
                                    maxlength="100" placeholder="Opcional"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50">
                                @error('official_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="road-description-modal" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Descripción
                            </label>
                            <textarea id="road-description-modal" wire:model="description"
                                rows="3" maxlength="5000"
                                placeholder="Observaciones de la vía..."
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50"></textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4 hover:bg-blue-50/40">
                            <input type="checkbox" wire:model="active"
                                class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600">
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">Vía activa</span>
                                <span class="mt-1 block text-xs text-slate-500">Habilitada para utilizarse en Mizona.</span>
                            </span>
                        </label>
                    </div>

                    <footer class="flex shrink-0 flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <button type="button"
                            wire:click="{{ $showCreateModal ? 'closeCreateModal' : 'closeEditModal' }}"
                            class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            Cancelar
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            wire:target="{{ $showCreateModal ? 'save' : 'update' }}"
                            class="rounded-full bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="{{ $showCreateModal ? 'save' : 'update' }}">
                                {{ $showCreateModal ? 'Guardar vía' : 'Guardar cambios' }}
                            </span>
                            <span wire:loading wire:target="{{ $showCreateModal ? 'save' : 'update' }}">Guardando...</span>
                        </button>
                    </footer>
                </form>
            </section>
        </div>
    @endif

    {{-- MODAL DETALLES --}}
    @if ($showDetailsModal && $selectedRoad)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/55 p-3 sm:p-6"
            style="height: 100dvh;" wire:click.self="closeDetailsModal"
            x-data x-on:keydown.escape.window="$wire.closeDetailsModal()">
            <section class="w-full max-w-xl overflow-hidden rounded-2xl bg-white shadow-2xl" role="dialog" aria-modal="true">
                <header class="grid shrink-0 grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] items-center gap-3 border-b border-slate-200 px-5 py-4 sm:gap-4 sm:px-6">
                    {{-- Columna izquierda: título y descripción --}}
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Ficha de vía</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-900">Detalles de la vía</h2>
                        <p class="mt-1 text-sm text-slate-500">Información general del registro.</p>
                    </div>

                    {{-- Columna derecha: vista previa del letrero vial --}}
                    <div class="min-w-0" x-data="{
                        fitLogo() {
                            const text = this.$refs.roadLogoText;
                            if (!text) return;
                            const length = text.textContent.trim().length;
                            text.style.fontSize = (length > 25 ? 8 : length > 20 ? 9 : length > 15 ? 10 : length > 11 ? 12 : 14) + 'px';
                        }
                    }" x-init="fitLogo()">
                        <div class="mx-auto w-full max-w-[190px] rounded-md bg-black p-1 shadow-sm">
                            <div class="flex h-10 items-center rounded border border-white px-1">
                                <div class="flex min-w-0 flex-1 items-center justify-center overflow-hidden px-1">
                                    <span x-ref="roadLogoText"
                                        class="block w-full overflow-hidden text-ellipsis whitespace-nowrap text-center font-extrabold uppercase leading-none tracking-tight text-white"
                                        style="font-size: 14px;">{{ $selectedRoad->current_name }}</span>
                                </div>
                                <span class="flex h-full w-6 shrink-0 items-center justify-center border-l border-white pl-1" aria-hidden="true">
                                    <svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M13.2 4.8 20.4 12l-7.2 7.2-1.7-1.7 4.3-4.3H3.6v-2.4h12.2l-4.3-4.3z"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <p class="mt-1 text-center text-xs text-slate-400">Vista previa del letrero vial</p>
                    </div>

                    {{-- Botón cerrar --}}
                    <button type="button" wire:click="closeDetailsModal" aria-label="Cerrar"
                        class="rounded-full p-2 text-slate-500 hover:bg-slate-100">✕</button>
                </header>

                <div class="space-y-5 p-6">

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Nombre</p>
                        <p class="mt-1 text-lg font-semibold text-slate-900">{{ $selectedRoad->current_name }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Tipo de vía</p>
                            <p class="mt-1 text-sm text-slate-800">{{ str($selectedRoad->road_type)->replace('_', ' ')->ucfirst() }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Código oficial</p>
                            <p class="mt-1 text-sm text-slate-800">{{ $selectedRoad->official_code ?: 'Sin código registrado' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Estado</p>
                        <p class="mt-2">
                            @if ($selectedRoad->active)
                                <span class="rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">Activa</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">Inactiva</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Descripción</p>
                        <p class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-700">
                            {{ $selectedRoad->description ?: 'Sin descripción registrada.' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-3 text-xs text-slate-500">
                        Identificador del registro: {{ $selectedRoad->id }}
                    </div>
                </div>

                <footer class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="closeDetailsModal"
                        class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                        Cerrar
                    </button>
                    <button type="button" wire:click="openEditModal({{ $selectedRoad->id }})"
                        class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Editar vía
                    </button>
                </footer>
            </section>
        </div>
    @endif

    {{-- CONFIRMACIÓN DE ESTADO --}}
    @if ($showStatusModal && $selectedRoad)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/55 p-4"
            style="height: 100dvh;" wire:click.self="closeStatusModal"
            x-data x-on:keydown.escape.window="$wire.closeStatusModal()">
            <section class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl" role="dialog" aria-modal="true">
                <div class="p-6">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full {{ $selectedRoad->active ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                        @if ($selectedRoad->active)
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M5.6 5.6l12.8 12.8"/>
                            </svg>
                        @else
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m5 12 4 4L19 6"/>
                            </svg>
                        @endif
                    </div>

                    <h2 class="text-lg font-bold text-slate-900">
                        {{ $selectedRoad->active ? '¿Desactivar esta vía?' : '¿Reactivar esta vía?' }}
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Vas a {{ $selectedRoad->active ? 'desactivar' : 'reactivar' }}
                        <strong>{{ $selectedRoad->current_name }}</strong>.
                        @if ($selectedRoad->active)
                            El registro se conservará y no se eliminará de la base de datos.
                        @else
                            La vía volverá a quedar marcada como activa.
                        @endif
                    </p>
                </div>

                <footer class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="closeStatusModal"
                        class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                        Cancelar
                    </button>
                    <button type="button" wire:click="confirmToggleActive" wire:loading.attr="disabled"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60 {{ $selectedRoad->active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                        {{ $selectedRoad->active ? 'Sí, desactivar' : 'Sí, reactivar' }}
                    </button>
                </footer>
            </section>
        </div>
    @endif

</div>