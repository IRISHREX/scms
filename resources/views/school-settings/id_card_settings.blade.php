@extends('layouts.master')

@section('title')
    {{ __('id_card_setting') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('id_card_setting') }}
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <form class="pt-3 create-form-without-reset reload-window" id="formdata" action="{{ url('id-card-settings') }}"
                            method="POST" novalidate="novalidate" data-pre-submit-function="updateAllHiddenInputs">
                            <div class="row">
                                {{--  --}}
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <div class="col-12 d-flex row">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input type" name="type"
                                                        id="type" value="Student" checked required="required">
                                                    {{ __('student') }}
                                                </label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input type" name="type"
                                                        id="type" value="Staff" required="required">
                                                    {{ __('staff') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                               
                            </div>

                            <div id="student-id-card">
                                @include('school-settings.student_id_card')
                            </div>

                            <div id="staff-id-card">
                                @include('school-settings.staff_id_card')
                            </div>
                            
                            {{-- Undo/Redo/Reset Controls --}}
                            <div class="col-sm-12 col-md-12 mt-4">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0"><i class="fa fa-history mr-2"></i>{{ __('Undo/Redo/Reset') }}</h6>
                                                <small class="text-muted">{{ __('Undo, redo, or reset all settings to default') }}</small>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-secondary" id="undo-btn" title="{{ __('Undo') }}">
                                                    <i class="fa fa-undo mr-1"></i>{{ __('Undo') }}
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary" id="redo-btn" title="{{ __('Redo') }}">
                                                    <i class="fa fa-redo mr-1"></i>{{ __('Redo') }}
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" id="reset-design-btn" title="{{ __('Reset All Settings') }}">
                                                    <i class="fa fa-refresh mr-1"></i>{{ __('Reset Design') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Text Formatting Panel --}}
                            <div class="col-sm-12 col-md-12 mt-4">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fa fa-paint-brush mr-2"></i>{{ __('Text Formatting') }}</h5>
                                        <small class="text-muted">{{ __('Select an element in the design template to format it') }}</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- Selected Element Display --}}
                                            <div class="col-md-12 mb-3">
                                                <label class="font-weight-bold">{{ __('Selected Element') }}:</label>
                                                <span id="selected-element-name" class="badge badge-info ml-2">{{ __('None') }}</span>
                                            </div>
                                            
                                            {{-- Font Size --}}
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <label for="format-font-size">{{ __('font_size') }} (px)</label>
                                                <input type="number" id="format-font-size" class="form-control format-control" min="8" max="72" value="12" placeholder="12">
                                            </div>
                                            
                                            {{-- Text Color --}}
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <label for="format-text-color">{{ __('text_color') }}</label>
                                                <input type="color" id="format-text-color" class="form-control format-control" value="#000000" style="height: 38px; padding: 2px;">
                                            </div>
                                            
                                            {{-- Background Color --}}
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <label for="format-bg-color">{{ __('background_color') }}</label>
                                                <div class="input-group">
                                                    <input type="color" id="format-bg-color" class="form-control format-control" value="#ffffff" style="height: 38px; padding: 2px;">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-secondary" id="clear-bg-color" title="{{ __('Clear Background') }}">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Border Width --}}
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <label for="format-border-width">{{ __('border_width') }} (px)</label>
                                                <input type="number" id="format-border-width" class="form-control format-control" min="0" max="10" value="0" placeholder="0">
                                            </div>
                                            
                                            {{-- Border Color --}}
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <label for="format-border-color">{{ __('border_color') }}</label>
                                                <input type="color" id="format-border-color" class="form-control format-control" value="#000000" style="height: 38px; padding: 2px;">
                                            </div>
                                            
                                            {{-- Text Alignment --}}
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <label>{{ __('alignment') }}</label>
                                                <div class="btn-group d-flex" role="group">
                                                    <button type="button" class="btn btn-outline-secondary format-align" data-align="left" title="{{ __('Left') }}">
                                                        <i class="fa fa-align-left"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary format-align" data-align="center" title="{{ __('Center') }}">
                                                        <i class="fa fa-align-center"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary format-align" data-align="right" title="{{ __('Right') }}">
                                                        <i class="fa fa-align-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            {{-- Text Style (Bold, Italic) --}}
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <label>{{ __('text_style') }}</label>
                                                <div class="btn-group d-flex" role="group">
                                                    <button type="button" class="btn btn-outline-secondary format-style" data-style="bold" title="{{ __('Bold') }}">
                                                        <i class="fa fa-bold"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary format-style" data-style="italic" title="{{ __('Italic') }}">
                                                        <i class="fa fa-italic"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary format-style" data-style="underline" title="{{ __('Underline') }}">
                                                        <i class="fa fa-underline"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            {{-- Width Control for Images --}}
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <label for="format-width">{{ __('width') }} (px)</label>
                                                <input type="number" id="format-width" class="form-control format-control" min="20" max="500" value="60" placeholder="60">
                                            </div>
                                            
                                            {{-- Height Control for Images --}}
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <label for="format-height">{{ __('height') }} (px)</label>
                                                <input type="number" id="format-height" class="form-control format-control" min="20" max="500" value="60" placeholder="60">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             
                             <div class="col-sm-12 col-md-12">
                                    <hr>
                                </div>
                             {{-- Signature Image - Common for both Student and Staff --}}
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="signature_image">{{ __('signature') }} <span class="text-muted">({{ __('Common for Student & Staff') }})</span></label>
                                        <input type="file" name="signature"
                                            accept="image/jpg,image/png,image/jpeg,image/svg" class="file-upload-default" />
                                        <div class="input-group col-xs-12">
                                            <input type="text" id="signature_image" class="form-control file-upload-info" disabled=""
                                                placeholder="{{ __('image') }}" />
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                            </span>
                                        </div>
                                        @if ($settings['signature'] ?? '')
                                            <div id="signature" class="mt-3">
                                                <img src="{{ $settings['signature'] }}" class="img-fluid w-25" alt="">

                                                <div class="mt-2">
                                                    <a href="" data-type="signature"
                                                        class="btn btn-inverse-danger btn-sm id-card-settings">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </div>
                                                <div class="mt-3">
                                                    <span class="text-info">
                                                        {{ __('note_these_signature_image_are_also_used_in_certificates') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            <input class="btn btn-theme float-right ml-3 mt-3" id="create-btn" type="submit" value={{ __('submit') }}>
                            <input class="btn btn-secondary float-right mt-3" type="reset" value={{ __('reset') }}>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // Global function to update all hidden inputs - called before form submission
        function updateAllHiddenInputs() {
            console.log('Pre-submit: Updating all hidden inputs...');
            document.querySelectorAll('.draggableItem').forEach(function(element) {
                let type = element.id; // e.g., student_item_student_name
                // Save only the CSS properties (not the style="..." wrapper)
                let cssProps = '';
                
                // Position
                if (element.style.left) cssProps += 'left:' + element.style.left + ';';
                if (element.style.top) cssProps += 'top:' + element.style.top + ';';
                
                // Size
                if (element.style.width) cssProps += 'width:' + element.style.width + ';';
                if (element.style.height) cssProps += 'height:' + element.style.height + ';';
                
                // Font
                if (element.style.fontSize) cssProps += 'font-size:' + element.style.fontSize + ';';
                if (element.style.fontWeight && element.style.fontWeight !== 'normal' && element.style.fontWeight !== '400') cssProps += 'font-weight:' + element.style.fontWeight + ';';
                if (element.style.fontStyle && element.style.fontStyle !== 'normal') cssProps += 'font-style:' + element.style.fontStyle + ';';
                
                // Text color - Convert to hex for DOMPDF
                if (element.style.color) {
                    let hexColor = rgbToHexGlobal(element.style.color);
                    if (hexColor) cssProps += 'color:' + hexColor + ';';
                }
                if (element.style.textAlign) cssProps += 'text-align:' + element.style.textAlign + ';';
                if (element.style.textDecoration && element.style.textDecoration !== 'none' && !element.style.textDecoration.includes('none')) cssProps += 'text-decoration:' + element.style.textDecoration + ';';
                
                // Background - Convert to hex for DOMPDF
                if (element.style.backgroundColor && element.style.backgroundColor !== 'transparent' && element.style.backgroundColor !== 'rgba(0, 0, 0, 0)') {
                    let hexBgColor = rgbToHexGlobal(element.style.backgroundColor);
                    if (hexBgColor) cssProps += 'background-color:' + hexBgColor + ';';
                }
                
                // Border - Convert to hex for DOMPDF
                if (element.style.borderWidth && parseFloat(element.style.borderWidth) > 0) {
                    cssProps += 'border-width:' + element.style.borderWidth + ';';
                    cssProps += 'border-style:' + (element.style.borderStyle || 'solid') + ';';
                    if (element.style.borderColor) {
                        let hexBorderColor = rgbToHexGlobal(element.style.borderColor);
                        if (hexBorderColor) cssProps += 'border-color:' + hexBorderColor + ';';
                    }
                }
                
                // Replace item_ with style_ to match hidden input ID
                let inputId = type.replace('item_', "style_");
                $('#' + inputId).val(cssProps);
                console.log('Pre-submit updated:', inputId, cssProps);
            });
        }
        
        // Global RGB to Hex converter
        function rgbToHexGlobal(rgb) {
            if (!rgb) return '';
            if (rgb.startsWith('#')) return rgb;
            if (rgb === 'transparent' || rgb === 'rgba(0, 0, 0, 0)') return '';
            const result = rgb.match(/\d+/g);
            if (!result || result.length < 3) return '#000000';
            return '#' + result.slice(0, 3).map(x => {
                const hex = parseInt(x).toString(16);
                return hex.length === 1 ? '0' + hex : hex;
            }).join('');
        }
        
        $(document).ready(function() {
            // Undo/Redo History Management
            let historyStack = [];
            let historyIndex = -1;
            const MAX_HISTORY = 50; // Maximum number of undo steps
            
            // Save current state to history
            function saveState() {
                // Remove any states after current index (when undoing and then making new changes)
                if (historyIndex < historyStack.length - 1) {
                    historyStack = historyStack.slice(0, historyIndex + 1);
                }
                
                // Get current state of all elements
                let state = {
                    timestamp: Date.now(),
                    elements: {}
                };
                
                document.querySelectorAll('.draggableItem').forEach(element => {
                    let elementId = element.id;
                    state.elements[elementId] = {
                        left: element.style.left || '',
                        top: element.style.top || '',
                        width: element.style.width || '',
                        height: element.style.height || '',
                        fontSize: element.style.fontSize || '',
                        color: element.style.color || '',
                        backgroundColor: element.style.backgroundColor || '',
                        borderWidth: element.style.borderWidth || '',
                        borderColor: element.style.borderColor || '',
                        borderStyle: element.style.borderStyle || '',
                        textAlign: element.style.textAlign || '',
                        fontWeight: element.style.fontWeight || '',
                        fontStyle: element.style.fontStyle || '',
                        textDecoration: element.style.textDecoration || '',
                        display: element.style.display || ''
                    };
                });
                
                // Save hidden inputs state
                state.hiddenInputs = {};
                $('input[type="hidden"][name*="_id_card_styles"]').each(function() {
                    state.hiddenInputs[$(this).attr('id')] = $(this).val();
                });
                
                // Add to history
                historyStack.push(state);
                historyIndex = historyStack.length - 1;
                
                // Limit history size
                if (historyStack.length > MAX_HISTORY) {
                    historyStack.shift();
                    historyIndex--;
                }
                
                updateUndoRedoButtons();
            }
            
            // Restore state from history
            function restoreState(state) {
                if (!state || !state.elements) return;
                
                // Restore element styles
                Object.keys(state.elements).forEach(elementId => {
                    let element = document.getElementById(elementId);
                    if (element) {
                        let elementState = state.elements[elementId];
                        element.style.left = elementState.left || '';
                        element.style.top = elementState.top || '';
                        element.style.width = elementState.width || '';
                        element.style.height = elementState.height || '';
                        element.style.fontSize = elementState.fontSize || '';
                        element.style.color = elementState.color || '';
                        element.style.backgroundColor = elementState.backgroundColor || '';
                        element.style.borderWidth = elementState.borderWidth || '';
                        element.style.borderColor = elementState.borderColor || '';
                        element.style.borderStyle = elementState.borderStyle || '';
                        element.style.textAlign = elementState.textAlign || '';
                        element.style.fontWeight = elementState.fontWeight || '';
                        element.style.fontStyle = elementState.fontStyle || '';
                        element.style.textDecoration = elementState.textDecoration || '';
                        element.style.display = elementState.display || '';
                        
                        // Update hidden input
                        updateHiddenInput(element);
                    }
                });
                
                // Restore hidden inputs
                if (state.hiddenInputs) {
                    Object.keys(state.hiddenInputs).forEach(inputId => {
                        $('#' + inputId).val(state.hiddenInputs[inputId] || '');
                    });
                }
                
                // Update formatting panel if element is selected
                if (selectedElement) {
                    updateFormattingPanel();
                    positionResizeHandle();
                }
            }
            
            // Undo function
            function undo() {
                if (historyIndex > 0) {
                    historyIndex--;
                    let previousState = historyStack[historyIndex];
                    restoreState(previousState);
                    updateUndoRedoButtons();
                }
            }
            
            // Redo function
            function redo() {
                if (historyIndex < historyStack.length - 1) {
                    historyIndex++;
                    let nextState = historyStack[historyIndex];
                    restoreState(nextState);
                    updateUndoRedoButtons();
                }
            }
            
            // Update undo/redo button states
            function updateUndoRedoButtons() {
                $('#undo-btn').prop('disabled', historyIndex <= 0);
                $('#redo-btn').prop('disabled', historyIndex >= historyStack.length - 1);
            }
            
            // Undo/Redo button handlers
            $('#undo-btn').on('click', function() {
                undo();
            });
            
            $('#redo-btn').on('click', function() {
                redo();
            });
            
            // Reset Design button handler
            $('#reset-design-btn').on('click', function() {
                if (confirm('{{ __("Are you sure you want to reset all design settings to default? This action cannot be undone.") }}')) {
                    resetDesignToDefault();
                }
            });
            
            // Reset all design settings to default
            function resetDesignToDefault() {
                // Reset all draggable elements to default state
                document.querySelectorAll('.draggableItem').forEach(element => {
                    let elementId = element.id;
                    
                    // Reset all styles
                    element.style.left = '';
                    element.style.top = '';
                    element.style.fontSize = '';
                    element.style.color = '';
                    element.style.backgroundColor = '';
                    element.style.borderWidth = '';
                    element.style.borderColor = '';
                    element.style.borderStyle = '';
                    element.style.textAlign = '';
                    element.style.fontWeight = '';
                    element.style.fontStyle = '';
                    element.style.textDecoration = '';
                    
                    // Set default sizes for images
                    if (element.tagName === 'IMG') {
                        if (elementId.includes('profile_image')) {
                            element.style.width = '60px';
                            element.style.height = '60px';
                        } else if (elementId.includes('school_logo')) {
                            element.style.width = '40px';
                            element.style.height = '40px';
                        } else if (elementId.includes('signature')) {
                            element.style.width = '60px';
                            element.style.height = '30px';
                        } else {
                            element.style.width = '';
                            element.style.height = '';
                        }
                    } else {
                        element.style.width = '';
                        element.style.height = '';
                    }
                    // Keep display as is (based on checkbox state)
                    
                    // Update hidden input
                    updateHiddenInput(element);
                });
                
                // Reset formatting panel
                if (selectedElement) {
                    updateFormattingPanel();
                    positionResizeHandle();
                }
                
                // Clear history and save new default state
                historyStack = [];
                historyIndex = -1;
                saveState();
                updateUndoRedoButtons();
                
                // Show success message
                alert('{{ __("Design settings have been reset to default.") }}');
            }
            
            // Keyboard shortcuts (Ctrl+Z for undo, Ctrl+Y for redo)
            $(document).on('keydown', function(e) {
                if (e.ctrlKey || e.metaKey) {
                    if (e.key === 'z' && !e.shiftKey) {
                        e.preventDefault();
                        undo();
                    } else if (e.key === 'y' || (e.key === 'z' && e.shiftKey)) {
                        e.preventDefault();
                        redo();
                    }
                }
            });
            
            // Migrate old format styles (style="...") to new format (just CSS props)
            function migrateOldStyles() {
                $('input[type="hidden"][name*="_id_card_styles"]').each(function() {
                    let value = $(this).val();
                    if (value && value.startsWith('style="')) {
                        // Extract CSS properties from style="..."
                        let css = value.replace(/^style="/, '').replace(/"$/, '');
                        // Remove duplicate position:absolute if present
                        css = css.replace(/position:\s*absolute;\s*/gi, '');
                        $(this).val(css);
                        console.log('Migrated old style format for:', $(this).attr('id'));
                    }
                });
            }
            
            // Initialize hidden inputs from element styles on page load
            function initializeHiddenInputs() {
                document.querySelectorAll('.draggableItem').forEach(element => {
                    updateHiddenInput(element);
                });
            }
            
            // Run migration on page load
            migrateOldStyles();
            
            // Save initial state
            setTimeout(() => {
                saveState();
            }, 1000);
            
            // Trigger change on load to set initial state
            setTimeout(() => {
                $('.type').trigger('change');
                // Initialize checkbox states to show/hide elements correctly
                initializeCheckboxStates();
                updateDesignSize();
            }, 500);

            $('.type').change(function(e) {
                e.preventDefault();
                let type = $('input[name="type"]:checked').val();
                if (type == 'Student') {
                    $('#student-id-card').slideDown(500);
                    $('#staff-id-card').slideUp(500);
                }
                if (type == 'Staff') {
                    $('#student-id-card').slideUp(500);
                    $('#staff-id-card').slideDown(500);
                }
            });

            // Advanced Resize & Drag Logic
            let isDragging = false;
            let isResizing = false;
            let currentElement = null; // Element being dragged
            let selectedElement = null; // Element currently selected
            let currentContainer = null;
            let currentResizeHandle = null;
            let offsetX, offsetY;
            let startX, startY, startWidth, startHeight, startFontSize;

            // Initialize resize handles for both containers
            const studentContainer = document.getElementById('student_draggableElements');
            const staffContainer = document.getElementById('staff_draggableElements');
            
            const studentResizeHandle = document.createElement('div');
            studentResizeHandle.classList.add('resize-handle');
            studentContainer.appendChild(studentResizeHandle);
            
            const staffResizeHandle = document.createElement('div');
            staffResizeHandle.classList.add('resize-handle');
            staffContainer.appendChild(staffResizeHandle);

            // Add mousedown to handles
            [studentResizeHandle, staffResizeHandle].forEach(handle => {
                handle.addEventListener('mousedown', (e) => {
                    if (!selectedElement) return;
                    
                    e.stopPropagation();
                    e.preventDefault();
                    
                    isResizing = true;
                    startX = e.clientX;
                    startY = e.clientY;
                    startWidth = selectedElement.offsetWidth;
                    startHeight = selectedElement.offsetHeight;
                    
                    const computedStyle = getComputedStyle(selectedElement);
                    startFontSize = parseFloat(computedStyle.fontSize);
                });
            });

            // Add event listeners to all draggable items
            document.querySelectorAll('.draggableItem').forEach(element => {
                element.addEventListener('mousedown', (e) => {
                    // If clicking on resize handle, ignore
                    if (e.target.classList.contains('resize-handle')) return;
                    
                    e.stopPropagation();
                    
                    // Select this element
                    selectElement(element);
                    
                    isDragging = true;
                    currentElement = element;
                    currentContainer = element.closest('.design');
                    
                    offsetX = e.clientX - element.getBoundingClientRect().left;
                    offsetY = e.clientY - element.getBoundingClientRect().top;
                });
            });

            document.addEventListener('mousemove', (e) => {
                if (isResizing && selectedElement) {
                    const deltaX = e.clientX - startX;
                    const deltaY = e.clientY - startY;
                    
                    let newWidth = startWidth + deltaX;
                    let newHeight = startHeight + deltaY;
                    
                    newWidth = Math.max(20, newWidth);
                    newHeight = Math.max(20, newHeight);
                    
                    if (selectedElement.tagName === 'IMG') {
                        selectedElement.style.width = newWidth + 'px';
                        selectedElement.style.height = newHeight + 'px';
                    } else {
                        selectedElement.style.width = newWidth + 'px';
                        if (Math.abs(newWidth - startWidth) > 1) {
                            const scaleFactor = newWidth / startWidth;
                            const newFontSize = startFontSize * scaleFactor;
                            selectedElement.style.fontSize = newFontSize + 'px';
                        }
                    }
                    
                    positionResizeHandle();
                    updateFormattingPanel();
                    
                } else if (isDragging && currentElement && currentContainer) {
                    const containerRect = currentContainer.getBoundingClientRect();
                    const elemRect = currentElement.getBoundingClientRect();

                    let newLeft = e.clientX - containerRect.left - offsetX;
                    let newTop = e.clientY - containerRect.top - offsetY;

                    newLeft = Math.max(0, Math.min(newLeft, containerRect.width - 10));
                    newTop = Math.max(0, Math.min(newTop, containerRect.height - 10));

                    currentElement.style.left = newLeft + 'px';
                    currentElement.style.top = newTop + 'px';
                    
                    if (currentElement === selectedElement) {
                        positionResizeHandle();
                    }
                }
            });

            document.addEventListener('mouseup', () => {
                if (isDragging && currentElement) {
                    updateHiddenInput(currentElement);
                    saveState(); // Save state after drag
                    isDragging = false;
                    currentElement = null;
                    currentContainer = null;
                }
                
                if (isResizing && selectedElement) {
                    updateHiddenInput(selectedElement);
                    saveState(); // Save state after resize
                    isResizing = false;
                }
            });
            
            // Click outside to deselect
            document.addEventListener('mousedown', (e) => {
                if (!e.target.closest('.draggableItem') && !e.target.classList.contains('resize-handle') && !e.target.closest('.format-control') && !e.target.closest('.format-align') && !e.target.closest('.format-style') && !e.target.closest('#clear-bg-color')) {
                    deselectElement();
                }
            });

            function selectElement(element) {
                if (selectedElement) {
                    selectedElement.classList.remove('selected-item');
                }
                
                selectedElement = element;
                selectedElement.classList.add('selected-item');
                
                // Determine which handle to use
                const container = element.closest('.design');
                if (container.id === 'student_draggableElements') {
                    currentResizeHandle = studentResizeHandle;
                    staffResizeHandle.style.display = 'none';
                } else {
                    currentResizeHandle = staffResizeHandle;
                    studentResizeHandle.style.display = 'none';
                }
                
                currentResizeHandle.style.display = 'block';
                positionResizeHandle();
                updateFormattingPanel();
            }
            
            function deselectElement() {
                if (selectedElement) {
                    selectedElement.classList.remove('selected-item');
                    selectedElement = null;
                }
                if (studentResizeHandle) studentResizeHandle.style.display = 'none';
                if (staffResizeHandle) staffResizeHandle.style.display = 'none';
                currentResizeHandle = null;
                
                // Reset formatting panel
                $('#selected-element-name').text('{{ __("None") }}');
                $('.format-align').removeClass('active btn-primary').addClass('btn-outline-secondary');
                $('.format-style').removeClass('active btn-primary').addClass('btn-outline-secondary');
            }
            
            function positionResizeHandle() {
                if (!selectedElement || !currentResizeHandle) return;
                
                const rect = selectedElement.getBoundingClientRect();
                const container = selectedElement.closest('.design');
                const containerRect = container.getBoundingClientRect();
                
                const left = rect.left - containerRect.left + rect.width - 5;
                const top = rect.top - containerRect.top + rect.height - 5;
                
                currentResizeHandle.style.left = left + 'px';
                currentResizeHandle.style.top = top + 'px';
            }
            
            // Update formatting panel based on selected element
            function updateFormattingPanel() {
                if (!selectedElement) return;
                
                const computedStyle = getComputedStyle(selectedElement);
                const elementId = selectedElement.id;
                
                // Display element name
                let elementName = elementId.replace('student_item_', '').replace('staff_item_', '').replace(/_/g, ' ');
                elementName = elementName.charAt(0).toUpperCase() + elementName.slice(1);
                $('#selected-element-name').text(elementName);
                
                // Font size
                const fontSize = parseFloat(computedStyle.fontSize);
                $('#format-font-size').val(Math.round(fontSize));
                
                // Text color
                const textColor = rgbToHex(computedStyle.color);
                $('#format-text-color').val(textColor);
                
                // Background color
                const bgColor = computedStyle.backgroundColor;
                if (bgColor && bgColor !== 'rgba(0, 0, 0, 0)' && bgColor !== 'transparent') {
                    $('#format-bg-color').val(rgbToHex(bgColor));
                } else {
                    $('#format-bg-color').val('#ffffff');
                }
                
                // Border
                const borderWidth = parseFloat(computedStyle.borderWidth) || 0;
                $('#format-border-width').val(Math.round(borderWidth));
                const borderColor = computedStyle.borderColor;
                if (borderColor) {
                    $('#format-border-color').val(rgbToHex(borderColor));
                }
                
                // Text alignment
                // Determine horizontal alignment by element position inside its design container
                try {
                    const container = selectedElement.closest('.design');
                    const containerRect = container.getBoundingClientRect();
                    const elemRect = selectedElement.getBoundingClientRect();
                    const relLeft = elemRect.left - containerRect.left;
                    const centerLeft = Math.round((containerRect.width - elemRect.width) / 2);
                    const rightLeft = Math.round(containerRect.width - elemRect.width);
                    const tolerance = 12;

                    let align = 'left';
                    if (Math.abs(relLeft - centerLeft) <= tolerance) {
                        align = 'center';
                    } else if (Math.abs(relLeft - rightLeft) <= tolerance) {
                        align = 'right';
                    } else {
                        // fallback to computed textAlign if not close to edges
                        align = (computedStyle.textAlign && computedStyle.textAlign !== 'start') ? computedStyle.textAlign : 'left';
                    }

                    $('.format-align').removeClass('active btn-primary').addClass('btn-outline-secondary');
                    $(`.format-align[data-align="${align}"]`).addClass('active btn-primary').removeClass('btn-outline-secondary');
                } catch (err) {
                    const textAlign = computedStyle.textAlign || 'left';
                    $('.format-align').removeClass('active btn-primary').addClass('btn-outline-secondary');
                    $(`.format-align[data-align="${textAlign}"]`).addClass('active btn-primary').removeClass('btn-outline-secondary');
                }
                
                // Text styles
                const fontWeight = computedStyle.fontWeight;
                const fontStyle = computedStyle.fontStyle;
                const textDecoration = computedStyle.textDecoration;
                
                $('.format-style').removeClass('active btn-primary').addClass('btn-outline-secondary');
                if (fontWeight === 'bold' || parseInt(fontWeight) >= 700) {
                    $('.format-style[data-style="bold"]').addClass('active btn-primary').removeClass('btn-outline-secondary');
                }
                if (fontStyle === 'italic') {
                    $('.format-style[data-style="italic"]').addClass('active btn-primary').removeClass('btn-outline-secondary');
                }
                if (textDecoration.includes('underline')) {
                    $('.format-style[data-style="underline"]').addClass('active btn-primary').removeClass('btn-outline-secondary');
                }
                
                // Width and Height
                const width = parseFloat(selectedElement.style.width) || selectedElement.offsetWidth;
                const height = parseFloat(selectedElement.style.height) || selectedElement.offsetHeight;
                $('#format-width').val(Math.round(width));
                $('#format-height').val(Math.round(height));
            }
            
            // Convert RGB to Hex
            function rgbToHex(rgb) {
                if (!rgb) return '';
                if (rgb.startsWith('#')) return rgb;
                if (rgb === 'transparent' || rgb === 'rgba(0, 0, 0, 0)') return '';
                const result = rgb.match(/\d+/g);
                if (!result || result.length < 3) return '#000000';
                return '#' + result.slice(0, 3).map(x => {
                    const hex = parseInt(x).toString(16);
                    return hex.length === 1 ? '0' + hex : hex;
                }).join('');
            }
            
            // Convert any color value to hex for DOMPDF compatibility
            function toHexColor(color) {
                if (!color || color === 'transparent' || color === 'rgba(0, 0, 0, 0)') return '';
                return rgbToHex(color);
            }
            
            // Debounce function for saving state (to avoid too many history entries)
            let saveStateTimeout;
            function debouncedSaveState() {
                clearTimeout(saveStateTimeout);
                saveStateTimeout = setTimeout(() => {
                    saveState();
                }, 300); // Save state 300ms after last change
            }
            
            // Format control event handlers - use event delegation
            $(document).on('input change', '#format-font-size', function() {
                if (!selectedElement) {
                    console.warn('No element selected');
                    return;
                }
                if (selectedElement.tagName === 'IMG') {
                    console.warn('Cannot apply font size to images');
                    return;
                }
                selectedElement.style.fontSize = $(this).val() + 'px';
                updateHiddenInput(selectedElement);
                debouncedSaveState();
            });
            
            $(document).on('input change', '#format-text-color', function() {
                if (!selectedElement) {
                    console.warn('No element selected');
                    return;
                }
                if (selectedElement.tagName === 'IMG') {
                    console.warn('Cannot apply text color to images');
                    return;
                }
                selectedElement.style.color = $(this).val();
                updateHiddenInput(selectedElement);
                debouncedSaveState();
            });
            
            $(document).on('input change', '#format-bg-color', function() {
                if (!selectedElement) {
                    console.warn('No element selected');
                    return;
                }
                if (selectedElement.tagName === 'IMG') {
                    console.warn('Cannot apply background color to images');
                    return;
                }
                selectedElement.style.backgroundColor = $(this).val();
                updateHiddenInput(selectedElement);
                debouncedSaveState();
            });
            
            $(document).on('click', '#clear-bg-color', function() {
                if (!selectedElement) {
                    console.warn('No element selected');
                    return;
                }
                if (selectedElement.tagName === 'IMG') {
                    console.warn('Cannot apply background color to images');
                    return;
                }
                selectedElement.style.backgroundColor = 'transparent';
                $('#format-bg-color').val('#ffffff');
                updateHiddenInput(selectedElement);
                saveState();
            });
            
            $(document).on('input change', '#format-border-width', function() {
                if (!selectedElement) {
                    console.warn('No element selected');
                    return;
                }
                const width = $(this).val();
                if (width > 0) {
                    selectedElement.style.borderWidth = width + 'px';
                    selectedElement.style.borderStyle = 'solid';
                } else {
                    selectedElement.style.border = 'none';
                }
                updateHiddenInput(selectedElement);
                debouncedSaveState();
            });
            
            $(document).on('input change', '#format-border-color', function() {
                if (!selectedElement) {
                    console.warn('No element selected');
                    return;
                }
                if (selectedElement.style.borderWidth && parseFloat(selectedElement.style.borderWidth) > 0) {
                    selectedElement.style.borderColor = $(this).val();
                    updateHiddenInput(selectedElement);
                    debouncedSaveState();
                }
            });
            
            $(document).on('click', '.format-align', function() {
                if (!selectedElement) {
                    console.warn('No element selected');
                    return;
                }
                const align = $(this).data('align');

                // Try to reposition element horizontally within its design container
                try {
                    const container = selectedElement.closest('.design');
                    const containerRect = container.getBoundingClientRect();
                    const elemRect = selectedElement.getBoundingClientRect();
                    const padding = 8; // inner padding

                    let newLeft = padding;
                    if (align === 'center') {
                        newLeft = Math.max(0, Math.round((containerRect.width - elemRect.width) / 2));
                    } else if (align === 'right') {
                        newLeft = Math.max(0, Math.round(containerRect.width - elemRect.width - padding));
                    } else {
                        newLeft = padding;
                    }

                    selectedElement.style.left = newLeft + 'px';
                } catch (err) {
                    // ignore positioning errors
                }

                // For text elements, also set inner text alignment so content aligns
                if (selectedElement.tagName !== 'IMG') {
                    selectedElement.style.textAlign = align;
                }

                $('.format-align').removeClass('active btn-primary').addClass('btn-outline-secondary');
                $(this).addClass('active btn-primary').removeClass('btn-outline-secondary');
                positionResizeHandle();
                updateHiddenInput(selectedElement);
                saveState();
            });
            
            $(document).on('click', '.format-style', function() {
                if (!selectedElement) {
                    console.warn('No element selected');
                    return;
                }
                if (selectedElement.tagName === 'IMG') {
                    console.warn('Cannot apply text styles to images');
                    return;
                }
                const style = $(this).data('style');
                const isActive = $(this).hasClass('active');
                
                if (style === 'bold') {
                    selectedElement.style.fontWeight = isActive ? 'normal' : 'bold';
                } else if (style === 'italic') {
                    selectedElement.style.fontStyle = isActive ? 'normal' : 'italic';
                } else if (style === 'underline') {
                    selectedElement.style.textDecoration = isActive ? 'none' : 'underline';
                }
                
                $(this).toggleClass('active btn-primary btn-outline-secondary');
                updateHiddenInput(selectedElement);
                saveState();
            });
            
            $(document).on('input change', '#format-width', function() {
                if (!selectedElement) {
                    console.warn('No element selected');
                    return;
                }
                selectedElement.style.width = $(this).val() + 'px';
                positionResizeHandle();
                updateHiddenInput(selectedElement);
                debouncedSaveState();
            });
            
            $(document).on('input change', '#format-height', function() {
                if (!selectedElement) {
                    console.warn('No element selected');
                    return;
                }
                selectedElement.style.height = $(this).val() + 'px';
                positionResizeHandle();
                updateHiddenInput(selectedElement);
                debouncedSaveState();
            });

            function updateHiddenInput(element) {
                let type = element.id; // e.g., student_item_student_name
                // Save only the CSS properties (not the style="..." wrapper)
                let cssProps = '';
                
                // Position
                if (element.style.left) cssProps += 'left:' + element.style.left + ';';
                if (element.style.top) cssProps += 'top:' + element.style.top + ';';
                
                // Size
                if (element.style.width) cssProps += 'width:' + element.style.width + ';';
                if (element.style.height) cssProps += 'height:' + element.style.height + ';';
                
                // Font
                if (element.style.fontSize) cssProps += 'font-size:' + element.style.fontSize + ';';
                if (element.style.fontWeight && element.style.fontWeight !== 'normal' && element.style.fontWeight !== '400') cssProps += 'font-weight:' + element.style.fontWeight + ';';
                if (element.style.fontStyle && element.style.fontStyle !== 'normal') cssProps += 'font-style:' + element.style.fontStyle + ';';
                
                // Text - Convert colors to hex for DOMPDF
                if (element.style.color) {
                    let hexColor = toHexColor(element.style.color);
                    if (hexColor) cssProps += 'color:' + hexColor + ';';
                }
                if (element.style.textAlign) cssProps += 'text-align:' + element.style.textAlign + ';';
                if (element.style.textDecoration && element.style.textDecoration !== 'none' && !element.style.textDecoration.includes('none')) cssProps += 'text-decoration:' + element.style.textDecoration + ';';
                
                // Background - Convert colors to hex for DOMPDF
                if (element.style.backgroundColor && element.style.backgroundColor !== 'transparent' && element.style.backgroundColor !== 'rgba(0, 0, 0, 0)') {
                    let hexBgColor = toHexColor(element.style.backgroundColor);
                    if (hexBgColor) cssProps += 'background-color:' + hexBgColor + ';';
                }
                
                // Border - Convert colors to hex for DOMPDF
                if (element.style.borderWidth && parseFloat(element.style.borderWidth) > 0) {
                    cssProps += 'border-width:' + element.style.borderWidth + ';';
                    cssProps += 'border-style:' + (element.style.borderStyle || 'solid') + ';';
                    if (element.style.borderColor) {
                        let hexBorderColor = toHexColor(element.style.borderColor);
                        if (hexBorderColor) cssProps += 'border-color:' + hexBorderColor + ';';
                    }
                }
                
                // Replace item_ with style_ to match hidden input ID
                let inputId = type.replace('item_', "style_");
                $('#' + inputId).val(cssProps);
                
                console.log('Updated hidden input:', inputId, cssProps); // Debug log
            }

            $('.feature-checkbox').change(function (e) {
                // Check if it's student or staff
                let isStudent = $(this).closest('#student-id-card').length > 0;
                let prefix = isStudent ? 'student_item_' : 'staff_item_';
                
                let value = $(this).val();
                let field = '#' + prefix + value;
                
                let status = $(this).is(':checked');
                let targetElement = $(field);
                
                // For extra form fields, the value is the field ID, so the element ID is student_item_{field_id}
                if (!targetElement.length && $(this).attr('name') === 'extra_form_fields[]') {
                    // Already using the correct format, try again
                    field = '#' + prefix + value;
                    targetElement = $(field);
                }
                
                if (targetElement.length) {
                    if (status) {
                        targetElement.css('display', 'block');
                    } else {
                        targetElement.css('display', 'none');
                    }
                    
                    // Update hidden input after toggling visibility
                    setTimeout(() => {
                        if (targetElement[0]) {
                            updateHiddenInput(targetElement[0]);
                        }
                        saveState(); // Save state after checkbox change
                    }, 100);
                } else {
                    console.warn('Element not found for checkbox change:', field, 'Value:', value, 'Name:', $(this).attr('name'));
                }
            });
            
            // Initialize checkbox states on page load
            function initializeCheckboxStates() {
                $('.feature-checkbox').each(function() {
                    let isStudent = $(this).closest('#student-id-card').length > 0;
                    let prefix = isStudent ? 'student_item_' : 'staff_item_';
                    let value = $(this).val();
                    let field = '#' + prefix + value;
                    let targetElement = $(field);
                    
                    // For extra form fields, the value is the field ID
                    if (!targetElement.length && $(this).attr('name') === 'extra_form_fields[]') {
                        field = '#' + prefix + value;
                        targetElement = $(field);
                    }
                    
                    if (targetElement.length) {
                        let isChecked = $(this).is(':checked');
                        // Force update visibility based on checkbox state
                        if (isChecked) {
                            targetElement.css('display', 'block');
                        } else {
                            targetElement.css('display', 'none');
                        }
                    } else {
                        // If element not found, log for debugging
                        console.log('Element not found for checkbox:', field, 'Value:', value, 'Name:', $(this).attr('name'));
                    }
                });
            }

            // Dynamic Size
            function updateDesignSize() {
                let mmToPx = 3.78; 
                
                // Student
                let s_w = $('#student_page_width').val();
                let s_h = $('#student_page_height').val();
                if(s_w && s_h) {
                    $('#student_draggableElements').css({
                        width: (s_w * mmToPx) + 'px', 
                        height: (s_h * mmToPx) + 'px'
                    });
                }

                // Staff
                let st_w = $('#staff_page_width').val();
                let st_h = $('#staff_page_height').val();
                if(st_w && st_h) {
                    $('#staff_draggableElements').css({
                        width: (st_w * mmToPx) + 'px', 
                        height: (st_h * mmToPx) + 'px'
                    });
                }
            }

            $('#student_page_width, #student_page_height, #staff_page_width, #staff_page_height').on('input change', updateDesignSize);
            
            // Color Updates - Staff only (Header/Footer removed from design)
            $('input[name="staff_header_color"]').on('input change', function() {
                let staffHeader = $('#staff_item_header');
                if (staffHeader.length) {
                    staffHeader.css('background-color', $(this).val());
                }
            });
            $('input[name="staff_footer_color"]').on('input change', function() {
                let staffFooter = $('#staff_item_footer');
                if (staffFooter.length) {
                    staffFooter.css('background-color', $(this).val());
                }
            });
            $('input[name="staff_header_footer_text_color"]').on('input change', function() {
                let staffHeader = $('#staff_item_header');
                let staffFooter = $('#staff_item_footer');
                if (staffHeader.length) staffHeader.css('color', $(this).val());
                if (staffFooter.length) staffFooter.css('color', $(this).val());
            });
        });
    </script>
@endsection
@section('css')
    <style>
        .design {
            width: 380px;
            height: 250px; 
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            background-color: white;
            border: 1px solid #ddd;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }

        .frame-border {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            border: 1px solid black;
            pointer-events: none;
            z-index: 0;
        }

        .draggableItem {
            position: absolute;
            cursor: move;
            white-space: nowrap;
            font-size: 12px;
            z-index: 10;
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
        
        /* Undo/Redo/Reset button styles */
        #undo-btn:disabled, #redo-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        #reset-design-btn {
            margin-left: 10px;
        }
        
        #reset-design-btn {
            margin-left: 10px;
        }
    </style>
@endsection
