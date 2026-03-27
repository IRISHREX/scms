@extends('layouts.master')

@section('title')
    {{ __('admit_card') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('manage_admit_card') . ' ' . __('template') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            {{ __('edit_admit_card') . ' ' . __('template') }}
                        </h4>
                        {!! Form::model($admitCardTemplate, [
                            'route' => ['admit-card-template.update', $admitCardTemplate->id],
                            'method' => 'post',
                            'class' => 'edit-form-without-reset',
                            'novalidate' => 'novalidate',
                            'enctype' => 'multipart/form-data',
                            'data-success-function' => 'formSuccessFunction'
                        ]) !!}
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('name') }} <span class="text-danger">*</span></label>
                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('page_layout') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('page_layout', ['A4 Landscape' => 'A4 Landscape','A4 Portrait' => 'A4 Portrait','Custom' => 'Custom'], null, ['class' => 'form-control page_layout']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('height') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    {!! Form::number('height', null, ['class' => 'form-control height', 'min' => '50']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-2">
                                    <label>{{ __('width') }} <span class="text-small text-info">({{ __('mm') }})</span> <span class="text-danger">*</span></label>
                                    {!! Form::number('width', null, ['class' => 'form-control width', 'min' => '50']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('user_image_shape') }} <span class="text-danger">*</span></label>
                                    {!! Form::select('user_image_shape', ['Round' => 'Round','Square' => 'Square'], null, ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('image_size') }} <span class="text-small text-info">({{ __('px') }})</span><span class="text-danger">*</span></label>
                                    {!! Form::number('image_size', null, ['class' => 'form-control', 'min' => '50']) !!}
                                </div>

                                <div class="form-group col-sm-12 col-md-4">
                                    <label>{{ __('background_image') }} <span class="text-danger">*</span></label>
                                    <input type="file" name="background_image" id="thumbnail" class="file-upload-default" accept="image/*"/>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled=""
                                                placeholder="{{ __('thumbnail') }}" aria-label=""/>
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-theme"
                                                    type="button">{{ __('upload') }}</button>
                                        </span>
                                    </div>
                                    <img src="{{ $admitCardTemplate->background_image }}" class="img-fluid w-75 mt-2" alt="">
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <label>{{ __('description') }} <span class="text-danger">*</span></label>
                                    <div class="alert alert-info mb-2" role="alert">
                                        <i class="fa fa-info-circle"></i> <strong>Note:</strong> Each paragraph will create a separate draggable chunk in the design template. HTML formatting (bold, tables, etc.) will be preserved within each chunk. This allows you to position and resize each text block independently.
                                    </div>
                                    <textarea id="tinymce_message" name="description" id="description" required placeholder="{{__('description')}}">{{ $admitCardTemplate->description }}</textarea>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    @include('admit-card.tags')
                                </div>

                            </div>
                            <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit" value={{ __('submit') }}>
                            <input class="btn btn-secondary float-right" type="reset" value={{ __('reset') }}>
                        {!! Form::close() !!}
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

        function formSuccessFunction(response) {
            setTimeout(() => {
                window.location.href = "{{route('admit-card-template.index')}}"
            }, 2000);
        }
    </script>
@endsection
