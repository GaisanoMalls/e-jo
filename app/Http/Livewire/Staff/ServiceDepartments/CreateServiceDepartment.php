<?php

namespace App\Http\Livewire\Staff\ServiceDepartments;

use App\Http\Requests\SysAdmin\Manage\ServiceDepartment\StoreServiceDepartmentRequest;
use App\Http\Traits\AppErrorLog;
use App\Models\ServiceDepartment;
use App\Models\ServiceDepartmentChildren;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateServiceDepartment extends Component
{
    public $name;
    public $children;
    public $hasChildren = false;
    public $addedChildren = [];

    public function rules()
    {
        return (new StoreServiceDepartmentRequest())->rules();
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
    }

    private function actionOnSubmit()
    {
        $this->clearFormField();
        $this->emit('loadServiceDepartments');
    }

    public function addChildren()
    {
        if ($this->hasChildren) {
            if (!is_null($this->children)) {
                $isExistsInDB = ServiceDepartmentChildren::where('name', $this->children)->exists();
                $childNameLowerCase = strtolower($this->children);
                $addedChildrenLowerCase = array_map('strtolower', $this->addedChildren);

                if ($isExistsInDB) {
                    session()->flash('childError', 'Child name is already exists');

                } elseif (in_array($childNameLowerCase, $addedChildrenLowerCase)) {
                    session()->flash('childError', 'Subdepartment name is already added');

                } else {
                    array_push($this->addedChildren, $this->children);
                    $this->reset('children');
                }
            } else {
                session()->flash('childError', 'The subdepartment field is required');
            }
        }
    }

    public function removeChild(int $child_key)
    {
        foreach (array_keys($this->addedChildren) as $key) {
            if ($child_key === $key) {
                unset($this->addedChildren[$key]);
            }
        }
    }

    public function updatedHasChildren()
    {
        // Clear the added children inside the array when unchecked.
        if (!empty($this->addedChildren)) {
            $this->addedChildren = [];
        }
    }

    public function saveServiceDepartment()
    {
        $this->validate();
        try {
            DB::transaction(function () {
                if ($this->hasChildren) {
                    if (is_null($this->children) && empty ($this->addedChildren)) {
                        session()->flash('childError', 'Subdepartment field is required');

                    } elseif (empty ($this->addedChildren) || !empty ($this->name) && !empty ($this->children)) {
                        session()->flash('childError', 'Please add the subdepartment');

                    } else {
                        $service_department = ServiceDepartment::create([
                            'name' => $this->name,
                            'slug' => Str::slug($this->name),
                        ]);

                        collect($this->addedChildren)->each(function ($child) use ($service_department) {
                            $service_department->children()->create(['name' => $child]);
                        });

                        $this->actionOnSubmit();
                        noty()->addSuccess('A new service department has been created.');
                    }

                } else {
                    $service_department = ServiceDepartment::create([
                        'name' => $this->name,
                        'slug' => Str::slug($this->name),
                    ]);

                    $this->actionOnSubmit();
                    noty()->addSuccess('A new service department has been created.');
                }
            });

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.service-departments.create-service-department');
    }
}