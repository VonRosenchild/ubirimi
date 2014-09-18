jQuery(document).ready(function ($) {
    var formlang = 'en';
    var doc = document;
    var body = $(doc.body);

    function translateForm(language) {
        var lang = translation[language];

        $(".card-number-label").text(lang["form"]["card-number"]);
        $(".card-cvc-label").text(lang["form"]["card-cvc"]);
        $(".card-holdername-label").text(lang["form"]["card-holdername"]);
        $(".card-expiry-label").text(lang["form"]["card-expiry"]);
        $(".amount-label").text(lang["form"]["amount"]);
        $(".currency-label").text(lang["form"]["currency"]);
        $(".submit-button").text(lang["form"]["submit-button"]);
    }

    $('.card-number').keyup(function () {
        var brand = detectCreditcardBranding($('.card-number').val());
        brand = brand.replace(' ', '-');
        $(".card-number")[0].className = $(".card-number")[0].className.replace(/paymill-card-number-.*/g, '');
        if (brand !== 'unknown') {
            $('#card-number').addClass("paymill-card-number-" + brand);
        }

        if (brand !== 'maestro') {
            VALIDATE_CVC = true;
        } else {
            VALIDATE_CVC = false;
        }
    });

    $('.card-expiry').keyup(function () {
        if (/^\d\d$/.test($('.card-expiry').val())) {
            text = $('.card-expiry').val();
            $('.card-expiry').val(text += "/");
        }
    });


    function PaymillResponseHandler(error, result) {

        if (error) {
            $(".payment_errors").text(translation["en"]["error"][error.apierror]);
            $(".payment_errors").css("display", "inline-block");
            $('#paymill-submit-button').removeClass('disabled');
        } else {
            $(".payment_errors").html("&nbsp;");
            var form = $("#signUp-form");
            // Token
            var token = result.token;

            form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");
            form.get(0).submit();
        }
        $(".submit-button").removeAttr("disabled");
    }

    $("#signUp-form").submit(function (event) {

        if (false === paymill.validateHolder($('#card_name').val())) {
            $(".payment_errors").text(translation[formlang]["error"]["invalid-card-holdername"]);
            $(".payment_errors").css("display", "inline-block");
            return false;
        }

        if ((false === paymill.validateCvc($('#card_security').val()))) {
            if (VALIDATE_CVC) {
                $(".payment_errors").text(translation[formlang]["error"]["invalid-card-cvc"]);
                $(".payment_errors").css("display", "inline-block");
                return false;
            } else {
                $('#card_security').val("000");
            }
        }

        if (false === paymill.validateCardNumber($('#card_number').val())) {
            $(".payment_errors").text(translation[formlang]["error"]["invalid-card-number"]);
            $(".payment_errors").css("display", "inline-block");
            return false;
        }

        var expiry = [];
        expiry[0] = $('#card_exp_month').val();
        expiry[1] = $('#card_exp_year').val();

        if (false === paymill.validateExpiry(expiry[0], expiry[1])) {
            $(".payment_errors").text(translation[formlang]["error"]["invalid-card-expiry-date"]);
            $(".payment_errors").css("display", "inline-block");
            return false;
        }

        var params = {
            amount_int: $('#pay_amount').val() * 100,
            currency: 'USD',
            number: $('#card_number').val(),
            exp_month: expiry[0],
            exp_year: expiry[1],
            cvc: $('#card_security').val(),
            cardholder: $('#card_name').val()
        };

        paymill.createToken(params, PaymillResponseHandler);
        return false;
    });

    translateForm(formlang);
});