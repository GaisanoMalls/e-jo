<?php

namespace App\Http\Livewire\Staff\ServiceDepartments;

use App\Http\Requests\SysAdmin\Manage\ServiceDepartment\StoreServiceDepartmentRequest;
use App\Models\ServiceDepartment;
use Livewire\Component;

class CreateServiceDepartment extends Component
{
    public $name;

    protected function rules()
    {
        return (new StoreServiceDepartmentRequest())->rules();
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function saveServiceDepartment()
    {
        $validatedData = $this->validate();

        try {
            ServiceDepartment::create([
                'name' => $validatedData['name'],
                'slug' => \Str::slug($validatedData['name'])
            ]);

            $this->clearFormField();
            $this->emit('loadServiceDepartments');
            flash()->addSuccess('A new service department has been created.');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.service-departments.create-service-department');
    }
}