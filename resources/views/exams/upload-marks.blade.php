@extends('layouts.master')

@section('title')
    {{ __('exam_marks') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage') . ' ' . __('exam_marks') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card search-container">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('create') . ' ' . __('exam_marks') }}
                        </h4>
                        <form action="{{ route('exams.submit-marks') }}" class="create-form" id="formdata"
                            data-success-function="formSuccessFunction">
                            @csrf
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-4">
                                    <label for="">{{ __('class_section') }}</label>
                                    <select name="class_section_id" id="class_section_id" required class="form-control">
                                        <option value="">-- {{ __('select_class_section') }} --</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" data-classId="{{ $class->class_id }}">
                                                {{ $class->full_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label for="">{{ __('exams') }}</label>
                                    <select required name="exam_id" id="exam_id" class="form-control select2">
                                        <option value="">{{ __('select') . ' ' . __('exam') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label for="">{{ __('subject') }}</label>
                                    <select required name="class_subject_id" id="subject_id" class="form-control select2">
                                        <option value="">{{ __('select') . ' ' . __('subject')}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 col-md-12">
                                    <button type="button" id="search" class="btn btn-theme">{{ __('search') }}</button>
                                </div>
                            </div>
                            <div class="show_student_list">
                                <div id="toolbar"></div>
                                <table id="table_list" class="table student_table" data-toggle="table"
                                    data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th scope="col" data-field="id" data-visible="false">{{ __('id') }}</th>
                                            <th scope="col" data-field="no">{{ __('no.') }}</th>
                                            <th scope="col" data-field="student_name">{{ __('name') }}</th>
                                            <th scope="col" data-field="roll_number">{{ __('roll_number') }}</th>
                                            <th scope="col" data-field="total_marks">{{ __('total_marks') }}</th>
                                        </tr>
                                    </thead>
                                </table>

                                <div class="form-group mt-3">
                                    <input class="btn btn-theme float-right ml-3" id="create-btn-result" type="submit"
                                        value={{ __('submit') }}>
                                    <input class="btn btn-secondary float-right" type="reset" value={{ __('reset') }}>
                                </div>

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
        $(document).ready(function () {
            $('.show_student_list').hide();

            $('#search').off('click').on('click', function () {
                $('.show_student_list').show();
                loadDynamicColumnsAndInitTable();
            });

            function getQueryParams() {
                return {
                    class_section_id: $('#class_section_id').val(),
                    exam_id: $('#exam_id').val(),
                    class_subject_id: $('#subject_id').val()
                };
            }

            function loadDynamicColumnsAndInitTable() {
                const params = {
                    class_section_id: $('#class_section_id').val(),
                    exam_id: $('#exam_id').val(),
                    class_subject_id: $('#subject_id').val(),
                };

                if (!params.class_section_id || !params.exam_id || !params.class_subject_id) {
                    alert('Please select Class, Exam and Subject');
                    return;
                }

                $.ajax({
                    url: '{{ route('exams.marks-list') }}',
                    type: 'GET',
                    data: params,
                    success: function (data) {
                        if (!data || !data.rows || data.rows.length === 0) {
                            $('.student_table').bootstrapTable('destroy').bootstrapTable();
                            return;
                        }

                        const sample = data.rows[0];
                        const dynamicCols = [];

                        // Get all exam types dynamically from the first row
                        Object.keys(sample).forEach(key => {
                            // Skip standard columns and look for exam type columns
                            if (![
                                'id',
                                'no',
                                'student_name',
                                'roll_number',   // ✅ Added this line
                                'total_marks',
                                'obtained_marks',
                                'exam_marks_id',
                                'operate'
                            ].includes(key)) {
                                dynamicCols.push({
                                    field: key,
                                    title: key.charAt(0).toUpperCase() + key.slice(1),
                                    formatter: marksInputFormatter,
                                    align: 'center',
                                });
                            }
                        });

                        const columns = [
                            { field: 'id', title: 'ID', visible: false },
                            { field: 'no', title: "{{ __('no.') }}" },
                            { field: 'student_name', title: "{{ __('name') }}" },
                            // make roll number sortable so clicking header requests sorted data from server
                            { field: 'roll_number', title: "{{ __('Roll_number') }}", sortable: true },
                            { field: 'total_marks', title: "{{ __('total_marks') }}" },
                            ...dynamicCols,
                            {
                                field: 'obtained_marks',
                                title: "{{ __('obtained_marks') }}",
                                formatter: obtainedMarksFormatter,
                                align: 'center'
                            }
                        ];

                        $('.student_table').bootstrapTable('destroy').bootstrapTable({
                            columns: columns,
                            url: '{{ route('exams.marks-list') }}',
                            method: 'GET',
                            // merge bootstrap-table generated params (sort, order, limit, offset, search)
                            // with our local params so server receives sorting requests
                            queryParams: function (tableParams) {
                                return $.extend({}, params, {
                                    sort: tableParams.sort,
                                    order: tableParams.order,
                                    search: tableParams.search,
                                    limit: tableParams.limit,
                                    offset: tableParams.offset
                                });
                            },
                            search: true,
                            pagination: false,
                            showColumns: true,
                            showRefresh: true,
                            showExport: true,
                            exportDataType: 'all',
                            exportTypes: ['excel', 'csv', 'pdf'],
                            toolbar: '#toolbar',
                            sidePagination: 'server',
                            // keep sorting stable
                            sortStable: true,
                            onPostBody: attachAutoSumHandler
                        });
                    },
                    error: function (xhr) {
                        console.error('Error loading data', xhr.responseText);
                    }
                });
            }

            // -------- Formatters --------
            function marksInputFormatter(value, row, index, field) {
                const safeVal = value || '';
                return `
                    <input type="number" class="form-control mark-input"
                           name="exam_marks[${index}][${field}]"
                           data-student="${row.id}"
                           data-type="${field}"
                           min="0"
                           max="${row.total_marks}"
                           value="${safeVal}"
                           style="width:90px; text-align:center;">
                `;
            }

            function obtainedMarksFormatter(value, row, index, field) {
                const val = value || 0;
                return `
                    <input type="hidden" name="exam_marks[${index}][student_id]" value="${row.id}">
                    <input type="hidden" name="exam_marks[${index}][exam_marks_id]" value="${row.exam_marks_id}">
                    <input type="hidden" name="exam_marks[${index}][total_marks]" value="${row.total_marks}">
                    <input type="number" class="form-control obtained-marks"
                           data-student="${row.id}"
                           name="exam_marks[${index}][obtained_marks]"
                           value="${val}"
                           readonly
                           style="width:90px; background:#f1f1f1; text-align:center;">
                `;
            }

            // -------- Auto Sum Logic --------
            function attachAutoSumHandler() {
                $('.mark-input').off('input').on('input', function () {
                    const studentId = $(this).data('student');
                    const studentInputs = $(`.mark-input[data-student="${studentId}"]`);

                    let total = 0;
                    studentInputs.each(function () {
                        const value = parseFloat($(this).val()) || 0;
                        total += value;
                    });

                    // Update obtained marks input
                    const obtainedField = $(`.obtained-marks[data-student="${studentId}"]`);
                    obtainedField.val(total.toFixed(2));
                });
            }

            // Refresh after submit
            window.formSuccessFunction = function (response) {
                setTimeout(() => {
                    $('.student_table').bootstrapTable('refresh');
                }, 500);
            };

            // Load exams when class section changes
            $('#class_section_id').change(function () {
                const classSectionId = $(this).val();
                if (classSectionId) {
                    $.ajax({
                        url: '{{ url('exams/get-exam-by-class-id') }}/' + classSectionId,
                        type: 'GET',
                        success: function (response) {
                            $('#exam_id').empty().append('<option value="">{{ __('select') . ' ' . __('exam') }}</option>');
                            if (response.data && response.data.length > 0) {
                                response.data.forEach(exam => {
                                    $('#exam_id').append(`<option value="${exam.id}">${exam.name}</option>`);
                                });
                            }
                        }
                    });
                }
            });

            // Load subjects when exam changes
            $('#exam_id').change(function () {
                const examId = $(this).val();
                const classSectionId = $('#class_section_id').val();

                if (examId && classSectionId) {
                    $.ajax({
                        url: '{{ url('exams/get-subject-by-exam') }}/' + examId,
                        type: 'GET',
                        data: {
                            class_section_id: classSectionId
                        },
                        success: function (response) {
                            $('#subject_id').empty().append('<option value="">{{ __('select') . ' ' . __('subject')}}</option>');
                            if (response.data && response.data.length > 0) {
                                response.data.forEach(subject => {
                                    const subjectName = subject.class_subject?.subject_with_name || 'Unknown Subject';
                                    $('#subject_id').append(`<option value="${subject.class_subject_id}">${subjectName}</option>`);
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection