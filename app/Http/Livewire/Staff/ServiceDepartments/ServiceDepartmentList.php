<?php

namespace App\Http\Livewire\Staff\ServiceDepartments;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\ServiceDepartment;
use App\Models\ServiceDepartmentChildren;
use Exception;
use Illuminate\Support\Str;
use Livewire\Component;

class ServiceDepartmentList extends Component
{
    use BasicModelQueries;

    public $isCurrentServiceDepartmentHasChildren;
    public $serviceDepartments = [];
    public $newlyAddedChildren = [];
    public $serviceDepartmentEditId;
    public $serviceDepartmentDeleteId;
    public $childEditId;
    public $childEditName;
    public $childName;
    public $serviceDeptHasChildren = false;
    public $name;

    protected $listeners = ['loadServiceDepartments' => 'fetchServiceDepartments'];

    protected function rules()
    {
        return [
            'name' => "required|unique:service_departments,name,{$this->serviceDepartmentEditId}",
        ];
    }

    public function fetchServiceDepartments()
    {
        $this->serviceDepartments = $this->queryServiceDepartments();
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function editServiceDepartment(ServiceDepartment $serviceDepartment)
    {
        $this->serviceDepartmentEditId = $serviceDepartment->id;
        $this->name = $serviceDepartment->name;
        $this->isCurrentServiceDepartmentHasChildren();
        $this->serviceDepartmentChildren();
        $this->dispatchBrowserEvent('show-edit-service-department-modal');
        $this->resetValidation();
    }

    public function isCurrentServiceDepartmentHasChildren()
    {
        return ServiceDepartmentChildren::where('service_department_id', $this->serviceDepartmentEditId)->exists();
    }

    public function serviceDepartmentChildren()
    {
        return ServiceDepartmentChildren::where('service_department_id', $this->serviceDepartmentEditId)->orderByDesc('created_at')->get();
    }

    public function updateServiceDepartment()
    {
        $this->validate();

        try {
            if (!empty($this->name) && !empty($this->childName)) {
                session()->flash('childError', 'Please add the subdepartment');

            } else {
                $serviceDepartment = ServiceDepartment::findOrFail($this->serviceDepartmentEditId);
                $childrenUpdated = false; // Flag to track if children have been updated

                collect($this->newlyAddedChildren)->each(function ($child) use ($serviceDepartment, &$childrenUpdated) {
                    if ($serviceDepartment->children()->where('name', $child)->doesntExist()) {
                        $serviceDepartment->children()->create(['name' => $child]);
                        $childrenUpdated = true; // Set flag to true since a child has been added
                    }
                });

                if ($childrenUpdated) {
                    noty()->addSuccess('A new subdepartment have been added');
                    $this->newlyAddedChildren = [];
                }

                $serviceDepartment->update([
                    'name' => $this->name,
                    'slug' => Str::slug($this->name),
                ]);
                noty()->addSuccess('Service department successfully updated');

                $this->dispatchBrowserEvent('close-modal');
                $this->clearFormField();
                $this->fetchServiceDepartments();
            }

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updatedserviceDeptHasChildren()
    {
        if (!empty($this->newlyAddedChildren)) {
            // Clear the added children inside the array when unchecked.
            $this->newlyAddedChildren = [];
        }
    }

    public function deleteServiceDepartment(ServiceDepartment $serviceDepartment)
    {
        $this->serviceDepartmentDeleteId = $serviceDepartment->id;
        $this->name = $serviceDepartment->name;
        $this->dispatchBrowserEvent('show-delete-service-department-modal');
    }

    public function delete()
    {
        try {
            $serviceDept = ServiceDepartment::find($this->serviceDepartmentDeleteId);
            $serviceDept->delete();
            $this->serviceDepartmentDeleteId = null;
            $this->fetchServiceDepartments();
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('Service department successfully deleted');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function addChildren()
    {
        try {
            if (!is_null($this->childName)) {
                $isExistsInDB = ServiceDepartmentChildren::where('name', $this->childName)->exists();
                $childNameLowerCase = strtolower($this->childName);
                $newlyAddedChildrenrenLowerCase = array_map('strtolower', $this->newlyAddedChildren);

                if ($isExistsInDB) {
                    session()->flash('childError', 'Subdepartment name is already exists');

                } elseif (in_array($childNameLowerCase, $newlyAddedChildrenrenLowerCase)) {
                    session()->flash('childError', 'Subdepartment name is already added');

                } else {
                    array_push($this->newlyAddedChildren, $this->childName);
                    $this->reset('childName');
                }
            } else {
                session()->flash('childError', 'The subdepartment field is required');
            }
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function removeChild(int $child_key)
    {
        foreach (array_keys($this->newlyAddedChildrenren) as $key) {
            if ($child_key === $key) {
                unset($this->newlyAddedChildrenren[$key]);
            }
        }
    }

    public function deleteChild(ServiceDepartmentChildren $serviceDepartmentChild)
    {
        try {
            $serviceDepartmentChild->delete();
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function editChild(ServiceDepartmentChildren $serviceDepartmentChild)
    {
        try {
            $this->childEditId = $serviceDepartmentChild->id;
            $this->childEditName = $serviceDepartmentChild->name;
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function updateChild(ServiceDepartmentChildren $serviceDepartmentChild)
    {
        try {
            $serviceDepartmentChild->update(['name' => $this->childEditName]);
            $this->childEditId = null;
            $this->childEditName = '';
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function cancelEditChild(ServiceDepartmentChildren $serviceDepartmentChild)
    {
        if ($this->childEditId === $serviceDepartmentChild->id) {
            $this->childEditId = null;
            $this->childEditName = '';
        }
    }

    public function render()
    {
        return view('livewire.staff.service-departments.service-department-list', [
            'serviceDepartments' => $this->fetchServiceDepartments(),
        ]);
    }
}