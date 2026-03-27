<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Staff ID Cards</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0px !important;
        }

        .full-width {
            width: 100%;
        }

        table {
            border-collapse: collapse;
            border: none;
            font-size: 14px;
            z-index: 1;
        }

        .staff-image {
            width: 30%;
            padding: 0px 10px;
            text-align: center;
            vertical-align: middle;
            height: 80px;
        }

        .staff-data {
            text-align: left;
            padding-left: 10px;
            padding: 2px 5px;
            font-weight: bold;
        }

        .card-title {
            padding: 6px 0px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .school-name {
            padding-right: 10px !important;
            text-align: right;
            font-size: 20px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom-right-radius: 10px;
        }

        .school-logo {
            border-bottom-left-radius: 10px;
        }

        .card-body {
            height: auto;
            display: block;
            page-break-after: auto !important;
        }

        .vertical-staff-data {
            text-align: left;
            padding: 2px 2px 5px 10px;
            font-weight: bold;
        }

        .signature {
            background-size: contain;
            background-position: center center;
            background-repeat: no-repeat;
            padding: 10px;
            position: fixed;
            bottom: 35px;
            right: 10px;
        }

        .vertical-school-name {
            padding: 10px 10px !important;
            text-align: center;
            font-size: 20px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom-right-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .draggable-content {
            white-space: nowrap;
            padding: 2px;
        }
        
        /* Multiple cards per page grid */
        .cards-grid {
            width: 100%;
            display: table;
        }
        .cards-row {
            display: table-row;
        }
        .card-cell {
            display: table-cell;
            padding: 5mm;
            vertical-align: top;
        }
        
        /* Cards per page layouts */
        .cards-1 .card-body {
            width: 100%;
            height: 100%;
        }
        .cards-2 .card-cell {
            width: 50%;
        }
        .cards-4 .card-cell {
            width: 50%;
        }
        .cards-6 .card-cell {
            width: 33.33%;
        }
        .cards-8 .card-cell {
            width: 25%;
        }
    </style>

    @if (isset($settings['staff_profile_image_style']) && $settings['staff_profile_image_style'] == 'squre')
        <style>
            .staff-profile {
                border: none;
                border-radius: 6px !important;
                object-fit: cover !important;
                padding: 2px;
            }
        </style>
    @else
        <style>
            .staff-profile {
                border: none;
                border-radius: 50% !important;
                object-fit: cover !important;
                padding: 2px;
            }
        </style>
    @endif

    @if (isset($settings['staff_layout_type']) && $settings['staff_layout_type'] == 'horizontal')
        <style>
            .background-image {
                position: fixed;
                width: auto;
                padding: 5px;
                height: auto;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0.2;
                z-index: -1;
            }

            .background_image {
                z-index: -1;
                object-fit: cover;
                background-size: contain;
                background-position: center center;
                background-repeat: no-repeat;
            }
        </style>
    @else
        <style>
            .background-image {
                position: fixed;
                width: auto;
                padding: 5px;
                height: auto;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0.2;
                z-index: -1;
            }

            .background_image {
                z-index: -1;
                object-fit: cover;
                background-size: contain;
                background-position: center center;
                background-repeat: no-repeat;
            }
        </style>
    @endif
</head>

<body>
    
    @php
function staffValueWithOptionalLabel($labels, $key, $value) {
    $label = trim($labels[$key] ?? '');

    if ($label !== '') {
        return '<b>' . e($label) . ' :</b> ' . e($value);
    }

    return e($value); // ONLY VALUE, NO LABEL, NO :
}
@endphp
    @php
    $staffLabels = [];

    if (!empty($settings['staff_id_card_labels'])) {
        $staffLabels = is_array($settings['staff_id_card_labels'])
            ? $settings['staff_id_card_labels']
            : json_decode($settings['staff_id_card_labels'], true);
    }

    function staffLabel($labels, $key, $default) {
        return $labels[$key] ?? $default;
    }
    @endphp
    @php
        $cardsPerPage = (int) ($settings['staff_cards_per_page'] ?? 1);
        $cardWidth = $settings['staff_page_width_px'] ?? '100%';
        $cardHeight = $settings['staff_page_height_px'] ?? '100%';
        
        // Calculate grid dimensions based on cards per page
        $cols = 1;
        $rows = 1;
        switch($cardsPerPage) {
            case 2: $cols = 2; $rows = 1; break;
            case 4: $cols = 2; $rows = 2; break;
            case 6: $cols = 3; $rows = 2; break;
            case 8: $cols = 4; $rows = 2; break;
            default: $cols = 1; $rows = 1;
        }
    @endphp

    @if (!empty($settings['staff_id_card_styles']))
        @if ($cardsPerPage > 1)
            {{-- Multiple cards per page layout --}}
            @php
                $userChunks = $users->chunk($cardsPerPage);
            @endphp
            
            @foreach ($userChunks as $chunk)
                <div class="cards-grid cards-{{ $cardsPerPage }}" style="page-break-after: always;">
                    <table style="width: 100%; border-collapse: collapse;">
                        @for ($row = 0; $row < $rows; $row++)
                            <tr>
                                @for ($col = 0; $col < $cols; $col++)
                                    @php
                                        $index = $row * $cols + $col;
                                        $user = $chunk->values()->get($index);
                                    @endphp
                                    <td style="width: {{ 100/$cols }}%; vertical-align: top; padding: 3mm;">
                                        @if ($user)
                                            <div class="card-body" style="position: relative; width: {{ $cardWidth }}; height: {{ $cardHeight }}; overflow: hidden; border: 1px solid #ddd;">
                                                @if ($settings['staff_background_image'] ?? '')
                                                    <img src="{{ public_path('storage/') . $settings['staff_background_image'] }}"
                                                        style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; object-fit: cover; z-index: -1;"
                                                        alt="">
                                                @endif

                                              @if (in_array('name', $settings['staff_id_card_fields']))
                                            <div class="draggable-content"
                                                style="position:absolute; {{ $settings['staff_id_card_styles']['name'] ?? '' }}">
                                                {!! staffValueWithOptionalLabel($staffLabels, 'name', $user->full_name) !!}
                                            </div>
                                        @endif
                                                @if (in_array('role', $settings['staff_id_card_fields']))
<div class="draggable-content"
     style="position:absolute; {{ $settings['staff_id_card_styles']['role'] ?? '' }}">
    {!! staffValueWithOptionalLabel(
        $staffLabels,
        'role',
        implode(',', $user->roles->pluck('name')->toArray())
    ) !!}
</div>
@endif
@if (in_array('principal_signature', $settings['staff_id_card_fields']))
    <div class="draggable-content"
         style="position:absolute; {{ $settings['staff_id_card_styles']['principal_signature'] ?? '' }}">
       {!! staffValueWithOptionalLabel($staffLabels, 'principal_signature', $user->name) !!}
    </div>
@endif

                                              @if (in_array('contact', $settings['staff_id_card_fields']))
<div class="draggable-content"
     style="position:absolute; {{ $settings['staff_id_card_styles']['contact'] ?? '' }}">
    {!! staffValueWithOptionalLabel($staffLabels, 'contact', $user->mobile) !!}
</div>
@endif


                                               @if (in_array('email', $settings['staff_id_card_fields']))
<div class="draggable-content"
     style="position:absolute; {{ $settings['staff_id_card_styles']['email'] ?? '' }}">
    {!! staffValueWithOptionalLabel($staffLabels, 'email', $user->email) !!}
</div>
@endif


                                              @if (in_array('qualification', $settings['staff_id_card_fields']))
<div class="draggable-content"
     style="position:absolute; {{ $settings['staff_id_card_styles']['qualification'] ?? '' }}">
    {!! staffValueWithOptionalLabel($staffLabels, 'qualification', $user->staff->qualification) !!}
</div>
@endif


                                             @if (in_array('dob', $settings['staff_id_card_fields']))
<div class="draggable-content"
     style="position:absolute; {{ $settings['staff_id_card_styles']['dob'] ?? '' }}">
    {!! staffValueWithOptionalLabel(
        $staffLabels,
        'dob',
        date($settings['date_format'], strtotime($user->dob))
    ) !!}
</div>
@endif

                                             @if (in_array('gender', $settings['staff_id_card_fields']))
<div class="draggable-content"
     style="position:absolute; {{ $settings['staff_id_card_styles']['gender'] ?? '' }}">
    {!! staffValueWithOptionalLabel($staffLabels, 'gender', ucfirst($user->gender)) !!}
</div>
@endif

                                            @if (in_array('session_year', $settings['staff_id_card_fields']))
<div class="draggable-content"
     style="position:absolute; {{ $settings['staff_id_card_styles']['session_year'] ?? '' }}">
    {!! staffValueWithOptionalLabel($staffLabels, 'session_year', $sessionYear->name) !!}
</div>
@endif


                                                @if (in_array('profile_image', $settings['staff_id_card_fields']))
                                                    @if ($user->getRawOriginal('image'))
                                                        <img class="staff-profile" src="{{ public_path('storage/' . $user->getRawOriginal('image')) }}" alt="profile"
                                                            style="position:absolute; height:80px; width:80px; {{ $settings['staff_id_card_styles']['profile_image'] ?? '' }}">
                                                    @else
                                                        <img class="staff-profile" src="{{ public_path('assets/dummy_logo.jpg') }}" alt="profile"
                                                            style="position:absolute; height:80px; width:80px; {{ $settings['staff_id_card_styles']['profile_image'] ?? '' }}">
                                                    @endif
                                                @endif

                                             @if (in_array('school_name', $settings['staff_id_card_fields']))
<div class="draggable-content"
     style="position:absolute; {{ $settings['staff_id_card_styles']['school_name'] ?? '' }}">
    {!! staffValueWithOptionalLabel($staffLabels, 'school_name', $settings['school_name']) !!}
</div>
@endif


                                               @if (in_array('school_address', $settings['staff_id_card_fields']))
<div class="draggable-content"
     style="position:absolute; {{ $settings['staff_id_card_styles']['school_address'] ?? '' }}">
    {!! staffValueWithOptionalLabel($staffLabels, 'school_address', $settings['school_address']) !!}
</div>
@endif


                                                @if (in_array('school_logo', $settings['staff_id_card_fields']))
                                                    <img src="{{ !empty($settings['vertical_logo'])
                                                            ? public_path(ltrim(parse_url($settings['vertical_logo'], PHP_URL_PATH), '/'))
                                                            : public_path('assets/dummy_logo.jpg') }}" alt="school_logo"
                                                        style="position:absolute; height:50px; width:50px; object-fit:cover; {{ $settings['staff_id_card_styles']['school_logo'] ?? '' }}">
                                                @endif

                                                @if (in_array('signature', $settings['staff_id_card_fields']))
                                                    <img src="{{ !empty($settings['signature'])
                                                            ? public_path('storage/' . $settings['signature'])
                                                            : public_path('assets/dummy_logo.jpg') }}" alt="signature"
                                                        style="position:absolute; height:40px; width:80px; object-fit:contain; {{ $settings['staff_id_card_styles']['signature'] ?? '' }}">
                                                @endif

                                                {{-- Extra Fields --}}
                                                @foreach ($user->extra_user_datas as $data)
                                                    @if ($data->form_field && $data->form_field->display_on_id == 1)
                                                        <div class="draggable-content" style="position:absolute; {{ $settings['staff_id_card_styles'][$data->form_field->id] ?? '' }}">
                                                            <b>{{ $data->form_field->name }} :</b>
                                                            @if (in_array($data->form_field->type, ['text', 'number', 'radio', 'textarea']))
                                                                {{ $data->data }}
                                                            @elseif($data->form_field->type == 'dropdown')
                                                                {!! isset($data->form_field->default_values[$data->data]) ? $data->form_field->default_values[$data->data] : $data->data !!}
                                                            @elseif($data->form_field->type == 'checkbox')
                                                                {!! implode(",", json_decode($data->data ?? '[]')) !!}
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endfor
                    </table>
                </div>
            @endforeach
        @else
            {{-- Single card per page layout (original behavior) --}}
            @foreach ($users as $user)
                <div class="card-body" style="position: relative; width: 100%; height: 100%; overflow: hidden;">
                       @if ($settings['staff_background_image'] ?? '')
        <img src="{{ public_path('storage/' . $settings['staff_background_image']) }}"
             style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:-1;">
    @endif

    @if (in_array('name', $settings['staff_id_card_fields']))
        <div class="draggable-content"
             style="position:absolute; {{ $settings['staff_id_card_styles']['name'] ?? '' }}">
            {!! staffValueWithOptionalLabel($staffLabels,'name',$user->full_name) !!}
        </div>
    @endif

    @if (in_array('role', $settings['staff_id_card_fields']))
        <div class="draggable-content"
             style="position:absolute; {{ $settings['staff_id_card_styles']['role'] ?? '' }}">
            {!! staffValueWithOptionalLabel(
                $staffLabels,
                'role',
                implode(',', $user->roles->pluck('name')->toArray())
            ) !!}
        </div>
    @endif

    @if (in_array('contact', $settings['staff_id_card_fields']))
        <div class="draggable-content"
             style="position:absolute; {{ $settings['staff_id_card_styles']['contact'] ?? '' }}">
            {!! staffValueWithOptionalLabel($staffLabels,'contact',$user->mobile) !!}
        </div>
    @endif

    @if (in_array('email', $settings['staff_id_card_fields']))
        <div class="draggable-content"
             style="position:absolute; {{ $settings['staff_id_card_styles']['email'] ?? '' }}">
            {!! staffValueWithOptionalLabel($staffLabels,'email',$user->email) !!}
        </div>
    @endif

    @if (in_array('qualification', $settings['staff_id_card_fields']))
        <div class="draggable-content"
             style="position:absolute; {{ $settings['staff_id_card_styles']['qualification'] ?? '' }}">
            {!! staffValueWithOptionalLabel(
                $staffLabels,
                'qualification',
                $user->staff->qualification
            ) !!}
        </div>
    @endif

    @if (in_array('dob', $settings['staff_id_card_fields']))
        <div class="draggable-content"
             style="position:absolute; {{ $settings['staff_id_card_styles']['dob'] ?? '' }}">
            {!! staffValueWithOptionalLabel(
                $staffLabels,
                'dob',
                date($settings['date_format'], strtotime($user->dob))
            ) !!}
        </div>
    @endif

    @if (in_array('gender', $settings['staff_id_card_fields']))
        <div class="draggable-content"
             style="position:absolute; {{ $settings['staff_id_card_styles']['gender'] ?? '' }}">
            {!! staffValueWithOptionalLabel($staffLabels,'gender',ucfirst($user->gender)) !!}
        </div>
    @endif

    @if (in_array('session_year', $settings['staff_id_card_fields']))
        <div class="draggable-content"
             style="position:absolute; {{ $settings['staff_id_card_styles']['session_year'] ?? '' }}">
            {!! staffValueWithOptionalLabel($staffLabels,'session_year',$sessionYear->name) !!}
        </div>
    @endif

    @if (in_array('profile_image', $settings['staff_id_card_fields']))
        <img class="staff-profile"
             src="{{ $user->getRawOriginal('image')
                ? public_path('storage/'.$user->getRawOriginal('image'))
                : public_path('assets/dummy_logo.jpg') }}"
             style="position:absolute; height:80px; width:80px; {{ $settings['staff_id_card_styles']['profile_image'] ?? '' }}">
    @endif

    @if (in_array('school_name', $settings['staff_id_card_fields']))
        <div class="draggable-content"
             style="position:absolute; {{ $settings['staff_id_card_styles']['school_name'] ?? '' }}">
            {!! staffValueWithOptionalLabel($staffLabels,'school_name',$settings['school_name']) !!}
        </div>
    @endif

    @if (in_array('school_address', $settings['staff_id_card_fields']))
        <div class="draggable-content"
             style="position:absolute; {{ $settings['staff_id_card_styles']['school_address'] ?? '' }}">
            {!! staffValueWithOptionalLabel($staffLabels,'school_address',$settings['school_address']) !!}
        </div>
    @endif

    @if (in_array('school_logo', $settings['staff_id_card_fields']))
        <img src="{{ !empty($settings['vertical_logo'])
            ? public_path(ltrim(parse_url($settings['vertical_logo'], PHP_URL_PATH), '/'))
            : public_path('assets/dummy_logo.jpg') }}"
             style="position:absolute; height:50px; width:50px; {{ $settings['staff_id_card_styles']['school_logo'] ?? '' }}">
    @endif

    @if (in_array('signature', $settings['staff_id_card_fields']))
        <img src="{{ !empty($settings['signature'])
            ? public_path('storage/'.$settings['signature'])
            : public_path('assets/dummy_logo.jpg') }}"
             style="position:absolute; height:40px; width:80px; {{ $settings['staff_id_card_styles']['signature'] ?? '' }}">
    @endif
                    {{-- Extra Fields --}}
                    @foreach ($user->extra_user_datas as $data)
                        @if ($data->form_field && $data->form_field->display_on_id == 1)
                            <div class="draggable-content" style="position:absolute; {{ $settings['staff_id_card_styles'][$data->form_field->id] ?? '' }}">
                                <b>{{ $data->form_field->name }} :</b>
                                @if (in_array($data->form_field->type, ['text', 'number', 'radio', 'textarea']))
                                    {{ $data->data }}
                                @elseif($data->form_field->type == 'dropdown')
                                    {!! isset($data->form_field->default_values[$data->data]) ? $data->form_field->default_values[$data->data] : $data->data !!}
                                @elseif($data->form_field->type == 'checkbox')
                                    {!! implode(",", json_decode($data->data ?? '[]')) !!}
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        @endif
    @else
        {{-- Fallback layout when no custom styles are set --}}
        @foreach ($users as $key => $user)
            <div class="card-body">
                @if ($settings['staff_layout_type'] == 'horizontal')

                    <table class="table full-width">
                        <tr class="header">
                            <th class="school-logo">
                                @if ($settings['horizontal_logo'] ?? '')
                                    <img height="40" src="{{ public_path('storage/') . $settings['horizontal_logo'] }}" alt="">
                                @else
                                    <img height="40" src="{{ public_path('assets/horizontal-logo2.svg') }}" alt="">
                                @endif
                            </th>
                            <th class="school-name" colspan="2">{{ $settings['school_name'] }}</th>
                        </tr>
                        <tr>
                            <th class="card-title" colspan="3">Staff Identification Card</th>
                        </tr>
                        <tr>
                            <td class="staff-image" rowspan="{{ count($settings['staff_id_card_fields']) }}">
                                @if ($user->getRawOriginal('image'))
                                    <img class="staff-profile" height="120" width="120" align="center"
                                        src="{{ public_path('storage/') . $user->getRawOriginal('image') }}" alt="">
                                @else
                                    <img class="staff-profile" height="120" width="120" align="center"
                                        src="{{ public_path('assets/dummy_logo.jpg') }}" alt="">
                                @endif

                            </td>
                            @if (in_array('name', $settings['staff_id_card_fields']))
                                <th class="staff-data">Name :</th>
                                <td>{{ $user->full_name }}</td>
                            @endif

                        </tr>

                        @if (in_array('role', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="staff-data">Role :</th>
                                <td>{{ implode(',', $user->roles->pluck('name')->toArray()) }}</td>
                            </tr>
                        @endif

                        @if (in_array('contact', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="staff-data">Contact :</th>
                                <td>{{ $user->mobile }}</td>
                            </tr>
                        @endif

                        @if (in_array('email', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="staff-data">Email :</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @endif

                        @if (in_array('qualification', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="staff-data">Qualification :</th>
                                <td>{{ $user->staff->qualification }}</td>
                            </tr>
                        @endif

                        @if (in_array('dob', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="staff-data">DOB :</th>
                                <td>{{ date($settings['date_format'], strtotime($user->dob)) }}</td>
                            </tr>
                        @endif

                        @if (in_array('gender', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="staff-data">Gender :</th>
                                <td style="text-transform: capitalize">{{ $user->gender }}</td>
                            </tr>
                        @endif

                        @if (in_array('session_year', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="staff-data">Session Year :</th>
                                <td>{{ $sessionYear->name }}</td>
                            </tr>
                        @endif

                        @foreach ($users[0]->extra_user_details as $data)
                            @if (in_array($data->form_field->type, ['text', 'number', 'radio', 'textarea']))
                                <tr>
                                    <th class="staff-data">{{ $data->form_field->name }} :</th>
                                    <td>{{ $data->data }}</td>
                                </tr>
                            @elseif($data->form_field->type == 'dropdown')
                                <tr>
                                    <th class="staff-data">{{ $data->form_field->name }} :</th>
                                    <td>{!! $data->form_field->default_values[$data->data] !!}</td>
                                </tr>
                            @elseif($data->form_field->type == 'checkbox')
                                <tr>
                                    <th class="staff-data">{{ $data->form_field->name }} :</th>
                                    <td>{!! implode(",", json_decode($data->data ?? '[]')) !!}</td>
                                </tr>
                            @endif
                        @endforeach

                        <tr>
                            <td></td>
                            <td colspan="">
                                @if ($settings['signature'] ?? '')
                                    <img height="40" width="100" align="center"
                                        src="{{ public_path('storage/' . $settings['signature']) }}" alt=""
                                        style="object-fit: contain;">
                                    <span style="display: inline-block; margin-left: 10px;"><b>Signature</b></span>
                                @endif
                            </td>
                        </tr>
                    </table>
                @else
                    {{-- Vertical --}}
                    <table class="table full-width">
                        <tr class="header">
                            <th class="vertical-school-name" colspan="2">{{ $settings['school_name'] }}</th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                @if ($settings['horizontal_logo'] ?? '')
                                    <img height="40" style="padding-top: 5px"
                                        src="{{ public_path('storage/') . $settings['horizontal_logo'] }}" alt="">
                                @else
                                    <img height="40" style="padding-top: 5px" src="{{ public_path('assets/horizontal-logo2.svg') }}"
                                        alt="">
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th class="card-title" colspan="2" style="font-size: 12px">Staff Identification Card</th>
                        </tr>
                        <tr>
                            <td class="staff-image" colspan="2">
                                @if ($user->getRawOriginal('image'))
                                    <img class="staff-profile" height="120" width="120" align="center"
                                        src="{{ public_path('storage/') . $user->getRawOriginal('image') }}" alt="">
                                @else
                                    <img class="staff-profile" height="120" width="120" align="center"
                                        src="{{ public_path('assets/dummy_logo.jpg') }}" alt="">
                                @endif
                            </td>
                        </tr>

                        @if (in_array('name', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="vertical-staff-data">Name :</th>
                                <td>{{ $user->full_name }}</td>
                            </tr>
                        @endif

                        @if (in_array('role', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="vertical-staff-data">Role :</th>
                                <td>{{ implode(',', $user->roles->pluck('name')->toArray()) }}</td>
                            </tr>
                        @endif

                        @if (in_array('contact', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="vertical-staff-data">Contact :</th>
                                <td>{{ $user->mobile }}</td>
                            </tr>
                        @endif

                        @if (in_array('email', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="vertical-staff-data">Email :</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @endif

                        @if (in_array('qualification', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="vertical-staff-data">Qualification :</th>
                                <td>{{ $user->staff->qualification }}</td>
                            </tr>
                        @endif

                        @if (in_array('dob', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="vertical-staff-data">DOB :</th>
                                <td>{{ date($settings['date_format'], strtotime($user->dob)) }}</td>
                            </tr>
                        @endif

                        @if (in_array('gender', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="vertical-staff-data">Gender :</th>
                                <td style="text-transform: capitalize">{{ $user->gender }}</td>
                            </tr>
                        @endif

                        @if (in_array('session_year', $settings['staff_id_card_fields']))
                            <tr>
                                <th class="vertical-staff-data">Session Year :</th>
                                <td>{{ $sessionYear->name }}</td>
                            </tr>
                        @endif

                        @foreach ($users[0]->extra_user_details as $data)
                            @if (in_array($data->form_field->type, ['text', 'number', 'radio', 'textarea']))
                                <tr>
                                    <th class="vertical-staff-data">{{ $data->form_field->name }} :</th>
                                    <td>{{ $data->data }}</td>
                                </tr>
                            @elseif($data->form_field->type == 'dropdown')
                                <tr>
                                    <th class="vertical-staff-data">{{ $data->form_field->name }} :</th>
                                    <td>{!! $data->form_field->default_values[$data->data] !!}</td>
                                </tr>
                            @elseif($data->form_field->type == 'checkbox')
                                <tr>
                                    <th class="vertical-staff-data">{{ $data->form_field->name }} :</th>
                                    <td>{!! implode(",", json_decode($data->data ?? '[]')) !!}</td>
                                </tr>
                            @endif
                        @endforeach

                        <tr>
                            <td></td>
                            <td>
                                @if ($settings['signature'] ?? '')
                                    <img height="40" width="100" align="center"
                                        src="{{ public_path('storage/' . $settings['signature']) }}" alt=""
                                        style="object-fit: contain;">
                                    <span style="display: inline-block; margin-left: 10px;"><b>Signature</b></span>
                                @endif
                            </td>
                        </tr>
                    </table>
                @endif
                @if (isset($settings['staff_layout_type']) && $settings['staff_layout_type'] == 'horizontal')
                    <div class="background-image">
                        @if ($settings['staff_background_image'] ?? '')
                            <img src="{{ public_path('storage/') . $settings['staff_background_image'] }}" class="background_image"
                                height="140" width="360" alt="">

                        @endif
                    </div>
                @else
                    <div class="background-image">
                        @if ($settings['staff_background_image'] ?? '')
                            <img src="{{ public_path('storage/') . $settings['staff_background_image'] }}" class="background_image"
                                height="140" width="280" alt="">

                        @endif
                    </div>
                @endif


            </div>
        @endforeach
    @endif
</body>

</html>
