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
                            {{ __('Edit Report Card Template') }}
                        </h4>
                        @php
                            $colors = json_decode($reportCardTemplate->colors, true) ?? [];
                            $configuration = json_decode($reportCardTemplate->configuration, true) ?? [];
                            $style = json_decode($reportCardTemplate->style, true) ?? [];
                        @endphp
                        <form class="pt-3 edit-form-without-reset" id="edit-form" action="{{ route('report-card-template.update', $reportCardTemplate->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data" data-success-function="formSuccessFunction">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- Basic Info -->
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input name="name" type="text" value="{{ $reportCardTemplate->name }}" placeholder="{{ __('name') }}" class="form-control"/>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('page_layout') }} <span class="text-danger">*</span></label>
                                    <select name="page_layout" class="form-control page_layout">
                                        <option value="A4 Portrait" {{ $reportCardTemplate->page_layout == 'A4 Portrait' ? 'selected' : '' }}>A4 Portrait</option>
                                        <option value="A4 Landscape" {{ $reportCardTemplate->page_layout == 'A4 Landscape' ? 'selected' : '' }}>A4 Landscape</option>
                                        <option value="Custom" {{ $reportCardTemplate->page_layout == 'Custom' ? 'selected' : '' }}>Custom</option>
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('height') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    <input name="height" min="50" type="number" required value="{{ $reportCardTemplate->height }}" placeholder="{{ __('height') }}" class="form-control height"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('width') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    <input name="width" min="50" type="number" required value="{{ $reportCardTemplate->width }}" placeholder="{{ __('width') }}" class="form-control width"/>
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
                                        <input type="color" name="colors[header_bg]" value="{{ $colors['header_bg'] ?? '#22577a' }}" class="form-control form-control-color" style="height: 40px; cursor: pointer;"/>
                                        <span class="input-group-text">{{ $colors['header_bg'] ?? '#22577a' }}</span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-3">
                                    <label>{{ __('Table Header Background') }}</label>
                                    <div class="input-group">
                                        <input type="color" name="colors[table_header_bg]" value="{{ $colors['table_header_bg'] ?? '#22577a' }}" class="form-control form-control-color" style="height: 40px; cursor: pointer;"/>
                                        <span class="input-group-text">{{ $colors['table_header_bg'] ?? '#22577a' }}</span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-3">
                                    <label>{{ __('Table Text Color') }}</label>
                                    <div class="input-group">
                                        <input type="color" name="colors[table_text_color]" value="{{ $colors['table_text_color'] ?? '#333333' }}" class="form-control form-control-color" style="height: 40px; cursor: pointer;"/>
                                        <span class="input-group-text">{{ $colors['table_text_color'] ?? '#333333' }}</span>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-3">
                                    <label>{{ __('Footer Background Color') }}</label>
                                    <div class="input-group">
                                        <input type="color" name="colors[footer_bg]" value="{{ $colors['footer_bg'] ?? '#f2f5f7' }}" class="form-control form-control-color" style="height: 40px; cursor: pointer;"/>
                                        <span class="input-group-text">{{ $colors['footer_bg'] ?? '#f2f5f7' }}</span>
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
                                    <textarea id="tinymce_message" name="style[template_description]" required placeholder="{{__('Design your template here')}}">{!! $style['template_description'] ?? '' !!}</textarea>
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

                            <div class="form-group col-sm-12 col-md-12">
                                <input class="btn btn-theme float-right ml-3" id="update-btn" type="submit" value="{{ __('submit') }}">
                                <a href="{{ route('report-card-template.index') }}" class="btn btn-secondary float-right">{{ __('back') }}</a>
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
        function formSuccessFunction(response) {
            setTimeout(() => {
                location.href = '{{ route("report-card-template.index") }}';
            }, 1000);
        }
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
