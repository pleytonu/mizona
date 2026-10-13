<?php

namespace App\Livewire\RoadSegments;

use App\Models\Location;
use App\Models\Road;
use App\Models\RoadSegment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;
    public bool $showDetailsModal = false;
    public bool $editing = false;

    public ?int $selectedId = null;

    public ?int $road_id = null;
    public ?int $location_id = null;

    public string $segment_name = '';
    public string $valid_from = '';
    public string $valid_to = '';

    public string $start_latitude = '';
    public string $start_longitude = '';
    public string $end_latitude = '';
    public string $end_longitude = '';

    public string $geometryJson = '';

    public string $direction_type = 'unknown';
    public string $circulation_direction = 'unknown';
    public string $circulation_source = '';

    public bool $active = true;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->resetValidation();

        $this->editing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $segment = RoadSegment::findOrFail($id);

        $this->resetValidation();
        $this->resetForm();

        $this->selectedId = $segment->id;
        $this->road_id = $segment->road_id;
        $this->location_id = $segment->location_id;
        $this->segment_name = $segment->segment_name ?? '';
        $this->valid_from = $segment->valid_from?->format('Y-m-d') ?? '';
        $this->valid_to = $segment->valid_to?->format('Y-m-d') ?? '';

        $this->start_latitude = (string) ($segment->start_latitude ?? '');
        $this->start_longitude = (string) ($segment->start_longitude ?? '');
        $this->end_latitude = (string) ($segment->end_latitude ?? '');
        $this->end_longitude = (string) ($segment->end_longitude ?? '');

        $this->geometryJson = $segment->geometry
            ? json_encode(
                $segment->geometry,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
            : '';

        $this->direction_type = $segment->direction_type ?? 'unknown';

        $this->circulation_direction = match (true) {
            $segment->forward_allowed && $segment->reverse_allowed => 'both',
            $segment->forward_allowed => 'forward',
            $segment->reverse_allowed => 'reverse',
            default => 'unknown',
        };

        $this->circulation_source = $segment->circulation_source ?? '';
        $this->active = (bool) $segment->active;

        $this->editing = true;
        $this->showModal = true;
    }

    public function openDetails(int $id): void
    {
        $this->selectedId = $id;
        $this->showDetailsModal = true;
    }

    public function closeDetails(): void
    {
        $this->showDetailsModal = false;
        $this->selectedId = null;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    private function resetForm(): void
    {
        $this->reset([
            'selectedId',
            'road_id',
            'location_id',
            'segment_name',
            'valid_from',
            'valid_to',
            'start_latitude',
            'start_longitude',
            'end_latitude',
            'end_longitude',
            'geometryJson',
            'circulation_source',
        ]);

        $this->direction_type = 'unknown';
        $this->circulation_direction = 'unknown';
        $this->active = true;
    }

    private function rulesForForm(): array
    {
        return [
            'road_id' => [
                'required',
                'integer',
                Rule::exists('roads', 'id'),
            ],
            'location_id' => [
                'required',
                'integer',
                Rule::exists('locations', 'id'),
            ],
            'segment_name' => ['nullable', 'string', 'max:180'],
            'valid_from' => ['nullable', 'date'],
            'valid_to' => [
                'nullable',
                'date',
                'after_or_equal:valid_from',
            ],
            'start_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'start_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'end_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'end_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'geometryJson' => ['nullable', 'string', 'max:1000000'],
            'direction_type' => [
                'required',
                Rule::in(['unknown', 'one_way', 'two_way']),
            ],
            'circulation_direction' => [
                'required',
                Rule::in(['unknown', 'forward', 'reverse', 'both']),
            ],
            'circulation_source' => ['nullable', 'string', 'max:100'],
            'active' => ['boolean'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate($this->rulesForForm());

        $geometry = null;

        if (trim($this->geometryJson) !== '') {
            $geometry = json_decode(
                $this->geometryJson,
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if (! is_array($geometry)) {
                $this->addError(
                    'geometryJson',
                    'La geometría debe ser un objeto o arreglo JSON válido.'
                );

                return;
            }
        }

        if (
            $validated['direction_type'] === 'unknown'
            && $validated['circulation_direction'] !== 'unknown'
        ) {
            $this->addError(
                'circulation_direction',
                'Si el tipo de circulación es desconocido, el sentido permitido también debe ser desconocido.'
            );

            return;
        }

        if (
            $validated['direction_type'] === 'one_way'
            && ! in_array(
                $validated['circulation_direction'],
                ['forward', 'reverse'],
                true
            )
        ) {
            $this->addError(
                'circulation_direction',
                'Para una vía de sentido único, selecciona inicio a fin o fin a inicio.'
            );

            return;
        }

        if (
            $validated['direction_type'] === 'two_way'
            && $validated['circulation_direction'] !== 'both'
        ) {
            $this->addError(
                'circulation_direction',
                'Para una vía bidireccional, selecciona ambos sentidos.'
            );

            return;
        }

        $forward = in_array(
            $validated['circulation_direction'],
            ['forward', 'both'],
            true
        );

        $reverse = in_array(
            $validated['circulation_direction'],
            ['reverse', 'both'],
            true
        );

        $data = [
            'road_id' => $validated['road_id'],
            'location_id' => $validated['location_id'],
            'segment_name' => trim($validated['segment_name'] ?? '') ?: null,
            'valid_from' => $validated['valid_from'] ?: null,
            'valid_to' => $validated['valid_to'] ?: null,
            'start_latitude' => $validated['start_latitude'] ?: null,
            'start_longitude' => $validated['start_longitude'] ?: null,
            'end_latitude' => $validated['end_latitude'] ?: null,
            'end_longitude' => $validated['end_longitude'] ?: null,
            'geometry' => $geometry,
            'direction_type' => $validated['direction_type'],
            'forward_allowed' => $forward,
            'reverse_allowed' => $reverse,
            'circulation_source' => trim(
                $validated['circulation_source'] ?? ''
            ) ?: null,
            'active' => $validated['active'],
        ];

        if ($validated['circulation_direction'] !== 'unknown') {
            $data['circulation_verified_at'] = now();
        } else {
            $data['circulation_verified_at'] = null;
        }

        DB::transaction(function () use ($data): void {
            if ($this->editing && $this->selectedId) {
                RoadSegment::findOrFail($this->selectedId)->update($data);
            } else {
                RoadSegment::create($data);
            }
        });

        $wasEditing = $this->editing;

        $this->closeModal();
        $this->resetPage();

        session()->flash(
            'success',
            $wasEditing
                ? 'El tramo se actualizó correctamente.'
                : 'El tramo se registró correctamente.'
        );
    }

    public function toggleActive(int $id): void
    {
        $segment = RoadSegment::findOrFail($id);

        $segment->update([
            'active' => ! $segment->active,
        ]);

        session()->flash(
            'success',
            $segment->active
                ? 'El tramo se activó correctamente.'
                : 'El tramo se desactivó correctamente.'
        );
    }

    public function render()
    {
        $segments = RoadSegment::query()
            ->with(['road', 'location'])
            ->when(trim($this->search) !== '', function (Builder $query): void {
                $term = trim($this->search);

                $query->where(function (Builder $subquery) use ($term): void {
                    $subquery
                        ->where('segment_name', 'like', "%{$term}%")
                        ->orWhereHas('road', function (Builder $roadQuery) use ($term): void {
                            $roadQuery->where(
                                'current_name',
                                'like',
                                "%{$term}%"
                            );
                        })
                        ->orWhereHas('location', function (Builder $locationQuery) use ($term): void {
                            $locationQuery->where(
                                'name',
                                'like',
                                "%{$term}%"
                            );
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.road-segments.index', [
            'segments' => $segments,
            'roads' => Road::query()
                ->where('active', true)
                ->orderBy('current_name')
                ->get(),
            'locations' => Location::query()
                ->where('active', true)
                ->orderBy('name')
                ->get(),
            'selectedSegment' => $this->selectedId
                ? RoadSegment::with(['road', 'location'])->find($this->selectedId)
                : null,
            'totalSegments' => RoadSegment::count(),
            'activeSegments' => RoadSegment::where('active', true)->count(),
        ]);
    }
}
