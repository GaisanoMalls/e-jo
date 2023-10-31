<?php

namespace App\Http\Livewire\Staff\Branches;

use App\Http\Requests\SysAdmin\Manage\Branch\StoreBranchRequest;
use App\Models\Branch;
use Exception;
use Livewire\Component;

class CreateBranch extends Component
{
    public $name;

    public function rules(): array
    {
        return (new StoreBranchRequest())->rules();
    }

    public function clearFormField(): void
    {
        $this->reset();
        $this->resetValidation();
    }

    private function actionOnSubmit(): void
    {
        sleep(1);
        $this->clearFormField();
        $this->emit('loadBranches');
    }

    public function saveBranch(): void
    {
        $this->validate();

        try {
            Branch::create([
                'name' => $this->name,
                'slug' => \Str::slug($this->name),
            ]);

            $this->actionOnSubmit();
            flash()->addSuccess('New branch has been created.');

        } catch (Exception $e) {
            dd($e->getMessage());
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.branches.create-branch');
    }
}
