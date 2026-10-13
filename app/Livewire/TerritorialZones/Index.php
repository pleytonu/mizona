<?php

namespace App\Livewire\TerritorialZones;

use App\Models\Location;
use App\Models\TerritorialZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showCreateModal = false;
    public bool $showDetailsModal = false;
    public bool $showEditModal = false;
    public bool $showStatusModal = false;

    public ?int $selectedZoneId = null;

    public string $name = '';
    public string $location_id = '';
    public string $zone_type = 'centro_urbano';
    public string $description = '';
    public bool $active = true;

    private function zoneTypes(): array
    {
        return [
            'centro_urbano',
            'sector_comercial',
            'sector_industrial',
            'barrio',
            'localidad',
            'otro',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->resetValidation();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetValidation();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name',
            'location_id',
            'description',
        ]);

        $this->zone_type = 'centro_urbano';
        $this->active = true;
    }

    private function validationRules(?int $ignoreId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],

            'location_id' => [
                'required',
                'integer',
                Rule::exists('locations', 'id')->where(
                    fn ($query) => $query
                        ->where('location_type_id', 3)
                        ->where('active', true)
                ),
            ],

            'zone_type' => [
                'required',
                Rule::in($this->zoneTypes()),
            ],

            'description' => ['nullable', 'string', 'max:500'],
            'active' => ['boolean'],
        ];
    }

    private function validationMessages(): array
    {
        return [
            'name.required' => 'Ingresa el nombre de la zona.',
            'name.max' => 'El nombre no puede superar los 150 caracteres.',
            'location_id.required' => 'Selecciona una comuna.',
            'location_id.exists' => 'Selecciona una comuna válida y activa.',
            'zone_type.required' => 'Selecciona el tipo de zona.',
            'zone_type.in' => 'El tipo de zona no es válido.',
            'description.max' => 'La descripción no puede superar los 500 caracteres.',
        ];
    }

    private function makeUniqueSlug(string $name, int $locationId, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'zona';
        }

        $slug = $baseSlug;
        $number = 1;

        while (
            TerritorialZone::query()
                ->where('location_id', $locationId)
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $number;
            $number++;
        }

        return $slug;
    }

    public function save(): void
    {
        $validated = $this->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $slug = $this->makeUniqueSlug(
            $validated['name'],
            (int) $validated['location_id']
        );

        DB::transaction(function () use ($validated, $slug): void {
            TerritorialZone::create([
                'location_id' => (int) $validated['location_id'],
                'parent_id' => null,
                'name' => $validated['name'],
                'slug' => $slug,
                'zone_type' => $validated['zone_type'],
                'description' => $validated['description'] ?: null,
                'active' => $validated['active'],
            ]);
        });

        $this->closeCreateModal();
        $this->resetForm();
        $this->resetPage();

        session()->flash('success', 'La zona territorial se registró correctamente.');
    }

    public function openDetails(int $id): void
    {
        $this->findZone($id);

        $this->selectedZoneId = $id;
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->selectedZoneId = null;
    }

    public function openEditModal(int $id): void
    {
        $zone = $this->findZone($id);

        $this->resetValidation();

        $this->selectedZoneId = $zone->id;
        $this->name = $zone->name;
        $this->location_id = (string) $zone->location_id;
        $this->zone_type = $zone->zone_type;
        $this->description = $zone->description ?? '';
        $this->active = (bool) $zone->active;

        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->selectedZoneId = null;
        $this->resetValidation();
        $this->resetForm();
    }

    public function update(): void
    {
        if (! $this->selectedZoneId) {
            abort(404);
        }

        $zone = $this->findZone($this->selectedZoneId);

        $validated = $this->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $slug = $this->makeUniqueSlug(
            $validated['name'],
            (int) $validated['location_id'],
            $zone->id
        );

        DB::transaction(function () use ($zone, $validated, $slug): void {
            $zone->update([
                'location_id' => (int) $validated['location_id'],
                'name' => $validated['name'],
                'slug' => $slug,
                'zone_type' => $validated['zone_type'],
                'description' => $validated['description'] ?: null,
                'active' => $validated['active'],
            ]);
        });

        $this->closeEditModal();

        session()->flash('success', 'La zona territorial se actualizó correctamente.');
    }

    public function openStatusModal(int $id): void
    {
        $this->findZone($id);

        $this->selectedZoneId = $id;
        $this->showStatusModal = true;
    }

    public function closeStatusModal(): void
    {
        $this->showStatusModal = false;
        $this->selectedZoneId = null;
    }

    public function confirmToggleActive(): void
    {
        if (! $this->selectedZoneId) {
            abort(404);
        }

        $zone = $this->findZone($this->selectedZoneId);
        $willActivate = ! (bool) $zone->active;

        $zone->update(['active' => $willActivate]);

        $this->closeStatusModal();

        session()->flash(
            'success',
            $willActivate
                ? 'La zona territorial se reactivó correctamente.'
                : 'La zona territorial se desactivó correctamente.'
        );
    }

    private function findZone(int $id): TerritorialZone
    {
        return TerritorialZone::query()->findOrFail($id);
    }

    public function render()
    {
        $zones = TerritorialZone::query()
            ->with('location')
            ->when(trim($this->search) !== '', function ($query): void {
                $search = trim($this->search);

                $query->where(function ($subquery) use ($search): void {
                    $subquery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('zone_type', 'like', '%' . $search . '%')
                        ->orWhereHas('location', function ($locationQuery) use ($search): void {
                            $locationQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('name')
            ->paginate(10);

        $communes = Location::query()
            ->where('location_type_id', 3)
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $selectedZone = $this->selectedZoneId
            ? TerritorialZone::query()->with('location')->find($this->selectedZoneId)
            : null;

        return view('livewire.territorial-zones.index', [
            'zones' => $zones,
            'communes' => $communes,
            'selectedZone' => $selectedZone,
        ]);
    }
}
