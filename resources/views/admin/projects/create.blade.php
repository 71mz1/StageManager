@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ __('Create New Project') }}</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.projects.store') }}" id="projectForm">
                        @csrf

                        <!-- Name Field -->
                        <div class="form-group row mb-3">
                            <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="form-group row mb-3">
                            <label for="description" class="col-md-3 col-form-label text-md-right">{{ __('Description') }} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3" required placeholder="{{ __('Enter project description...') }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Date Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="start_date" class="col-md-6 col-form-label text-md-right">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input id="start_date" type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" required>
                                        @error('start_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="end_date" class="col-md-6 col-form-label text-md-right">{{ __('End Date') }} <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input id="end_date" type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}" required>
                                        @error('end_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Priority Field -->
                        <div class="form-group row mb-3">
                            <label for="priority" class="col-md-3 col-form-label text-md-right">{{ __('Priority') }} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select id="priority" class="form-select @error('priority') is-invalid @enderror" name="priority" required>
                                    <option value="">{{ __('Select Priority') }}</option>
                                    <option value="Basse" {{ old('priority') == 'Basse' ? 'selected' : '' }}>
                                        🟢 {{ __('Low') }}
                                    </option>
                                    <option value="Normale" {{ old('priority', 'Normale') == 'Normale' ? 'selected' : '' }}>
                                        🟡 {{ __('Normal') }}
                                    </option>
                                    <option value="Haute" {{ old('priority') == 'Haute' ? 'selected' : '' }}>
                                        🔴 {{ __('High') }}
                                    </option>
                                </select>
                                @error('priority')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Supervisor Field -->
                        <div class="form-group row mb-3">
                            <label for="encadrant_id" class="col-md-3 col-form-label text-md-right">{{ __('Supervisor') }}</label>
                            <div class="col-md-8">
                                <select id="encadrant_id" class="form-select @error('encadrant_id') is-invalid @enderror" name="encadrant_id">
                                    <option value="">{{ __('Select Supervisor') }}</option>
                                    @if(isset($encadrants) && count($encadrants) > 0)
                                        @foreach($encadrants as $id => $name)
                                            <option value="{{ $id }}" {{ old('encadrant_id') == $id ? 'selected' : '' }}>
                                                👨‍💼 {{ Str::title($name) }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option disabled>{{ __('No supervisors available') }}</option>
                                    @endif
                                </select>
                                @error('encadrant_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Stagiaires Field - محسّن -->
                        <div class="form-group row mb-4">
                            <label for="stagiaires" class="col-md-3 col-form-label text-md-right">{{ __('Assign Stagiaires') }}</label>
                            <div class="col-md-8">
                                <select id="stagiaires" name="stagiaires[]" class="form-select @error('stagiaires') is-invalid @enderror" multiple style="width: 100%;">
                                    @if(isset($stagiaires) && count($stagiaires) > 0)
                                        @foreach($stagiaires as $stagiaire)
                                            @php
                                                // التأكد من وجود البيانات المطلوبة
                                                $id = isset($stagiaire['id']) ? $stagiaire['id'] : (is_array($stagiaire) ? array_keys($stagiaire)[0] : $stagiaire);
                                                $name = isset($stagiaire['name']) ? $stagiaire['name'] : (is_array($stagiaire) ? array_values($stagiaire)[0] : $stagiaire);
                                                $email = isset($stagiaire['email']) ? $stagiaire['email'] : '';
                                                $currentProject = isset($stagiaire['current_project']) ? $stagiaire['current_project'] : null;
                                                $isSelected = in_array($id, old('stagiaires', []));
                                            @endphp
                                            <option value="{{ $id }}" 
                                                    {{ $isSelected ? 'selected' : '' }}
                                                    @if($currentProject) 
                                                        data-current-project="{{ $currentProject }}"
                                                        data-current-project-name="{{ $currentProject }}"
                                                    @endif
                                                    data-email="{{ $email }}"
                                                    title="{{ $name }} {{ $email ? '(' . $email . ')' : '' }} {{ $currentProject ? ' - مُعيَّن حالياً لمشروع: ' . $currentProject : '' }}">
                                                {{ Str::title($name) }}
                                                @if($email)
                                                    ({{ $email }})
                                                @endif
                                                @if($currentProject)
                                                    - ⚠️ {{ __('Currently assigned') }}
                                                @endif
                                            </option>
                                        @endforeach
                                    @else
                                        <option disabled>{{ __('No stagiaires available') }}</option>
                                    @endif
                                </select>
                                <small class="form-text text-muted mt-1">
                                    <i class="fas fa-info-circle"></i> {{ __('Hold Ctrl/Cmd to select multiple stagiaires') }}
                                </small>
                                @error('stagiaires')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                                <!-- عرض المتدربين المحددين -->
                                <div id="selected-stagiaires" class="mt-2" style="display: none;">
                                    <small class="text-info">{{ __('Selected Stagiaires') }}:</small>
                                    <div id="selected-list" class="mt-1"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-3">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save"></i> {{ __('Create Project') }}
                                </button>
                                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* تحسين Select2 */
    .select2-container {
        width: 100% !important;
        z-index: 9999;
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 42px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        background-color: #fff;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e2e6ea;
        border: 1px solid #dae0e5;
        color: #495057;
        padding: 0.25rem 0.5rem;
        margin: 0.125rem 0.25rem 0.125rem 0;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        line-height: 1.5;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 250px;
        display: inline-flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #6c757d;
        margin-left: 0.5rem;
        margin-right: 0;
        cursor: pointer;
        font-weight: bold;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #dc3545;
    }

    .select2-dropdown {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        min-width: 400px !important;
        z-index: 9999;
    }

    .select2-results__option {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        border-bottom: 1px solid #f8f9fa;
    }

    .select2-results__option[data-current-project] {
        background-color: #fff3cd;
        border-left: 3px solid #ffc107;
        color: #856404;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #0d6efd;
        color: white;
    }

    .select2-container--default .select2-search--inline .select2-search__field {
        min-width: 120px !important;
        margin-top: 0;
        height: 30px;
        border: none;
        outline: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        gap: 0.125rem;
        padding: 0;
        margin: 0;
    }

    /* تحسين المظهر العام */
    .form-label {
        font-weight: 500;
        color: #495057;
    }

    .text-danger {
        font-size: 1.1em;
    }

    #selected-stagiaires {
        background-color: #f8f9fa;
        padding: 0.5rem;
        border-radius: 0.375rem;
        border: 1px solid #e9ecef;
    }

    .selected-stagiaire-item {
        display: inline-block;
        background-color: #d1ecf1;
        color: #0c5460;
        padding: 0.25rem 0.5rem;
        margin: 0.125rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        border: 1px solid #bee5eb;
    }

    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
        transition: all 0.15s ease-in-out;
    }

    .btn i {
        margin-right: 0.25rem;
    }

    /* تحسين الاستجابة */
    @media (max-width: 768px) {
        .col-md-3 {
            text-align: left !important;
            margin-bottom: 0.5rem;
        }
        
        .select2-dropdown {
            min-width: 300px !important;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            max-width: 200px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // تحسين Select2 للمتدربين
        $('#stagiaires').select2({
            width: '100%',
            placeholder: "{{ __('Search and select stagiaires...') }}",
            allowClear: true,
            closeOnSelect: false,
            templateResult: formatStagiaire,
            templateSelection: formatStagiaireSelection,
            escapeMarkup: function(markup) {
                return markup;
            }
        });

        // تنسيق عرض المتدربين في القائمة
        function formatStagiaire(stagiaire) {
            if (stagiaire.loading) {
                return stagiaire.text;
            }
            
            const $option = $(stagiaire.element);
            const currentProject = $option.data('current-project');
            const email = $option.data('email');
            
            let html = '<div class="stagiaire-option">';
            html += '<i class="fas fa-user-graduate"></i> ';
            html += '<strong>' + stagiaire.text.split('(')[0].trim() + '</strong>';
            
            if (email) {
                html += '<br><small class="text-muted"><i class="fas fa-envelope"></i> ' + email + '</small>';
            }
            
            if (currentProject) {
                html += '<br><small class="text-warning"><i class="fas fa-exclamation-triangle"></i> {{ __("Currently assigned to") }}: ' + currentProject + '</small>';
            }
            
            html += '</div>';
            
            return $(html);
        }

        // تنسيق عرض المتدربين المحددين
        function formatStagiaireSelection(stagiaire) {
            const $option = $(stagiaire.element);
            const currentProject = $option.data('current-project');
            
            let text = stagiaire.text.split('(')[0].trim();
            if (currentProject) {
                text += ' ⚠️';
            }
            
            return text;
        }

        // عرض قائمة المتدربين المحددين
        $('#stagiaires').on('change', function() {
            const selectedValues = $(this).val();
            const $selectedDiv = $('#selected-stagiaires');
            const $selectedList = $('#selected-list');
            
            if (selectedValues && selectedValues.length > 0) {
                let html = '';
                selectedValues.forEach(function(value) {
                    const $option = $('#stagiaires option[value="' + value + '"]');
                    const name = $option.text().split('(')[0].trim();
                    const currentProject = $option.data('current-project');
                    
                    html += '<span class="selected-stagiaire-item">';
                    html += name;
                    if (currentProject) {
                        html += ' <small>(⚠️ ' + currentProject + ')</small>';
                    }
                    html += '</span>';
                });
                
                $selectedList.html(html);
                $selectedDiv.show();
            } else {
                $selectedDiv.hide();
            }
        });

        // تأكيد عند تعيين متدرب لديه مشروع حالي
        $('#projectForm').on('submit', function(e) {
            const selectedOptions = $('#stagiaires option:selected');
            let conflictingStagiaires = [];
            
            selectedOptions.each(function() {
                const currentProject = $(this).data('current-project');
                if (currentProject) {
                    const name = $(this).text().split('(')[0].trim();
                    conflictingStagiaires.push(name + ' ({{ __("assigned to") }}: ' + currentProject + ')');
                }
            });
            
            if (conflictingStagiaires.length > 0) {
                const message = "{{ __('The following stagiaires are currently assigned to other projects') }}:\n\n" +
                               conflictingStagiaires.join('\n') + 
                               "\n\n{{ __('Assigning them to this project will remove them from their current projects. Continue?') }}";
                
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // تعطيل الزر وإظهار حالة التحميل
            const $submitBtn = $('#submitBtn');
            $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> {{ __("Creating...") }}');
        });

        // التحقق من التواريخ
        $('#start_date, #end_date').on('change', function() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();
            
            if (startDate && endDate) {
                if (new Date(endDate) <= new Date(startDate)) {
                    alert('{{ __("End date must be after start date") }}');
                    $('#end_date').val('');
                }
            }
        });

        // تحديث الحد الأدنى لتاريخ النهاية عند تغيير تاريخ البداية
        $('#start_date').on('change', function() {
            const startDate = $(this).val();
            if (startDate) {
                $('#end_date').attr('min', startDate);
            }
        });

        // إعادة تشغيل Select2 عند إعادة تحميل محتوى AJAX (إذا كان مطلوباً)
        $(document).ajaxComplete(function() {
            if (!$('#stagiaires').hasClass('select2-hidden-accessible')) {
                $('#stagiaires').select2({
                    width: '100%',
                    placeholder: "{{ __('Search and select stagiaires...') }}",
                    allowClear: true,
                    closeOnSelect: false,
                    templateResult: formatStagiaire,
                    templateSelection: formatStagiaireSelection,
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });
            }
        });
    });
</script>
@endpush