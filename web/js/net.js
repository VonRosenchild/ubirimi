$(document).ready(function () {

    /* process recover password */
    $("div .container.recover-user-password").on("click", "button", function() {
        $(this).hide();
        $('#loader').show();

        $.ajax({
            type: "POST",
            url: "/recover-password/do",
            data: $("form[name='user-account']").serialize(),
            success: function(response) {
                $('div .container button').show();
                $('#loader').hide();

                $('form').replaceWith(response);
            }
        });
    });

    /* cancel button on new user account */
    $(".container.user-sing-up").on("click", "button[name='cancel']", function() {
        document.location = "/";
    });

    setInputFieldFocused('sign-in-username');
});