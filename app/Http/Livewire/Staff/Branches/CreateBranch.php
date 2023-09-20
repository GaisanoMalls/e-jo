<?php

namespace App\Http\Livewire\Staff\Branches;

use App\Http\Requests\SysAdmin\Manage\Branch\StoreBranchRequest;
use App\Models\Branch;
use Livewire\Component;

class CreateBranch extends Component
{
    public $name;

    protected function rules()
    {
        return (new StoreBranchRequest())->rules();
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

    public function saveBranch()
    {
        $validatedData = $this->validate();

        try {
            Branch::create([
                'name' => $validatedData['name'],
                'slug' => \Str::slug($validatedData['name']),
            ]);

            $this->clearFormField();
            $this->emit('loadBranches');
            flash()->addSuccess('New branch has been created.');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.branches.create-branch');
    }
}