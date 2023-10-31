<?php

namespace App\Http\Livewire\Staff\Branches;

use App\Http\Traits\BasicModelQueries;
use App\Models\Branch;
use Exception;
use Livewire\Component;

class BranchList extends Component
{
    use BasicModelQueries;

    public $branches = [];
    public $branchEditId, $branchDeleteId, $name;

    protected $listeners = ['loadBranches' => 'fetchBranches'];

    protected function rules(): array
    {
        return [
            'name' => "required|unique:branches,name,{$this->branchEditId}"
        ];
    }

    public function fetchBranches(): void
    {
        $this->branches = $this->queryBranches();
    }

    public function clearFormField(): void
    {
        $this->reset();
        $this->resetValidation();
    }

    public function editBranch(Branch $branch): void
    {
        $this->branchEditId = $branch->id;
        $this->name = $branch->name;
        $this->dispatchBrowserEvent('show-edit-branch-modal');
        $this->resetValidation();
    }

    public function update(): void
    {
        $this->validate();

        try {
            Branch::find($this->branchEditId)->update([
                'name' => $this->name,
                'slug' => \Str::slug($this->name)
            ]);

            sleep(1);
            $this->clearFormField();
            $this->fetchBranches();
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Branch successfully updated');

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function deleteBranch(Branch $branch): void
    {
        $this->branchDeleteId = $branch->id;
        $this->name = $branch->name;
        $this->dispatchBrowserEvent('show-delete-branch-modal');
    }

    public function delete(): void
    {
        try {
            Branch::find($this->branchDeleteId)->delete();
            sleep(1);
            $this->branchDeleteId = '';
            $this->fetchBranches();
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Branch successfully deleted');

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.branches.branch-list', [
            'branches' => $this->fetchBranches(),
        ]);
    }
}
