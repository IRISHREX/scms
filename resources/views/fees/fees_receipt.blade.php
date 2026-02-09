<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * {
            font-family: DejaVu Sans, sans-serif;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fees Receipt || {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
<div class="container ">
    <div class="row mt-4">
        <div class="col">
            <div class="row">
                <div class="col">
                    <div class="text-center">
                        <div>
                            @if ($school['horizontal_logo'] ?? '')
                                <img style="height: 5rem;width: 5rem;" src="{{ public_path('storage/') . $school['horizontal_logo'] }}" alt="">                    
                            @else
                                <img style="height: 5rem;width: 5rem;" src="{{ public_path('assets/horizontal-logo2.svg') }}" alt="">
                            @endif
                        </div>

                        <span class="text-default-d3 ml-4" style="font-size:1.5rem"><strong>{{$school['school_name'] ?? ''}}</strong></span><br>
                        <span class="text-default-d3 ml-4" style="font-size:1rem">{{$school['school_address'] ?? ''}}</span>
                        <hr style="border: 1px solid">
                        <h4>Fee Receipt</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                </div>

                <div class="col-sm-6 align-self-start d-sm-flex justify-content-end">
                    <div class="text-grey-m2 mt-2 ml-3">
                        <p><strong><u>Invoice</u></strong><br>
                            <strong>Fee Receipt</strong> :- {{$feesPaid->id ?? ''}}<br>
                        </p>
                    </div>
                </div>
            </div>
            <hr style="border: 1px solid">
            <div class="row ml-3">
                <div class="col-sm-6 align-self-start">
                    <div class="row text-black">
                        <p><strong><u>Student Details :- </u></strong><br>

                            <strong>Name</strong> :- {{$student->user->full_name}} <br>
                            {{--                            <strong>Session</strong> :- {{isset($feesPaid) ? $feesPaid->session_year->name : '-'}} <br>--}}
                            <strong>Class</strong> :- {{$student->class_section->full_name ?? ''}}<br>
                    </div>
                </div>
            </div>
            <div class="mt-4 ml-4">
                <table class="table" style="text-align: center">
                    <thead>
                    <tr>
                        <th scope="col">Sr no.</th>
                        <th scope="col" colspan="2">Fee Type</th>
                        <th scope="col">Amount</th>
                    </tr>
                    </thead>
                    @php
                        $no = 1;
                        $total_fees = 0;
                        $total_optional_fees = 0;
                        $due_charges = 0;
                        $total_displayed_amount = 0; // Track actual displayed amounts
                    @endphp
                    <tbody>
                    @php
                        $compulsoryFeesType = $feesPaid->fees->compulsory_fees->pluck('fees_type_name');
                        $compulsoryFeesType = implode(" , ",$compulsoryFeesType->toArray());
                    @endphp
                    {{--Compulsory Fees Listing --}}
                    @if(isset($feesPaid->compulsory_fee) && $feesPaid->compulsory_fee->isNotEmpty())
                        @foreach ($feesPaid->compulsory_fee as $index => $compulsoryFee)
                            @if($compulsoryFee->type == "Full Payment")
                                {{-- @foreach ($feesPaid->compulsory_fee as $data) --}}
                                    <tr>
                                        <th scope="row" class="text-left">{{$no++}}</th>
                                        <td colspan="2" class="text-left">
                                            {{$compulsoryFee->type}}<br>
                                            <small class="font-weight-bold">( {{$compulsoryFeesType}} )</small><br>
                                            <small>Mode : <span class="font-weight-bold">({{ $compulsoryFee->mode}})</span></small><br>
                                            <small>Date &nbsp;: <span class="font-weight-bold">{{date('d-m-Y',strtotime($compulsoryFee->date))}} </span></small><br>
                                            @if ((float)$compulsoryFee->due_charges > 0)
                                                <small>Due Charges : <b>{{$compulsoryFee->due_charges ?? 0}}</b></small><br>
                                            @endif
                                            @if ((float)$compulsoryFee->discount > 0)
                                                <small>Discount : <b>-{{$compulsoryFee->discount}}</b></small><br>
                                            @endif
                                            @if ((float)$compulsoryFee->extra_fee > 0)
                                                <small>Extra Charges : <b>{{$compulsoryFee->extra_fee}}</b></small><br>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @php
                                                // The amount field already includes due charges when stored
                                                $row_amount = $compulsoryFee->amount;
                                                $total_displayed_amount += $row_amount;
                                                $due_charges += $compulsoryFee->due_charges ?? 0;
                                            @endphp
                                            {{$row_amount}} {{$school['currency_symbol'] ?? ''}}<br><br><br><br><br>
                                        </td>
                                    </tr>
                                {{-- @endforeach --}}
                                {{--                                <tr>--}}
                                {{--                                    <th scope="row" class="text-left">{{$no++}}</th>--}}
                                {{--                                    <td colspan="2" class="text-left">Due Charges</td>--}}
                                {{--                                    <td class="text-right">{{$compulsoryFee->due_charges ?? 0}} {{$school['currency_symbol'] ?? ''}}</td>--}}
                                {{--                                </tr>--}}
                            @elseif($compulsoryFee->type == "Installment Payment")
                                <tr>
                                    <th scope="row" class="text-left">{{$no++}}</th>
                                    <td colspan="2" class="text-left">{{$compulsoryFee->installment_fee->name}}
                                        <br><small>Mode : <span class="font-weight-bold">({{ $compulsoryFee->mode}})</span></small>
                                        <br><small>Date &nbsp;: <span class="font-weight-bold">{{date('d-m-Y',strtotime($compulsoryFee->date))}} </span></small>
                                        <br><small>Includes : <span class="font-weight-bold">{{$compulsoryFeesType}} </span></small>

                                        @if ((float)$compulsoryFee->due_charges > 0)
                                            <br><small>Due Charges: <b>{{ $compulsoryFee->due_charges }}</b></small>
                                        @endif
                                        @if ((float)$compulsoryFee->discount > 0)
                                            <br><small>Discount: <b>-{{ $compulsoryFee->discount }}</b></small>
                                        @endif
                                        @if ((float)$compulsoryFee->extra_fee > 0)
                                            <br><small>Extra Charges: <b>{{ $compulsoryFee->extra_fee }}</b></small>
                                        @endif
                                        @php
                                            $due_charges += $compulsoryFee->due_charges ?? 0;
                                        @endphp
                                    </td>
                                    <td class="text-right">
                                        @php
                                            // The amount field already includes due charges when stored
                                            $row_amount = $compulsoryFee->amount;
                                            $total_displayed_amount += $row_amount;
                                        @endphp
                                        {{$row_amount}} {{$school['currency_symbol'] ?? ''}}
                                    </td>
                                </tr>
                            @endif

                            @php
                                $total_fees += $compulsoryFee->amount;
                            @endphp

                        @endforeach
                    @endif

                    {{-- Optional Fees Listing --}}
                    @if(isset($feesPaid->optional_fee) && $feesPaid->optional_fee->isNotEmpty())
                        @foreach ($feesPaid->optional_fee as $optionalFee)
                            <tr>
                                <th scope="row" class="text-left">{{$no++}}</th>
                                <td colspan="2" class="text-left">{{ $optionalFee->fees_class_type->fees_type_name}} <small class="font-weight-bold">({{__("optional")}})</small>
                                    <br><small>Mode : <span class="font-weight-bold">({{ $optionalFee->mode }})</span></small>
                                    <br><small>Date &nbsp;: <span class="font-weight-bold">{{date('d-m-Y',strtotime($optionalFee->date))}} </span></small>
                                </td>
                                <td class="text-right">{{$optionalFee->amount}} {{$school['currency_symbol'] ?? ''}}</td>
                            </tr>
                            @php
                                $total_fees += $optionalFee->amount;
                                $total_optional_fees += $optionalFee->amount;
                                $total_displayed_amount += $optionalFee->amount;
                            @endphp
                        @endforeach
                    @endif
                    
                    {{-- Payment Summary Breakdown --}}
                    @php
                        // Calculate total discount and extra fees from all compulsory fees
                        $total_discount = 0;
                        $total_extra_fee = 0;
                        if(isset($feesPaid->compulsory_fee) && $feesPaid->compulsory_fee->isNotEmpty()) {
                            foreach ($feesPaid->compulsory_fee as $fee) {
                                $total_discount += $fee->discount ?? 0;
                                $total_extra_fee += $fee->extra_fee ?? 0;
                            }
                        }
                    @endphp
                    
                    {{-- Show breakdown if there are any adjustments --}}
                    @if($total_discount > 0 || $due_charges > 0 || $total_extra_fee > 0)
                        <tr style="border-top: 2px solid #333;">
                            <th scope="row"></th>
                            <td colspan="3" class="text-center"><strong><u>Payment Summary</u></strong></td>
                        </tr>
                        
                        <tr>
                            <th scope="row"></th>
                            <td colspan="2" class="text-left"><strong>Base Compulsory Fees</strong></td>
                            <td class="text-right">{{ number_format($feesPaid->fees->total_compulsory_fees, 2) }} {{$school['currency_symbol'] ?? ''}}</td>
                        </tr>
                        
                        @if($total_discount > 0)
                            <tr>
                                <th scope="row"></th>
                                <td colspan="2" class="text-left" style="color: #28a745;"><strong>Total Discount</strong></td>
                                <td class="text-right" style="color: #28a745;">- {{ number_format($total_discount, 2) }} {{$school['currency_symbol'] ?? ''}}</td>
                            </tr>
                        @endif
                        
                        @if($due_charges > 0)
                            <tr>
                                <th scope="row"></th>
                                <td colspan="2" class="text-left" style="color: #dc3545;"><strong>Total Due Charges</strong></td>
                                <td class="text-right" style="color: #dc3545;">+ {{ number_format($due_charges, 2) }} {{$school['currency_symbol'] ?? ''}}</td>
                            </tr>
                        @endif
                        
                        @if($total_extra_fee > 0)
                            <tr>
                                <th scope="row"></th>
                                <td colspan="2" class="text-left" style="color: #17a2b8;"><strong>Total Extra Charges</strong></td>
                                <td class="text-right" style="color: #17a2b8;">+ {{ number_format($total_extra_fee, 2) }} {{$school['currency_symbol'] ?? ''}}</td>
                            </tr>
                        @endif
                    @endif
                    
                    <tr style="border-top: 3px double #333; background-color: #f8f9fa;">
                        <th scope="row"></th>
                        <td colspan="2" class="text-left"><strong style="font-size: 1.1em;">Total Paid Amount</strong></td>
                        <td class="text-right">
                            @php
                                // Calculate Total Paid = Base - Discount + Due Charges + Extra Fees
                                $calculated_total = $feesPaid->fees->total_compulsory_fees - $total_discount + $due_charges + $total_extra_fee + $total_optional_fees;
                            @endphp
                            <strong style="font-size: 1.1em;">{{number_format($calculated_total, 2)}} {{$school['currency_symbol'] ?? ''}}</strong>
                        </td>
                    </tr>

                    @php
                        // Calculate remaining balance: Base fees - Amount paid (actual amounts without due/extra/discount)
                        $actual_amount_paid_towards_base = $total_fees - $total_optional_fees + $total_discount;
                        $remaining_balance = $feesPaid->fees->total_compulsory_fees - $actual_amount_paid_towards_base;
                    @endphp
                    
                    @if ($remaining_balance > 0.01)
                        <tr style="border-top: 1px solid #dee2e6;">
                            <th scope="row"></th>
                            <td colspan="2" class="text-left"><small><strong>Remaining Balance</strong></small></td>
                            <td class="text-right"><small><strong>{{ number_format($remaining_balance, 2) }} {{$school['currency_symbol'] ?? ''}}</strong></small></td>
                        </tr>
                    @endif
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>

</html>
