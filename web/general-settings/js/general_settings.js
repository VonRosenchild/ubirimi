$('document').ready(function () {

    var filterLogDateFrom = $("#log_filter_from_date");
    if (filterLogDateFrom.length) {
        filterLogDateFrom.datepicker({dateFormat: "yy-mm-dd"});
    }
    var filterLogDateTo = $("#log_filter_to_date");
    if (filterLogDateTo.length) {
        filterLogDateTo.datepicker({dateFormat: "yy-mm-dd"});
    }

    $('#btnFilterLog').click(function (event) {
        event.preventDefault();
        document.location.href = '/general-settings/logs/' + $('#log_filter_from_date').val() + '/' + $('#log_filter_to_date').val();
    });

    $('.app_client').click(function (event) {
        var checked = $(this).is(':checked') ? 1 : 0;
        $.ajax({
            type: "POST",
            url: '/general-settings/update-app-list',
            data: {
                app: $(this).attr('name'),
                visible: checked
            },
            success: function (response) {
                location.reload();
            }
        });
    });
});