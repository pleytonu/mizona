<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    @php
        $user = auth()->user();
        $currentRoute = request()->route()?->getName();
    @endphp

    <div class="min-h-screen lg:flex">

        {{-- MENÚ LATERAL MIZONA --}}
        <aside class="flex w-full shrink-0 flex-col border-b border-slate-200 bg-white lg:sticky lg:top-0 lg:h-screen lg:w-64 lg:border-b-0 lg:border-r">

            {{-- Identidad --}}
            <div class="border-b border-slate-100 px-5 py-5">
                <a href="{{ route('dashboard') }}" wire:navigate
                   class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-sm">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M12 21s7-6 7-12a7 7 0 1 0-14 0c0 6 7 12 7 12Z"/>
                            <path d="M9 12V8l3 3 3-3v4"/>
                        </svg>
                    </div>

                    <div>
                        <div class="text-xl font-bold tracking-tight text-slate-900">
                            Mizona<span class="text-blue-700">.</span>
                        </div>
                        <div class="text-xs text-slate-500">
                            Tu zona, tus negocios
                        </div>
                    </div>
                </a>
            </div>

            {{-- Acción principal --}}
            <div class="px-4 pt-5">
                <a href="{{ route('territorial-zones.index') }}"
                   wire:navigate
                   class="flex items-center justify-center gap-2 rounded-full bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Gestionar zonas
                </a>
            </div>

            {{-- Navegación --}}
            <nav class="flex-1 space-y-6 px-3 py-5">

                <div>
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Principal
                    </p>

                    <a href="{{ route('dashboard') }}"
                       wire:navigate
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $currentRoute === 'dashboard' ? 'bg-blue-100 text-blue-800' : 'text-slate-600 hover:bg-slate-100' }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <rect x="3" y="3" width="8" height="8" rx="2"/>
                            <rect x="13" y="3" width="8" height="5" rx="2"/>
                            <rect x="13" y="10" width="8" height="11" rx="2"/>
                            <rect x="3" y="13" width="8" height="8" rx="2"/>
                        </svg>
                        Dashboard
                    </a>
                </div>

                <div>
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Gestión territorial
                    </p>

                    <a href="{{ route('territorial-zones.index') }}"
                       wire:navigate
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ str_starts_with($currentRoute ?? '', 'territorial-zones.') ? 'bg-blue-100 text-blue-800' : 'text-slate-600 hover:bg-slate-100' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M3 6.5 9 4l6 2.5L21 4v13.5L15 20l-6-2.5L3 20V6.5Z"/>
                            <path d="M9 4v13.5M15 6.5V20"/>
                        </svg>
                        Zonas territoriales
                    </a>

                    <div class="mt-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M3 10 12 3l9 7v10H3V10Z"/>
                            <path d="M9 20v-7h6v7"/>
                        </svg>
                        <span class="flex-1">Regiones y comunas</span>
                        <span class="text-xs">Pronto</span>
                    </div>
                </div>

                <div>
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Catastro
                    </p>

                    @foreach (['Predios', 'Direcciones', 'Edificios y estructuras', 'Unidades y oficinas'] as $item)
                        <div class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-400">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <rect x="4" y="3" width="16" height="18" rx="1"/>
                                <path d="M9 7h2m2 0h2M9 11h2m2 0h2M9 15h2m2 0h2M10 21v-3h4v3"/>
                            </svg>
                            <span class="flex-1">{{ $item }}</span>
                            <span class="text-xs">Pronto</span>
                        </div>
                    @endforeach
                </div>

                <div>
                    <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Directorio
                    </p>

                    @foreach (['Organizaciones', 'Establecimientos'] as $item)
                        <div class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-400">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <rect x="3" y="7" width="18" height="14" rx="2"/>
                                <path d="M8 7V4h8v3M3 12h18"/>
                            </svg>
                            <span class="flex-1">{{ $item }}</span>
                            <span class="text-xs">Pronto</span>
                        </div>
                    @endforeach
                </div>
            </nav>

            {{-- Usuario y cierre de sesión --}}
            <div class="border-t border-slate-200 p-3">
                @if ($user)
                    <div class="flex items-center gap-3 rounded-xl p-2">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-800">
                            {{ mb_strtoupper(mb_substr($user->name ?? 'U', 0, 1)) }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-slate-900">
                                {{ $user->name }}
                            </p>
                            <p class="truncate text-xs text-slate-500">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-600 transition hover:bg-red-50 hover:text-red-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M10 17l5-5-5-5M15 12H3"/>
                                <path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/>
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                @endif

                <p class="mt-3 px-2 text-center text-xs text-slate-400">
                    Mizona.cl · Plataforma territorial
                </p>
            </div>
        </aside>

        {{-- ÁREA PRINCIPAL --}}
        <div class="min-w-0 flex-1">

            {{-- Barra superior --}}
            <header class="sticky top-0 z-30 flex h-[76px] items-center gap-4 border-b border-slate-200 bg-white/95 px-5 backdrop-blur sm:px-8">

                <div class="hidden text-sm text-slate-500 sm:block">
                    Plataforma de gestión
                </div>

                <div class="flex-1"></div>

                <div class="hidden max-w-md flex-1 items-center gap-3 rounded-full bg-slate-100 px-4 py-2.5 md:flex">
                    <svg class="h-5 w-5 shrink-0 text-slate-500" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m16 16 4 4"/>
                    </svg>
                    <span class="text-sm text-slate-500">
                        Mizona · Gestión territorial
                    </span>
                </div>

                @if ($user)
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-800">
                        {{ mb_strtoupper(mb_substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                @endif
            </header>

            {{-- Contenido de cada página --}}
            <main class="min-w-0 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>

    </div>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
