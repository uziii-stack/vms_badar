@auth
@extends('layouts.layout')
@section('content')
<!-- include summernote css/js -->
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Create New Template</h2>
            <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Template Name <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter template name"
                                required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type Field -->
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Template Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror"
                                id="type"
                                name="type"
                                required>
                                <option value="">Select Type</option>
                                <option value="Invitation Letter" {{ old('type') == 'Invitation Letter' ? 'selected' : '' }}>Invitation Letter</option>
                                <option value="E-Badge" {{ old('type') == 'E-Badge' ? 'selected' : '' }}>E-Badge</option>
                                <option value="A4 E-Badge" {{ old('type') == 'A4 E-Badge' ? 'selected' : '' }}>A4 E-Badge</option>
                                <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 event-template-field">
                            <label for="event_id" class="form-label">Event <span class="text-danger">*</span></label>
                            <select class="form-select @error('event_id') is-invalid @enderror"
                                id="event_id"
                                name="event_id">
                                <option value="">Select Event</option>
                                @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->display_name }} ({{ $event->name }})
                                </option>
                                @endforeach
                            </select>
                            @error('event_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Image Fields -->
                    <div class="row">
                        <!-- Image 1 -->
                        <div class="col-md-4 mb-3">
                            <label for="image_1" class="form-label">
                                Image 1 (Header) <span class="text-danger"></span>
                            </label>
                            <input type="file"
                                class="form-control @error('image_1') is-invalid @enderror"
                                id="image_1"
                                name="image_1"
                                accept="image/*"
                                onchange="previewImage(this, 'preview_1')">
                            @error('image_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="preview_1" class="img-thumbnail mt-2" style="max-height: 100px; display: none;">
                        </div>

                        <!-- Image 2 -->
                        <div class="col-md-4 mb-3">
                            <label for="image_2" class="form-label">
                                Image 2 (Logo) <span class="text-danger"></span>
                            </label>
                            <input type="file"
                                class="form-control @error('image_2') is-invalid @enderror"
                                id="image_2"
                                name="image_2"
                                accept="image/*"
                                onchange="previewImage(this, 'preview_2')">
                            @error('image_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="preview_2" class="img-thumbnail mt-2" style="max-height: 100px; display: none;">
                        </div>

                        <!-- Image 3 -->
                        <div class="col-md-4 mb-3">
                            <label for="image_3" class="form-label">
                                Image 3 (Footer) <span class="text-danger"></span>
                            </label>
                            <input type="file"
                                class="form-control @error('image_3') is-invalid @enderror"
                                id="image_3"
                                name="image_3"
                                accept="image/*"
                                onchange="previewImage(this, 'preview_3')">
                            @error('image_3')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="preview_3" class="img-thumbnail mt-2" style="max-height: 100px; display: none;">
                        </div>
                    </div>

                    <!-- Content Field with Summernote -->
                    <div class="mb-3">
                        <label for="content" class=" form-label">Template Content Section 1 <span class="text-danger">*</span></label>
                        <textarea class="summernote form-control @error('head_content') is-invalid @enderror"
                            id="summernote1"
                            name="head_content">{!! old('head_content') !!}</textarea>
                        @error('head_content')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content Field with Summernote -->
                    <div class="mb-3">
                        <label for="content" class=" form-label">Template Content Section 2 <span class="text-danger">*</span></label>
                        <textarea class="summernote form-control @error('body_content') is-invalid @enderror"
                            id="summernote2"
                            name="body_content">{!! old('body_content') !!}</textarea>
                        @error('body_content')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content Field with Summernote -->
                    <div class="mb-3 non-a4-template-field">
                        <label for="content" class="form-label">Template Content Section 3 <span class="text-danger">*</span></label>
                        <textarea class="summernote form-control @error('foot_content') is-invalid @enderror"
                            id="summernote3"
                            name="foot_content">{!! old('foot_content') !!}</textarea>
                        @error('foot_content')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 d-none">
                        <label for="section_4_content" class="form-label">Template Content Section 4 <span class="text-danger">*</span></label>
                        <textarea class="summernote form-control @error('section_4_content') is-invalid @enderror"
                            id="summernote4"
                            name="section_4_content">{!! old('section_4_content') !!}</textarea>
                        @error('section_4_content')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Create Template
                        </button>
                        <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Image preview function
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Initialize Summernote
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        const typeInput = document.getElementById('type');
        const eventFields = document.querySelectorAll('.event-template-field');
        const nonA4Fields = document.querySelectorAll('.non-a4-template-field');

        function toggleA4Fields() {
            const isA4 = typeInput.value === 'A4 E-Badge';
            eventFields.forEach(field => field.style.display = isA4 ? '' : 'none');
            nonA4Fields.forEach(field => field.style.display = isA4 ? 'none' : '');
        }

        typeInput.addEventListener('change', toggleA4Fields);
        toggleA4Fields();
    });
</script>
@endsection
@endauth
