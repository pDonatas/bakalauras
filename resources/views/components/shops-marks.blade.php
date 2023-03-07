<div>
    @foreach($marks as $mark)
        <div class="mark">
            <div class="mark-value">{{ $mark->mark }} / 5</div>
            <div class="comment">{{ $mark->comment }}</div>
            <div class="author">
                <img src="{{ $mark->user->photo }}" alt="" />
                <span>{{ $mark->user->name }}</span>
            </div>
        </div>
    @endforeach
</div>
