@extends('layouts.layout')

@auth
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Template</h2>
            <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('templates.update', $template) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Template Name <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name', $template->name) }}"
                                placeholder="Enter template name"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type Field -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Template Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                                <option value="">Select Type</option>
                                <option value="Invitation Letter" {{ old('type', $template->type) == 'Invitation Letter' ? 'selected' : '' }}>Invitation Letter</option>
                                <option value="E-Badge" {{ old('type', $template->type) == 'E-Badge' ? 'selected' : '' }}>E-Badge</option>
                                <option value="A4 E-Badge" {{ old('type', $template->type) == 'A4 E-Badge' ? 'selected' : '' }}>A4 E-Badge</option>
                                <option value="Other" {{ old('type', $template->type) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 event-template-field">
                            <label class="form-label">Event <span class="text-danger">*</span></label>
                            <select class="form-select @error('event_id') is-invalid @enderror" name="event_id">
                                <option value="">Select Event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id', $template->event_id) == $event->id ? 'selected' : '' }}>
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
                            <label class="form-label">Image 1 (Header)</label>
                            <input type="file" class="form-control @error('image_1') is-invalid @enderror" name="image_1" accept="image/*" onchange="previewImage(this, 'preview_1')">
                            @error('image_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="mt-2">
                                @if($template->image_1)
                                    <img id="preview_1" src="{{ Storage::url($template->image_1) }}" class="img-thumbnail" style="max-height: 100px;">
                                    <small class="d-block text-muted">Current image (upload new to replace)</small>
                                @else
                                    <img id="preview_1" class="img-thumbnail" style="max-height: 100px; display:none;">
                                @endif
                            </div>
                        </div>

                        <!-- Image 2 -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Image 2 (Logo)</label>
                            <input type="file" class="form-control @error('image_2') is-invalid @enderror" name="image_2" accept="image/*" onchange="previewImage(this, 'preview_2')">
                            @error('image_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="mt-2">
                                @if($template->image_2)
                                    <img id="preview_2" src="{{ Storage::url($template->image_2) }}" class="img-thumbnail" style="max-height: 100px;">
                                    <small class="d-block text-muted">Current image (upload new to replace)</small>
                                @else
                                    <img id="preview_2" class="img-thumbnail" style="max-height: 100px; display:none;">
                                @endif
                            </div>
                        </div>

                        <!-- Image 3 -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Image 3 (Footer)</label>
                            <input type="file" class="form-control @error('image_3') is-invalid @enderror" name="image_3" accept="image/*" onchange="previewImage(this, 'preview_3')">
                            @error('image_3')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="mt-2">
                                @if($template->image_3)
                                    <img id="preview_3" src="{{ Storage::url($template->image_3) }}" class="img-thumbnail" style="max-height: 100px;">
                                    <small class="d-block text-muted">Current image (upload new to replace)</small>
                                @else
                                    <img id="preview_3" class="img-thumbnail" style="max-height: 100px; display:none;">
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Content Section 1 -->
                    <div class="mb-3">
                        <label class="form-label">Template Content Section 1 <span class="text-danger">*</span></label>
                        <textarea class="summernote form-control @error('head_content') is-invalid @enderror"
                            id="summernote1"
                            name="head_content">{!! old('head_content', $template->head_content) !!}</textarea>
                        @error('head_content')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content Section 2 -->
                    <div class="mb-3">
                        <label class="form-label">Template Content Section 2 <span class="text-danger">*</span></label>
                        <textarea class="summernote form-control @error('body_content') is-invalid @enderror"
                            id="summernote2"
                            name="body_content">{!! old('body_content', $template->body_content) !!}</textarea>
                        @error('body_content')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content Section 3 -->
                    <div class="mb-3 non-a4-template-field">
                        <label class="form-label">Template Content Section 3 <span class="text-danger">*</span></label>
                        <textarea class="summernote form-control @error('foot_content') is-invalid @enderror"
                            id="summernote3"
                            name="foot_content">{!! old('foot_content', $template->foot_content) !!}</textarea>
                        @error('foot_content')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 d-none">
                        <label class="form-label">Template Content Section 4 <span class="text-danger">*</span></label>
                        <textarea class="summernote form-control @error('section_4_content') is-invalid @enderror"
                            id="summernote4"
                            name="section_4_content">{!! old('section_4_content', $template->section_4_content) !!}</textarea>
                        @error('section_4_content')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Template
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

        const typeInput = document.querySelector('select[name="type"]');
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

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
@endauth
