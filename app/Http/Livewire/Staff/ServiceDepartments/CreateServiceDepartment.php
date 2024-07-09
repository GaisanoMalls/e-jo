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
    public ?string $name = null;

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

    public function saveServiceDepartment()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                ServiceDepartment::create([
                    'name' => $this->name,
                    'slug' => Str::slug($this->name),
                ]);

                $this->actionOnSubmit();
                noty()->addSuccess('A new service department has been created.');
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