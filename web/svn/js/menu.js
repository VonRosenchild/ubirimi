$('document').ready(function () {

    $('#menuSVN').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        if ($('#contentMenuSVN').is(':visible') && $('#contentMenuSVN').html()) {
            $('#contentMenuSVN').hide();
            return
        }

        $('#menuSVN').css('background-color', '#cccccc');
        $('#contentMenuIssues').hide();
        $('#contentMenuProjects').hide();
        $('#contentUserHome').hide();
        $('#contentMenuDocumentator').hide();
        $('#menu_more_actions').hide();
        $('#menu_workflow').hide();
        $('#contentMenuAgile').hide();
        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'project') {
            $('#menuProjects').css('background-color', '#eeeeee');
        } else
            $('#menuProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'issue') {
            $('#menuIssues').css('background-color', '#eeeeee');
        } else
            $('#menuIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'filters') {
            $('#menuFilters').css('background-color', '#eeeeee');
        } else
            $('#menuFilters').css('background-color', '#6A8EB2');

        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        if (menuSelected == 'documentator') {
            $('#menuDocumentator').css('background-color', '#eeeeee');
        } else
            $('#menuDocumentator').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');

        $.ajax({
            type: "POST",
            url: '/menu/svn-menu',
            data: {
            },
            success: function (response) {
                $('#contentMenuSVN').html(response);
                $('#contentMenuSVN').css('left',$('#menuSVN').position().left)
                $('#contentMenuSVN').css('top', $('#menuSVN').position().top + 28)
                $('#contentMenuSVN').css('z-index', '500');
                $('#contentMenuSVN').css('padding', '4px');

                $('#contentMenuSVN').css('position', 'absolute');
                $('#contentMenuSVN').css('width', '200px');
                $('#contentMenuSVN').css('background-color', '#ffffff');
                $('#contentMenuSVN').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                $('#contentMenuSVN').show();
            }
        });
    });

    $('html').click(function() {
        $('#menu_top_user').css('background-color', '#003466');
        var menuToClose = ['contentUserHome'];
        for (var i = 0; i < menuToClose.length; i++) {
            if ($('#' + menuToClose[i]).is(':visible')) {
                $('#' + menuToClose[i]).hide();
            }
        }
    });
})