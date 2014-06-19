$('document').ready(function () {

    $('#menuSVN').mouseenter(function (event) {
        $('#menuSVN').css('cursor', 'pointer');
    });

    $('#btnEditSvnRepo').click(function (event) {
        event.preventDefault();
        if (selected_rows.length != 1)
            return

        if (1 == selected_rows.length) {
            document.location.href = '/svn-hosting/administration/repository/edit/' + selected_rows[0];
        }
    });
})