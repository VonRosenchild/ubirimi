$(document).ready(function () {

    $(".cycle-slideshow").cycle("goto", 0);

    $(".compare-items a").on("click", function () {
        $(".cycle-slideshow").cycle("goto", parseInt($(this).data("slide"), 10));

        return false;
    });

    $(".cycle-slideshow").on("cycle-after", function(event, optionHash) {
        var nextSlide = optionHash.nextSlide;
        var data;

        switch (nextSlide) {
            case 0:
                data = 'issues';
                $('.content-issues').hide();
                $('.content-processes').hide();
                $('.content-planning').hide();
                $('.content-collaboration').hide();
                break;
            case 1:
                data = 'issues_and_stories';
                $('.content-issues_and_stories').hide();
                $('.content-workflows').hide();
                $('.content-planning_agile').hide();
                break;
            case 2:
                data = 'scrum';
                $('.content-scrum').hide();
                $('.content-fast_project').hide();
                break;
            case 3:
                data = 'perms';
                $('.content-perms').hide();
                $('.content-nots').hide();
                break;
        }

        $('#last_menu_selected').val(data);
        $('.content-' + $('#last_menu_selected').val()).hide();

        $('.content-' + data).show();
        $('.content-' + data).prev().find('a').eq(0).click();
        $('.content-' + data).prev().find('a:first').addClass('selected');
    });

    $(".cycle-slideshow").on("cycle-before", function(event, optionHash) {

        var $anchor = $(".compare-items a").eq(optionHash.nextSlide),
            $parent = $anchor.parent(),
            $marker = $(".marker-container .marker"),
            markerWidth = $marker.outerWidth(),
            anchorPosition = $anchor.offset(),
            parentPosition = $parent.offset(),
            anchorLeft = anchorPosition.left - parentPosition.left,
            anchorWidth = $anchor.outerWidth(),
            offset = (anchorWidth - markerWidth) / 2;

        $marker.animate({
            left: anchorLeft + offset
        }, {
            duration: optionHash.speed
        });
    });

    $(".archive-list .year a").click(function () {
        var $this = $(this);
        $(".archive-list .year a").removeClass("open");
        $(".archive-list .links").slideUp();
        $this.addClass("open").parent().next().slideDown();
        return false;
    });

    doCheckboxesWork();

    $(".feature-text").on('click', function(event) {
        $('div .cycle-slideshow div .submenu a').removeClass('selected');
        $(this).addClass('selected');

        event.preventDefault();
        var data = $(this).attr('data');
        $('.content-' + $('#last_menu_selected').val()).hide();
        $('.content-' + data).show();
        $('#last_menu_selected').val(data);
    });

    /* process contact */
    $(".container.contact").on("click", "button", function() {
        $('div .container button').hide();
        $('#loader').show();

        $.ajax({
            type: "POST",
            url: "/contact/send",
            data: $("form[name='contact']").serialize(),
            success: function(response) {
                $('div .container button').show();
                $('#loader').hide();

                $('form').replaceWith(response);

                window.scrollTo(0, 0);
            }
        });
    });

    /* process recover password */
    $(".container.recover-account-panel").on("click", "button", function() {
        $('div .container button').hide();
        $('#loader').show();

        $.ajax({
            type: "POST",
            url: "/recover-password/do",
            data: $("form[name='client-account']").serialize(),
            success: function(response) {
                $('div .container button').show();
                $('#loader').hide();

                $('form').replaceWith(response);
            }
        });
    });

    $(document).on('change', '#country', function (event) {
        if ($(this).val() == 143) {
            $('#vat_container').show();
        } else {
            $('#vat_container').hide();
        }
    });

    $(document).on('change', '#number_users', function (event) {
        var noOfUsers = parseInt($(this).val());

        var amount = 10;

        switch (noOfUsers) {
            case 15:
                amount = 45;
                break;
            case 25:
                amount = 90;
                break;
            case 50:
                amount = 190;
                break;
            case 100:
                amount = 290;
                break;
            case 500:
                amount = 490;
                break;
            case 1000:
                amount = 990;
                break;
        }

        $('#pay_amount').val(amount);
    });
});

function doCheckboxesWork(event) {
    $(".checkbox-container input[type='checkbox']").each(function () {
        var $this = $(this);
        if ($this.prop("checked")) {
            $this.parent().find(".checkbox").addClass("checked");
        } else if ($this.attr("checked")) {
            $this.parent().find(".checkbox").addClass("checked");
            $this.prop("checked", true);
        } else {
            $this.parent().find(".checkbox").removeClass("checked");
            $this.prop("checked", false);
        }
    });

    $(".checkbox-container input[type='checkbox']").on("change", function (e) {
        var $this = $(e.target);
        if ($this.prop("checked")) {
            $this.parent().find(".checkbox").addClass("checked");
        } else {
            $this.parent().find(".checkbox").removeClass("checked");
        }
    });

    $(".yongo-layouts").fancybox();
    $(".yongo-search").fancybox();
    $(".yongo-notification-schemes").fancybox();
    $(".yongo-permission-schemes").fancybox();
    $(".yongo-workflows").fancybox();
    $(".yongo-screens").fancybox();
    $(".agile-boards").fancybox();
    $(".agile-columns").fancybox();
    $(".agile-multiple-sprints").fancybox();
    $(".documentador-permissions-content-tools").fancybox();
    $(".documentador-editor").fancybox();
    $(".documentador-spaces").fancybox();
    $(".svn-repositories").fancybox();
    $(".svn-control-panel").fancybox();
    $(".events-calendars").fancybox();
    $(".event-import-share-calendars").fancybox();
}