<?php

namespace App\Http\Livewire\Staff\ServiceDepartments;

use App\Http\Requests\SysAdmin\Manage\ServiceDepartment\StoreServiceDepartmentRequest;
use App\Models\ServiceDepartment;
use Exception;
use Livewire\Component;

class CreateServiceDepartment extends Component
{
    public $name;

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
            ServiceDepartment::create([
                'name' => $this->name,
                'slug' => \Str::slug($this->name),
            ]);

            $this->actionOnSubmit();
            noty()->addSuccess('A new service department has been created.');

        } catch (Exception $e) {
            dump($e->getMessage());
            noty()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.service-departments.create-service-department');
    }
}