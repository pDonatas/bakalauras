<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewsletterRequest;
use App\Http\Requests\UpdateNewsletterRequest;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(): View
    {
        $newsletters = Newsletter::paginate();

        return view('admin.newsletter.index', compact('newsletters'));
    }

    public function store(CreateNewsletterRequest $request): RedirectResponse
    {
        $newsletter = auth()->user()->newsletters()->create($request->validated());

        User::subscribedToNewsletter()->get()->each->notify(new \App\Notifications\Newsletter($newsletter));

        return redirect()->route('admin.newsletters.index');
    }

    public function show(Newsletter $newsletter): View
    {
        return view('admin.newsletter.show', compact('newsletter'));
    }

    public function edit(Newsletter $newsletter): View
    {
        return view('admin.newsletter.edit', compact('newsletter'));
    }

    public function update(UpdateNewsletterRequest $request, Newsletter $newsletter): RedirectResponse
    {
        $newsletter->update($request->validated());

        User::subscribedToNewsletter()->get()->each->notify(new \App\Notifications\Newsletter($newsletter));

        return redirect()->route('admin.newsletters.index');
    }

    public function destroy(Newsletter $newsletter): RedirectResponse
    {
        $newsletter->delete();
        return redirect()->route('admin.newsletters.index');
    }
}
