@extends('layouts.master')

@section('title')
    {{ __('manage') . ' ' . __('exam_result') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('exam_result') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row" id="toolbar">
                            <div class="form-group col-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="filter_session_year_id" class="filter-menu">{{__("session_year")}}</label>
                                <select name="filter_session_year_id" id="filter_session_year_id" class="form-control">
                                    @foreach ($sessionYears as $sessionYear)
                                        <option value="{{ $sessionYear->id }}" {{$sessionYear->default == 1 ? "selected" : ""}}>
                                            {{ $sessionYear->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-5 col-lg-3">
                                <label for="filter_exam_id" class="filter-menu">{{__("exam")}}</label>
                                <select name="exam" class="form-control result_exam" id="filter_exam_id"
                                    class="form-control">
                                    <option value="">{{ __('select') . ' ' . __('exam') }}</option>
                                    <option value="data-not-found" style="display: none;">-- {{ __('no_data_found') }} --
                                    </option>
                                    @foreach ($exams as $exam)
                                        <option data-session-year="{{ $exam->session_year_id }}"
                                            data-class-id="{{ $exam->class_id }}" value="{{ $exam->id }}">
                                            {{ $exam->prefix_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                <label for="filter_class_section_id" class="filter-menu">{{__("Class Section")}}</label>
                                <select name="filter_class_section_id" id="filter_class_section_id" class="form-control">
                                    <option value="">{{ __('select_class_section') }}</option>
                                    <option value="data-not-found" style="display: none;">-- {{ __('no_data_found') }} --
                                    </option>
                                    @foreach ($classSections as $classSection)
                                        <option value="{{ $classSection->id }}" data-class-id="{{ $classSection->class_id }}">
                                            {{ $classSection->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            {{-- Report Card Template Selection --}}
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-2">
                                <label for="report_card_template_id" class="filter-menu">{{__("Report Card Template")}}</label>
                                <select name="report_card_template_id" id="report_card_template_id" class="form-control">
                                    <option value="">{{ __('select_template') }}</option>
                                    @if(isset($reportCardTemplates) && $reportCardTemplates->isNotEmpty())
                                        @foreach ($reportCardTemplates as $template)
                                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>{{ __('no_template_found') }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-12 col-sm-12 col-md-8 col-lg-8 mb-2 d-flex align-items-end">
                                <button type="button" id="bulk_print_btn" class="btn btn-primary no-print">
                                    <i class="fa fa-print"></i> {{__("Print All Reports")}}
                                </button>
                                <button type="button" id="print_selected_btn" class="btn btn-success no-print" style="margin-left: 10px;">
                                    <i class="fa fa-print"></i> {{__("Print Selected")}}
                                </button>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table"
                                    data-url="{{ route('exams.show-result', 1) }}" data-click-to-select="true"
                                    data-side-pagination="server" data-pagination="true"
                                    data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-toolbar="#toolbar"
                                    data-show-columns="true" data-show-refresh="true" data-trim-on-search="false"
                                    data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                                    data-maintain-selected="true" data-export-data-type='all'
                                    data-export-options='{ "fileName": "exam-list-<?= date(' d-m-y') ?>" ,"ignoreColumn": ["operate", "state"]}'
                                    data-show-export="true" data-detail-formatter="examListFormatter"
                                    data-query-params="getExamResult" data-escape="true">
                                    <thead>
                                        <tr>
                                            <th data-field="state" data-checkbox="true" data-width="50"></th>
                                            <th scope="col" data-field="id" data-sortable="true" data-visible="false">
                                                {{ __('id') }}</th>
                                            <th scope="col" data-field="no">{{ __('no.') }}</th>
                                            <th scope="col" data-field="user.full_name">{{ __('students') . ' ' . __('name') }}
                                            </th>
                                            <th scope="col" data-field="user.student.roll_number" data-sortable="false">
                                                {{ __('roll_no') }}</th>
                                            <th scope="col" data-field="total_marks" data-sortable="true">
                                                {{ __('total_marks') }}</th>
                                            <th scope="col" data-field="obtained_marks" data-sortable="true">
                                                {{ __('obtained_marks') }}</th>
                                            <th scope="col" data-field="percentage" data-sortable="true">
                                                {{ __('percentage') }}</th>
                                            <th scope="col" data-field="grade" data-sortable="true">{{ __('grade') }}</th>
                                            <th scope="col" data-field="class_rank" class="text-center">{{ __('class_rank') }}</th>
                                            <th scope="col" data-field="created_at" data-sortable="true"
                                                data-visible="false">{{ __('created_at') }}</th>
                                            <th scope="col" data-field="updated_at" data-sortable="true"
                                                data-visible="false">{{ __('updated_at') }}</th>
                                            @can('exam-result-edit')
                                                <th scope="col" data-field="operate" data-escape="false"
                                                    data-events="examResultEvents" data-escape="false">{{ __('action') }}</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLabel">
                                {{ __('edit') . ' ' . __('exam_marks') }}
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="pt-3 edit-exam-result-marks-form" method="post"
                            action="{{ route('exams.update-result-marks') }}" novalidate="novalidate">
                            <input type="hidden" name="edit_id" id="edit_id" value="" />
                            <div class="modal-body">
                                <h5 title="{{ __('All Subjects marks all compulsory') }}" class="mb-3">
                                    <span class="student_name"></span>
                                    <span class="fa fa-info-circle pl-2 mx-2"></span>
                                </h5>
                                <hr>
                                <div class="row mx-3">
                                    <div class="form-group col-sm-12 col-md-4">
                                        <h5>{{__('subject')}}</h5>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-3">
                                        <h5>{{__('total_marks')}}</h5>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-3">
                                        <h5>{{__('obtained_marks')}}</h5>
                                    </div>
                                </div>
                                <div class="subject_container"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ __('close') }}</button>
                                <input class="btn btn-theme" type="submit" value={{ __('update') }} />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Bulk Print All Students
            $(document).on('click', '#bulk_print_btn', function() {
                // Get current filter values
                let sessionYearId = $('#filter_session_year_id').val();
                let classSectionId = $('#filter_class_section_id').val();
                let examId = $('#filter_exam_id').val();
                let templateId = $('#report_card_template_id').val();

                if (!sessionYearId) {
                    alert('{{ __("Please select session year") }}');
                    return;
                }

                if (!examId) {
                    alert('{{ __("Please select exam") }}');
                    return;
                }

                if (!templateId) {
                    alert('{{ __("Please select report card template") }}');
                    return;
                }

                // Open bulk print PDF with template
                let bulkPrintUrl = "{{ route('reports.exam.bulk-print-report-card') }}"
                    + "?session_year_id=" + sessionYearId
                    + "&class_section_id=" + (classSectionId || '')
                    + "&exam_id=" + examId
                    + "&template_id=" + templateId;
                
                window.open(bulkPrintUrl, '_blank');
            });

            // Print Selected Students
            $(document).on('click', '#print_selected_btn', function() {
                let selections = $('#table_list').bootstrapTable('getSelections');
                
                if (selections.length === 0) {
                    alert('{{ __("Please select at least one student") }}');
                    return;
                }

                let sessionYearId = $('#filter_session_year_id').val();
                let classSectionId = $('#filter_class_section_id').val();
                let examId = $('#filter_exam_id').val();
                let templateId = $('#report_card_template_id').val();

                if (!sessionYearId) {
                    alert('{{ __("Please select session year") }}');
                    return;
                }

                if (!examId) {
                    alert('{{ __("Please select exam") }}');
                    return;
                }

                if (!templateId) {
                    alert('{{ __("Please select report card template") }}');
                    return;
                }

                // Get student IDs from selections
                let studentIds = selections.map(row => {
                    // The student_id is directly in the row
                    return row.student_id;
                }).filter(id => id).join(',');

                if (!studentIds) {
                    alert('{{ __("Could not determine student IDs from selection") }}');
                    return;
                }

                // Create print URL with template
                let printUrl = "{{ route('reports.exam.print-selected-report-card') }}"
                    + "?session_year_id=" + sessionYearId
                    + "&class_section_id=" + (classSectionId || '')
                    + "&exam_id=" + examId
                    + "&template_id=" + templateId
                    + "&student_ids=" + studentIds;
                
                window.open(printUrl, '_blank');
            });

            // Handle PDF button click in action column
            $(document).on('click', '.view-pdf-result', function(e) {
                e.preventDefault();
                
                let templateId = $('#report_card_template_id').val();
                
                if (!templateId) {
                    alert('{{ __("Please select report card template first") }}');
                    // Focus on template dropdown
                    $('#report_card_template_id').focus();
                    return;
                }

                let studentId = $(this).data('student_id');
                let examId = $(this).data('exam_id');
                
                if (!studentId || !examId) {
                    alert('{{ __("Invalid student or exam data") }}');
                    return;
                }

                // Create PDF URL with template
                let pdfUrl = "{{ url('exams/result/student') }}/" + studentId + "/exam/" + examId + "?template_id=" + templateId;
                
                window.open(pdfUrl, '_blank');
            });
        });
    </script>
@endsection

@section('css')
    <style>
        /* Ensure checkbox column is visible */
        #table_list th[data-field="state"],
        #table_list td[data-field="state"] {
            width: 50px !important;
            min-width: 50px !important;
        }
        #table_list th[data-field="state"] input[type="checkbox"],
        #table_list td[data-field="state"] input[type="checkbox"] {
            visibility: visible !important;
            display: block !important;
        }
    </style>
@endsection