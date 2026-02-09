@extends('layouts.master')

@section('title')
    {{ __('Pay Compulsory Fees') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header modern-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <h3 class="page-title mb-2">
                        <span class="page-title-icon bg-gradient-primary text-white me-2">
                            <i class="mdi mdi-cash-multiple"></i>
                        </span>
                        {{ __('Pay Compulsory Fees') }}
                    </h3>
                    <p class="text-muted mb-0"><i class="mdi mdi-information-outline"></i> Process fee payments quickly and securely</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card search-container">
                <div class="card modern-card shadow-lg border-0">
                    <div class="card-header modern-card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-4">
                        <div>
                            <h4 class="mb-1 font-weight-bold"><i class="mdi mdi-wallet"></i> {{ __('Fee Payment Form') }}</h4>
                            <small class="opacity-90">Complete the details below to process the payment</small>
                        </div>
                        <div class="btn-group shadow-sm">
                            <button type="button" class="btn btn-warning btn-sm modern-btn" id="print-fee-details" onclick="printFeeDetails()">
                                <i class="mdi mdi-printer"></i> {{ __('Print') }}
                            </button>
                            <a href="{{ route('fees.paid.index') }}" class="btn btn-light btn-sm modern-btn">
                                <i class="mdi mdi-arrow-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="pt-3 create-form form-validation" method="post" action="{{ route('fees.compulsory.store') }}" data-success-function="successFunction" novalidate="novalidate">
                            <input type="hidden" name="fees_id" id="compulsory-fees-id" value="{{$fees->id}}"/>
                            <input type="hidden" name="student_id" id="student-id" value="{{$student->id}}"/>
                            <input type="hidden" name="parent_id" id="parent-id" value="{{$student->student->guardian_id}}"/>
                            <input type="hidden" name="installment_mode" id="installment-mode" value="0"/>
                            <input type="hidden" name="total_amount" id="total-amount" value="{{$fees->total_compulsory_fees}}"/>
                            <input type="hidden" id="total_compulsory_fees" name="total_compulsory_fees" value="{{$fees->total_compulsory_fees}}">
                            <input type="hidden" id="remaining_amount" value="{{$fees->remaining_amount}}">
                            <input type="hidden" id="total_installment_amount" value="0">
                            <input type="hidden" name="due_charges_amount" value="{{ $due_charges }}">
                            <input type="hidden" name="discount_amount" id="discount_amount" value="0">
                            
                            <!-- Student Information Card -->
                            <div class="student-info-card mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <div class="student-avatar">
                                            <i class="mdi mdi-account-circle"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <h4 class="mb-1 font-weight-bold text-dark">{{$student->full_name}}</h4>
                                        <p class="mb-0 text-muted"><i class="mdi mdi-google-classroom mr-1"></i> {{$student->student->class_section->full_name}}</p>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <span class="student-id-badge">
                                            <i class="mdi mdi-card-account-details"></i> ID: {{$student->id}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @php
                                $total_compulsory_fees = $fees->total_compulsory_fees;
                            @endphp
                            
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-group modern-input-group">
                                        <label for="payment-date" class="modern-label">
                                            <i class="mdi mdi-calendar text-primary"></i> {{ __('date') }} 
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <input id="payment-date" type="text" name="date" class="datepicker-popup paid-date form-control modern-input" placeholder="{{ __('Select payment date') }}" autocomplete="off" required>
                                            <i class="mdi mdi-calendar-outline input-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group modern-input-group">
                                        <label for="receipt_no" class="modern-label">
                                            <i class="mdi mdi-receipt text-success"></i> {{ __('Receipt No') }}
                                        </label>
                                        <div class="input-with-icon">
                                            <input id="receipt_no" type="text" name="receipt_no" class="form-control modern-input" placeholder="{{ __('Auto Generated') }}" readonly>
                                            <i class="mdi mdi-lock-outline input-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modern-divider my-4"></div>
                            
                            <div id="printable-area">
                                <!-- Print Header (hidden on screen) -->
                                <div class="print-header" style="display: none;">
                                    <div class="text-center mb-4 print-header-content">
                                        <h2 style="margin: 0; font-size: 28px; font-weight: bold; color: #333;">{{ config('app.name', 'School Management System') }}</h2>
                                        <p style="margin: 5px 0; font-size: 16px; color: #666;">{{ __('Fee Payment Receipt') }}</p>
                                        <hr style="border: 0; border-top: 2px solid #333; margin: 15px 0;">
                                    </div>
                                    
                                    <!-- Student Info for Print -->
                                    <div class="print-student-info" style="display: block; margin-bottom: 20px; padding: 15px; border: 2px solid #333; border-radius: 8px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <h4 style="margin: 0 0 5px 0; font-size: 18px; font-weight: bold; color: #333;">{{$student->full_name}}</h4>
                                                <p style="margin: 0; font-size: 14px; color: #666;">{{ __('Class') }}: {{$student->student->class_section->full_name}}</p>
                                            </div>
                                            <div style="text-align: right;">
                                                <p style="margin: 0; font-size: 14px; color: #666;"><strong>{{ __('Student ID') }}:</strong> {{$student->id}}</p>
                                                <p style="margin: 5px 0 0 0; font-size: 14px; color: #666;"><strong>{{ __('Date') }}:</strong> {{ date('d-m-Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-title-modern mb-4">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon-box">
                                            <i class="mdi mdi-format-list-bulleted"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 font-weight-bold">{{ __('Fee Details') }}</h5>
                                            <small class="text-muted">Review the fee breakdown below</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group col-sm-12 col-md-12">
                                    <div class="compulsory-fees-content">
                                        <table class="table table-hover table-bordered fee-details-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="5%" class="no-print"></th>
                                                    <th width="60%">{{ __('Fee Type') }}</th>
                                                    <th width="35%" class="text-right">{{ __('Amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($fees->compulsory_fees as $compulsoryFee)
                                                <tr>
                                                    <td class="text-center no-print"><i class="mdi mdi-checkbox-marked-circle text-success"></i></td>
                                                    <td class="text-left">
                                                        <strong>{{$compulsoryFee->fees_type_name}}</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="amount-badge">{{$compulsoryFee->amount.' '.$currencySymbol}}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                            @php
                                                // Calculate total discount and due charges from paid fees
                                                $totalDiscountDetail = 0;
                                                $totalDueChargesDetail = 0;
                                                $totalExtraFeeDetail = 0;
                                                
                                                if ($student->fees_paid && $student->fees_paid->compulsory_fee) {
                                                    foreach ($student->fees_paid->compulsory_fee as $paidFee) {
                                                        $totalDiscountDetail += $paidFee->discount ?? 0;
                                                        $totalDueChargesDetail += $paidFee->due_charges ?? 0;
                                                        $totalExtraFeeDetail += $paidFee->extra_fee ?? 0;
                                                    }
                                                }
                                            @endphp
                                            
                                            @if($totalDiscountDetail > 0 && !count($fees->installments))
                                                <tr>
                                                    <td class="text-center no-print"><i class="mdi mdi-sale text-success"></i></td>
                                                    <th colspan="2" class="text-left text-success">
                                                        <i class="mdi mdi-minus-circle"></i> {{__("Discount")}}
                                                    </th>
                                                    <th class="text-right text-success">
                                                        - <span>{{number_format($totalDiscountDetail, 2)}}</span>{{' '.$currencySymbol}}
                                                    </th>
                                                </tr>
                                            @endif
                                            
                                            @if($totalDueChargesDetail > 0 && !count($fees->installments))
                                                <tr>
                                                    <td class="text-center no-print"><i class="mdi mdi-clock-alert text-danger"></i></td>
                                                    <th colspan="2" class="text-left text-danger">
                                                        <i class="mdi mdi-alert-circle"></i> {{__("Due Charges")}}
                                                    </th>
                                                    <th class="text-right text-danger">
                                                        + <span>{{number_format($totalDueChargesDetail, 2)}}</span>{{' '.$currencySymbol}}
                                                    </th>
                                                </tr>
                                            @endif
                                            
                                            @if($totalExtraFeeDetail > 0 && !count($fees->installments))
                                                <tr>
                                                    <td class="text-center no-print"><i class="mdi mdi-plus-circle text-info"></i></td>
                                                    <th colspan="2" class="text-left text-info">
                                                        <i class="mdi mdi-plus-circle"></i> {{__("Extra Charges")}}
                                                    </th>
                                                    <th class="text-right text-info">
                                                        + <span>{{number_format($totalExtraFeeDetail, 2)}}</span>{{' '.$currencySymbol}}
                                                    </th>
                                                </tr>
                                            @endif
                                        
                                        @if(count($fees->installments))
                                            <tr class="pay-in-installment-row">
                                                <td class="text-left"></td>
                                                <td colspan="2" class="text-left">
                                                    <label for="pay-in-installment-chk">{{__("Pay in installment")}}</label>
                                                </td>
                                                <td class="text-right">
                                                    <input type="checkbox" id="pay-in-installment-chk" class="form-check-input pay-in-installment">
                                                </td>
                                            </tr>
                                        @endif

                                        @foreach($fees->installments as $key=>$installment)
                                            <tr class="installment_rows" style="display: none;">
                                                @if(!empty($installment->is_paid))
                                                    {{--If installment is paid--}}
                                                    <td colspan="4" class="installment-card-wrapper">
                                                        <div class="installment-card paid-card">
                                                            <span class="remove-installment-fees-paid card-remove-btn no-print" data-id="{{$installment->is_paid->id}}">
                                                                <i class="mdi mdi-close-circle"></i>
                                                            </span>
                                                            
                                                            <div class="installment-card-content">
                                                                <div class="installment-card-left">
                                                        <div class="installment-info-container">
                                                            <div class="installment-header">
                                                                <div class="installment-icon-box paid">
                                                                    <i class="mdi mdi-calendar-check"></i>
                                                                </div>
                                                                <div class="installment-details">
                                                                    <h5 class="installment-name paid">{{$installment->name}}</h5>
                                                                    <div class="installment-meta">
                                                                        <span class="paid-date">
                                                                            <i class="mdi mdi-check-circle"></i> {{__("paid_on")}} : {{$installment->is_paid->date}}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                @if(!empty($installment->is_paid->discount) && $installment->is_paid->discount > 0)
                                                                    <span class="modern-badge discount-badge">
                                                                        <i class="mdi mdi-sale"></i> {{__("Discount")}}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            @if(!empty($installment->is_paid->discount) && $installment->is_paid->discount > 0)
                                                                <div class="installment-extra-info">
                                                                    <span class="discount-info">
                                                                        <i class="mdi mdi-tag"></i> {{__("Discount")}}: {{$installment->is_paid->discount.' '.$currencySymbol}}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            @if(!empty($installment->is_paid->advance_fees))
                                                                <div class="installment-extra-info">
                                                                    @foreach($installment->is_paid->advance_fees as $advance)
                                                                        <span class="advance-info">
                                                                            <i class="mdi mdi-cash-plus"></i> {{__("Advance")}} ({{date('Y-m-d',strtotime($advance['created_at']))}})
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="installment-card-right">
                                                        @php
                                                            $advance_payment = 0;
                                                            if (!empty($installment->is_paid->advance_fees)) {
                                                                foreach($installment->is_paid->advance_fees as $advance)
                                                                {
                                                                    $advance_payment += $advance['amount'];
                                                                }
                                                            }
                                                            
                                                            // Get discount amount if exists
                                                            $discount_amount = $installment->is_paid->discount ?? 0;
                                                        @endphp
                                                        <div class="modern-installment-pricing">
                                                            <div class="main-amount-badge paid">
                                                                <span class="amount-value">{{($installment->is_paid->amount - $advance_payment)}}</span>
                                                                <span class="currency">{{$currencySymbol}}</span>
                                                            </div>
                                                            
                                                            <div class="pricing-breakdown">
                                                                @if($installment->is_paid->due_charges > 0)
                                                                    <div class="price-item due-charges">
                                                                        <i class="mdi mdi-clock-alert"></i>
                                                                        <span class="label">{{__("Due Charges")}}:</span>
                                                                        <span class="value">{{$installment->is_paid->due_charges.' '.$currencySymbol}}</span>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if($discount_amount > 0)
                                                                    <div class="price-item discount-applied">
                                                                        <i class="mdi mdi-sale"></i>
                                                                        <span class="label">{{__("Discount Applied")}}:</span>
                                                                        <span class="value">-{{$discount_amount.' '.$currencySymbol}}</span>
                                                                    </div>
                                                                @endif

                                                                @if(!empty($installment->is_paid->advance_fees))
                                                                    @foreach($installment->is_paid->advance_fees as $advance)
                                                                        <div class="price-item advance-payment">
                                                                            <i class="mdi mdi-cash-plus"></i>
                                                                            <span class="label">{{__("Advance")}}:</span>
                                                                            <span class="value">{{$advance['amount'].' '.$currencySymbol}}</span>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            
                                                            <div class="total-paid-section">
                                                                <div class="total-paid-badge">
                                                                    <i class="mdi mdi-check-circle-outline"></i>
                                                                    <span class="label">{{__("Total Paid")}}:</span>
                                                                    <span class="amount">{{$installment->is_paid->amount+$installment->is_paid->due_charges.' '.$currencySymbol}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        </div>
                                                    </td>
                                                @else
                                                    {{--If Installment is not Paid--}}
                                                    <td colspan="4" class="installment-card-wrapper">
                                                        <div class="installment-card unpaid-card {{$installment->due_charges_amount > 0 ? 'overdue-card' : ''}}">
                                                            <input type="checkbox" id="installment-fees-{{$key}}" name="installment_fees[{{$key}}][id]" class="installment-checkbox card-checkbox {{($installment->due_charges_amount > 0) ? 'default-checked-installment' : ''}}" value="{{$installment->id}}" data-amount="{{$installment->total_amount}}" data-base-amount="{{$installment->minimum_amount}}" data-key="{{$key}}" aria-label=""/>
                                                            <input type="hidden" name="installment_fees[{{$key}}][due_charges]" class="due-charges-amount" value="{{$installment->due_charges_amount}}" disabled/>
                                                            <input type="hidden" name="installment_fees[{{$key}}][amount]" class="installment-amount" value="{{$installment->minimum_amount}}" disabled/>
                                                            <input type="hidden" name="installment_fees[{{$key}}][discount]" class="installment-discount-amount" value="0" disabled/>
                                                            
                                                            <div class="installment-card-content">
                                                                <div class="installment-card-left">
                                                        <label for="installment-fees-{{$key}}" class="installment-label-clickable">
                                                            <div class="installment-info-container">
                                                                <div class="installment-header">
                                                                    <div class="installment-icon-box unpaid {{$installment->due_charges_amount > 0 ? 'overdue' : ''}}">
                                                                        <i class="mdi mdi-calendar-clock"></i>
                                                                    </div>
                                                                    <div class="installment-details">
                                                                        <h5 class="installment-name unpaid">{{$installment->name}}</h5>
                                                                        <div class="installment-meta">
                                                                            <span class="due-date {{$installment->due_charges_amount > 0 ? 'overdue' : ''}}">
                                                                                <i class="mdi mdi-calendar-outline"></i> {{__("Due date on").' '.$installment->due_date}}
                                                                            </span>
                                                                            <span class="charges-info {{$installment->due_charges_amount > 0 ? 'overdue' : ''}}">
                                                                                <i class="mdi mdi-alert-circle"></i> {{__("Charges").' '.$installment->due_charges}}
                                                                                {{$installment->due_charges_type=="percentage" ? "%" : $currencySymbol}}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                                </div>
                                                            </div>
                                                        
                                                        <!-- Installment-wise discount option -->
                                                        <div class="installment-discount-section mt-2" id="installment-discount-{{$key}}" style="display: none;">
                                                            <div class="input-group input-group-sm">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text bg-success text-white"><i class="mdi mdi-sale"></i></span>
                                                                </div>
                                                                <select class="form-control form-control-sm installment-discount-type" data-key="{{$key}}">
                                                                    <option value="">{{ __('No Discount') }}</option>
                                                                    <option value="percentage">{{ __('Percentage') }} (%)</option>
                                                                    <option value="fixed">{{ __('Fixed') }}</option>
                                                                </select>
                                                                <input type="number" class="form-control form-control-sm installment-discount-value" data-key="{{$key}}" placeholder="0" min="0" step="0.01" disabled>
                                                            </div>
                                                        </div>
                                                    
                                                    <div class="installment-card-right">
                                                        <div class="modern-installment-pricing unpaid">
                                                            <div class="main-amount-badge unpaid">
                                                                <span class="amount-value">{{round($installment->minimum_amount,2)}}</span>
                                                                <span class="currency">{{$currencySymbol}}</span>
                                                            </div>
                                                            
                                                            <div class="pricing-breakdown">
                                                                <div class="price-item charges">
                                                                    <i class="mdi mdi-clock-alert"></i>
                                                                    <span class="label">{{__("Charges")}}:</span>
                                                                    <span class="value">{{round($installment->due_charges_amount,2).' '.$currencySymbol}}</span>
                                                                </div>
                                                                
                                                                <div class="price-item discount installment-discount-display" style="display: none;">
                                                                    <i class="mdi mdi-sale"></i>
                                                                    <span class="label">{{__("Discount")}}:</span>
                                                                    <span class="value">-<span class="installment-discount-amount-text">0.00</span> {{$currencySymbol}}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="total-amount-section">
                                                                <div class="total-amount-badge">
                                                                    <span class="label">{{__("Total Amount")}}:</span>
                                                                    <span class="amount installment-final-amount">{{round($installment->total_amount,2).' '.$currencySymbol}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        
                                        @if ($isFullyPaid && $installment_status == 1)
                                            {{-- Show summary when all installments are paid --}}
                                            @php
                                                $totalDiscount = 0;
                                                $totalDueCharges = 0;
                                                $totalExtraFee = 0;
                                                foreach($fees->installments as $installment) {
                                                    if (!empty($installment->is_paid)) {
                                                        $totalDiscount += $installment->is_paid->discount ?? 0;
                                                        $totalDueCharges += $installment->is_paid->due_charges ?? 0;
                                                        $totalExtraFee += $installment->is_paid->extra_fee ?? 0;
                                                    }
                                                }
                                                // Calculate total paid amount: (Total Fees - Discount + Due Charges + Extra Fee)
                                                $totalPaidAmount = $fees->total_compulsory_fees - $totalDiscount + $totalDueCharges + $totalExtraFee;
                                            @endphp
                                            
                                            <tr class="installment-summary-row">
                                                <td colspan="4" class="p-0">
                                                    <div class="payment-summary-card mt-3 mb-3">
                                                        <div class="summary-header">
                                                            <i class="mdi mdi-check-circle-outline"></i>
                                                            <h5 class="mb-0">{{ __('Payment Summary') }}</h5>
                                                        </div>
                                                        <div class="summary-content">
                                                            <div class="summary-row">
                                                                <div class="summary-label">
                                                                    <i class="mdi mdi-cash-multiple text-primary"></i>
                                                                    <span>{{ __('Total Compulsory Fees') }}</span>
                                                                </div>
                                                                <div class="summary-value text-primary">
                                                                    {{$fees->total_compulsory_fees.' '.$currencySymbol}}
                                                                </div>
                                                            </div>
                                                            
                                                            @if($totalDiscount > 0)
                                                            <div class="summary-row discount-row">
                                                                <div class="summary-label">
                                                                    <i class="mdi mdi-sale text-success"></i>
                                                                    <span>{{ __('Total Discount') }}</span>
                                                                </div>
                                                                <div class="summary-value text-success">
                                                                    -{{number_format($totalDiscount, 2).' '.$currencySymbol}}
                                                                </div>
                                                            </div>
                                                            @endif
                                                            
                                                            @if($totalDueCharges > 0)
                                                            <div class="summary-row due-charges-row">
                                                                <div class="summary-label">
                                                                    <i class="mdi mdi-clock-alert text-danger"></i>
                                                                    <span>{{ __('Total Due Charges') }}</span>
                                                                </div>
                                                                <div class="summary-value text-danger">
                                                                    +{{number_format($totalDueCharges, 2).' '.$currencySymbol}}
                                                                </div>
                                                            </div>
                                                            @endif
                                                            
                                                            @if($totalExtraFee > 0)
                                                            <div class="summary-row extra-fee-row">
                                                                <div class="summary-label">
                                                                    <i class="mdi mdi-plus-circle text-info"></i>
                                                                    <span>{{ __('Total Extra Charges') }}</span>
                                                                </div>
                                                                <div class="summary-value text-info">
                                                                    +{{number_format($totalExtraFee, 2).' '.$currencySymbol}}
                                                                </div>
                                                            </div>
                                                            @endif
                                                            
                                                            <div class="summary-row total-row">
                                                                <div class="summary-label">
                                                                    <i class="mdi mdi-check-all text-success"></i>
                                                                    <span><strong>{{ __('Total Paid Amount') }}</strong></span>
                                                                </div>
                                                                <div class="summary-value total-value">
                                                                    {{number_format($totalPaidAmount, 2).' '.$currencySymbol}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            {{-- Show enter amount field when installments are not fully paid --}}
                                            <tr class="installment_rows" style="display: none;">
                                                <td class="text-left"></td>
                                                <td colspan="2" class="text-left"><label>{{__("enter_amount")}}</label></td>
                                                <td class="justify-content-end row">
                                                    <input type="number" id="advance" name="advance" aria-label="" class="form-control enter_amount col-6 text-right " min="0" max="{{$fees->remaining_amount}}" value="{{$fees->remaining_amount}}" {{!$oneInstallmentPaid ? "disabled" : ""}} placeholder="{{ __('enter_amount') }}"/>
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($due_charges && !count($fees->installments) && !$isFullyPaid)
                                            <tr class="due-charges-row">
                                                <td class="text-center no-print"><i class="mdi mdi-alert-circle text-danger"></i></td>
                                                <th colspan="2">
                                                    <i class="mdi mdi-clock-alert"></i> {{ __('due_charges') }} 
                                                    <span class="badge badge-danger ml-2">{{ __('Due Date') }}: {{ $fees->due_date }}</span>
                                                </th>
                                                <td class="text-right">
                                                    <span class="amount-badge-danger">{{ $due_charges }} {{' '.$currencySymbol}}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if (!($isFullyPaid && $installment_status == 1))
                                            <tr>
                                                <td class="text-left"></td>
                                                <th colspan="2" class="text-left"><label>{{__("Total Amount")}}</label></th>
                                                <th class="text-right">
                                                    <span id="total_amount_text">
                                                        @php
                                                            // Calculate total: Fees - Discount + Due Charges + Extra Fee
                                                            $calculatedTotal = $fees->total_compulsory_fees - $totalDiscountDetail + $totalDueChargesDetail + $totalExtraFeeDetail;
                                                        @endphp
                                                        {{number_format($calculatedTotal, 2)}}
                                                    </span>{{' '.$currencySymbol}}
                                                </th>
                                            </tr>
                                        @endif

                                        <tr id="discount_row" style="display: none;">
                                            <td class="text-left"></td>
                                            <th colspan="2" class="text-left text-success">
                                                <label><i class="mdi mdi-minus-circle"></i> {{__("Discount")}}</label>
                                            </th>
                                            <th class="text-right text-success">
                                                - <span id="discount_amount_display">0.00</span>{{' '.$currencySymbol}}
                                            </th>
                                        </tr>

                                        <tr id="extras_row" style="display: none;">
                                            <td class="text-left"></td>
                                            <th colspan="2" class="text-left text-info">
                                                <label><i class="mdi mdi-plus-circle"></i> {{__("Additional Charges")}}</label>
                                                <br><small id="extras_description_display" class="text-muted"></small>
                                            </th>
                                            <th class="text-right text-info">
                                                + <span id="extras_amount_display">0.00</span>{{' '.$currencySymbol}}
                                            </th>
                                        </tr>

                                        <tr id="final_amount_row" style="display: none;">
                                            <td class="text-left"></td>
                                            <th colspan="2" class="text-left">
                                                <label class="text-primary"><i class="mdi mdi-cash-multiple"></i> {{__("Final Payment Amount")}}</label>
                                            </th>
                                            <th class="text-right">
                                                <h5 class="mb-0 text-primary"><strong><span id="final_amount_text">{{$fees->total_compulsory_fees + $due_charges}}</span>{{' '.$currencySymbol}}</strong></h5>
                                            </th>
                                        </tr>

                                        @if ($student->fees_paid && !$isFullyPaid && $installment_status == 0)
                                            <tr class="without_installment_enter_amount section-header-row">
                                                <th colspan="4" class="bg-light">
                                                    <i class="mdi mdi-history"></i> {{ __('fees_paid_records') }}
                                                </th>
                                            </tr>
                                            <tr class="without_installment_enter_amount table-subheader">
                                                <th class="no-print"></th>
                                                <th>{{ __('date') }}</th>
                                                <th>{{ __('cheque_no') }}</th>
                                                <th class="text-right">{{ __('Amount') }}</th>
                                            </tr>
                                            @foreach ($student->fees_paid->compulsory_fee as $fees)
                                                <tr class="without_installment_enter_amount fees-paid-row">
                                                    <td class="no-print">
                                                        <span class="remove-installment-fees-paid text-left" title="{{ __('delete') }}" data-id="{{ $fees->id }}">
                                                            <i class="fa fa-times text-danger" style="cursor:pointer" aria-hidden="true"></i>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <i class="mdi mdi-calendar-check text-success"></i>
                                                        {{ $fees->date }}
                                                    </td>
                                                    <td>
                                                        @if($fees->cheque_no)
                                                            <i class="mdi mdi-checkbook"></i> {{ $fees->cheque_no }}
                                                        @else
                                                            <span class="text-muted">{{ __('Cash Payment') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="amount-badge-paid">{{ $fees->amount }} {{' '.$currencySymbol}}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        
                                        @if ($isFullyPaid && $installment_status == 0)
                                            {{-- Show fully paid message for non-installment fees --}}
                                            <tr>
                                                <td colspan="4" class="p-0">
                                                    <div class="alert alert-success m-3" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 2px solid #28a745; border-radius: 12px;">
                                                        <div class="d-flex align-items-center">
                                                            <div style="font-size: 48px; color: #28a745; margin-right: 20px;">
                                                                <i class="mdi mdi-check-circle"></i>
                                                            </div>
                                                            <div>
                                                                <h4 class="mb-2" style="color: #155724; font-weight: bold;">
                                                                    <i class="mdi mdi-check-all"></i> {{ __('Fees Fully Paid') }}
                                                                </h4>
                                                                <p class="mb-0" style="color: #155724;">
                                                                    {{ __('All compulsory fees including due charges have been successfully paid.') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @elseif (!$isFullyPaid && $installment_status == 0)
                                        <tr class="without_installment_enter_amount">
                                            <td class="text-left"></td>
                                            <th colspan="2" class="text-left"><label>{{__("enter_amount")}} <span class="text-danger">*</span></label></th>
                                            <td class="text-right"><span id="total_amount_text">
                                                @if ($student->fees_paid)
                                                    <input type="number" name="enter_amount" min="1" class="form-control" max="{{ ($total_compulsory_fees - $student->fees_paid->compulsory_fee_sum_amount + $due_charges) }}" id="enter_amount" value="{{ ($total_compulsory_fees - $student->fees_paid->compulsory_fee_sum_amount + $due_charges) }}" placeholder="{{ __('enter_amount') }}">
                                                @else
                                                    <input type="number" name="enter_amount" min="1" class="form-control" max="{{ $total_compulsory_fees + $due_charges }}" id="enter_amount" value="{{ $total_compulsory_fees + $due_charges }}" placeholder="{{ __('enter_amount') }}">
                                                @endif
                                                
                                            </td>
                                        </tr>
                                        @endif
                                        

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Print Footer (hidden on screen) -->
                            <div class="print-footer" style="display: none;">
                                <hr>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <p><strong>{{ __('Paid By') }}:</strong> _______________________</p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <p><strong>{{ __('Authorized Signature') }}:</strong> _______________________</p>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <small class="text-muted">{{ __('Generated on') }}: {{ date('d-m-Y h:i A') }}</small>
                                </div>
                            </div>
                            
                            </div><!-- End printable-area -->
                            
                            <hr class="my-4 no-print">
                            
                            @if (!$isFullyPaid)
                                <!-- Discount Section -->
                                <div class="card modern-discount-card mb-4 border-0 shadow-sm">
                                    <div class="card-header modern-discount-header bg-gradient-success text-white py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="discount-icon-box mr-3">
                                                <i class="mdi mdi-sale"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 font-weight-bold">{{ __('Discount & Additional Details') }}</h5>
                                                <small class="opacity-90">Apply discounts or add custom charges</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="alert modern-alert alert-info alert-dismissible fade show" role="alert">
                                            <div class="d-flex align-items-start">
                                                <i class="mdi mdi-information-outline mr-2" style="font-size: 24px;"></i>
                                                <div>
                                                    <strong>{{ __('Note') }}:</strong> 
                                                    {{ __('For full payment, use discount fields below. For installment payments, individual discounts can be applied to each selected installment.') }}
                                                </div>
                                            </div>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group modern-input-group">
                                                    <label class="modern-label"><i class="mdi mdi-tag-outline text-success"></i> {{ __('Discount Type') }}</label>
                                                    <select name="discount_type" id="discount_type" class="form-control modern-select">
                                                        <option value="">{{ __('No Discount') }}</option>
                                                        <option value="percentage">{{ __('Percentage') }} (%)</option>
                                                        <option value="fixed">{{ __('Fixed Amount') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group modern-input-group">
                                                    <label for="discount_value" class="modern-label"><i class="mdi mdi-calculator text-success"></i> {{ __('Discount Value') }}</label>
                                                    <div class="input-group modern-input-group-style">
                                                        <input type="number" name="discount_value" id="discount_value" class="form-control modern-input" placeholder="0" min="0" step="0.01" disabled value="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text modern-input-append" id="discount_symbol">-</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group modern-input-group">
                                                    <label class="modern-label"><i class="mdi mdi-cash-minus text-success"></i> {{ __('Discount Amount') }}</label>
                                                    <input type="text" id="calculated_discount" class="form-control modern-input calculated-result" value="0 {{$currencySymbol}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group modern-input-group">
                                                    <label for="remarks" class="modern-label"><i class="mdi mdi-note-text text-primary"></i> {{ __('Remarks / Notes') }}</label>
                                                    <textarea name="remarks" id="remarks" class="form-control modern-textarea" rows="3" placeholder="{{ __('Add any additional notes or remarks here...') }}"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Extras/Additional Charges Section -->
                                <div class="card modern-extras-card mb-4 border-0 shadow-sm">
                                    <div class="card-header modern-extras-header bg-gradient-info text-white py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="extras-icon-box mr-3">
                                                <i class="mdi mdi-plus-circle"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 font-weight-bold">{{ __('Additional Charges / Extras') }}</h5>
                                                <small class="opacity-90">({{ __('Optional') }}) - Add any extra charges to the payment</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="form-group modern-input-group">
                                                    <label class="modern-label"><i class="mdi mdi-text text-info"></i> {{ __('Description') }}</label>
                                                    <input type="text" name="extra_fee_name" id="extra_fee_name" class="form-control modern-input" placeholder="{{ __('E.g., Late Fee, Transport, Books, Uniform, etc.') }}">
                                                    <small class="form-text text-muted mt-2">
                                                        <i class="mdi mdi-information-outline"></i> {{ __('Enter description for any additional charges') }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group modern-input-group">
                                                    <label class="modern-label"><i class="mdi mdi-currency-usd text-info"></i> {{ __('Amount') }} ({{$currencySymbol}})</label>
                                                    <div class="input-group modern-input-group-style">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text modern-input-prepend bg-info text-white"><strong>+</strong></span>
                                                        </div>
                                                        <input type="number" name="extra_fee" id="extra_fee" class="form-control modern-input" placeholder="0.00" min="0" step="0.01" value="0">
                                                    </div>
                                                    <small class="form-text text-info font-weight-bold mt-2">
                                                        <i class="mdi mdi-information-outline"></i> {{ __('Direct amount (not %) - will be added to total') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- No Payment Required Message -->
                                <div class="modern-success-alert mb-4" id="no-payment-message" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <div class="success-icon-box mr-3">
                                            <i class="mdi mdi-check-circle"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1 font-weight-bold text-success">{{ __('No Payment Required') }}</h5>
                                            <p class="mb-0 text-muted">
                                                <i class="mdi mdi-information-outline"></i> {{ __('The current payment amount is 0. No payment method selection is required.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payment Mode Section -->
                                <div class="card modern-payment-card border-0 shadow-lg mb-4" id="payment-mode-section">
                                    <div class="card-header bg-gradient-payment text-white py-3">
                                        <h5 class="mb-0">
                                            <i class="mdi mdi-credit-card-outline"></i> {{ __('Select Payment Mode') }} 
                                            <span class="badge badge-light text-dark ml-2">{{ __('Required') }}</span>
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row mode-container">
                                            <div class="col-md-12">
                                                <div class="payment-mode-options">
                                                    <!-- Cash Option -->
                                                    <div class="payment-mode-card">
                                                        <input type="radio" name="mode" id="mode_cash" class="cash-compulsory-mode mode" value="1" checked>
                                                        <label for="mode_cash" class="payment-mode-label">
                                                            <div class="payment-mode-icon cash-icon">
                                                                <i class="mdi mdi-cash-multiple"></i>
                                                            </div>
                                                            <div class="payment-mode-text">
                                                                <strong>{{ __('Cash Payment') }}</strong>
                                                                <small>{{ __('Pay with cash') }}</small>
                                                            </div>
                                                            <div class="payment-mode-check">
                                                                <i class="mdi mdi-check-circle"></i>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    
                                                    <!-- Cheque Option -->
                                                    <div class="payment-mode-card">
                                                        <input type="radio" name="mode" id="mode_cheque" class="cheque-compulsory-mode mode" value="2">
                                                        <label for="mode_cheque" class="payment-mode-label">
                                                            <div class="payment-mode-icon cheque-icon">
                                                                <i class="mdi mdi-checkbook"></i>
                                                            </div>
                                                            <div class="payment-mode-text">
                                                                <strong>{{ __('Cheque Payment') }}</strong>
                                                                <small>{{ __('Pay by cheque') }}</small>
                                                            </div>
                                                            <div class="payment-mode-check">
                                                                <i class="mdi mdi-check-circle"></i>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    
                                                    <!-- UPI Option -->
                                                    <div class="payment-mode-card">
                                                        <input type="radio" name="mode" id="mode_upi" class="upi-compulsory-mode mode" value="3">
                                                        <label for="mode_upi" class="payment-mode-label">
                                                            <div class="payment-mode-icon upi-icon">
                                                                <i class="mdi mdi-qrcode-scan"></i>
                                                            </div>
                                                            <div class="payment-mode-text">
                                                                <strong>{{ __('UPI Payment') }}</strong>
                                                                <small>{{ __('Pay via UPI') }}</small>
                                                            </div>
                                                            <div class="payment-mode-check">
                                                                <i class="mdi mdi-check-circle"></i>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Cheque Details -->
                                        <div class="row cheque-details-container mt-4" style="display: none;">
                                            <div class="col-md-12">
                                                <div class="payment-details-box">
                                                    <h6 class="mb-3"><i class="mdi mdi-information"></i> {{ __('Cheque Details') }}</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="cheque_no"><i class="mdi mdi-numeric"></i> {{ __('Cheque Number') }} <span class="text-danger">*</span></label>
                                                                <input type="text" id="cheque_no" name="cheque_no" placeholder="{{ __('Enter cheque number') }}" class="form-control cheque-no"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="bank_name"><i class="mdi mdi-bank"></i> {{ __('Bank Name') }}</label>
                                                                <input type="text" id="bank_name" name="bank_name" placeholder="{{ __('Enter bank name') }}" class="form-control"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="cheque_date"><i class="mdi mdi-calendar"></i> {{ __('Cheque Date') }}</label>
                                                                <input type="text" id="cheque_date" name="cheque_date" placeholder="{{ __('Select date') }}" class="form-control datepicker-popup"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- UPI Details -->
                                        <div class="row upi-details-container mt-4" style="display: none;">
                                            <div class="col-md-12">
                                                <div class="payment-details-box upi-box">
                                                    <h6 class="mb-3"><i class="mdi mdi-qrcode"></i> {{ __('UPI Payment Details') }}</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="upi_id"><i class="mdi mdi-at"></i> {{ __('UPI ID / VPA') }} <span class="text-danger">*</span></label>
                                                                <input type="text" id="upi_id" name="upi_id" placeholder="{{ __('example@upi') }}" class="form-control"/>
                                                                <small class="form-text text-muted">{{ __('Enter your UPI ID (e.g., 9876543210@paytm)') }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="transaction_id"><i class="mdi mdi-key-variant"></i> {{ __('Transaction ID / Reference No') }} <span class="text-danger">*</span></label>
                                                                <input type="text" id="transaction_id" name="transaction_id" placeholder="{{ __('Enter transaction ID') }}" class="form-control"/>
                                                                <small class="form-text text-muted">{{ __('12-digit UPI transaction reference number') }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="form-group text-center" id="submit-button-section">
                                    <button class="btn modern-submit-btn btn-lg px-5 py-3" type="submit">
                                        <i class="mdi mdi-check-circle-outline mr-2"></i> 
                                        <span>{{ __('Process Payment') }}</span>
                                        <i class="mdi mdi-arrow-right ml-2"></i>
                                    </button>
                                    <p class="text-muted mt-3 mb-0">
                                        <i class="mdi mdi-shield-check"></i> {{ __('Secure and encrypted payment processing') }}
                                    </p>
                                </div>
                            @endif
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        $('#payment-date').datepicker({
            format: "dd-mm-yyyy",
            rtl: isRTL()
        }).datepicker("setDate", 'now');

        @if($fees->include_fee_installments)
        setTimeout(() => {
            $('#advance').trigger('change');
        }, 1000);
        @endif
        

        // @if($student->fees_paid && $student->fees_paid->is_used_installment)
        // $('.pay-in-installment').trigger('click').attr("disabled", true);
        // @endif

        @if($student->fees_paid)
        $('.pay-in-installment').trigger('click').attr("disabled", true);
        @endif

        function successFunction() {
            window.location.href = "{{route('fees.paid.index')}}";
        }

        // Print functionality
        function printFeeDetails() {
            // Hide all no-print elements
            $('.no-print').hide();
            
            // Show print-only elements
            $('.print-header, .print-footer').show();
            
            // Store the original page content
            var originalContent = document.body.innerHTML;
            var printableArea = document.getElementById('printable-area').innerHTML;
            
            // Replace body content with printable area
            document.body.innerHTML = printableArea;
            
            // Trigger print
            window.print();
            
            // Restore original content
            document.body.innerHTML = originalContent;
            
            // Restore the page functionality
            location.reload();
        }

        // Payment mode and extras setup
        $(document).ready(function() {
            // Function to check payment amount and show/hide payment sections
            function checkPaymentAmount() {
                var finalAmount = parseFloat($('#total-amount').val()) || 0;
                
                if (finalAmount <= 0) {
                    // Hide payment mode and submit button if amount is 0 or less
                    $('#payment-mode-section').fadeOut();
                    $('#submit-button-section').fadeOut();
                    $('#no-payment-message').fadeIn();
                } else {
                    // Show payment mode and submit button if amount is greater than 0
                    $('#payment-mode-section').fadeIn();
                    $('#submit-button-section').fadeIn();
                    $('#no-payment-message').fadeOut();
                }
            }
            
            // Make function globally available
            window.checkPaymentAmount = checkPaymentAmount;
            
            // Check on page load
            checkPaymentAmount();
            
            // Payment mode handling
            $('.mode').on('change', function() {
                var selectedMode = $(this).val();
                
                // Hide all detail containers
                $('.cheque-details-container, .upi-details-container').slideUp();
                
                // Show relevant container
                if (selectedMode == '2') { // Cheque
                    $('.cheque-details-container').slideDown();
                } else if (selectedMode == '3') { // UPI
                    $('.upi-details-container').slideDown();
                }
                
                // Add visual feedback
                $('.payment-mode-card').removeClass('selected');
                $(this).closest('.payment-mode-card').addClass('selected');
            });
            
            // Initialize cheque date picker
            $('#cheque_date').datepicker({
                format: "dd-mm-yyyy",
                rtl: isRTL()
            });
        });

        // Discount functionality
        $(document).ready(function() {
            // Store original total amount
            var originalTotalAmount = parseFloat($('#total_amount_text').text()) || 0;
            var isInstallmentMode = false;
            
            // Check if installment mode is active
            $('.pay-in-installment').on('change', function() {
                isInstallmentMode = $(this).is(':checked');
                
                if (isInstallmentMode) {
                    // Disable full payment discount when in installment mode
                    $('#discount_type').prop('disabled', true).val('').trigger('change');
                    $('.card.bg-light').hide();
                    updateInstallmentTotals();
                } else {
                    // Enable full payment discount
                    $('#discount_type').prop('disabled', false);
                    $('.card.bg-light').show();
                    updateDiscountCalculation();
                }
            });
            
            // Installment checkbox change handler
            $('.installment-checkbox').on('change', function() {
                var key = $(this).data('key');
                var isChecked = $(this).is(':checked');
                
                // Show/hide discount section for this installment
                if (isChecked) {
                    $('#installment-discount-' + key).slideDown();
                } else {
                    $('#installment-discount-' + key).slideUp();
                    // Reset discount for this installment
                    $('.installment-discount-type[data-key="' + key + '"]').val('').trigger('change');
                }
                
                updateInstallmentTotals();
            });
            
            // Installment discount type change handler
            $(document).on('change', '.installment-discount-type', function() {
                var key = $(this).data('key');
                var discountType = $(this).val();
                var $valueInput = $('.installment-discount-value[data-key="' + key + '"]');
                
                if (discountType === '' || discountType === 'none') {
                    $valueInput.prop('disabled', true).val('0');
                } else {
                    $valueInput.prop('disabled', false);
                }
                
                calculateInstallmentDiscount(key);
            });
            
            // Installment discount value change handler
            $(document).on('input', '.installment-discount-value', function() {
                var key = $(this).data('key');
                calculateInstallmentDiscount(key);
            });
            
            // Calculate discount for specific installment
            function calculateInstallmentDiscount(key) {
                var $checkbox = $('#installment-fees-' + key);
                if (!$checkbox.is(':checked')) return;
                
                var discountType = $('.installment-discount-type[data-key="' + key + '"]').val();
                var discountValue = parseFloat($('.installment-discount-value[data-key="' + key + '"]').val()) || 0;
                var baseAmount = parseFloat($checkbox.data('base-amount')) || 0;
                var totalAmount = parseFloat($checkbox.data('amount')) || 0;
                var discountAmount = 0;
                
                if (discountType === 'percentage') {
                    if (discountValue > 100) {
                        discountValue = 100;
                        $('.installment-discount-value[data-key="' + key + '"]').val(100);
                    }
                    discountAmount = (totalAmount * discountValue) / 100;
                } else if (discountType === 'fixed') {
                    if (discountValue > totalAmount) {
                        discountValue = totalAmount;
                        $('.installment-discount-value[data-key="' + key + '"]').val(discountValue.toFixed(2));
                    }
                    discountAmount = discountValue;
                }
                
                var finalAmount = totalAmount - discountAmount;
                
                // Update UI for this installment
                var $row = $checkbox.closest('tr');
                if (discountAmount > 0) {
                    $row.find('.installment-discount-display').show();
                    $row.find('.installment-discount-amount-text').text(discountAmount.toFixed(2));
                } else {
                    $row.find('.installment-discount-display').hide();
                }
                $row.find('.installment-final-amount').text(finalAmount.toFixed(2) + ' {{$currencySymbol}}');
                
                // Update hidden field
                $row.find('.installment-discount-amount').val(discountAmount.toFixed(2));
                $row.find('.installment-amount').val(finalAmount.toFixed(2));
                
                updateInstallmentTotals();
            }
            
            // Update installment totals
            function updateInstallmentTotals() {
                var totalInstallmentAmount = 0;
                var totalDiscountAmount = 0;
                var extrasAmount = parseFloat($('#extra_fee').val()) || 0;
                
                $('.installment-checkbox:checked').each(function() {
                    var $row = $(this).closest('tr');
                    var amount = parseFloat($(this).data('amount')) || 0;
                    var discount = parseFloat($row.find('.installment-discount-amount').val()) || 0;
                    
                    totalInstallmentAmount += amount;
                    totalDiscountAmount += discount;
                });
                
                var finalTotal = totalInstallmentAmount - totalDiscountAmount + extrasAmount;
                
                // Update total amount display
                if (isInstallmentMode && $('.installment-checkbox:checked').length > 0) {
                    $('#total_amount_text').text(totalInstallmentAmount.toFixed(2));
                    
                    if (totalDiscountAmount > 0 || extrasAmount > 0) {
                        if (totalDiscountAmount > 0) {
                            $('#discount_amount_display').text(totalDiscountAmount.toFixed(2));
                            $('#discount_row').fadeIn();
                        } else {
                            $('#discount_row').fadeOut();
                        }
                        $('#final_amount_text').text(finalTotal.toFixed(2));
                        $('#final_amount_row').fadeIn();
                    } else {
                        $('#discount_row').fadeOut();
                        $('#final_amount_row').fadeOut();
                    }
                    
                    $('#discount_amount').val(totalDiscountAmount.toFixed(2));
                    $('#total-amount').val(finalTotal.toFixed(2));
                    
                    // Check if we need to show/hide payment sections
                    if (typeof window.checkPaymentAmount === 'function') {
                        window.checkPaymentAmount();
                    }
                }
            }
            
            // Discount type change handler (Full Payment)
            $('#discount_type').on('change', function() {
                if (isInstallmentMode) return; // Skip if in installment mode
                
                var discountType = $(this).val();
                var $discountValue = $('#discount_value');
                var $discountSymbol = $('#discount_symbol');
                
                if (discountType === '' || discountType === 'none') {
                    $discountValue.prop('disabled', true).val('0');
                    $discountSymbol.text('-');
                    updateDiscountCalculation();
                } else {
                    $discountValue.prop('disabled', false);
                    if (discountType === 'percentage') {
                        $discountSymbol.text('%');
                        $discountValue.attr('max', '100');
                    } else if (discountType === 'fixed') {
                        $discountSymbol.text('{{$currencySymbol}}');
                        $discountValue.attr('max', originalTotalAmount.toFixed(2));
                    }
                }
            });

            // Discount value change handler (Full Payment)
            $('#discount_value').on('input', function() {
                if (!isInstallmentMode) {
                    updateDiscountCalculation();
                }
            });
            
            // Extra fee amount change handler
            $('#extra_fee').on('input', function() {
                var extraFeeAmount = parseFloat($(this).val()) || 0;
                var extraFeeName = $('#extra_fee_name').val() || '';
                
                // Update extras display in table
                if (extraFeeAmount > 0) {
                    $('#extras_amount_display').text(extraFeeAmount.toFixed(2));
                    $('#extras_description_display').text(extraFeeName || 'Additional Charges');
                    $('#extras_row').fadeIn();
                } else {
                    $('#extras_row').fadeOut();
                }
                
                if (!isInstallmentMode) {
                    updateDiscountCalculation();
                } else {
                    updateInstallmentTotals();
                }
            });
            
            // Extra fee name change handler
            $('#extra_fee_name').on('input', function() {
                var extraFeeName = $(this).val() || '';
                var extraFeeAmount = parseFloat($('#extra_fee').val()) || 0;
                
                if (extraFeeAmount > 0) {
                    $('#extras_description_display').text(extraFeeName || 'Additional Charges');
                }
            });

            // Function to calculate and update discount (Full Payment)
            function updateDiscountCalculation() {
                if (isInstallmentMode) return; // Skip if in installment mode
                
                var discountType = $('#discount_type').val();
                var discountValue = parseFloat($('#discount_value').val()) || 0;
                var totalAmount = originalTotalAmount;
                var discountAmount = 0;
                var extrasAmount = parseFloat($('#extra_fee').val()) || 0;

                if (discountType === 'percentage') {
                    if (discountValue > 100) {
                        discountValue = 100;
                        $('#discount_value').val(100);
                    }
                    discountAmount = (totalAmount * discountValue) / 100;
                } else if (discountType === 'fixed') {
                    if (discountValue > totalAmount) {
                        discountValue = totalAmount;
                        $('#discount_value').val(discountValue.toFixed(2));
                    }
                    discountAmount = discountValue;
                }

                // Calculate final amount after discount and adding extras (direct amount, not percentage)
                var finalAmount = totalAmount - discountAmount + extrasAmount;
                
                // Debug log to verify calculation
                console.log('Payment Calculation:', {
                    'Total Fees': totalAmount,
                    'Discount': -discountAmount,
                    'Extras (Direct Amount)': +extrasAmount,
                    'Final Amount': finalAmount
                });

                // Update discount amount hidden field
                $('#discount_amount').val(discountAmount.toFixed(2));

                // Update calculated discount display in discount section
                $('#calculated_discount').val(discountAmount.toFixed(2) + ' {{$currencySymbol}}');

                // Update discount amount in table
                $('#discount_amount_display').text(discountAmount.toFixed(2));
                
                // Update final amount in table
                $('#final_amount_text').text(finalAmount.toFixed(2));

                // Show/hide discount and final amount rows with animation
                if (discountAmount > 0 || extrasAmount > 0) {
                    if (discountAmount > 0) {
                        $('#discount_row').fadeIn();
                    }
                    $('#final_amount_row').fadeIn();
                    
                    // Add pulse animation to final amount
                    $('#final_amount_text').addClass('discount-active');
                    setTimeout(function() {
                        $('#final_amount_text').removeClass('discount-active');
                    }, 500);
                } else {
                    $('#discount_row').fadeOut();
                    $('#final_amount_row').fadeOut();
                }

                // Update enter amount field if it exists
                var enterAmount = $('#enter_amount');
                if (enterAmount.length) {
                    enterAmount.val(finalAmount.toFixed(2));
                    enterAmount.attr('max', finalAmount.toFixed(2));
                    
                    // Also update the min to prevent payment less than discounted amount
                    if (finalAmount > 0) {
                        enterAmount.attr('min', '1');
                    }
                }

                // Update total amount in hidden field to reflect discounted amount and extras
                $('#total-amount').val(finalAmount.toFixed(2));
                
                // Check if we need to show/hide payment sections
                if (typeof window.checkPaymentAmount === 'function') {
                    window.checkPaymentAmount();
                }
            }
            
            // Initialize first payment mode as selected
            $('.payment-mode-card:first-child').addClass('selected');

            // Add custom styling for better UX
            $('input[type="radio"]').on('change', function() {
                $(this).closest('.form-check').addClass('active').siblings().removeClass('active');
            });
        });
    </script>

    <style>
        /* ===== MODERN DESIGN SYSTEM ===== */
        
        /* General Styling */
        body {
            background-color: #f8f9fc;
        }
        
        .content-wrapper {
            padding: 20px;
        }
        
        /* Modern Header */
        .modern-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .modern-header .page-title {
            color: white;
            font-size: 28px;
            font-weight: 700;
        }
        
        .modern-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
        }
        
        .page-title-icon {
            width: 50px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Modern Card Styling */
        .modern-card {
            border-radius: 20px;
            overflow: hidden;
            background: white;
            transition: all 0.3s ease;
        }
        
        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        }
        
        .modern-card-header {
            border-radius: 0 !important;
            border-bottom: none;
        }
        
        .modern-btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 20px;
        }
        
        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        /* Student Info Card */
        .student-info-card {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border: 2px solid #667eea30;
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
        }
        
        .student-info-card:hover {
            border-color: #667eea;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
        }
        
        .student-avatar {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .student-id-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        /* Modern Form Inputs */
        .modern-input-group {
            margin-bottom: 25px;
        }
        
        .modern-label {
            font-weight: 600;
            font-size: 14px;
            color: #2d3748;
            margin-bottom: 10px;
            display: block;
        }
        
        .modern-input, .modern-select, .modern-textarea {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #f7fafc;
        }
        
        .modern-input:focus, .modern-select:focus, .modern-textarea:focus {
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 20px;
        }
        
        .modern-input-group-style .input-group-append .modern-input-append,
        .modern-input-group-style .input-group-prepend .modern-input-prepend {
            border: 2px solid #e2e8f0;
            border-radius: 0 10px 10px 0;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            font-weight: 600;
            color: #4a5568;
        }
        
        .modern-input-group-style .input-group-prepend .modern-input-prepend {
            border-radius: 10px 0 0 10px;
        }
        
        .calculated-result {
            background: linear-gradient(135deg, #f0fff4 0%, #e6ffed 100%) !important;
            border-color: #9ae6b4 !important;
            font-weight: 700;
            color: #22543d;
        }
        
        /* Modern Divider */
        .modern-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #e2e8f0 50%, transparent 100%);
            border: none;
        }
        
        /* Section Title Modern */
        .section-title-modern {
            position: relative;
        }
        
        .section-icon-box {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-right: 15px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        /* Discount Card */
        .modern-discount-card {
            overflow: hidden;
            border-radius: 15px;
        }
        
        .modern-discount-header {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border-bottom: none;
        }
        
        .discount-icon-box {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .modern-alert {
            border-radius: 10px;
            border-left: 4px solid #4299e1;
            background-color: #ebf8ff;
        }
        
        /* Extras Card */
        .modern-extras-card {
            overflow: hidden;
            border-radius: 15px;
        }
        
        .modern-extras-header {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            border-bottom: none;
        }
        
        .extras-icon-box {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        /* Success Alert */
        .modern-success-alert {
            background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
            border: 2px solid #9ae6b4;
            border-radius: 15px;
            padding: 25px;
        }
        
        .success-icon-box {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            flex-shrink: 0;
        }
        
        /* Payment Card */
        .modern-payment-card {
            border-radius: 20px;
            overflow: hidden;
        }
        
        /* Submit Button */
        .modern-submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .modern-submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .modern-submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }
        
        .modern-submit-btn:hover::before {
            left: 100%;
        }
        
        .modern-submit-btn:active {
            transform: translateY(-1px);
        }
        
        .border-left-primary {
            border-left: 4px solid #007bff;
        }
        
        .alert-info {
            background-color: #e7f3ff;
            border-color: #b3d9ff;
        }
        
        .card {
            border-radius: 10px;
            transition: box-shadow 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        /* Enhanced Table Styling */
        .fee-details-table {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .fee-details-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 18px 15px;
            letter-spacing: 0.5px;
        }
        
        .fee-details-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .fee-details-table tbody tr:last-child {
            border-bottom: none;
        }
        
        .fee-details-table tbody tr:hover {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .fee-details-table tbody td {
            padding: 15px;
            vertical-align: middle;
            font-size: 15px;
        }
        
        .fee-details-table tbody th {
            font-weight: 600;
            color: #2d3748;
        }
        
        /* Amount Badge Styling */
        .amount-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1.05rem;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        
        .amount-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
        }
        
        .amount-badge-paid {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 8px 18px;
            border-radius: 25px;
            font-weight: 700;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.4);
            transition: all 0.3s ease;
        }
        
        .amount-badge-paid:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(72, 187, 120, 0.5);
        }
        
        .amount-badge-danger {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1.05rem;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.4);
            transition: all 0.3s ease;
        }
        
        .amount-badge-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(245, 101, 101, 0.5);
        }
        
        /* Section Headers */
        .section-header-row th {
            background-color: #f8f9fa !important;
            color: #333;
            font-size: 1.1rem;
            padding: 15px !important;
            border-top: 2px solid #dee2e6 !important;
        }
        
        .table-subheader th {
            background-color: #e9ecef !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            padding: 10px !important;
        }
        
        /* Fees Paid Row */
        .fees-paid-row {
            background-color: #f9fff9;
        }
        
        .fees-paid-row:hover {
            background-color: #e8f5e9 !important;
        }
        
        /* Due Charges Row */
        .due-charges-row {
            background-color: #fff3cd;
            border-left: 4px solid #dc3545;
        }
        
        .due-charges-row:hover {
            background-color: #ffe8a1 !important;
        }
        
        .due-charges-row th {
            color: #721c24;
            font-weight: 600;
        }
        
        /* ===== MODERN INSTALLMENT CARD STYLING ===== */
        
        /* Card Wrapper */
        .installment-card-wrapper {
            padding: 16px 12px !important;
            border: none !important;
            background: transparent !important;
        }
        
        /* Installment Card */
        .installment-card {
            background: white;
            border-radius: 20px;
            padding: 28px 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: 2px solid;
            overflow: visible;
            margin-bottom: 8px;
        }
        
        .installment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, currentColor, transparent);
            border-radius: 16px 16px 0 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .installment-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
        }
        
        .installment-card:hover::before {
            opacity: 0.8;
        }
        
        /* Paid Card */
        .paid-card {
            border-color: rgba(16, 185, 129, 0.25);
            background: linear-gradient(135deg, #ffffff 0%, rgba(236, 253, 245, 0.5) 100%);
            color: #10b981;
        }
        
        .paid-card::before {
            background: linear-gradient(90deg, transparent, #10b981, transparent);
        }
        
        .paid-card::after {
            content: 'PAID';
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 4px 12px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-radius: 0 18px 0 12px;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
        }
        
        .paid-card:hover {
            border-color: rgba(16, 185, 129, 0.5);
            box-shadow: 0 12px 32px rgba(16, 185, 129, 0.18);
        }
        
        /* Unpaid Card */
        .unpaid-card {
            border-color: rgba(99, 102, 241, 0.25);
            background: linear-gradient(135deg, #ffffff 0%, rgba(238, 242, 255, 0.5) 100%);
            color: #6366f1;
        }
        
        .unpaid-card::before {
            background: linear-gradient(90deg, transparent, #6366f1, transparent);
        }
        
        .unpaid-card:not(.overdue-card)::after {
            content: 'PENDING';
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 4px 12px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-radius: 0 18px 0 12px;
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.3);
        }
        
        .unpaid-card:hover {
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 12px 32px rgba(99, 102, 241, 0.18);
        }
        
        /* Overdue Card */
        .overdue-card {
            border-color: rgba(239, 68, 68, 0.35);
            background: linear-gradient(135deg, #ffffff 0%, rgba(254, 242, 242, 0.6) 100%);
            color: #ef4444;
        }
        
        .overdue-card::before {
            background: linear-gradient(90deg, transparent, #ef4444, transparent);
        }
        
        .overdue-card::after {
            content: 'OVERDUE';
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 4px 12px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-radius: 0 18px 0 12px;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
            animation: pulse-badge 2s ease-in-out infinite;
        }
        
        @keyframes pulse-badge {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.85; }
        }
        
        .overdue-card:hover {
            border-color: rgba(239, 68, 68, 0.6);
            box-shadow: 0 12px 32px rgba(239, 68, 68, 0.25);
        }
        
        /* Card Content */
        .installment-card-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 32px;
        }
        
        .installment-card-left {
            flex: 1;
            min-width: 0;
            padding-right: 16px;
        }
        
        .installment-card-right {
            flex-shrink: 0;
            min-width: 220px;
        }
        
        /* Card Checkbox */
        .card-checkbox {
            position: absolute;
            top: 28px;
            left: 24px;
            width: 24px;
            height: 24px;
            cursor: pointer;
            z-index: 10;
            transform: scale(1.2);
        }
        
        .card-checkbox:checked ~ .installment-card-content {
            opacity: 1;
        }
        
        .unpaid-card .installment-card-content {
            padding-left: 40px;
        }
        
        /* Card Remove Button */
        .card-remove-btn {
            position: absolute;
            top: 18px;
            left: 20px;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.08);
            color: #ef4444;
            font-size: 20px;
            transition: all 0.25s ease;
            z-index: 10;
            border: 2px solid rgba(239, 68, 68, 0.15);
        }
        
        .card-remove-btn:hover {
            background: rgba(239, 68, 68, 0.15);
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 3px 10px rgba(239, 68, 68, 0.25);
            border-color: rgba(239, 68, 68, 0.3);
        }
        
        /* Adjust content padding for remove button */
        .paid-card .installment-card-content {
            padding-left: 60px;
        }
        
        /* Installment Info Container */
        .installment-info-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        /* Installment Header */
        .installment-header {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        
        /* Installment Icon Box */
        .installment-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
            position: relative;
            overflow: hidden;
        }
        
        .installment-icon-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .installment-icon-box:hover::before {
            left: 100%;
        }
        
        .installment-icon-box.paid {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .installment-icon-box.unpaid {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
        }
        
        .installment-icon-box.unpaid.overdue {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            animation: subtle-pulse 2s ease-in-out infinite;
        }
        
        @keyframes subtle-pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3); }
            50% { transform: scale(1.02); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4); }
        }
        
        /* Installment Details */
        .installment-details {
            flex: 1;
            min-width: 0;
        }
        
        /* Installment Name */
        .installment-name {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 5px 0;
            color: #1f2937;
            letter-spacing: 0.1px;
            line-height: 1.4;
        }
        
        .installment-name.paid {
            color: #065f46;
        }
        
        .installment-name.unpaid {
            color: #1f2937;
        }
        
        /* Installment Meta */
        .installment-meta {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        
        .installment-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12.5px;
            font-weight: 500;
            line-height: 1.5;
        }
        
        .paid-date {
            color: #059669;
        }
        
        .due-date {
            color: #3b82f6;
        }
        
        .due-date.overdue {
            color: #dc2626;
            font-weight: 600;
        }
        
        .charges-info {
            color: #6b7280;
            font-size: 12px;
        }
        
        .charges-info.overdue {
            color: #dc2626;
            font-weight: 600;
        }
        
        /* Modern Badge */
        .modern-badge {
            padding: 5px 11px;
            border-radius: 12px;
            font-size: 11.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.2px;
        }
        
        .modern-badge.discount-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .modern-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(16, 185, 129, 0.3);
        }
        
        /* Installment Extra Info */
        .installment-extra-info {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 6px;
            padding-left: 62px;
        }
        
        .discount-info,
        .advance-info {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 9px;
            border-radius: 10px;
            font-size: 11.5px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .discount-info {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .discount-info:hover {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.3);
        }
        
        .advance-info {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .advance-info:hover {
            background: rgba(59, 130, 246, 0.15);
            border-color: rgba(59, 130, 246, 0.3);
        }
        
        /* Modern Installment Pricing */
        .modern-installment-pricing {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-end;
            min-width: 220px;
            padding-left: 20px;
            border-left: 2px solid rgba(0, 0, 0, 0.06);
        }
        
        /* Main Amount Badge */
        .main-amount-badge {
            padding: 9px 18px;
            border-radius: 18px;
            display: inline-flex;
            align-items: baseline;
            gap: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .main-amount-badge::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .main-amount-badge:hover::after {
            left: 100%;
        }
        
        .main-amount-badge.paid {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .main-amount-badge.unpaid {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }
        
        .main-amount-badge .amount-value {
            font-size: 17px;
            font-weight: 700;
            color: white;
            letter-spacing: 0.2px;
            line-height: 1;
        }
        
        .main-amount-badge .currency {
            font-size: 12.5px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.95);
            opacity: 0.95;
        }
        
        .main-amount-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Pricing Breakdown */
        .pricing-breakdown {
            display: flex;
            flex-direction: column;
            gap: 6px;
            width: 100%;
        }
        
        .price-item {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 12px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid;
        }
        
        .price-item i {
            font-size: 14px;
            opacity: 0.85;
        }
        
        .price-item .label {
            font-weight: 500;
            font-size: 12px;
        }
        
        .price-item .value {
            font-weight: 700;
            font-size: 12px;
        }
        
        .price-item.due-charges,
        .price-item.charges {
            background: rgba(239, 68, 68, 0.08);
            border-left-color: #ef4444;
        }
        
        .price-item.due-charges .label,
        .price-item.charges .label {
            color: #991b1b;
        }
        
        .price-item.due-charges .value,
        .price-item.charges .value {
            color: #dc2626;
        }
        
        .price-item.discount-applied,
        .price-item.discount {
            background: rgba(16, 185, 129, 0.08);
            border-left-color: #10b981;
        }
        
        .price-item.discount-applied .label,
        .price-item.discount .label {
            color: #065f46;
        }
        
        .price-item.discount-applied .value,
        .price-item.discount .value {
            color: #059669;
        }
        
        .price-item.advance-payment {
            background: rgba(59, 130, 246, 0.08);
            border-left-color: #3b82f6;
        }
        
        .price-item.advance-payment .label {
            color: #1e40af;
        }
        
        .price-item.advance-payment .value {
            color: #2563eb;
        }
        
        .price-item:hover {
            transform: translateX(-3px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }
        
        /* Total Paid Section */
        .total-paid-section,
        .total-amount-section {
            width: 100%;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px dashed rgba(0, 0, 0, 0.1);
        }
        
        .total-paid-badge,
        .total-amount-badge {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding: 12px 18px;
            border-radius: 12px;
            background: rgba(16, 185, 129, 0.08);
            border: 2px solid rgba(16, 185, 129, 0.25);
            box-shadow: 0 3px 8px rgba(16, 185, 129, 0.12);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .total-paid-badge::before,
        .total-amount-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.5), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }
        
        .total-paid-badge:hover::before,
        .total-amount-badge:hover::before {
            transform: translateX(100%);
        }
        
        .total-amount-badge {
            background: rgba(99, 102, 241, 0.08);
            border-color: rgba(99, 102, 241, 0.25);
            box-shadow: 0 3px 8px rgba(99, 102, 241, 0.12);
        }
        
        .total-paid-badge i,
        .total-amount-badge i {
            font-size: 18px;
            color: #059669;
            opacity: 0.95;
        }
        
        .total-amount-badge i {
            color: #4f46e5;
        }
        
        .total-paid-badge .label,
        .total-amount-badge .label {
            font-size: 13px;
            font-weight: 600;
            color: #065f46;
            letter-spacing: 0.2px;
        }
        
        .total-amount-badge .label {
            color: #312e81;
        }
        
        .total-paid-badge .amount,
        .total-amount-badge .amount {
            font-size: 17px;
            font-weight: 800;
            color: #059669;
            letter-spacing: 0.3px;
            line-height: 1;
        }
        
        .total-amount-badge .amount {
            color: #4f46e5;
        }
        
        .total-paid-badge:hover,
        .total-amount-badge:hover {
            transform: translateY(-1px);
            border-color: rgba(16, 185, 129, 0.3);
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15);
            background: rgba(16, 185, 129, 0.08);
        }
        
        .total-amount-badge:hover {
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.15);
            background: rgba(99, 102, 241, 0.08);
        }
        
        /* Payment Summary Card */
        .payment-summary-card {
            background: linear-gradient(135deg, #ffffff 0%, rgba(236, 253, 245, 0.3) 100%);
            border: 2px solid rgba(16, 185, 129, 0.2);
            border-radius: 16px;
            padding: 0;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.08);
            overflow: hidden;
        }
        
        .summary-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 2px solid rgba(16, 185, 129, 0.3);
        }
        
        .summary-header i {
            font-size: 24px;
        }
        
        .summary-header h5 {
            font-weight: 700;
            letter-spacing: 0.3px;
            margin: 0;
        }
        
        .summary-content {
            padding: 24px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            margin-bottom: 10px;
            background: white;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        
        .summary-row:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .summary-row:last-child {
            margin-bottom: 0;
        }
        
        .summary-row.discount-row {
            background: rgba(16, 185, 129, 0.05);
            border-color: rgba(16, 185, 129, 0.2);
        }
        
        .summary-row.due-charges-row {
            background: rgba(239, 68, 68, 0.05);
            border-color: rgba(239, 68, 68, 0.2);
        }
        
        .summary-row.extra-fee-row {
            background: rgba(14, 165, 233, 0.05);
            border-color: rgba(14, 165, 233, 0.2);
        }
        
        .summary-row.total-row {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
            border: 2px solid rgba(16, 185, 129, 0.3);
            padding: 16px 18px;
            margin-top: 8px;
        }
        
        .summary-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }
        
        .summary-label i {
            font-size: 18px;
        }
        
        .summary-value {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        
        .summary-value.total-value {
            font-size: 20px;
            font-weight: 800;
            color: #059669;
        }
        
        /* Installment Label Clickable */
        .installment-label-clickable {
            cursor: pointer;
            margin: 0;
            width: 100%;
            transition: all 0.2s ease;
        }
        
        .installment-label-clickable:hover {
            opacity: 0.9;
        }
        
        /* Installment Price Cell */
        .installment-price-cell {
            padding: 12px 10px !important;
            vertical-align: middle !important;
        }
        
        /* Installment Discount Section */
        .installment-discount-section {
            background: rgba(16, 185, 129, 0.06);
            padding: 14px;
            border-radius: 12px;
            border: 2px solid rgba(16, 185, 129, 0.15);
            margin-top: 14px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08);
            transition: all 0.25s ease;
        }
        
        .installment-discount-section:hover {
            background: rgba(16, 185, 129, 0.1);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.25);
        }
        
        .installment-discount-section .input-group-text {
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: #065f46;
        }
        
        .installment-discount-section .form-control-sm {
            font-size: 0.85rem;
            border: 1.5px solid rgba(16, 185, 129, 0.2);
            background: white;
        }
        
        .installment-discount-section .form-control-sm:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }
        
        /* Table Row Enhancements for Cards */
        .fee-details-table tbody tr.installment_rows {
            background: transparent;
            transition: none;
        }
        
        .fee-details-table tbody tr.installment_rows:hover {
            background: transparent;
            transform: none;
        }
        
        .fee-details-table tbody tr.installment_rows td {
            border: none;
        }
        
        /* Better Alignment */
        .installment-header {
            align-items: center;
        }
        
        /* Smooth Transitions */
        .installment-info-container,
        .modern-installment-pricing {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .installment-amount-breakdown {
            line-height: 2;
        }
        
        .installment-amount-breakdown small {
            display: block;
            margin-top: 5px;
            padding: 3px 8px;
            border-radius: 4px;
        }
        
        .installment-amount-breakdown small.text-success {
            background-color: #d4edda;
            border-left: 3px solid #28a745;
        }
        
        .installment-amount-breakdown small.text-muted {
            background-color: #f8f9fa;
            border-left: 3px solid #6c757d;
        }
        
        .installment-amount-breakdown small.text-info {
            background-color: #d1ecf1;
            border-left: 3px solid #17a2b8;
        }
        
        .installment-amount-breakdown hr {
            margin: 10px 0;
            border-top: 2px solid #dee2e6;
        }
        
        .installment-amount-breakdown strong {
            font-size: 1.1rem;
        }
        
        .installment-discount-display {
            font-weight: 600;
            animation: fadeInSlide 0.3s ease;
        }
        
        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .installment-final-amount {
            color: #667eea;
            font-size: 1.1rem;
        }
        
        /* Payment Mode Section Styling */
        .bg-gradient-payment {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .payment-mode-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 20px;
            padding: 10px;
        }
        
        .payment-mode-card {
            position: relative;
        }
        
        .payment-mode-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .payment-mode-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 25px;
            border: 3px solid #e2e8f0;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: white;
            position: relative;
            height: 100%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        
        .payment-mode-label:hover {
            border-color: #667eea;
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.25);
        }
        
        .payment-mode-card input[type="radio"]:checked + .payment-mode-label {
            border-color: #667eea;
            border-width: 3px;
            background: linear-gradient(135deg, #f7faff 0%, #edf2ff 100%);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.35);
            transform: translateY(-5px) scale(1.05);
        }
        
        .payment-mode-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            font-size: 32px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        
        .payment-mode-icon::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: inherit;
            opacity: 0.5;
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }
        
        .payment-mode-label:hover .payment-mode-icon::before {
            transform: scale(1.2);
            opacity: 0;
        }
        
        .cash-icon {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }
        
        .cheque-icon {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
        }
        
        .upi-icon {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
        }
        
        .payment-mode-card input[type="radio"]:checked + .payment-mode-label .payment-mode-icon {
            transform: scale(1.15) rotate(5deg);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        
        .payment-mode-text {
            text-align: center;
        }
        
        .payment-mode-text strong {
            display: block;
            font-size: 17px;
            margin-bottom: 8px;
            color: #2d3748;
            font-weight: 700;
        }
        
        .payment-mode-text small {
            color: #718096;
            font-size: 13px;
            font-weight: 500;
        }
        
        .payment-mode-check {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 28px;
            color: #667eea;
            opacity: 0;
            transition: all 0.3s ease;
            transform: scale(0.5) rotate(-45deg);
        }
        
        .payment-mode-card input[type="radio"]:checked + .payment-mode-label .payment-mode-check {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
        
        .payment-details-box {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #667eea;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .payment-details-box:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }
        
        .upi-box {
            border-left-color: #4299e1;
        }
        
        .payment-details-box h6 {
            color: #667eea;
            font-weight: 700;
            font-size: 17px;
            margin-bottom: 20px;
        }
        
        /* Extras Section Styling */
        .card.border-info {
            border-width: 2px !important;
        }
        
        .card.border-info .card-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        
        .card.border-info .card-body {
            background: #f8fdff;
        }
        
        /* No Payment Message */
        #no-payment-message {
            border-left: 4px solid #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            animation: slideIn 0.3s ease;
        }
        
        #no-payment-message .alert-heading {
            color: #155724;
            font-weight: 600;
        }
        
        #no-payment-message p {
            color: #155724;
        }
        
        .border-left-success {
            border-left-width: 4px;
        }
        
        .badge {
            font-size: 0.95rem;
            padding: 0.5rem 0.8rem;
        }
        
        .form-check-label {
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .form-check-label:hover {
            background-color: #f8f9fa;
        }
        
        .form-check-input:checked + .form-check-label {
            font-weight: 600;
        }
        
        .btn-gradient-primary {
            background: linear-gradient(to right, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-gradient-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .card-title {
            color: #333;
            font-weight: 600;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(to right, #667eea 0%, #764ba2 100%);
        }
        
        .input-group-text {
            background-color: #e9ecef;
            font-weight: 600;
        }
        
        textarea.form-control {
            resize: vertical;
        }
        
        .shadow-sm {
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .modern-header {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .modern-header .page-title {
                font-size: 22px;
            }
            
            .page-title-icon {
                width: 40px;
                height: 40px;
            }
            
            .modern-card {
                border-radius: 15px;
            }
            
            .student-avatar {
                width: 60px;
                height: 60px;
                font-size: 32px;
            }
            
            .student-info-card {
                padding: 20px;
            }
            
            .payment-mode-options {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .payment-mode-icon {
                width: 60px;
                height: 60px;
                font-size: 28px;
            }
            
            .payment-mode-text strong {
                font-size: 15px;
            }
            
            .payment-mode-label {
                padding: 25px 20px;
            }
            
            .modern-submit-btn {
                padding: 15px 40px !important;
                font-size: 16px;
            }
            
            .section-icon-box {
                width: 45px;
                height: 45px;
                font-size: 22px;
            }
            
            .discount-icon-box, .extras-icon-box {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
            
            .fee-details-table {
                font-size: 14px;
            }
            
            .amount-badge, .amount-badge-danger {
                padding: 8px 15px;
                font-size: 0.95rem;
            }
            
            /* Installment Card Responsive */
            .installment-card-wrapper {
                padding: 12px 8px !important;
            }
            
            .installment-card {
                padding: 22px 18px;
                border-radius: 16px;
            }
            
            .installment-card-content {
                gap: 24px;
            }
            
            .installment-card-left {
                padding-right: 12px;
            }
            
            .installment-card-right {
                min-width: 180px;
            }
            
            .installment-icon-box {
                width: 44px;
                height: 44px;
                font-size: 20px;
            }
            
            .installment-name {
                font-size: 14.5px;
            }
            
            .installment-extra-info {
                padding-left: 58px;
                gap: 7px;
            }
            
            .modern-installment-pricing {
                gap: 8px;
                min-width: 180px;
                padding-left: 16px;
            }
            
            .main-amount-badge {
                padding: 8px 16px;
            }
            
            .main-amount-badge .amount-value {
                font-size: 16px;
            }
            
            .main-amount-badge .currency {
                font-size: 12px;
            }
            
            .pricing-breakdown {
                gap: 5px;
            }
            
            .price-item {
                font-size: 11.5px;
                padding: 6px 10px;
            }
            
            .total-paid-section,
            .total-amount-section {
                margin-top: 8px;
                padding-top: 8px;
            }
            
            .total-paid-badge,
            .total-amount-badge {
                padding: 10px 16px;
            }
            
            .total-paid-badge .amount,
            .total-amount-badge .amount {
                font-size: 16px;
            }
            
            /* Payment Summary Card - Tablet */
            .payment-summary-card {
                border-radius: 14px;
            }
            
            .summary-header {
                padding: 14px 20px;
            }
            
            .summary-header i {
                font-size: 22px;
            }
            
            .summary-content {
                padding: 20px;
            }
            
            .summary-row {
                padding: 12px 16px;
            }
            
            .summary-label {
                font-size: 13px;
            }
            
            .summary-value {
                font-size: 15px;
            }
            
            .summary-value.total-value {
                font-size: 18px;
            }
        }
        
        @media (max-width: 576px) {
            .modern-header .page-title {
                font-size: 20px;
            }
            
            .student-info-card .col-md-1,
            .student-info-card .col-md-7,
            .student-info-card .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
                text-align: center !important;
                margin-bottom: 10px;
            }
            
            .student-avatar {
                margin: 0 auto 10px;
            }
            
            /* Installment Card Mobile Responsive */
            .installment-card-wrapper {
                padding: 10px 6px !important;
            }
            
            .installment-card {
                padding: 20px 16px;
                border-radius: 14px;
            }
            
            .installment-card-content {
                flex-direction: column;
                gap: 20px;
            }
            
            .installment-card-left {
                width: 100%;
                padding-right: 0;
                padding-bottom: 16px;
                border-bottom: 2px dashed rgba(0, 0, 0, 0.08);
            }
            
            .installment-card-right {
                width: 100%;
                padding-left: 0;
                border-left: none;
            }
            
            .unpaid-card .installment-card-content {
                padding-left: 0;
                padding-top: 40px;
            }
            
            .card-checkbox {
                top: 18px;
                left: 18px;
                width: 22px;
                height: 22px;
            }
            
            .card-remove-btn {
                top: 16px;
                right: 16px;
                width: 32px;
                height: 32px;
                font-size: 20px;
            }
            
            .installment-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 8px;
            }
            
            .installment-icon-box {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }
            
            .installment-name {
                font-size: 14px;
            }
            
            .installment-meta {
                gap: 2px;
            }
            
            .installment-meta span {
                font-size: 11px;
                justify-content: center;
            }
            
            .installment-extra-info {
                padding-left: 0;
                justify-content: center;
                gap: 5px;
            }
            
            .modern-installment-pricing {
                align-items: stretch;
                min-width: auto;
                gap: 8px;
                padding-left: 0;
                border-left: none;
            }
            
            .main-amount-badge {
                padding: 10px 16px;
                width: 100%;
                justify-content: center;
            }
            
            .main-amount-badge .amount-value {
                font-size: 17px;
            }
            
            .main-amount-badge .currency {
                font-size: 12px;
            }
            
            .pricing-breakdown {
                gap: 5px;
            }
            
            .price-item {
                font-size: 11px;
                padding: 6px 10px;
                justify-content: space-between;
            }
            
            .total-paid-section,
            .total-amount-section {
                margin-top: 8px;
                padding-top: 8px;
            }
            
            .total-paid-badge,
            .total-amount-badge {
                padding: 10px 14px;
                width: 100%;
            }
            
            .total-paid-badge .amount,
            .total-amount-badge .amount {
                font-size: 15px;
            }
            
            /* Payment Summary Card - Mobile */
            .payment-summary-card {
                border-radius: 12px;
                margin: 0 -4px;
            }
            
            .summary-header {
                padding: 12px 16px;
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .summary-header i {
                font-size: 20px;
            }
            
            .summary-header h5 {
                font-size: 16px;
            }
            
            .summary-content {
                padding: 16px;
            }
            
            .summary-row {
                padding: 12px 14px;
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .summary-row.total-row {
                padding: 14px;
            }
            
            .summary-label {
                font-size: 12px;
                width: 100%;
            }
            
            .summary-label i {
                font-size: 16px;
            }
            
            .summary-value {
                font-size: 14px;
                width: 100%;
                text-align: right;
            }
            
            .summary-value.total-value {
                font-size: 17px;
            }
        }
        
        /* Smooth Transitions & Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
        
        .modern-card,
        .student-info-card,
        .modern-discount-card,
        .modern-extras-card,
        .modern-payment-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Hover Effects */
        .modern-input:hover,
        .modern-select:hover,
        .modern-textarea:hover {
            border-color: #cbd5e0;
            transform: translateY(-1px);
        }
        
        /* Discount and Final Amount Styling */
        #discount_row {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            animation: slideIn 0.3s ease;
            border-left: 4px solid #48bb78;
        }
        
        #discount_row th {
            color: #22543d;
            font-weight: 700;
        }
        
        /* Extras Row Styling */
        #extras_row {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            animation: slideIn 0.3s ease;
            border-left: 4px solid #4299e1;
        }
        
        #extras_row th {
            color: #1a365d;
            font-weight: 700;
        }
        
        #extras_row small {
            font-style: italic;
            color: #2c5282;
        }
        
        #final_amount_row {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-top: 4px solid #667eea;
            animation: slideIn 0.3s ease;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.2);
        }
        
        #final_amount_row th {
            padding: 20px 15px;
            font-size: 18px;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Highlight the discount section when active */
        .card.bg-light:has(#discount_type option:checked:not([value="none"])) {
            border: 2px solid #28a745;
            background-color: #f1f9f4 !important;
        }
        
        /* Make the final amount stand out */
        #final_amount_text {
            font-size: 1.5rem;
            font-weight: 800;
            color: #667eea;
            text-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
            letter-spacing: 0.5px;
        }
        
        /* Pulse animation for discount amount */
        .discount-active {
            animation: pulse 0.5s ease;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        /* Print Button Styling */
        #print-fee-details {
            font-weight: 700;
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        
        #print-fee-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 193, 7, 0.5);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        
        /* Badge Enhancements */
        .badge {
            font-size: 0.95rem;
            padding: 0.6rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        
        .badge-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }
        
        .badge-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            box-shadow: 0 3px 10px rgba(72, 187, 120, 0.3);
        }
        
        .badge-danger {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            box-shadow: 0 3px 10px rgba(245, 101, 101, 0.3);
        }
        
        /* Loading State */
        .form-control:disabled,
        .form-control[readonly] {
            background-color: #edf2f7;
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        /* Focus States */
        button:focus,
        input:focus,
        select:focus,
        textarea:focus {
            outline: none !important;
        }
        
        /* Opacity Classes */
        .opacity-90 {
            opacity: 0.9;
        }
        
        .opacity-80 {
            opacity: 0.8;
        }
        
        /* Shadow Utilities */
        .shadow-sm {
            box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important;
        }
        
        .shadow-lg {
            box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
        }
        
        /* Print-specific styles */
        @media print {
            /* Hide unnecessary elements */
            .no-print,
            .content-header,
            .page-header,
            .card-header,
            .btn,
            .sidebar,
            nav,
            footer,
            .mode-container,
            .card.bg-light,
            form .form-group:not(.compulsory-fees-content),
            hr.no-print {
                display: none !important;
            }
            
            /* Show print-only elements */
            .print-header,
            .print-footer {
                display: block !important;
            }
            
            /* Reset page styling for print */
            body {
                margin: 0;
                padding: 20px;
                background: white;
                color: black;
            }
            
            .card {
                border: none;
                box-shadow: none;
            }
            
            .card-body {
                padding: 0;
            }
            
            /* Table print styling */
            .fee-details-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            
            .fee-details-table thead th {
                background: #333 !important;
                color: white !important;
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .fee-details-table tbody td,
            .fee-details-table tbody th {
                border: 1px solid #000 !important;
                padding: 10px !important;
            }
            
            /* Amount badges for print */
            .amount-badge,
            .amount-badge-paid {
                background: #333 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .amount-badge-danger {
                background: #dc3545 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Due charges row */
            .due-charges-row {
                background: #fff3cd !important;
                border-left: 4px solid #dc3545 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Installment discount sections - hide in print */
            .installment-discount-section {
                display: none !important;
            }
            
            /* Show installment discount display in print */
            .installment-discount-display {
                display: inline !important;
                color: #28a745 !important;
            }
            
            /* Installment amount breakdown in print */
            .installment-amount-breakdown small {
                border-left-width: 3px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .installment-amount-breakdown small.text-success {
                background: #d4edda !important;
                border-left-color: #28a745 !important;
            }
            
            .installment-amount-breakdown small.text-muted {
                background: #f8f9fa !important;
                border-left-color: #6c757d !important;
            }
            
            .installment-amount-breakdown small.text-info {
                background: #d1ecf1 !important;
                border-left-color: #17a2b8 !important;
            }
            
            .badge-success {
                background: #28a745 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Extras section in print */
            .card.border-info .card-header {
                background: #17a2b8 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .card.border-info {
                page-break-inside: avoid;
            }
            
            /* No payment message in print */
            #no-payment-message {
                background: #d4edda !important;
                border-left: 4px solid #28a745 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                page-break-inside: avoid;
            }
            
            /* Payment mode section in print */
            .card.shadow-lg {
                box-shadow: none !important;
            }
            
            .payment-mode-options {
                display: block !important;
            }
            
            .payment-mode-card {
                display: inline-block;
                margin-right: 10px;
            }
            
            .payment-mode-card input[type="radio"]:checked + .payment-mode-label {
                border: 2px solid #000 !important;
                background: #f0f0f0 !important;
            }
            
            .payment-details-box {
                border: 1px solid #000 !important;
                page-break-inside: avoid;
            }
            
            /* Section headers */
            .section-header-row th {
                background: #f0f0f0 !important;
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .table-subheader th {
                background: #e0e0e0 !important;
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Discount and final amount rows */
            #discount_row,
            #extras_row,
            #final_amount_row {
                display: table-row !important;
            }
            
            #discount_row th {
                background: #d4edda !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            #extras_row {
                background: #d1ecf1 !important;
                border-left: 4px solid #17a2b8 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            #extras_row th {
                background: #d1ecf1 !important;
                color: #0c5460 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            #final_amount_row th {
                background: #e3f2fd !important;
                border-top: 3px solid #2196F3 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Page breaks */
            .fee-details-table {
                page-break-inside: avoid;
            }
            
            /* Alert info */
            .alert-info {
                background: #e7f3ff !important;
                border: 1px solid #b3d9ff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Print header */
            .print-header {
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 3px solid #333;
            }
            
            .print-header h2 {
                margin-bottom: 10px;
                font-size: 24px;
                font-weight: bold;
            }
            
            .print-student-info {
                background: #f9f9f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Student info card - print version */
            .student-info-card {
                display: block !important;
                background: #f9f9f9 !important;
                border: 2px solid #333 !important;
                padding: 15px !important;
                margin-bottom: 20px !important;
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .student-info-card .student-avatar {
                display: none;
            }
            
            .student-info-card h4 {
                font-size: 16px !important;
                margin-bottom: 5px !important;
            }
            
            .student-id-badge {
                background: #333 !important;
                color: white !important;
                padding: 5px 10px !important;
                border-radius: 4px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Section titles */
            .section-title-modern {
                display: block !important;
                margin-bottom: 15px !important;
                padding-bottom: 10px;
                border-bottom: 2px solid #ddd;
            }
            
            .section-icon-box {
                display: none !important;
            }
            
            /* Payment Summary Card for Print */
            .payment-summary-card {
                background: white !important;
                border: 2px solid #10b981 !important;
                border-radius: 8px !important;
                padding: 0 !important;
                margin: 20px 0 !important;
                page-break-inside: avoid;
                box-shadow: none !important;
            }
            
            .summary-header {
                background: #10b981 !important;
                color: white !important;
                padding: 12px 20px !important;
                border-bottom: 2px solid #059669 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .summary-header h5 {
                font-size: 16px !important;
                font-weight: bold !important;
                margin: 0 !important;
            }
            
            .summary-content {
                padding: 15px !important;
            }
            
            .summary-row {
                display: flex !important;
                justify-content: space-between !important;
                padding: 10px 15px !important;
                margin-bottom: 8px !important;
                border: 1px solid #ddd !important;
                border-radius: 4px !important;
                background: white !important;
            }
            
            .summary-row.discount-row {
                background: #d4edda !important;
                border-color: #10b981 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .summary-row.due-charges-row {
                background: #fee !important;
                border-color: #ef4444 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .summary-row.extra-fee-row {
                background: #e0f2fe !important;
                border-color: #0ea5e9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .summary-row.total-row {
                background: #d4edda !important;
                border: 2px solid #10b981 !important;
                padding: 12px 15px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .summary-label,
            .summary-value {
                font-size: 13px !important;
                display: inline !important;
            }
            
            .summary-value.total-value {
                font-size: 16px !important;
                font-weight: bold !important;
            }
            
            /* Installment Cards for Print */
            .installment-card {
                background: white !important;
                border: 2px solid #333 !important;
                border-radius: 6px !important;
                padding: 15px !important;
                margin: 10px 0 !important;
                page-break-inside: avoid;
                box-shadow: none !important;
            }
            
            .installment-card.paid-card {
                border-color: #10b981 !important;
                background: #f0fdf4 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .installment-card.paid-card::after {
                background: #10b981 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .installment-card.unpaid-card {
                border-color: #6366f1 !important;
                background: #f5f5ff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .installment-card.overdue-card {
                border-color: #ef4444 !important;
                background: #fef2f2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .installment-card-content {
                display: block !important;
            }
            
            .installment-card-left,
            .installment-card-right {
                width: 100% !important;
                padding: 0 !important;
                border: none !important;
            }
            
            .installment-card-right {
                margin-top: 10px !important;
                padding-top: 10px !important;
                border-top: 1px dashed #ddd !important;
            }
            
            .installment-name {
                font-size: 15px !important;
                font-weight: bold !important;
                margin-bottom: 5px !important;
            }
            
            .installment-meta {
                font-size: 12px !important;
            }
            
            .card-remove-btn,
            .card-checkbox {
                display: none !important;
            }
            
            .modern-installment-pricing {
                display: block !important;
                padding: 0 !important;
                border: none !important;
            }
            
            .main-amount-badge {
                background: #333 !important;
                color: white !important;
                padding: 8px 15px !important;
                border-radius: 4px !important;
                display: inline-block !important;
                margin-bottom: 10px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .main-amount-badge.paid {
                background: #10b981 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .pricing-breakdown,
            .total-paid-section,
            .total-amount-section {
                display: block !important;
            }
            
            .price-item {
                display: block !important;
                padding: 5px 10px !important;
                margin: 5px 0 !important;
                border-radius: 3px !important;
                font-size: 12px !important;
            }
            
            .price-item.due-charges,
            .price-item.charges {
                background: #fee !important;
                border-left: 3px solid #ef4444 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .price-item.discount,
            .price-item.discount-applied {
                background: #d4edda !important;
                border-left: 3px solid #10b981 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .total-paid-badge,
            .total-amount-badge {
                background: #f0fdf4 !important;
                border: 2px solid #10b981 !important;
                padding: 10px 15px !important;
                border-radius: 4px !important;
                margin-top: 10px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Modern badges */
            .modern-badge {
                background: #10b981 !important;
                color: white !important;
                padding: 3px 8px !important;
                border-radius: 3px !important;
                font-size: 11px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Hide form elements in print */
            input[type="text"],
            input[type="number"],
            input[type="checkbox"]:not(.installment-checkbox),
            select,
            textarea,
            .form-control,
            .input-with-icon i {
                border: 1px solid #333 !important;
                background: white !important;
            }
            
            .datepicker-popup {
                border: 1px solid #333 !important;
            }
            
            /* Print footer */
            .print-footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 2px solid #333;
                page-break-inside: avoid;
                text-align: center;
            }
            
            /* Generated timestamp */
            small.text-muted {
                color: #666 !important;
                font-size: 11px !important;
            }
            
            /* Page breaks */
            .installment-summary-row,
            .payment-summary-card {
                page-break-inside: avoid;
            }
            
            /* Remove unnecessary spacing */
            .modern-divider,
            .modern-card-header,
            br {
                display: none !important;
            }
            
            /* Improve spacing */
            tbody tr {
                page-break-inside: avoid;
            }
        }
    </style>
@endsection
