<?php

namespace App\Http\Livewire\Staff\StoreGroups;

use App\Http\Traits\AppErrorLog;
use App\Models\StoreGroup;
use Exception;
use Livewire\Component;

class CreateStoreGroup extends Component
{
    public $name;

    protected function rules()
    {
        return [
            'name' => 'required|unique:store_groups,name',
        ];
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

    public function store()
    {
        $this->validate();

        try {
            StoreGroup::create([
                'name' => $this->name,
            ]);

            $this->clearFormField();
            $this->dispatchBrowserEvent('close-modal');
            $this->emit('loadStoreGroups');
            noty()->addSuccess('Store Group successfully created.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.store-groups.create-store-group');
    }
}
