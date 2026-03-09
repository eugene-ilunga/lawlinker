<script>
    var PUSHER_APP_KEY = "{{ config('broadcasting.connections.pusher.key') }}";
    var PUSHER_APP_CLUSTER = "{{ config('broadcasting.connections.pusher.options.cluster') }}";
    var PUSHER_PORT= 443;
    var PUSHER_SCHEME= "https"
    var DYNAMIC_URL= "{{url('/broadcasting/auth')}}";
    var auth_id = '';
    @auth('web')
    auth_id = "{{ Auth::guard('web')->user()->id }}";
    @endauth

    var doc_id = '';
    @auth('lawyer')
    doc_id = "{{ lawyerAuth()?->id }}";
    @endauth

    // Function to append a new message
    function appendMessage(data, boxId, userId, prefix) {
        const messageList = $(`#${boxId}`);

        if (messageList.length) {
            const messageWrapper = $('.message-wrapper');

            const messageItem = $(`
                <li class="message clearfix">
                    <div class="received">
                        <p>${data.message}</p>
                        <p class="date">${data.created_at}</p>
                    </div>
                </li>
            `);
            messageList.append(messageItem);

            if (messageWrapper.length) {
                messageWrapper.animate({
                    scrollTop: messageWrapper.get(0).scrollHeight
                }, 50);
            }
            seenMessage(data.sender_id,prefix)
        }else{
            unSeenMessageCount(data.sender_id, data.un_seen);
        }


    }

    function unSeenMessageCount(userId, count) {
        $('.user').each(function() {
            const user_id = $(this).attr('id');
            if (user_id == userId && count) {
                $('.user').removeClass('active');
                $(this).addClass('active');
                $(this).find('.pending').html(count);
                if(count == 1){
                    $(this).find('.pending').removeClass('d-none');
                }
            }
        });
    }


    function seenMessage(userId,prefix) {
        $('.user').each(function() {
            const receiver_id = $(this).attr('id');

            if (receiver_id == userId) {
                $('.user').removeClass('active');

                $(this).addClass('active');

                $(this).find('.pending').addClass('d-none');

                $.ajax({
                    type: "GET",
                    url: "{{ url('/') }}" + `/${prefix}/seen-message/${receiver_id}`,
                    cache: false,
                    success: function(data) {}
                });
            }
        });
    };
</script>
