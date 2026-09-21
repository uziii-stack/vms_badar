@auth
@extends('layouts.layout')
@section("content")


<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Templates</h2>
    <a href="{{ route('templates.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create New Template
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 20%">Name</th>
                        <th style="width: 15%">Type</th>
                        <th style="width: 15%">Event</th>
                        <th style="width: 30%">Images</th>
                        <th style="width: 15%">Created Date</th>
                        <th style="width: 15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($templates as $template)
                    <tr>
                        <td>{{ $loop->iteration + ($templates->currentPage() - 1) * $templates->perPage() }}</td>
                        <td>{{ $template->name }}</td>
                        <td>
                            <span class="badge bg-info">{{ $template->type }}</span>
                        </td>
                        <td>{{ $template->event?->display_name ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                @if ($template->image_1)
                                <img src="{{ Storage::url($template->image_1) }}"
                                    class="img-thumbnail"
                                    style="width: 60px; height: 60px; object-fit: cover;"
                                    alt="Image 1">
                                @endif
                                @if ($template->image_2)
                                <img src="{{ Storage::url($template->image_2) }}"
                                    class="img-thumbnail"
                                    style="width: 60px; height: 60px; object-fit: cover;"
                                    alt="Image 2">
                                @endif
                                @if ($template->image_3)
                                <img src="{{ Storage::url($template->image_3) }}"
                                    class="img-thumbnail"
                                    style="width: 60px; height: 60px; object-fit: cover;"
                                    alt="Image 3">
                                @endif
                            </div>
                        </td>
                        <td>{{ $template->created_at->format('d M, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('templates.edit', $template) }}"
                                    class="btn btn-sm btn-primary"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('templates.destroy', $template) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this template?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No templates found. Create your first template!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $templates->links() }}
        </div>
    </div>
</div>

@endsection
@endauth
