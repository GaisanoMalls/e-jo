<?php

namespace App\Http\Livewire\Staff\SlaPlan;

use App\Http\Requests\SysAdmin\Manage\SLA\StoreSLARequest;
use App\Models\ServiceLevelAgreement;
use Livewire\Component;

class CreateSla extends Component
{
    public $countdown_approach, $time_unit;

    public function rules()
    {
        return (new StoreSLARequest())->rules();
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function clearFormFields()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function actionOnSubmit()
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
            flash()->addSuccess('A new SLA has been created.');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.sla-plan.create-sla');
    }
}