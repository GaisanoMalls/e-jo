<?php

namespace App\Http\Livewire\Staff\BUDepartments;

use App\Http\Requests\SysAdmin\Manage\BUDepartment\StoreBUDepartmentRequest;
use App\Http\Traits\BasicModelQueries;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateBuDepartment extends Component
{
    use BasicModelQueries;

    public $selectedBranches = [];
    public $name;

    public function rules()
    {
        return (new StoreBUDepartmentRequest())->rules();
    }

    public function messages()
    {
        return (new StoreBUDepartmentRequest())->messages();
    }

    private function actionOnSubmit()
    {
        sleep(1);
        $this->reset();
        $this->resetValidation();
        $this->emit('loadBUDepartments');
        $this->dispatchBrowserEvent('clear-branch-select-option');
    }

    public function saveBUDepartment()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $department = Department::create([
                    'name' => $this->name,
                    'slug' => \Str::slug($this->name)
                ]);

                $department->branches()->attach($this->selectedBranches);
            });

            $this->actionOnSubmit();
            flash()->addSuccess('New BU\Department has been created.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->reset('name', 'selectedBranches');
        $this->dispatchBrowserEvent('clear-branch-select-option');
    }

    public function render()
    {
        return view('livewire.staff.b-u-departments.create-bu-department', [
            'branches' => $this->queryBranches(),
        ]);
    }
}