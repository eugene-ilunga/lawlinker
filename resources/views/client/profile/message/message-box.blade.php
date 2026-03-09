<div class="message-wrapper">
    <ul class="messages" id="user-{{userAuth()?->id}}">
        @foreach ($messages as $message)
            <li class="message clearfix">
                <div class="{{ $message?->send_user ? 'sent' : 'received' }}">
                    <p>{{ $message?->message }}</p>
                    <p class="date">{{ formattedDateTime($message?->created_at) }}</p>
                </div>
            </li>
        @endforeach

    </ul>
</div>

<div class="input-text send_text">
    <input type="text" name="message" class="submit">
    <button class="btn btn-primary" id="sentMessageBtn" onclick="sendMessage()">{{ __('Send') }}</button>
</div>
