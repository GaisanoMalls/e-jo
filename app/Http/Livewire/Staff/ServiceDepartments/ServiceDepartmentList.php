<?php

namespace App\Http\Livewire\Staff\ServiceDepartments;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\BasicModelQueries;
use App\Models\ServiceDepartment;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class ServiceDepartmentList extends Component
{
    use BasicModelQueries;

    public ?Collection $serviceDepartments = null;
    public ?int $serviceDepartmentEditId = null;
    public ?int $serviceDepartmentDeleteId = null;
    public ?string $name = null;

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
        $this->dispatchBrowserEvent('show-edit-service-department-modal');
        $this->resetValidation();
    }

    public function updateServiceDepartment()
    {
        $this->validate();

        try {
            ServiceDepartment::findOrFail($this->serviceDepartmentEditId)
                ->update([
                    'name' => $this->name,
                    'slug' => Str::slug($this->name),
                ]);

            noty()->addSuccess('Service department successfully updated');
            $this->dispatchBrowserEvent('close-modal');
            $this->clearFormField();
            $this->fetchServiceDepartments();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
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

    public function render()
    {
        return view('livewire.staff.service-departments.service-department-list', [
            'serviceDepartments' => $this->fetchServiceDepartments(),
        ]);
    }
}