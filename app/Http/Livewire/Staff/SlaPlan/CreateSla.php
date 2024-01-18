<?php

namespace App\Http\Livewire\Staff\SlaPlan;

use App\Http\Requests\SysAdmin\Manage\SLA\StoreSLARequest;
use App\Models\ServiceLevelAgreement;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateSla extends Component
{
    public $countdown_approach;
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
            Log::channel('appErrorLog')->error($e->getMessage(), [url()->full()]);
            noty()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.sla-plan.create-sla');
    }
}