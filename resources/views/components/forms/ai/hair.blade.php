<div>
    <form method="post" id="ai-form" action="{{ route('ai') }}">
        @csrf
        <input type="hidden" name="category_id" value="{{ $category }}">
        <div class="form-group">
            <label for="hair-length">{{ __('Hair length') }}</label>
            <select class="form-control" id="hair-length" name="hair_length" required>
                <option value="short">{{ __('Short') }}</option>
                <option value="medium">{{ __('Medium') }}</option>
                <option value="long">{{ __('Long') }}</option>
            </select>
        </div>
        <div class="form-group">
            <label for="hair-color">{{ __('Hair color') }}</label>
            <select class="form-control" id="hair-color" name="hair_color" required>
                <option value="red">{{ __('Red') }}</option>
                <option value="pink">{{ __('Pink') }}</option>
                <option value="white">{{ __('White') }}</option>
                <option value="black">{{ __('Black') }}</option>
                <option value="blue">{{ __('Blue') }}</option>
                <option value="green">{{ __('Green') }}</option>
                <option value="yellow">{{ __('Yellow') }}</option>
                <option value="purple">{{ __('Purple') }}</option>
                <option value="orange">{{ __('Orange') }}</option>
                <option value="brown">{{ __('Brown') }}</option>
                <option value="gray">{{ __('Gray') }}</option>
                <option value="silver">{{ __('Silver') }}</option>
                <option value="gold">{{ __('Gold') }}</option>
                <option value="other">{{ __('Other') }}</option>
            </select>
        </div>
        <div class="form-group">
            <label for="hair-style">{{ __('Hair style') }}</label>
            <select class="form-control" id="hair-style" name="hair_style" required>
                <option value="straight">{{ __('Straight') }}</option>
                <option value="curly">{{ __('Curly') }}</option>
                <option value="wavy">{{ __('Wavy') }}</option>
                <option value="other">{{ __('Other') }}</option>
            </select>
        </div>
        <x-input type="submit" value="{{ __('Submit') }}" class="btn btn-primary" />
    </form>
</div>
