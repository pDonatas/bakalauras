<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationJobRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class NotificationJobsController extends Controller
{
    public function update(NotificationJobRequest $request): RedirectResponse
    {
        $notificationJob = auth()->user()->notificationJob;

        if ($notificationJob) {
            $notificationJob->update($request->validated());
        } else {
            auth()->user()->notificationJob()->create($request->validated());
        }

        return redirect()->route('admin.calendar.manage');
    }
}
