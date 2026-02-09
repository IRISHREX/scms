@extends('layouts.master')

@section('title')
    {{ __('Design Seat Number Template') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Design Seat Number Template') }}
            </h3>
        </div>
        <form class="pt-3" id="edit-form" action="{{ route('seat-number.save-template') }}" method="POST"
            novalidate="novalidate" enctype="multipart/form-data" data-success-function="formSuccessFunction">
            @csrf
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="custom-card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title">
                                            {{ __('Design Template') }}
                                        </h4>
                                        <a class="btn btn-sm btn-theme" href="{{ route('seat-number.index') }}">
                                            Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12">
                                    <div class="d-flex flex-wrap">
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_name', $template->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="school_name">{{ __('School Name') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('exam_name', $template->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="exam_name">{{ __('Exam Name') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('student_name', $template->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="student_name">{{ __('Student Name') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('seat_number', $template->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="seat_number">{{ __('Seat Number') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('roll_number', $template->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="roll_number">{{ __('Roll Number') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('class_section', $template->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="class_section">{{ __('Class Section') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('user_image', $template->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="user_image">{{ __('User Image') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_address', $template->fields ?? []) ? 'checked' : '' }}
                                                    name="school_data[]" value="school_address">{{ __('School Address') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('issue_date', $template->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="issue_date">{{ __('Issue Date') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('session_year', $template->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="session_year">{{ __('Session Year') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_logo', $template->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="school_logo">{{ __('School Logo') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <span class="text-danger">{{ __('note_required_medium_or_large_screen_only') }}</span>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit" value={{ __('save') }}>
                                    <input class="btn btn-secondary float-right" id="reset-btn" type="reset" value={{ __('reset') }}>
                                </div>

                                <input type="hidden" name="style[school_name]" value="{{ $style['school_name'] ?? '' }}"
                                    id="school_name">
                                <input type="hidden" name="style[exam_name]" value="{{ $style['exam_name'] ?? '' }}"
                                    id="exam_name">
                                <input type="hidden" name="style[student_name]" value="{{ $style['student_name'] ?? '' }}"
                                    id="student_name">
                                <input type="hidden" name="style[seat_number]" value="{{ $style['seat_number'] ?? '' }}"
                                    id="seat_number">
                                <input type="hidden" name="style[roll_number]" value="{{ $style['roll_number'] ?? '' }}"
                                    id="roll_number">
                                <input type="hidden" name="style[class_section]" value="{{ $style['class_section'] ?? '' }}"
                                    id="class_section">
                                <input type="hidden" name="style[user_image]" value="{{ $style['user_image'] ?? '' }}"
                                    id="user_image">
                                <input type="hidden" name="style[school_address]"
                                    value="{{ $style['school_address'] ?? '' }}" id="school_address">
                                <input type="hidden" name="style[issue_date]" value="{{ $style['issue_date'] ?? '' }}"
                                    id="issue_date">
                                <input type="hidden" name="style[session_year]" value="{{ $style['session_year'] ?? '' }}"
                                    id="session_year">
                                <input type="hidden" name="style[school_logo]" value="{{ $style['school_logo'] ?? '' }}"
                                    id="school_logo">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="position: relative">

                    <div class="design" id="draggableElements">
                        <div class="frame-border"></div>

                        <div class="draggableItem p-2 draggableText text-center h2" {!! $style['school_name'] ?? '' !!}
                            id="item_school_name">
                            <b>{{ $settings['school_name'] }}</b>
                        </div>

                        <div class="draggableItem p-2 draggableText text-center" {!! $style['exam_name'] ?? '' !!}
                            id="item_exam_name">
                            Exam Name
                        </div>

                        <div class="draggableItem p-2 draggableText text-center" {!! $style['student_name'] ?? '' !!}
                            id="item_student_name">
                            Student Name
                        </div>

                        <div class="draggableItem p-2 draggableText text-center" {!! $style['seat_number'] ?? '' !!}
                            id="item_seat_number">
                            Seat Number: 0001
                        </div>

                        <div class="draggableItem p-2 draggableText text-center" {!! $style['roll_number'] ?? '' !!}
                            id="item_roll_number">
                            Roll No: 1
                        </div>

                        <div class="draggableItem p-2 draggableText text-center" {!! $style['class_section'] ?? '' !!}
                            id="item_class_section">
                            Class Section
                        </div>

                        <img id="item_user_image" class="draggableItem" {!! $style['user_image'] ?? '' !!}
                            src="{{ url('assets/dummy_logo.jpg') }}" height="{{ $template->image_size }}"
                            width="{{ $template->image_size }}" alt="user_image">

                        <div class="draggableItem p-2 draggableText text-center" {!! $style['school_address'] ?? '' !!}
                            id="item_school_address">
                            {{ $settings['school_address'] ?? 'School Address' }}
                        </div>

                        <div class="draggableItem p-2 draggableText text-center" {!! $style['issue_date'] ?? '' !!}
                            id="item_issue_date">
                            Issue Date
                        </div>

                        <div class="draggableItem p-2 draggableText text-center" {!! $style['session_year'] ?? '' !!}
                            id="item_session_year">
                            Session Year
                        </div>

                        <img id="item_school_logo" class="draggableItem" {!! $style['school_logo'] ?? '' !!}
                            src="{{ $settings['vertical_logo'] ?? url('assets/dummy_logo.jpg') }}" height="50px"
                            alt="school_logo">
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        window.onload = setTimeout(() => {
            $('.form-check-input').trigger('change');
        }, 500);

        $('#reset-btn').click(function (e) {
            e.preventDefault();
            // Reset positions to default
            $('#item_school_name').css({ top: '10px', left: '10px', width: '', height: '', fontSize: '' });
            $('#item_exam_name').css({ top: '30px', left: '10px', width: '', height: '', fontSize: '' });
            $('#item_student_name').css({ top: '50px', left: '10px', width: '', height: '', fontSize: '' });
            $('#item_seat_number').css({ top: '70px', left: '10px', width: '', height: '', fontSize: '' });
            $('#item_roll_number').css({ top: '90px', left: '10px', width: '', height: '', fontSize: '' });
            $('#item_class_section').css({ top: '110px', left: '10px', width: '', height: '', fontSize: '' });
            $('#item_user_image').css({ top: '10px', left: '300px', width: '', height: '' });
            $('#item_school_address').css({ top: '130px', left: '10px', width: '', height: '', fontSize: '' });
            $('#item_issue_date').css({ top: '150px', left: '10px', width: '', height: '', fontSize: '' });
            $('#item_session_year').css({ top: '170px', left: '10px', width: '', height: '', fontSize: '' });
            $('#item_school_logo').css({ top: '10px', left: '240px', width: '', height: '' });
            
            // Clear selection
            deselectElement();
            
            // Update all hidden inputs
            $('.draggableItem').each(function() {
                updateHiddenInput(this);
            });
        });

        let isDragging = false;
        let isResizing = false;
        let currentElement = null; // Element being dragged
        let selectedElement = null; // Element currently selected (for resizing)
        let offsetX, offsetY;
        let startX, startY, startWidth, startHeight, startFontSize;

        const container = document.getElementById('draggableElements');
        
        // Create resize handle
        const resizeHandle = document.createElement('div');
        resizeHandle.classList.add('resize-handle');
        container.appendChild(resizeHandle);

        // Add event listeners to all draggable items
        document.querySelectorAll('.draggableItem').forEach(element => {
            element.addEventListener('mousedown', (e) => {
                // If clicking on the resize handle, don't start drag
                if (e.target.classList.contains('resize-handle')) return;
                
                e.stopPropagation(); // Prevent container click
                
                // Select this element
                selectElement(element);
                
                isDragging = true;
                currentElement = element;
                offsetX = e.clientX - element.getBoundingClientRect().left;
                offsetY = e.clientY - element.getBoundingClientRect().top;
            });
        });

        // Resize handle mousedown
        resizeHandle.addEventListener('mousedown', (e) => {
            if (!selectedElement) return;
            
            e.stopPropagation();
            e.preventDefault(); // Prevent text selection
            
            isResizing = true;
            startX = e.clientX;
            startY = e.clientY;
            startWidth = selectedElement.offsetWidth;
            startHeight = selectedElement.offsetHeight;
            
            const computedStyle = getComputedStyle(selectedElement);
            startFontSize = parseFloat(computedStyle.fontSize);
        });

        // Mouse move handler (for both dragging and resizing)
        document.addEventListener('mousemove', (e) => {
            if (isResizing && selectedElement) {
                const deltaX = e.clientX - startX;
                const deltaY = e.clientY - startY;
                
                // Calculate new dimensions
                let newWidth = startWidth + deltaX;
                let newHeight = startHeight + deltaY;
                
                // Set minimum size
                newWidth = Math.max(20, newWidth);
                newHeight = Math.max(20, newHeight);
                
                // Apply new dimensions
                if (selectedElement.tagName === 'IMG') {
                    selectedElement.style.width = newWidth + 'px';
                    selectedElement.style.height = newHeight + 'px';
                } else {
                    selectedElement.style.width = newWidth + 'px';
                    // Scale font size proportionally for text elements
                    // Only if width changed significantly to avoid jitter
                    if (Math.abs(newWidth - startWidth) > 1) {
                        const scaleFactor = newWidth / startWidth;
                        const newFontSize = startFontSize * scaleFactor;
                        selectedElement.style.fontSize = newFontSize + 'px';
                    }
                }
                
                // Update resize handle position
                positionResizeHandle();
                
            } else if (isDragging && currentElement) {
                const containerRect = container.getBoundingClientRect();
                const elemRect = currentElement.getBoundingClientRect();

                let newLeft = e.clientX - containerRect.left - offsetX;
                let newTop = e.clientY - containerRect.top - offsetY;

                // Ensure the element stays within the bounds of the container
                // Allow some flexibility but keep it mostly inside
                newLeft = Math.max(0, Math.min(newLeft, containerRect.width - 10));
                newTop = Math.max(0, Math.min(newTop, containerRect.height - 10));

                currentElement.style.left = newLeft + 'px';
                currentElement.style.top = newTop + 'px';
                
                // Update resize handle position if dragging the selected element
                if (currentElement === selectedElement) {
                    positionResizeHandle();
                }
            }
        });

        // Mouse up handler
        document.addEventListener('mouseup', () => {
            if (isDragging && currentElement) {
                updateHiddenInput(currentElement);
                isDragging = false;
                currentElement = null;
            }
            
            if (isResizing && selectedElement) {
                updateHiddenInput(selectedElement);
                isResizing = false;
            }
        });
        
        // Click outside to deselect
        document.addEventListener('mousedown', (e) => {
            if (!e.target.closest('.draggableItem') && !e.target.classList.contains('resize-handle')) {
                deselectElement();
            }
        });

        function selectElement(element) {
            // Deselect previous
            if (selectedElement) {
                selectedElement.classList.remove('selected-item');
            }
            
            selectedElement = element;
            selectedElement.classList.add('selected-item');
            
            // Show and position resize handle
            resizeHandle.style.display = 'block';
            positionResizeHandle();
        }
        
        function deselectElement() {
            if (selectedElement) {
                selectedElement.classList.remove('selected-item');
                selectedElement = null;
            }
            resizeHandle.style.display = 'none';
        }
        
        function positionResizeHandle() {
            if (!selectedElement) return;
            
            const rect = selectedElement.getBoundingClientRect();
            const containerRect = container.getBoundingClientRect();
            
            // Calculate position relative to container
            const left = rect.left - containerRect.left + rect.width - 5; // -5 to center on corner
            const top = rect.top - containerRect.top + rect.height - 5;
            
            resizeHandle.style.left = left + 'px';
            resizeHandle.style.top = top + 'px';
        }

        function updateHiddenInput(element) {
            let type = element.id;
            // Build style string including position, size, and font-size
            let style = 'style="position:absolute; left: ' + element.style.left + '; top: ' + element.style.top + ';';
            
            if (element.style.width) style += ' width: ' + element.style.width + ';';
            if (element.style.height) style += ' height: ' + element.style.height + ';';
            if (element.style.fontSize) style += ' font-size: ' + element.style.fontSize + ';';
            
            style += '"';
            
            type = type.replace('item_', "");
            $('#' + type).val(style);
        }

        $('.form-check-input').change(function (e) {
            e.preventDefault();
            let field = '#item_' + $(this).val();
            let status = $(this).is(':checked');
            if (status) {
                $(field).show(500);
            } else {
                $(field).hide(500);
            }
        });

        function formSuccessFunction(response) {
            if (!response.error) {
                setTimeout(() => {
                    window.location.href = "{{ route('seat-number.index') }}";
                }, 1000);
            }
        }
    </script>
@endsection
@section('css')
    <style>
        .design {
            width: 380px;
            height: 200px;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            background-color: white;
            border: 1px solid #ddd;
            margin: 0 auto;
        }

        .frame-border {
            height: 100%;
            width: 100%;
            border: 1px solid black;
        }

        .draggableItem {
            position: absolute;
            cursor: move; /* Changed to move to indicate dragging */
            white-space: nowrap;
            font-size: 12px;
            /* Add border to make it easier to see the bounding box when selected */
            border: 1px solid transparent; 
        }

        /* Resize handle styles */
        .resize-handle {
            width: 10px;
            height: 10px;
            background-color: #007bff; /* Blue color */
            position: absolute;
            right: -5px;
            bottom: -5px;
            cursor: se-resize;
            display: none; /* Hidden by default */
            z-index: 100;
        }

        /* Selected item style */
        .selected-item {
            border: 1px dashed #007bff !important; /* Blue dashed border */
        }
    </style>
@endsection