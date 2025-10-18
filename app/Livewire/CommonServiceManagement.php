<?php

namespace App\Livewire;

use App\Models\CommonService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Title('Управление типами работ')]
class CommonServiceManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingServiceId = null;

    // Поиск
    public $search = '';

    protected $paginationTheme = 'tailwind';

    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('required|string|max:255')]
    public $titleEn = '';

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-common-services');

        $user = auth()->user();
        $this->canCreate = $user->can('manage-common-services');
        $this->canEdit = $user->can('manage-common-services');
        $this->canDelete = $user->can('manage-common-services');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getServices()
    {
        $query = CommonService::query();

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('title_ru')->paginate(10);
    }

    public function createService()
    {
        $this->authorize('manage-common-services');

        $this->validate();

        CommonService::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'showCreateModal']);

        session()->flash('message', 'Тип работы успешно создан');

        // Перерисовываем компонент
        $this->render();
    }

    public function editService($serviceId)
    {
        $service = CommonService::findOrFail($serviceId);
        $this->authorize('manage-common-services');

        $this->editingServiceId = $service->id;
        $this->titleRu = $service->title_ru;
        $this->titleKk = $service->title_kk;
        $this->titleEn = $service->title_en;

        $this->showEditModal = true;
    }

    public function updateService()
    {
        $this->authorize('manage-common-services');

        $service = CommonService::findOrFail($this->editingServiceId);

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'titleEn' => 'required|string|max:255',
        ]);

        $service->update([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'showEditModal', 'editingServiceId']);

        session()->flash('message', 'Тип работы успешно обновлен');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteService($serviceId)
    {
        $this->authorize('manage-common-services');

        $service = CommonService::findOrFail($serviceId);

        // Проверяем, есть ли связанные записи
        if ($service->act_of_work_services()->count() > 0) {
            session()->flash('error', 'Нельзя удалить тип работы, так как с ним связаны акты выполненных работ');
            return;
        }

        $service->delete();

        session()->flash('message', 'Тип работы успешно удален');
    }

    public function render()
    {
        return view('livewire.common-service-management', [
            'services' => $this->getServices(),
        ])->layout(get_user_layout());
    }
}
