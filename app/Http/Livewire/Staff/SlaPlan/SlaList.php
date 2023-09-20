<?php

namespace App\Http\Livewire\Staff\SlaPlan;

use App\Http\Requests\SysAdmin\Manage\SLA\UpdateSLARequest;
use App\Http\Traits\BasicModelQueries;
use App\Models\ServiceLevelAgreement;
use Livewire\Component;

class SlaList extends Component
{
    use BasicModelQueries;

    public $serviceLevelAgreements = [];
    public $slaEditId, $slaDeleteId, $countdown_approach, $time_unit;

    protected $listeners = ['loadServiceLevelAgreements' => 'fetchServiceLevelAgreements'];

    public function rules()
    {
        return [
            'countdown_approach' => 'required|numeric',
            'time_unit' => 'required|unique:service_level_agreements,time_unit,' . $this->slaEditId
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function fetchServiceLevelAgreements()
    {
        $this->serviceLevelAgreements = $this->queryServiceLevelAgreements();
    }

    public function clearFormFields()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function editSLA(ServiceLevelAgreement $serviceLevelAgreement)
    {
        $this->slaEditId = $serviceLevelAgreement->id;
        $this->countdown_approach = $serviceLevelAgreement->countdown_approach;
        $this->time_unit = $serviceLevelAgreement->time_unit;
        $this->dispatchBrowserEvent('show-edit-sla-modal');
        $this->resetValidation();
    }

    public function updateSLA()
    {
        $validatedData = $this->validate();

        try {
            ServiceLevelAgreement::find($this->slaEditId)->update($validatedData);
            $this->clearFormFields();
            $this->fetchServiceLevelAgreements();
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('SLA successfully updated');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function deleteSLA(ServiceLevelAgreement $serviceLevelAgreement)
    {
        $this->slaDeleteId = $serviceLevelAgreement->id;
        $this->countdown_approach = $serviceLevelAgreement->countdown_approach;
        $this->time_unit = $serviceLevelAgreement->time_unit;
        $this->dispatchBrowserEvent('show-delete-sla-modal');
    }

    public function delete()
    {
        try {
            ServiceLevelAgreement::find($this->slaDeleteId)->delete();
            $this->slaDeleteId = null;
            $this->fetchServiceLevelAgreements();
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('SLA successfully deleted');

        } catch (\Exception $e) {
            flash()->addError('Oops, something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.staff.sla-plan.sla-list', [
            'serviceLevelAgreements' => $this->fetchServiceLevelAgreements(),
        ]);
    }
}