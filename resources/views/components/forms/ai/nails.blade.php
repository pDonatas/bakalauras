<div>
    <form method="post" id="ai-form" action="{{ route('ai') }}">
        @csrf
        <input type="hidden" name="category_id" value="2">
        <div class="form-group">
            <label for="nails-length">{{ __('Nails length') }}</label>
            <select class="form-control" id="nails-length" name="nails_length" required>
                <option value="short">{{ __('Short') }}</option>
                <option value="medium">{{ __('Medium') }}</option>
                <option value="long">{{ __('Long') }}</option>
            </select>
        </div>
        <div class="form-group">
            <label for="nails-shape">{{ __('Nails shape') }}</label>
            <select class="form-control" id="nails-shape" name="nails_shape" required>
                <option value="square">{{ __('Square') }}</option>
                <option value="round">{{ __('Round') }}</option>
                <option value="oval">{{ __('Oval') }}</option>
                <option value="stiletto">{{ __('Stiletto') }}</option>
            </select>
        </div>
        <div class="form-group">
            <label for="nails-color">{{ __('Nails color') }}</label>
            <select class="form-control" id="nails-color" name="nails_color" required>
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
        <div class="form-group mb-3">
            <label for="nails-design">{{ __('Nails design') }}</label>
            <select class="form-control" id="nails-design" name="nails_design" required>
                <option value="none">{{ __('None') }}</option>
                <option value="glitter">{{ __('Glitter') }}</option>
                <option value="rhinestones">{{ __('Rhinestones') }}</option>
                <option value="flowers">{{ __('Flowers') }}</option>
                <option value="other">{{ __('Other') }}</option>
            </select>
        </div>
        <x-input type="submit" value="{{ __('Submit') }}" class="btn btn-primary" />
    </form>
</div>
