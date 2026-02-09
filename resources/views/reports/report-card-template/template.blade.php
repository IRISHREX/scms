@extends('layouts.master')

@section('title')
    {{ __('Report Card Template') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Manage Report Card Template')}}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('Create Report Card Template')}}
                        </h4>
                        <form class="pt-3 subject-create-form" id="create-form" action="{{ url('report-card-template') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Basic Info -->
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input name="name" type="text" placeholder="{{ __('name') }}" class="form-control"/>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('page_layout') }} <span class="text-danger">*</span></label>
                                    <select name="page_layout" class="form-control page_layout">
                                        <option value="A4 Portrait">A4 Portrait</option>
                                        <option value="A4 Landscape">A4 Landscape</option>
                                        <option value="Custom">Custom</option>
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('height') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    <input name="height" min="50" type="number" required placeholder="{{ __('height') }}" class="form-control height" value="297"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('width') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    <input name="width" min="50" type="number" required placeholder="{{ __('width') }}" class="form-control width" value="210"/>
                                </div>
                            </div>
                            
                            <!-- Color Configuration -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="card-title mb-3">{{ __('Color Configuration') }}</h5>
                                </div>
                                <div class="form-group col-sm-12 col-md-3">
                                    <label>{{ __('Header Background Color') }}</label>
                                    <div class="input-group">
                                        <input type="color" name="colors[header_bg]" value="#22577a" class="form-control form-control-color" style="height: 40px; cursor: pointer;"/>
                                        <span class="input-group-text">#22577a</span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-3">
                                    <label>{{ __('Table Header Background') }}</label>
                                    <div class="input-group">
                                        <input type="color" name="colors[table_header_bg]" value="#22577a" class="form-control form-control-color" style="height: 40px; cursor: pointer;"/>
                                        <span class="input-group-text">#22577a</span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-3">
                                    <label>{{ __('Table Text Color') }}</label>
                                    <div class="input-group">
                                        <input type="color" name="colors[table_text_color]" value="#333333" class="form-control form-control-color" style="height: 40px; cursor: pointer;"/>
                                        <span class="input-group-text">#333333</span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-3">
                                    <label>{{ __('Footer Background Color') }}</label>
                                    <div class="input-group">
                                        <input type="color" name="colors[footer_bg]" value="#f2f5f7" class="form-control form-control-color" style="height: 40px; cursor: pointer;"/>
                                        <span class="input-group-text">#f2f5f7</span>
                                    </div>
                                </div>
                            </div>
                            <!-- TinyMCE -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="card-title mb-3">{{ __('Template Design') }}</h5>
                                </div>
                                <div class="form-group col-sm-12 col-md-12">
                                    <div class="alert alert-info mb-2" role="alert">
                                        <i class="fa fa-info-circle"></i> <strong>Note:</strong> Customize your report card layout here. Use the tags below to insert dynamic student data and result tables.
                                    </div>
                                    <textarea id="tinymce_message" name="style[template_description]" required placeholder="{{__('Design your template here')}}"></textarea>
                                </div>
                            </div>

                            <!-- Tags -->
                            <div class="row mt-4">
                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('Tags') }}</label>
                                    <div id="student_tags">
                                        @include('admit-card.tags')
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12">
                                    <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit" value="{{ __('submit') }}">
                                    <input class="btn btn-secondary float-right" type="reset" value="{{ __('reset') }}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('List Report Card Template') }}</h4>
                        <div id="toolbar">
                            
                        </div>
                        
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table" data-url="{{ route('report-card-template.show',[1]) }}" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-fixed-columns="false" data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all' data-query-params="reportCardTemplateQueryParams" data-toolbar="#toolbar" data-export-options='{ "fileName": "report-card-template-list-<?= date('d-m-y') ?>" ,"ignoreColumn":["operate"]}' data-show-export="true" data-escape="true">
                            <thead>
                            <tr>
                                <th scope="col" data-field="id" data-sortable="true" data-visible="false">{{ __('id') }}</th>
                                <th scope="col" data-field="no">{{ __('no.') }}</th>
                                <th scope="col" data-field="name">{{ __('name') }}</th>
                                <th scope="col" data-field="page_layout">{{ __('page_layout') }}</th>
                                <th scope="col" data-field="operate" data-events="reportCardTemplateEvents" data-escape="false">{{ __('action') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        window.onload = setTimeout(() => {
            $('.page_layout').trigger('change');
        }, 500);

        function reportCardTemplateQueryParams(p) {
            return {
                limit: p.limit,
                offset: p.offset,
                sort: p.sort,
                order: p.order,
                search: p.search,
            };
        }

        window.reportCardTemplateEvents = {
            'click .btn-gradient-primary': function (e, value, row) {
                location.href = '/report-card-template/' + row.id + '/edit';
            },
            'click .btn-gradient-info': function (e, value, row) {
                location.href = '/report-card-template/' + row.id + '/design';
            },
            'click .btn-delete': function (e, value, row) {
                deleteRecord('/report-card-template/' + row.id);
            }
        };
    </script>
@endsection

@section('css')
    <style>
        .form-control-color {
            border: 1px solid #ced4da;
            width: 50px;
        }
    </style>
@endsection
