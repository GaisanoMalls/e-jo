<?php

namespace App\Http\Livewire\Staff\StoreGroups;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\StoreGroup;
use Exception;
use Livewire\Component;

class StoreGroupList extends Component
{
    use BasicModelQueries;

    public $storeGroups = [];
    public $storeGroupEditId;
    public $storeGroupDeleteId;
    public $name;

    protected $listeners = ['loadStoreGroups' => 'fetchStoreGroups'];

    protected function rules()
    {
        return [
            'name' => "required|unique:store_groups,name,{$this->storeGroupEditId}",
        ];
    }

    public function mount()
    {
        $this->fetchStoreGroups();
    }

    public function fetchStoreGroups()
    {
        $this->storeGroups = $this->queryStoreGroups();
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function cancel()
    {
        $this->clearFormField();
    }

    public function editStoreGroup(StoreGroup $storeGroup)
    {
        $this->storeGroupEditId = $storeGroup->id;
        $this->name = $storeGroup->name;
        $this->dispatchBrowserEvent('show-edit-store-group-modal');
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate();

        try {
            StoreGroup::find($this->storeGroupEditId)->update([
                'name' => $this->name,
            ]);

            $this->clearFormField();
            $this->fetchStoreGroups();
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('Store Group successfully updated.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function deleteStoreGroup(StoreGroup $storeGroup)
    {
        $this->storeGroupDeleteId = $storeGroup->id;
        $this->name = $storeGroup->name;
        $this->dispatchBrowserEvent('show-delete-store-group-modal');
    }

    public function delete()
    {
        try {
            StoreGroup::find($this->storeGroupDeleteId)->delete();
            $this->storeGroupDeleteId = null;
            $this->fetchStoreGroups();
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('Store Group successfully deleted.');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.store-groups.store-group-list', [
            'storeGroups' => $this->storeGroups,
        ]);
    }
}
