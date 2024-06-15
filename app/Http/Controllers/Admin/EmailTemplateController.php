<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = EmailTemplate::get();

        return view('admin.email_templates', compact('templates'));
    }

    public function create()
    {
        return view('admin.add_template');
    }

    public function store(Request $request)
    {
        $request['slug'] = Str::slug($request->title);

        EmailTemplate::create($request->input);

        return redirect()->route('admin.templates')->with('success', 'Template added successfully');
    }
}
