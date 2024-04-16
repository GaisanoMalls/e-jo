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
                $isServiceDeptChildExists = ServiceDepartmentChildren::where('name', $this->children)->exists();

                if ($isServiceDeptChildExists) {
                    $this->addError('children', 'Subdepartment is already exists');

                } elseif (in_array(strtolower($this->children), array_map('strtolower', $this->addedChildren))) {
                    $this->addError('children', 'Subdepartment is already added');

                } else {
                    array_push($this->addedChildren, $this->children);
                    $this->reset('children');
                }
            } else {
                $this->addError('children', 'The subdepartment field is required');
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
                        $this->addError('children', 'Subdepartment field is required');

                    } elseif (empty ($this->addedChildren) || !empty ($this->name) && !empty ($this->children)) {
                        $this->addError('children', 'Please add the subdepartment');

                    } else {
                        $service_department = ServiceDepartment::create([
                            'name' => $this->name,
                            'slug' => Str::slug($this->name),
                        ]);

                        foreach ($this->addedChildren as $child) {
                            $service_department->children()->create(['name' => $child]);
                        }

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