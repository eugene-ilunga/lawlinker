(function($) {
    "use strict";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // load available appointment in lawyer details
    $(document).on('change', '#datepicker-value', function() {
        var value = $(this).val();
        var lawyer_id = $("#lawyer_id").val();
        var url = base_url + "/get-appointment";
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                'lawyer_id': lawyer_id,
                'date': value
            },
            success: function(response) {
                if (response.success) {
                    $("#lawyer-available-schedule").html(response.success)
                    $("#schedule-box-outer").removeClass('d-none')
                    $("#lawyer-schedule-error").addClass('d-none')
                    $("#sub").prop("disabled", false);
                }
                if (response.error) {
                    $("#schedule-box-outer").addClass('d-none')
                    $("#lawyer-schedule-error").removeClass('d-none')
                    $("#lawyer-schedule-error").html(response.error)
                    $("#sub").prop("disabled", true);
                }

            },
            error: function(err) {}
        });
    });

    //get client message
    $(document).ready(function() {
        $('.user').on('click', function() {
            $('.user').removeClass('active');
            $(this).addClass('active');
            $(this).find('.pending').addClass('d-none');

            receiver_id = $(this).attr('id');
            $.ajax({
                type: "get",
                url: base_url + "/client/get-message/" + receiver_id,
                data: "",
                cache: false,
                success: function(data) {
                    $('#messages').html(data.view);
                    if(!data.unseen_message){
                        $('.beep').removeClass('parent');
                    }
                    scrollToBottomFunc();
                }
            });
        });

        $(document).on('keyup', '.input-text input', function(e) {
            var message = sanitizeInput($(this).val());

            if (message != '') {
                $("#sentMessageBtn").prop("disabled", false);
            } else {
                $("#sentMessageBtn").prop("disabled", true);
            }

            if (e.keyCode == 13 && message != '' && receiver_id != '') {
                $(this).val('');

                var data = {
                    receiver_id: receiver_id,
                    message: message
                };

                sentMessageItemAppend(`#user-${my_id}`,message);

                // project demo mode check
                if (isDemo == 'DEMO') {
                    toastr.error(demo_mode_error);
                    return;
                }
                // end

                $.ajax({
                    type: "POST",
                    url: base_url + "/client/send-message",
                    data: data,
                    cache: false,
                    success: function(data) {
                        scrollToBottomFunc();
                        $('#' + data.lawyer_id).click();

                    },
                    error: function(jqXHR, status, err) {}
                })
            }
        });

    });


    // load available appointment in appoinment model
    $(document).on('change', '#modal-datepicker-value', function() {
        var value = $(this).val();
        var lawyerId = $("#modal_lawyer_id").val();
        var url = base_url + "/get-appointment";
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                'lawyer_id': lawyerId,
                'date': value
            },
            success: function(response) {
                if (response.success) {
                    $("#available-modal-schedule").html(response.success)
                    $("#modal-schedule-box").removeClass('d-none')
                    $("#modal-schedule-error").addClass('d-none')
                    $("#modal-sub").prop("disabled", false);
                }
                if (response.error) {
                    $("#modal-schedule-box").addClass('d-none')
                    $("#modal-schedule-error").removeClass('d-none')
                    $("#modal-schedule-error").html(response.error)
                    $("#modal-sub").prop("disabled", true);


                }
            },
            error: function(err) {}
        });
    });

    // load available appointment in appoinment model
    $(document).on('change', '#mobile-modal-datepicker-value', function() {
        var value = $(this).val();
        var lawyerId = $("#mobile_modal_lawyer_id").val();
        var url = base_url + "/get-appointment";
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                'lawyer_id': lawyerId,
                'date': value
            },
            success: function(response) {
                if (response.success) {
                    $("#available-mobile-modal-schedule").html(response.success)
                    $("#mobile-modal-schedule-box").removeClass('d-none')
                    $("#mobile-modal-schedule-error").addClass('d-none')
                    $("#mobile-modal-sub").prop("disabled", false);
                }
                if (response.error) {
                    $("#mobile-modal-schedule-box").addClass('d-none')
                    $("#mobile-modal-schedule-error").removeClass('d-none')
                    $("#mobile-modal-schedule-error").html(response.error)
                    $("#mobile-modal-sub").prop("disabled", true);
                }
            },
            error: function(err) {}
        });
    });

    $(document).on('click', '#zoom_demo_mode', function() {
        toastr.error(demo_mode_error);
    });
})(jQuery);

function sanitizeInput(input) {
    return input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
}

// send messag by click button
function sendMessage() {
    var message = sanitizeInput($(".submit").val());
    $(".submit").val('');

    var data = {
        receiver_id: receiver_id,
        message: message
    };
    sentMessageItemAppend(`#user-${my_id}`,message);

    // project demo mode check
    if (isDemo == 'DEMO') {
        toastr.error(demo_mode_error);
        return;
    }
    // end

    $.ajax({
        type: "POST",
        url: base_url + "/client/send-message",
        data: data,
        cache: false,
        success: function(data) {
            scrollToBottomFunc();
            $('#' + data.lawyer_id).click();
        },
        error: function(jqXHR, status, err) {}
    })
}

// load lawyer in modal
function loadlawyer() {
    var id = $(".department-id").val();
    if (id) {
        var url = base_url + "/get-department-lawyer/" + id;
        $.ajax({
            type: 'GET',
            url: url,
            success: function(response) {
                $(".lawyer-id").html(response).select2({
                    dropdownParent: $('#appointment_modal')
                });
                $("#modal-lawyer-box").removeClass('d-none')
            },
            error: function(err) {}
        });

    }
}
// load lawyer in mobile menu modal
function loadMobileModallawyer() {
    var id = $(".modal-department-id").val();
    if (id) {
        var url = base_url + "/get-department-lawyer/" + id;
        $.ajax({
            type: 'GET',
            url: url,
            success: function(response) {
                $(".modal-lawyer-id").html(response)
                $("#mobile-modal-lawyer-box").removeClass('d-none')
            },
            error: function(err) {}
        });

    }
}

// load date in modal
function loadDate() {
    var lawyerId = $('.lawyer-id').val()
    $('#modal_lawyer_id').val(lawyerId)
    $("#modal-date-box").removeClass('d-none')

}

// load date in mobile menu modal
function loadModalDate() {
    var lawyerId = $('.modal-lawyer-id').val()
    $('#mobile_modal_lawyer_id').val(lawyerId)
    $("#mobile-modal-date-box").removeClass('d-none')

}
function sentMessageItemAppend(boxId,message){
    scrollToBottomFunc();
    const messageList = $(boxId);
    const messageItem = `<li class="message clearfix">
            <div class="sent">
                <p>${message}</p>
                <p class="date"><i class="fas fa-spinner fa-spin"></i></p>
            </div>
        </li>`;
    messageList.append(messageItem);
}