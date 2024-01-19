<?php

namespace App\Http\Livewire\Staff\BUDepartments;

use App\Http\Traits\BasicModelQueries;
use App\Models\Department;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BUDepartmentList extends Component
{
    use BasicModelQueries;

    public $buDepartments = [];
    public $editSelectedBranches = [];
    public $buDepartmentEditId;
    public $buDepartmentDeleteId;
    public $name;

    protected $listeners = ['loadBUDepartments' => 'fetchBUDepartments'];

    protected function rules()
    {
        return [
            'name' => "required|unique:departments,name,{$this->buDepartmentEditId}",
            'editSelectedBranches' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'editSelectedBranches.required' => 'The branch field is required.',
        ];
    }

    public function fetchBUDepartments()
    {
        $this->buDepartments = $this->queryBUDepartments();
    }

    public function editBUDepartment(Department $department)
    {
        $this->buDepartmentEditId = $department->id;
        $this->name = $department->name;
        $this->editSelectedBranches = $department->branches->pluck('id')->toArray();

        $this->resetValidation();
        $this->dispatchBrowserEvent('show-edit-bu-department-modal');
        $this->dispatchBrowserEvent('update-branch-select-option', ['branchIds' => $this->editSelectedBranches]);
    }

    public function update()
    {
        $this->validate();

        try {
            $buDepartment = Department::findOrFail($this->buDepartmentEditId);

            if ($buDepartment) {
                DB::transaction(function () use ($buDepartment) {
                    $buDepartment->update([
                        'name' => $this->name,
                        'slug' => \Str::slug($this->name),
                    ]);

                    $buDepartment->branches()->sync($this->editSelectedBranches);
                });

                $this->actionOnSubmit();
            }
        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong');
        }
    }

    public function deleteBUDepartment(Department $department)
    {
        $this->buDepartmentDeleteId = $department->id;
        $this->name = $department->name;
        $this->dispatchBrowserEvent('show-delete-bu-department-modal');
    }

    public function delete()
    {
        try {
            Department::find($this->buDepartmentDeleteId)->delete();
            $this->buDepartmentDeleteId = null;
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('BU/Department successfully deleted');

        } catch (Exception $e) {
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong');
        }
    }

    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
    }

    private function actionOnSubmit()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('clear-branch-select-option');
    }

    public function render()
    {
        return view('livewire.staff.b-u-departments.b-u-department-list', [
            'buDepartments' => $this->fetchBUDepartments(),
            'branches' => $this->queryBranches(),
        ]);
    }
}
