<?php

namespace App\Livewire\Properties;

use App\Models\Property;
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

    public ?int $selectedPropertyId = null;

    public string $name = '';
    public string $territorial_zone_id = '';
    public string $cadastral_code = '';
    public string $property_type = 'terreno';
    public string $area_m2 = '';
    public string $description = '';
    public bool $active = true;

    private function propertyTypes(): array
    {
        return [
            'terreno',
            'urbano',
            'rural',
            'comercial',
            'industrial',
            'habitacional',
            'mixto',
            'otro',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name',
            'territorial_zone_id',
            'cadastral_code',
            'area_m2',
            'description',
        ]);

        $this->property_type = 'terreno';
        $this->active = true;
    }

    private function validationRules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:180'],

            'territorial_zone_id' => [
                'nullable',
                'integer',
                Rule::exists('territorial_zones', 'id'),
            ],

            'cadastral_code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'property_type' => [
                'required',
                Rule::in($this->propertyTypes()),
            ],

            'area_m2' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'active' => ['boolean'],
        ];
    }

    private function validationMessages(): array
    {
        return [
            'name.max' => 'El nombre no puede superar los 180 caracteres.',
            'territorial_zone_id.exists' => 'Selecciona una zona territorial válida.',
            'cadastral_code.max' => 'El código catastral no puede superar los 100 caracteres.',
            'property_type.required' => 'Selecciona el tipo de predio.',
            'property_type.in' => 'El tipo de predio no es válido.',
            'area_m2.numeric' => 'La superficie debe ser un número.',
            'area_m2.min' => 'La superficie no puede ser negativa.',
            'area_m2.max' => 'La superficie supera el límite permitido.',
            'description.max' => 'La descripción no puede superar los 1000 caracteres.',
        ];
    }

    private function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'predio';
        }

        $slug = $baseSlug;
        $number = 1;

        while (
            Property::query()
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $number;
            $number++;
        }

        return $slug;
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

    public function save(): void
    {
        $validated = $this->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $name = trim($validated['name'] ?? '');
        $slug = $this->makeUniqueSlug($name);

        DB::transaction(function () use ($validated, $name, $slug): void {
            Property::create([
                'territorial_zone_id' => $validated['territorial_zone_id'] ?: null,
                'name' => $name !== '' ? $name : null,
                'slug' => $slug,
                'cadastral_code' => trim($validated['cadastral_code'] ?? '') ?: null,
                'property_type' => $validated['property_type'],
                'area_m2' => $validated['area_m2'] !== ''
                    ? $validated['area_m2']
                    : null,
                'description' => trim($validated['description'] ?? '') ?: null,
                'active' => $validated['active'],
            ]);
        });

        $this->closeCreateModal();
        $this->resetForm();
        $this->resetPage();

        session()->flash('success', 'El predio se registró correctamente.');
    }

    public function openDetails(int $id): void
    {
        $this->findProperty($id);

        $this->selectedPropertyId = $id;
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->selectedPropertyId = null;
    }

    public function openEditModal(int $id): void
    {
        $property = $this->findProperty($id);

        $this->resetValidation();

        $this->selectedPropertyId = $property->id;
        $this->name = $property->name ?? '';
        $this->territorial_zone_id = (string) ($property->territorial_zone_id ?? '');
        $this->cadastral_code = $property->cadastral_code ?? '';
        $this->property_type = $property->property_type;
        $this->area_m2 = $property->area_m2 !== null
            ? (string) $property->area_m2
            : '';
        $this->description = $property->description ?? '';
        $this->active = (bool) $property->active;

        $this->showDetailsModal = false;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->selectedPropertyId = null;
        $this->resetValidation();
        $this->resetForm();
    }

    public function update(): void
    {
        if (! $this->selectedPropertyId) {
            abort(404);
        }

        $property = $this->findProperty($this->selectedPropertyId);

        $validated = $this->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $name = trim($validated['name'] ?? '');

        // Si no se escribe un nombre, se conserva el slug actual.
        $slug = $name !== ''
            ? $this->makeUniqueSlug($name, $property->id)
            : $property->slug;

        DB::transaction(function () use ($property, $validated, $name, $slug): void {
            $property->update([
                'territorial_zone_id' => $validated['territorial_zone_id'] ?: null,
                'name' => $name !== '' ? $name : null,
                'slug' => $slug,
                'cadastral_code' => trim($validated['cadastral_code'] ?? '') ?: null,
                'property_type' => $validated['property_type'],
                'area_m2' => $validated['area_m2'] !== ''
                    ? $validated['area_m2']
                    : null,
                'description' => trim($validated['description'] ?? '') ?: null,
                'active' => $validated['active'],
            ]);
        });

        $this->closeEditModal();

        session()->flash('success', 'El predio se actualizó correctamente.');
    }

    public function openStatusModal(int $id): void
    {
        $this->findProperty($id);

        $this->selectedPropertyId = $id;
        $this->showStatusModal = true;
    }

    public function closeStatusModal(): void
    {
        $this->showStatusModal = false;
        $this->selectedPropertyId = null;
    }

    public function confirmToggleActive(): void
    {
        if (! $this->selectedPropertyId) {
            abort(404);
        }

        $property = $this->findProperty($this->selectedPropertyId);
        $willActivate = ! (bool) $property->active;

        $property->update(['active' => $willActivate]);

        $this->closeStatusModal();

        session()->flash(
            'success',
            $willActivate
                ? 'El predio se reactivó correctamente.'
                : 'El predio se desactivó correctamente.'
        );
    }

    private function findProperty(int $id): Property
    {
        return Property::query()->findOrFail($id);
    }

    public function render()
    {
        $properties = Property::query()
            ->with('territorialZone.location')
            ->withCount(['addresses', 'structures'])
            ->when(trim($this->search) !== '', function ($query): void {
                $search = trim($this->search);

                $query->where(function ($subquery) use ($search): void {
                    $subquery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('cadastral_code', 'like', '%' . $search . '%')
                        ->orWhere('property_type', 'like', '%' . $search . '%')
                        ->orWhereHas('territorialZone', function ($zoneQuery) use ($search): void {
                            $zoneQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('territorialZone.location', function ($locationQuery) use ($search): void {
                            $locationQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(10);

        $zones = TerritorialZone::query()
            ->where('active', true)
            ->with('location')
            ->orderBy('name')
            ->get();

        $selectedProperty = $this->selectedPropertyId
            ? Property::query()
                ->with([
                    'territorialZone.location',
                    'addresses.location',
                    'structures.levels.units',
                ])
                ->withCount(['addresses', 'structures'])
                ->find($this->selectedPropertyId)
            : null;

        return view('livewire.properties.index', [
            'properties' => $properties,
            'zones' => $zones,
            'selectedProperty' => $selectedProperty,
        ]);
    }
}
