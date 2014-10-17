$('document').ready(function () {

    $(window).scroll(function(){
        console.log($(this).scrollTop());
        if ($(this).scrollTop() == 0) {
            $('.doc-left-side').css('top', '124px');
        } else if ($(this).scrollTop() < 108) {
            $('.doc-left-side').css('top', 124 - ($(this).scrollTop()) + 'px');
            $('.doc-left-side').css('height', ($(window).height() - 123 + $(this).scrollTop()) + 10 + 'px');
        } else {
            $('.doc-left-side').css('top', '-1px');
        }

    });

    $('.doc-left-side').css('height', ($(window).height() - 123) + 'px');

    $('#doc_view_page_add_comment_content').click(function (event) {
        event.preventDefault();

        $('#doc_view_page_add_comment_content').val('');
    });

    $('#doc_page_new_page').click(function (event) {
        event.preventDefault();
        var value = $('#doc_page_new_page').val();
        if (value == 'New Page Title')
            $('#doc_page_new_page').val('');
    });

    $('#doc_view_page_add_comment').click(function (event) {
        event.preventDefault();
        var pageId = $('#entity_id').val();

        $.ajax({
            type: "POST",
            url: '/documentador/add-comment',
            data: {
                entity_id: pageId,
                content: $('#doc_view_page_add_comment_content').val()
            },
            success: function (response) {
                $.ajax({
                    type: "POST",
                    url: '/documentador/render-page-comments',
                    data: {
                        id: pageId
                    },
                    success: function (response) {
                        $('#pageCommentsSection').html(response);
                        $('#doc_view_page_add_comment_content').val('Add a comment');
                    }
                });
            }
        });
    });

    $('#page_make_favourite').click(function (event) {

        event.preventDefault();
        $('#menu_page_tools').hide();
        $('#menu_child_pages').hide();

        var pageId = $('#entity_id').val();

        $.ajax({
            type: "POST",
            url: '/documentador/make-page-favourite',
            data: {
                id: pageId
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $('#page_remove_favourite').click(function (event) {

        event.preventDefault();
        $('#menu_page_tools').hide();
        $('#menu_child_pages').hide();

        var pageId = $('#entity_id').val();

        $.ajax({
            type: "POST",
            url: '/documentador/remove-page-favourite',
            data: {
                id: pageId
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $('#btnPageChildren').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        if ($(this).hasClass('disabled')) {
            return;
        }

        $('#contentUserHome').hide();
        $('#menu_page_tools').hide();
        $('#contentMenuDocumentator').hide();

        if ($('#menu_child_pages').is(':visible')) {
            $('#menu_child_pages').hide();

        } else {
            $('#menu_child_pages').css('left', $('#btnPageChildren').position().left)
            $('#menu_child_pages').css('top', $('#btnPageChildren').position().top + 32)
            $('#menu_child_pages').show();
        }

        var menuSelected = $('#menu_selected').val();
        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');
        $('#menuDocumentator').css('background-color', '#eeeeee');
    });

    $('#btnEditSpace').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/documentador/administration/space/edit/' + selected_rows[0];
    });
    $('#btnEditSpaceAdministration').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/documentador/administration/space/edit/' + selected_rows[0] + '?back=administration';
    });

    $('#btnSpaceTools').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/documentador/administration/space-tools/overview/' + selected_rows[0];
    });

    $('#btnEditPage').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/documentador/page/edit/' + selected_rows[0];
    });

    $('#btnEditGroupDocumentator').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/documentador/administration/group/edit/' + selected_rows[0];
    });

    $("[id^='add_space_to_favourites_']").click(function (event) {

        var spaceId = $(this).attr("id").replace('add_space_to_favourites_', '');
        $.ajax({
            type: "POST",
            url: '/documentador/space/add-favourites',
            data: {
                id: spaceId
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $("[id^='remove_space_from_favourites_']").click(function (event) {

        var spaceId = $(this).attr("id").replace('remove_space_from_favourites_', '');
        $.ajax({
            type: "POST",
            url: '/documentador/space/remove-favourites',
            data: {
                id: spaceId
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $(document).on('click', "[id^='doc_dialog_page_select_']", function (event) {
        $(this).parent().children().each(function () {
            $(this).css('background-color', '');
        });
        $(this).css('background-color', '#f5f5f5');

        var dialog = CKEDITOR.dialog.getCurrent();
        var linkField = dialog.getContentElement('search_page', 'dialog_page_link_location');

        var link = $(this).attr('data');
        linkField.setValue(link);

        dialog.setValueOf('info', 'url', link);  // Populates the URL field in the Links dialogue.
    });
    // image click in image dialog ckeditor
    $(document).on('click', "[id^='entity_existing_image_']", function (event) {

        var dialog = CKEDITOR.dialog.getCurrent();
        var linkField = dialog.getContentElement('existing_image', 'dialog_page_link_location');
        var link = $(this).attr('data');
        linkField.setValue(link);
        dialog.setValueOf('info', 'txtUrl', link);  // Populates the URL field in the Links dialogue.
    });

    $(document).on('mouseenter mouseleave', "[id^='entity_existing_image_']", function (event) {

        if (event.type == 'mouseenter') {
            $(this).css('cursor', 'pointer');
        }
        if (event.type == 'mouseleave') {
            $(this).css('background-color', 'white');
        }
    });
    $(document).on('click', "[id^='details_file_']", function (event) {

        var fileId = $(this).attr("id").replace('details_file_', '');

        if ($(this).attr('src') == '/img/minus.png') {
            $(this).attr("src", '/img/plus.png');
            $('#details_file_content_' + fileId).html('');
        } else {
            $(this).attr("src", '/img/minus.png');

            $.ajax({
                type: "POST",
                url: '/documentador/page/get-file-data',
                data: {
                    id: fileId
                },
                success: function (response) {
                    $('#details_file_content_' + fileId).html(response);
                }
            });
        }
    });

    $(document).on('click', "[id^='details_attachment_']", function (event) {

        var fileId = $(this).attr("id").replace('details_attachment_', '');

        if ($(this).attr('src') == '/img/minus.png') {
            $(this).attr("src", '/img/plus.png');
            $('#details_attachment_content_' + fileId).html('');
        } else {
            $(this).attr("src", '/img/minus.png');

            $.ajax({
                type: "POST",
                url: '/documentador/page/get-attachment-data',
                data: {
                    id: fileId
                },
                success: function (response) {
                    $('#details_attachment_content_' + fileId).html(response);
                }
            });
        }
    });

    $(document).on('click', "[id^='entity_reply_comment_']", function (event) {

        var parentCommentId = $(this).attr("id").replace('entity_reply_comment_', '');
        $('#innerCommentSection_' + parentCommentId).show();
    });

    $(document).on('click', "#snapshot_discard", function (event) {
        event.preventDefault();

        var snapshotId = $('#entity_last_snapshot').val();
        $.ajax({
            type: "POST",
            url: '/documentador/entity/delete-snapshot',
            data: {
                id: snapshotId
            },
            success: function (response) {
                $('#snapshotWarning').html('Your draft has been discarded.');
            }
        });
    });

    $(document).on('click', "[id^='inner_btn_doc_view_page_add_comment_']", function (event) {

        var parentCommentId = $(this).attr("id").replace('inner_btn_doc_view_page_add_comment_', '');
        $.ajax({
            type: "POST",
            url: '/documentador/add-comment',
            data: {
                parent_comment_id: parentCommentId,
                entity_id: $('#entity_id').val(),
                content: $('#inner_doc_view_page_add_comment_content_' + parentCommentId).val()
            },
            success: function (response) {
                $.ajax({
                    type: "POST",
                    url: '/documentador/render-page-comments',
                    data: {
                        id: $('#entity_id').val()
                    },
                    success: function (response) {
                        $('#pageCommentsSection').html(response);
                        $('#doc_view_page_add_comment_content').val('Add a comment');
                    }
                });
            }
        });
    });
    $('#documentator_quick_search').on('click', function (event) {
        $(this).val('');
    });

    $('#documentator_quick_search').keyup(function (event) {
        if (event.keyCode == 13) {
            var value = $('#documentator_quick_search').val();
            window.location.href = '/documentador/search?search_query=' + value;
        }
    });

    $('#documentator_quick_search').focusout(function () {
        $(this).val('Quick Search');
    });

    var editEntityContext = $('#entity_edit_context').val();
    if (editEntityContext) {

        function addEntitySnapshot() {
            $.ajax({
                type: "POST",
                url: '/documentador/entity/add-snapshot',
                data: {
                    id: $('#entity_id').val(),
                    entity_last_snapshot_id: $('#entity_last_snapshot').val(),
                    content: CKEDITOR.instances.entity_content.getData()
                },
                success: function (response) {
                    if (response) {
                        var text = 'This page is being edited by ';
                        var data = jQuery.parseJSON(response);
                        var usersUsing = [];
                        if (data) {
                            for (var i = 0; i < data.length; i++) {
                                if (data[i].last_edit_offset <= 1)
                                    usersUsing.push('<a href="/documentador/user/profile/' + data[i].user_id + '">' + data[i].first_name + ' ' + data[i].last_name + '</a>');
                            }
                        }
                        if (usersUsing.length) {
                            text = text + usersUsing.join(', ');
                            $('#multipleEntityEdits').html(text);
                        } else {
                            $('#multipleEntityEdits').html('');
                        }
                    }
                }
            });
        }

        setInterval(addEntitySnapshot, 30000);
    }
});