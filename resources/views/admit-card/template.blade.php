@extends('layouts.master')

@section('title')
    {{ __('admit_card') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Manage Admit Card Template')}}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('Create Admit Card Template')}}
                        </h4>
                        <form class="pt-3 subject-create-form" id="create-form" action="{{ url('admit-card-template') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                    <input name="name" type="text" placeholder="{{ __('name') }}" class="form-control"/>
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('page_layout') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('page_layout', ['A4 Landscape' => 'A4 Landscape','A4 Portrait' => 'A4 Portrait','Custom' => 'Custom'], 'A4 Landscape', ['class' => 'form-control page_layout']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('height') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    <input name="height" min="50" type="number" required placeholder="{{ __('height') }}" class="form-control height"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('width') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    <input name="width" min="50" type="number" required placeholder="{{ __('width') }}" class="form-control width"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('user_image_shape') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('user_image_shape', ['Round' => 'Round','Square' => 'Square'], 'Round', ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('image_size') }} <span class="text-small text-info">({{ __('px') }})</span><span class="text-danger">*</span></label>
                                    <input name="image_size" min="50" required type="number" placeholder="{{ __('image_size') }}" class="form-control"/>
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('background_image') }} </label>
                                    <input type="file" name="background_image" id="thumbnail" class="file-upload-default" accept="image/*"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled=""
                                                placeholder="{{ __('thumbnail') }}" required aria-label=""/>
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme"
                                                    type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('description') }} <span class="text-danger">*</span></label>
                                    <div class="alert alert-info mb-2" role="alert">
                                        <i class="fa fa-info-circle"></i> <strong>Note:</strong> Each paragraph will create a separate draggable chunk in the design template. HTML formatting (bold, tables, etc.) will be preserved within each chunk. This allows you to position and resize each text block independently.
                                    </div>
                                    <textarea id="tinymce_message" name="description" id="description" required placeholder="{{__('description')}}"></textarea>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    @include('admit-card.tags')
                                </div>

                            </div>
                            <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit" value={{ __('submit') }}>
                            <input class="btn btn-secondary float-right" type="reset" value={{ __('reset') }}>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ __('List Admit Card Template') }}</h4>
                        <div id="toolbar">
                            
                        </div>
                        
                        <table aria-describedby="mydesc" class='table' id='table_list' data-toggle="table" data-url="{{ route('admit-card-template.show',[1]) }}" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-fixed-columns="false" data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc" data-maintain-selected="true" data-export-data-type='all' data-query-params="admitCardTemplateQueryParams" data-toolbar="#toolbar" data-export-options='{ "fileName": "admit-card-template-list-<?= date('d-m-y') ?>" ,"ignoreColumn":["operate"]}' data-show-export="true" data-escape="true">
                            <thead>
                            <tr>
                                <th scope="col" data-field="id" data-sortable="true" data-visible="false">{{ __('id') }}</th>
                                <th scope="col" data-field="no">{{ __('no.') }}</th>
                                <th scope="col" data-field="name">{{ __('name') }}</th>
                                <th scope="col" data-field="page_layout">{{ __('page_layout') }}</th>
                                <th scope="col" data-field="background_image" data-formatter="imageFormatter">{{ __('background_image') }}</th>
                                <th scope="col" data-field="style" data-formatter="layoutFormatter">{{ __('layout') }}</th>
                                <th scope="col" data-field="operate" data-events="admitCardTemplateEvents" data-escape="false">{{ __('action') }}</th>
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
    </script>
@endsection
