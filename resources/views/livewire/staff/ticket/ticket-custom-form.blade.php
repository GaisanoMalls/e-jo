@php
    use App\Models\Role;
    use App\Enums\FieldTypesEnum as FieldType;
@endphp

@if (!empty($customFormHeaderFields) || !empty($customFormRowFields))
    <div class="mb-4">
        <div class="row" id="ticket-custom-form">
            <div class="col-12">
                <div class="row my-3 mx-auto ps-1 rounded-3 custom__form" style="border: 1px solid #ced4da;">
                    <div class="d-flex align-items-center justify-content-between flex-row mb-3">
                        <h6 class="fw-bold mt-2 mb-0 text-end mt-4 form__name" style="text-transform: uppercase;">
                            {{ $ticket->helpTopic->form->name }}
                        </h6>
                        <img src="{{ asset('images/gmall-davao-pr-form.png') }}" class="pr__form__gmall__logo mt-3" alt="GMall Ticketing System" height="50px;">
                    </div>
                    @if (!empty($customFormHeaderFields))
                        <div class="row mx-auto my-3">
                            @foreach ($customFormHeaderFields as $key => $headerField)
                                @if ($headerField['field']['assigned_column'] == 1)
                                    <div class="col-lg-6 col-md-12 col-sm-12 ps-0 pe-lg-4 pe-md-0 mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <label class="form-label fw-bold mb-0 input__field__label" style="white-space: nowrap">
                                                {{ $headerField['field']['label'] }}:
                                            </label>
                                            <label class="w-100 header__field">
                                                @if ($headerField['field']['type'] == 'date')
                                                    {{ date('F j, Y', strtotime($headerField['value'])) }}
                                                @else
                                                    {{ $headerField['value'] }}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                @if ($headerField['field']['assigned_column'] == 2)
                                    <div class="col-lg-6 col-md-12 col-sm-12 ps-0 pe-lg-4 pe-md-0 mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <label class="form-label mb-0 fw-bold input__field__label" style="white-space: nowrap">
                                                {{ $headerField['field']['label'] }}:
                                            </label>
                                            <label class="w-100 header__field">
                                                @if ($headerField['field']['type'] == 'date')
                                                    {{ date('F j, Y', strtotime($headerField['value'])) }}
                                                @else
                                                    {{ $headerField['value'] }}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    @if (!empty($customFormRowFields))
                        <div class="w-100 msx-auto d-flex flex-row flex-xl-nowrap flex-lg-nowrap flex-sm-wrap">
                            @php
                                $filteredRowField = $this->getFilteredRowFields();
                                $headers = $filteredRowField['headers'];
                                $fields = $filteredRowField['fields'];
                            @endphp
                            @if (!empty($headers) && !empty($fields))
                                <div class="w-100">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                @foreach ($headers as $header)
                                                    <th class="fw-bold input__field__label">
                                                        {{ Str::title($header) }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fields as $field)
                                                <tr class="row__field">
                                                    @foreach ($headers as $header)
                                                        <td class="field__value">
                                                            {{ $field[$header] ?? '' }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-sm d-flex align-items-center justify-content-center gap-2" id="save-custom-form-as-pdf" style="font-size: 0.75rem; height: 30px; color: #3e3d3d; background-color: #f3f4f6;">
            <i class="bi bi-download"></i>
            Download PDF
        </button>
    </div>
@endif

@push('extra')
    <script>
        document.getElementById('save-custom-form-as-pdf').addEventListener('click', function() {
            var content = document.getElementById('ticket-custom-form');
            html2canvas(content, {
                scale: 2,
                onrendered: function(canvas) {
                    var imgData = canvas.toDataURL('image/png');
                    var pdf = new jsPDF('p', 'mm', 'a4');
                    var imgWidth = 210;
                    var pageHeight = 295;
                    var imgHeight = canvas.height * imgWidth / canvas.width;
                    var heightLeft = imgHeight;

                    var position = 0;

                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }
                    pdf.save('{{ $ticket->helpTopic->form->name }}.pdf');
                }
            });
        });
    </script>
@endpush
