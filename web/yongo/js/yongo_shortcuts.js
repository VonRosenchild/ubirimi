$('document').ready(function () {
    var selectedProductId = $('#product_id').val();
    if (selectedProductId == 1) {
        if (!(typeof Mousetrap == 'undefined')) {

            var focused = $(':focus');

            if (focused) {
                if (focused.is("input") || focused.is("textarea")) {
                    return false;
                }
            }

            Mousetrap.bind('c', function () {
                if ($('.ui-dialog').length >= 1) {
                    return;
                }

                var currentProjectId = $('#current_project_id').val();
                if (currentProjectId)
                    createIssue();
            });

            if ($('#context_search').val() != 'context_search') {
                Mousetrap.bind('e', function () {
                    if ($('.ui-dialog').length >= 1) {
                        return;
                    }

                    if ($('#has_edit_permission').val()) {
                        editIssue($('#issue_id').val());
                    }
                });
            }

            Mousetrap.bind('a', function () {
                if ($('.ui-dialog').length >= 1) {
                    return;
                }

                assignIssue();
            });

            // save la dialog de new/edit/delete
            Mousetrap.bind('alt+s', function () {
                if ($('#modalCreateIssue').children().length) {
                    createIssueProcess();
                } else {
                    $('.ui-button-text')[1].click();
                }
            });

            Mousetrap.bind('m', function () {
                if ($('.ui-dialog').length >= 1) {
                    return;
                }

                commentIssue($('#issue_id').val());
            });

            Mousetrap.bind('g p', function (event) {
                if (event.srcElement.type == 'text' || event.srcElement.type == 'textarea') {
                    return;
                }
                var currentProjectId = $('#current_project_id').val();
                if (currentProjectId)
                    window.location.href = '/yongo/project/' + currentProjectId;
            });

            Mousetrap.bind('g a', function () {
                if (event.srcElement.type == 'text' || event.srcElement.type == 'textarea') {
                    return;
                }

                var currentProjectId = $('#current_project_id').val();
                if (currentProjectId)
                    window.location.href = '/yongo/issue/search?page=1&sort=created&order=desc&project=' + currentProjectId;
            });

            Mousetrap.bind('g i', function () {
                if (event.srcElement.type == 'text' || event.srcElement.type == 'textarea') {
                    return;
                }

                window.location.href = '/yongo/issue/search';
            });
            Mousetrap.bind('g d', function () {
                if (event.srcElement.type == 'text' || event.srcElement.type == 'textarea') {
                    return;
                }

                window.location.href = '/yongo/my-dashboard';
            });
        }
    }
});