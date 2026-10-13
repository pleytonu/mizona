<?php

namespace App\Livewire\Roads;

use App\Models\Road;
use Illuminate\Support\Facades\DB;
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

    public ?int $selectedRoadId = null;

    public string $current_name = '';
    public string $road_type = 'calle';
    public string $official_code = '';
    public string $description = '';
    public bool $active = true;

    private function roadTypes(): array
    {
        return [
            'calle',
            'avenida',
            'pasaje',
            'camino',
            'carretera',
            'autopista',
            'ruta',
            'callejon',
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
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'current_name',
            'official_code',
            'description',
        ]);

        $this->road_type = 'calle';
        $this->active = true;
    }

    private function validationRules(): array
    {
        return [
            'current_name' => ['required', 'string', 'max:180'],
            'road_type' => [
                'required',
                Rule::in($this->roadTypes()),
            ],
            'official_code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:5000'],
            'active' => ['boolean'],
        ];
    }

    private function validationMessages(): array
    {
        return [
            'current_name.required' => 'Ingresa el nombre de la vía.',
            'current_name.max' => 'El nombre no puede superar los 180 caracteres.',
            'road_type.required' => 'Selecciona el tipo de vía.',
            'road_type.in' => 'El tipo de vía no es válido.',
            'official_code.max' => 'El código oficial no puede superar los 100 caracteres.',
            'description.max' => 'La descripción no puede superar los 5000 caracteres.',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $validated['current_name'] = trim($validated['current_name']);
        $validated['official_code'] = trim($validated['official_code'] ?? '') ?: null;
        $validated['description'] = trim($validated['description'] ?? '') ?: null;

        DB::transaction(function () use ($validated): void {
            Road::create($validated);
        });

        $this->closeCreateModal();
        $this->resetPage();

        session()->flash('success', 'La vía se registró correctamente.');
    }

    public function openDetails(int $id): void
    {
        $this->findRoad($id);

        $this->selectedRoadId = $id;
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->selectedRoadId = null;
    }

    public function openEditModal(int $id): void
    {
        $road = $this->findRoad($id);

        $this->resetValidation();

        $this->selectedRoadId = $road->id;
        $this->current_name = $road->current_name;
        $this->road_type = $road->road_type;
        $this->official_code = $road->official_code ?? '';
        $this->description = $road->description ?? '';
        $this->active = (bool) $road->active;

        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->selectedRoadId = null;
        $this->resetValidation();
        $this->resetForm();
    }

    public function update(): void
    {
        if (! $this->selectedRoadId) {
            abort(404);
        }

        $road = $this->findRoad($this->selectedRoadId);

        $validated = $this->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $validated['current_name'] = trim($validated['current_name']);
        $validated['official_code'] = trim($validated['official_code'] ?? '') ?: null;
        $validated['description'] = trim($validated['description'] ?? '') ?: null;

        DB::transaction(function () use ($road, $validated): void {
            $road->update($validated);
        });

        $this->closeEditModal();

        session()->flash('success', 'La vía se actualizó correctamente.');
    }

    public function openStatusModal(int $id): void
    {
        $this->findRoad($id);

        $this->selectedRoadId = $id;
        $this->showStatusModal = true;
    }

    public function closeStatusModal(): void
    {
        $this->showStatusModal = false;
        $this->selectedRoadId = null;
    }

    public function confirmToggleActive(): void
    {
        if (! $this->selectedRoadId) {
            abort(404);
        }

        $road = $this->findRoad($this->selectedRoadId);
        $willActivate = ! (bool) $road->active;

        $road->update(['active' => $willActivate]);

        $this->closeStatusModal();

        session()->flash(
            'success',
            $willActivate
                ? 'La vía se reactivó correctamente.'
                : 'La vía se desactivó correctamente.'
        );
    }

    private function findRoad(int $id): Road
    {
        return Road::query()->findOrFail($id);
    }

    public function render()
    {
        $roads = Road::query()
            ->when(trim($this->search) !== '', function ($query): void {
                $search = trim($this->search);

                $query->where(function ($subquery) use ($search): void {
                    $subquery
                        ->where('current_name', 'like', '%' . $search . '%')
                        ->orWhere('road_type', 'like', '%' . $search . '%')
                        ->orWhere('official_code', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('current_name')
            ->orderBy('id')
            ->paginate(10);

        $selectedRoad = $this->selectedRoadId
            ? Road::query()->find($this->selectedRoadId)
            : null;

        $totalRoads = Road::count();
        $activeRoads = Road::where('active', true)->count();

        return view('livewire.roads.index', [
            'roads' => $roads,
            'selectedRoad' => $selectedRoad,
            'totalRoads' => $totalRoads,
            'activeRoads' => $activeRoads,
        ]);
    }
}
