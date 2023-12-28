<div>
    <div class="row my-4 px-3">
        <h6 class="px-0">List of fields</h6>
        @if ($fields->isNotEmpty())
            <div class="table-responsive custom__table">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 table__head__label px-2">Enable</th>
                            <th class="border-0 table__head__label px-2">Name</th>
                            <th class="border-0 table__head__label px-2">Type</th>
                            <th class="border-0 table__head__label px-2">Required</th>
                            <th class="border-0 table__head__label px-2">
                                Variable Name
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($fields as $field)
                            <tr wire:key="field-{{ $field->id }}">
                                <td>
                                    <div class="form-check" style="white-space: nowrap;">
                                        <input wire:model="" class="form-check-input" type="checkbox" role="switch"
                                            id="specialProjectCheck" wire:loading.attr="disabled">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start px-0 td__content">
                                        <span>{{ $field->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start px-0 td__content">
                                        <span>{{ $field->type }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start px-0 td__content">
                                        <span>{{ $field->isRequired() }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start px-0 td__content">
                                        <span>{{ $field->variable_name }}</span>
                                    </div>
                                </td>
                                <td class="px-0">
                                    <div class="d-flex align-items-center text-start px-1 td__content">
                                        <button class="btn btn-sm action__button mt-0"
                                            wire:click="deleteField({{ $field->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-light py-3 px-4 rounded-3">
                <small style="font-size: 14px;">Empty fields</small>
            </div>
        @endif
    </div>
</div>
