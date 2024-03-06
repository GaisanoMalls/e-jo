<?php

namespace App\Http\Livewire\Staff\Branches;

use App\Http\Requests\SysAdmin\Manage\Branch\StoreBranchRequest;
use App\Http\Traits\AppErrorLog;
use App\Models\Branch;
use Exception;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateBranch extends Component
{
    public $name;

    public function rules()
    {
        return(new StoreBranchRequest())->rules();
    }

    public function clearFormField()
    {
        $this->reset();
        $this->resetValidation();
    }

    private function actionOnSubmit()
    {
        $this->clearFormField();
        $this->emit('loadBranches');
    }

    public function saveBranch()
    {
        $this->validate();

        try {
            Branch::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
            ]);

            $this->actionOnSubmit();
            noty()->addSuccess('New branch has been created.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.branches.create-branch');
    }
}
