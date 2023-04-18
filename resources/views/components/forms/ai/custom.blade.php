<div>
    <form method="post" id="ai-form" action="{{ route('ai') }}">
        @csrf
        <input type="hidden" name="category_id" value="{{ $category }}">
        <div class="form-group">
            <label for="description">{{ __('Description') }}</label>
            <textarea name="description" id="description" class="form-control" rows="3" required
                      placeholder="{{ __('Example: Black hair with red stripes') }}"></textarea>
        </div>
        <x-input type="submit" value="{{ __('Submit') }}" class="btn btn-primary" />
    </form>
</div>
