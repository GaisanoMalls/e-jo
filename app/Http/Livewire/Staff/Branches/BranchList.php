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
    public $branchEditId;
    public $branchDeleteId;
    public $name;

    protected $listeners = ['loadBranches' => 'fetchBranches'];

    protected function rules()
    {
        return [
            'name' => "required|unique:branches,name,{$this->branchEditId}",
        ];
    }

    public function fetchBranches()
    {
        $this->branches = $this->queryBranches();
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function editBranch(Branch $branch)
    {
        $this->branchEditId = $branch->id;
        $this->name = $branch->name;
        $this->dispatchBrowserEvent('show-edit-branch-modal');
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate();

        try {
            Branch::find($this->branchEditId)->update([
                'name' => $this->name,
                'slug' => \Str::slug($this->name),
            ]);

            sleep(1);
            $this->clearFormField();
            $this->fetchBranches();
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Branch successfully updated');

        } catch (Exception $e) {
            dump($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function deleteBranch(Branch $branch)
    {
        $this->branchDeleteId = $branch->id;
        $this->name = $branch->name;
        $this->dispatchBrowserEvent('show-delete-branch-modal');
    }

    public function delete()
    {
        try {
            Branch::findOrFail($this->branchDeleteId)->delete();
            sleep(1);
            $this->branchDeleteId = '';
            $this->fetchBranches();
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('Branch successfully deleted');

        } catch (Exception $e) {
            dump($e->getMessage());
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
