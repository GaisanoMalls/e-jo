<?php

namespace App\Http\Livewire\Staff\SlaPlan;

use App\Http\Requests\SysAdmin\Manage\SLA\UpdateSLARequest;
use App\Http\Traits\AppErrorLog;
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
    public $hours;
    public $time_unit;

    protected $listeners = ['loadServiceLevelAgreements' => 'fetchServiceLevelAgreements'];

    protected function rules()
    {
        return [
            'hours' => 'required|numeric',
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

    private function actionOnSubmit()
    {
        $this->clearFormFields();
        $this->fetchServiceLevelAgreements();
        $this->dispatchBrowserEvent('close-modal');
        noty()->addSuccess('SLA successfully updated');
    }

    public function editSLA(ServiceLevelAgreement $serviceLevelAgreement)
    {
        $this->slaEditId = $serviceLevelAgreement->id;
        $this->hours = $serviceLevelAgreement->hours;
        $this->time_unit = $serviceLevelAgreement->time_unit;
        $this->resetValidation();
        $this->dispatchBrowserEvent('show-edit-sla-modal');
    }

    public function updateSLA()
    {
        $validatedData = $this->validate();

        try {
            ServiceLevelAgreement::find($this->slaEditId)->update($validatedData);
            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function deleteSLA(ServiceLevelAgreement $serviceLevelAgreement)
    {
        $this->slaDeleteId = $serviceLevelAgreement->id;
        $this->hours = $serviceLevelAgreement->hours;
        $this->time_unit = $serviceLevelAgreement->time_unit;
        $this->dispatchBrowserEvent('show-delete-sla-modal');
    }

    public function delete()
    {
        try {
            $sla = ServiceLevelAgreement::with(['helpTopics', 'tickets'])->find($this->slaDeleteId);
            $sla->delete();
            $this->slaDeleteId = null;
            $this->fetchServiceLevelAgreements();
            $this->dispatchBrowserEvent('close-modal');
            noty()->addSuccess('SLA successfully deleted');
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.sla-plan.sla-list', [
            'serviceLevelAgreements' => $this->fetchServiceLevelAgreements(),
        ]);
    }
}