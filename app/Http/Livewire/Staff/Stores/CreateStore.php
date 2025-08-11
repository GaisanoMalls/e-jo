<?php

namespace App\Http\Livewire\Staff\Stores;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\Store;
use Exception;
use Livewire\Component;

class CreateStore extends Component
{
    use BasicModelQueries;

    public $store_code;
    public $store_name;
    public $store_group_id;
    public $storeGroups = [];

    protected function rules()
    {
        return [
            'store_code' => 'required|unique:stores,store_code',
            'store_name' => 'required',
            'store_group_id' => 'required|exists:store_groups,id',
        ];
    }

    public function mount()
    {
        $this->fetchStoreGroups();
    }

    public function openModal()
    {
        $this->fetchStoreGroups();
    }

    public function fetchStoreGroups()
    {
        $this->storeGroups = $this->queryStoreGroups();
        $this->dispatchBrowserEvent('refresh-store-group-options', ['storeGroups' => $this->storeGroups]);
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
        $this->fetchStoreGroups();
        $this->dispatchBrowserEvent('clear-store-group-select-option');
    }

    public function cancel()
    {
        $this->clearFormField();
    }

    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);
    }

    public function store()
    {
        $this->validate();

        try {
            Store::create([
                'store_code' => $this->store_code,
                'store_name' => $this->store_name,
                'store_group_id' => $this->store_group_id,
            ]);

            $this->clearFormField();
            $this->dispatchBrowserEvent('close-modal');
            $this->emit('loadStores');
            noty()->addSuccess('Store successfully created.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.stores.create-store', [
            'storeGroups' => $this->storeGroups,
        ]);
    }
}
