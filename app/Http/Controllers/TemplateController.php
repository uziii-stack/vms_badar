<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Template;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = Template::with('event')->latest()->paginate(10);
        return view('pages.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $events = Event::active()->orderBy('start_date', 'desc')->get();
        return view('pages.templates.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'event_id' => 'nullable|required_if:type,A4 E-Badge|exists:events,id',
            'image_1' => 'sometimes|image|max:2048',
            'image_2' => 'sometimes|image|max:2048',
            'image_3' => 'sometimes|image|max:2048',
            'head_content' => 'required|string',
            'body_content' => 'required|string',
            'foot_content' => 'nullable|required_unless:type,A4 E-Badge|string',
            'section_4_content' => 'nullable|string',
        ], [
            'name.required' => 'Template name is required',
            'type.required' => 'Template type is required',
            'event_id.required_if' => 'Please select an event for A4 E-Badge templates',
            'head_content.required' => 'Template section 1 is required',
            'body_content.required' => 'Template section 2 is required',
            'foot_content.required_unless' => 'Template section 3 is required',
            'image_*.image' => 'File must be an image',
            'image_*.max' => 'Image size must not exceed 2MB',
        ]);

        // Store images
        if ($request->hasFile('image_1')) {
            $validated['image_1'] = $request->file('image_1')->store('letter-templates', 'public');
        }
        if ($request->hasFile('image_2')) {
            $validated['image_2'] = $request->file('image_2')->store('letter-templates', 'public');
        }
        if ($request->hasFile('image_3')) {
            $validated['image_3'] = $request->file('image_3')->store('letter-templates', 'public');
        }


        Template::create($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Template created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
        $events = Event::active()->orderBy('start_date', 'desc')->get();
        return view('pages.templates.edit', compact('template', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'event_id' => 'nullable|required_if:type,A4 E-Badge|exists:events,id',
            'image_1' => 'nullable|image|max:2048',
            'image_2' => 'nullable|image|max:2048',
            'image_3' => 'nullable|image|max:2048',
            'head_content' => 'required|string',
            'body_content' => 'required|string',
            'foot_content' => 'nullable|required_unless:type,A4 E-Badge|string',
            'section_4_content' => 'nullable|string',
        ], [
            'event_id.required_if' => 'Please select an event for A4 E-Badge templates',
            'foot_content.required_unless' => 'Template section 3 is required',
        ]);

        // Handle image updates
        if ($request->hasFile('image_1')) {
            if ($template->image_1) {
                Storage::disk('public')->delete($template->image_1);
            }
            $validated['image_1'] = $request->file('image_1')->store('letter-templates', 'public');
        }

        if ($request->hasFile('image_2')) {
            if ($template->image_2) {
                Storage::disk('public')->delete($template->image_2);
            }
            $validated['image_2'] = $request->file('image_2')->store('letter-templates', 'public');
        }

        if ($request->hasFile('image_3')) {
            if ($template->image_3) {
                Storage::disk('public')->delete($template->image_3);
            }
            $validated['image_3'] = $request->file('image_3')->store('letter-templates', 'public');
        }

        $template->update($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Template updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template deleted successfully!');
    }
}
