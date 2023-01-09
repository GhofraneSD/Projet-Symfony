$(document).ready(function () {
    var url = $("#talk_list").data('url');
    $("#submit_talk").on("click", function (event) {
        event.preventDefault();
    });
    $("#form_talk").on("submit", function (e) {


        e.preventDefault();
        var formData = new FormData(this);
        let url = $("#form_talk").attr('action')

        $.ajax({
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {
                $("#talk_textMessage").val('');
                $("#talk_list").html(data);
                $("#btnSubmit").prop("disabled", false);
            }
        });

    });

    $(".user_talk").on("click", function (e) {
        url = $(this).data('url');
        $(".user_talk").removeClass("current_talk_user")
        $(this).addClass('current_talk_user')
        $.get(url, function (data) {
            $("#talk_list").html(data);
        });

    });

    setInterval(function () {

        $.get(url, function (data) {
            $("#talk_list").html(data);
        });
    }, 3000)


});