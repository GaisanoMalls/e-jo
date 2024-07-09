<?php

namespace App\Http\Livewire\Staff\SlaPlan;

use App\Http\Requests\SysAdmin\Manage\SLA\StoreSLARequest;
use App\Http\Traits\AppErrorLog;
use App\Models\ServiceLevelAgreement;
use Exception;
use Livewire\Component;

class CreateSla extends Component
{
    public $hours;
    public $time_unit;

    public function rules()
    {
        return (new StoreSLARequest())->rules();
    }

    public function clearFormFields()
    {
        $this->reset();
        $this->resetValidation();
    }

    private function actionOnSubmit()
    {
        $this->clearFormFields();
        $this->emit('loadServiceLevelAgreements');
    }

    public function saveSLA()
    {
        $validatedData = $this->validate();

        try {
            ServiceLevelAgreement::create($validatedData);
            $this->actionOnSubmit();
            noty()->addSuccess('A new SLA has been created.');

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.sla-plan.create-sla');
    }
}