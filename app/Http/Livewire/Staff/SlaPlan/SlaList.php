<?php

namespace App\Http\Livewire\Staff\SlaPlan;

use App\Http\Requests\SysAdmin\Manage\SLA\UpdateSLARequest;
use App\Http\Traits\BasicModelQueries;
use App\Models\ServiceLevelAgreement;
use Exception;
use Livewire\Component;

class SlaList extends Component
{
    use BasicModelQueries;

    public $serviceLevelAgreements = [];
    public $slaEditId;
    public $slaDeleteId;
    public $countdown_approach;
    public $time_unit;

    protected $listeners = ['loadServiceLevelAgreements' => 'fetchServiceLevelAgreements'];

    protected function rules()
    {
        return [
            'countdown_approach' => 'required|numeric',
            'time_unit' => "required|unique:service_level_agreements,time_unit,{$this->slaEditId}",
        ];
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

    public function actionOnSubmit()
    {
        sleep(1);
        $this->clearFormFields();
        $this->fetchServiceLevelAgreements();
        $this->dispatchBrowserEvent('close-modal');
        flash()->addSuccess('SLA successfully updated');
    }

    public function editSLA(ServiceLevelAgreement $serviceLevelAgreement)
    {
        $this->slaEditId = $serviceLevelAgreement->id;
        $this->countdown_approach = $serviceLevelAgreement->countdown_approach;
        $this->time_unit = $serviceLevelAgreement->time_unit;
        $this->resetValidation();
        $this->dispatchBrowserEvent('show-edit-sla-modal');
    }

    public function updateSLA()
    {
        $validatedData = $this->validate();

        try {
            ServiceLevelAgreement::findOrFail($this->slaEditId)->update($validatedData);
            $this->actionOnSubmit();

        } catch (Exception $e) {
            dump($e->getMessage());
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
            ServiceLevelAgreement::findOrFail($this->slaDeleteId)->delete();
            sleep(1);
            $this->slaDeleteId = null;
            $this->fetchServiceLevelAgreements();
            $this->dispatchBrowserEvent('close-modal');
            flash()->addSuccess('SLA successfully deleted');
        } catch (Exception $e) {
            dump($e->getMessage());
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