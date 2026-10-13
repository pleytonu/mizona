<div class="space-y-5">
    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre del predio</label>
        <input type="text" wire:model="name" placeholder="Ej.: Predio industrial Don Carlos" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Zona territorial</label>
            <select wire:model="territorial_zone_id" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                <option value="">Sin zona asignada</option>
                @foreach ($zones as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                @endforeach
            </select>
            @error('territorial_zone_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Tipo de predio</label>
            <select wire:model="property_type" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                @foreach ([
                    'terreno' => 'Terreno',
                    'urbano' => 'Urbano',
                    'rural' => 'Rural',
                    'comercial' => 'Comercial',
                    'industrial' => 'Industrial',
                    'otro' => 'Otro',
                ] as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach

            </select>
            @error('property_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Código catastral</label>
            <input type="text" wire:model="cadastral_code" placeholder="Ej.: PA-0001" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('cadastral_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Superficie en m²</label>
            <input type="number" min="0" step="0.01" wire:model="area_m2" placeholder="Ej.: 450.50" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('area_m2') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Descripción</label>
        <textarea wire:model="description" rows="3" placeholder="Características y observaciones del predio..." class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-200">
        <input type="checkbox" wire:model="active" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        Predio activo
    </label>
</div>
