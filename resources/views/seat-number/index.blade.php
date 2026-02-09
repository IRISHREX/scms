@extends('layouts.master')

@section('title')
    {{ __('Seat Generation') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Seat Generation') }}
            </h3>
            <a class="btn btn-sm btn-theme" href="{{ route('seat-number.design') }}">{{ __('Design Template') }}</a>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('Generate Seat Numbers') }}
                        </h4>
                        
                        <form action="{{ route('seat-number.print') }}" id="formdata" class="pt-3" target="_blank" method="post">
                            @csrf

                            <div class="row" id="toolbar">
                                <div class="form-group col-sm-12 col-md-3">
                                    <label class="filter-menu">{{ __('Class Section') }} <span class="text-danger">*</span></label>
                                    <select name="class_section_id" id="filter_class_section_id" class="form-control" required>
                                        <option value="">{{ __('Select Class Section') }}</option>
                                        @foreach ($classSections as $classSection)
                                            <option value="{{ $classSection->id }}">{{ $classSection->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-sm-12 col-md-3">
                                    <label class="filter-menu">{{ __('Exam') }} <span class="text-danger">*</span></label>
                                    <select name="exam_id" id="exam_id" class="form-control" required>
                                        <option value="">{{ __('Select Exam') }}</option>
                                        @foreach ($exams as $exam)
                                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-3">
                                    <label class="filter-menu">{{ __('Sort By') }} <span class="text-danger">*</span></label>
                                    <select name="sort_by" id="sort_by" class="form-control" required>
                                        <option value="roll_number">{{ __('Roll Number') }}</option>
                                        <option value="admission_no">{{ __('Admission No') }}</option>
                                        <option value="gender">{{ __('Gender') }}</option>
                                        <option value="random">{{ __('Randomly') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table aria-describedby="mydesc" class='table table-hover' id='table_list'
                                            data-toggle="table" data-url="{{ route('seat-number.generate') }}" data-click-to-select="true"
                                            data-side-pagination="server" data-pagination="true"
                                            data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true"
                                            data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true" data-fixed-columns="false"
                                            data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                                            data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all' data-show-export="true"
                                            data-export-options='{ "fileName": "students-list-<?= date('d-m-y') ?>" ,"ignoreColumn": ["operate"]}' data-query-params="queryParams"
                                            data-check-on-init="true" data-escape="true" data-response-handler="responseHandler">
                                            <thead>
                                            <tr>
                                                <th data-field="state" data-checkbox="true"></th>
                                                <th scope="col" data-field="id" data-sortable="true" data-visible="false">{{ __('id') }}</th>
                                                <th scope="col" data-field="no">{{ __('no.') }}</th>
                                                <th scope="col" data-field="user.id" data-visible="false">{{ __('User Id') }}</th>
                                                <th scope="col" data-field="user.full_name">{{ __('name') }}</th>
                                                <th scope="col" data-field="seat_number">{{ __('Seat Number') }}</th>
                                                <th scope="col" data-field="admission_no"> {{ __('Gr Number') }}</th>
                                                <th scope="col" data-field="roll_number">{{ __('roll_no') }}</th>
                                                <th scope="col" data-field="user.gender">{{ __('gender') }}</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <textarea id="user_id" name="user_id" style="display: none"></textarea>
                                <input type="submit" class="btn btn-theme mt-4 float-right" value="{{ __('Generate & Print') }}">
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
        var $tableList = $('#table_list')
        var selections = []
        var user_list = [];

        function responseHandler(res) {
            $.each(res.rows, function (i, row) {
                row.state = $.inArray(row.id, selections) !== -1
            })
            return res
        }

        function queryParams(p) {
            return {
                limit: p.limit,
                offset: p.offset,
                sort: p.sort,
                order: p.order,
                search: p.search,
                class_section_id: $('#filter_class_section_id').val(),
                exam_id: $('#exam_id').val(),
            };
        }

        $('#filter_class_section_id, #exam_id').change(function() {
            $tableList.bootstrapTable('refresh');
        });

        $(function () {
            $tableList.on('check.bs.table check-all.bs.table uncheck.bs.table uncheck-all.bs.table',
                function (e, rowsAfter, rowsBefore) {
                    user_list = [];
                    var rows = rowsAfter
                    if (e.type === 'uncheck-all') {
                        rows = rowsBefore
                    }
                    var ids = $.map(!$.isArray(rows) ? [rows] : rows, function (row) {
                        return row.user.id
                    })

                    var func = $.inArray(e.type, ['check', 'check-all']) > -1 ? 'union' : 'difference'
                    selections = window._[func](selections, ids)
                    selections.forEach(element => {
                        user_list.push(element);
                    });
                    $('textarea#user_id').val(selections);
                })
        })
    </script>
@endsection
