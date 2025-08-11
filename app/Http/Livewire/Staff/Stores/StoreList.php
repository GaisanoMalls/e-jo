<?php

namespace App\Http\Livewire\Staff\Stores;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\Store;
use Exception;
use Livewire\Component;

class StoreList extends Component
{
    use BasicModelQueries;

    public $stores = [];
    public $storeGroups = [];
    public $storeEditId;
    public $storeDeleteId;
    public $store_code;
    public $store_name;
    public $store_group_id;

    protected $listeners = ['loadStores' => 'fetchStores'];

    protected function rules()
    {
        return [
            'store_code' => "required|unique:stores,store_code,{$this->storeEditId}",
            'store_name' => 'required',
            'store_group_id' => 'required|exists:store_groups,id',
        ];
    }

    public function mount()
    {
        $this->fetchStores();
        $this->fetchStoreGroups();
    }

    public function fetchStores()
    {
        $this->stores = $this->queryStores();
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

    public function editStore(Store $store)
    {
        $this->storeEditId = $store->id;
        $this->store_code = $store->store_code;
        $this->store_name = $store->store_name;
        $this->store_group_id = $store->store_group_id;
        $this->dispatchBrowserEvent('show-edit-store-modal');
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate();

        try {
            Store::find($this->storeEditId)->update([
                'store_code' => $this->store_code,
                'store_name' => $this->store_name,
                'store_group_id' => $this->store_group_id,
            ]);

            $this->clearFormField();
            $this->fetchStores();
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('Store successfully updated.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function deleteStore(Store $store)
    {
        $this->storeDeleteId = $store->id;
        $this->store_code = $store->store_code;
        $this->store_name = $store->store_name;
        $this->dispatchBrowserEvent('show-delete-store-modal');
    }

    public function delete()
    {
        try {
            Store::find($this->storeDeleteId)->delete();
            $this->storeDeleteId = null;
            $this->fetchStores();
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('Store successfully deleted.');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.stores.store-list', [
            'stores' => $this->stores,
            'storeGroups' => $this->storeGroups,
        ]);
    }
}
