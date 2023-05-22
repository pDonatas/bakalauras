@extends('layouts.admin')

@section('title') {{ __('Calendar') }} @endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                {{ __('Manage') }}
                            </div>
                        </div>
                        <form method="post" action="{{ route('admin.calendar.update') }}">
                            @csrf
                            <div class="card-body">
                                <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                                <div class="mb-3">
                                    <label for="work_days" class="form-label">{{ __('Work days') }}</label>
                                    <select name="work_days[]" class="form-control" id="work_days" multiple>
                                        <option value="1" {{ in_array(1, $workDays) ? 'selected' : '' }}>{{ __('Monday') }}</option>
                                        <option value="2" {{ in_array(2, $workDays) ? 'selected' : '' }}>{{ __('Tuesday') }}</option>
                                        <option value="3" {{ in_array(3, $workDays) ? 'selected' : '' }}>{{ __('Wednesday') }}</option>
                                        <option value="4" {{ in_array(4, $workDays) ? 'selected' : '' }}>{{ __('Thursday') }}</option>
                                        <option value="5" {{ in_array(5, $workDays) ? 'selected' : '' }}>{{ __('Friday') }}</option>
                                        <option value="6" {{ in_array(6, $workDays) ? 'selected' : '' }}>{{ __('Saturday') }}</option>
                                        <option value="7" {{ in_array(7, $workDays) ? 'selected' : '' }}>{{ __('Sunday') }}</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="from" class="form-label">{{ __('Work time from') }}</label>
                                    <input type="text" pattern="([0-1]{1}[0-9]{1}|20|21|22|23):[0-5]{1}[0-9]{1}" name="from" class="form-control timepicker" id="from" value="{{ $workDay->from ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="to" class="form-label">{{ __('Work time to') }}</label>
                                    <input type="text" pattern="([0-1]{1}[0-9]{1}|20|21|22|23):[0-5]{1}[0-9]{1}" name="to" class="form-control timepicker" id="to" value="{{ $workDay->to ?? '' }}">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                {{ __('Notifications manager') }}
                            </div>
                        </div>
                        <form method="post" action="{{ route('admin.notifications.update') }}">
                            @csrf
                            <div class="card-body">
                                <x-auth-validation-errors class="tw-mb-4" :errors="$errors" />
                                <div class="mb-3 form-group">
                                    <label for="notify_every">
                                        {{ __('Notify every') }}
                                    </label>
                                    <input type="number" name="notify_every" class="form-control" id="notify_every" value="{{ $notifyEvery }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="notify_period">
                                        {{ __('Notify period') }}
                                    </label>
                                    <select id="notify_period" class="form-control" name="notify_period">
                                        <option @if ($notifyPeriod == 0) selected @endif value="1">{{ __('Minutes') }}</option>
                                        <option @if ($notifyPeriod == 1) selected @endif value="2">{{ __('Hours') }}</option>
                                        <option @if ($notifyPeriod == 2) selected @endif value="3">{{ __('Days') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
