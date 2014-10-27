var availableTags = [];

CKEDITOR.disableAutoInline = true;

function resizeNoteContent() {

    if ($('#view_qn_entity').val() == 'note_tag') {
        var totalHeight = $(window).height();
        // Remove an extra 20px for good measure
        totalHeight -= 133;

        $('.pageContent').css('height', totalHeight);
        $('#contentNotesList').css('height', totalHeight - 28);
        if ('snippets' == $('#qn_view_type').val()) {
            $('#parentNoteContent').css('height', totalHeight - 194);
        }
    }

}

$('document').ready(function () {
    $('#parentNoteContent').height(function(index, height) {
        return window.innerHeight - $(this).offset().top;
    });

    $('#btnEditNotebook').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/quick-notes/notebook/edit/' + selected_rows[0];
    });

    $('#btnEditTag').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/quick-notes/tag/edit/' + selected_rows[0];
    });

    $(window).on('resize', function() {
        if ('snippets' == $('#qn_view_type').val()) {
            resizeNoteContent();
        }
    });

    resizeNoteContent();

    $("#note_enter_new_tag").autocomplete({
        source: availableTags
    });

    $(document).on('click', '#btnCreateNote', function (event) {
        $.ajax({
            type: "POST",
            url: '/quick-notes/note/add',
            data: {
                notebook_id: $('#notebook_id').val()
            },
            success: function (response) {
                window.location.href = '/quick-notes/note/' + response;
            }
        });
    });

    $(document).on('click', '#btnEditNote', function (event) {
        if ($(this).html() == 'Save Changes') {
            var content = CKEDITOR.instances.note_content.getData();
            $.ajax({
                type: "POST",
                url: '/quick-notes/note/edit',
                data: {
                    note_id: $('#note_id').val(),
                    content: content
                },
                success: function (response) {
                    CKEDITOR.instances.note_content.destroy();
                    $('#btnEditNote').html('<i class="icon-edit"></i> Update');
                    $('#note_content').val(content);
                }
            });
        } else {
            $('#btnEditNote').html('Save Changes');

            var config = {};
            var editor = CKEDITOR.replace('note_content', config, $('#note_content').val());
        }
    });

    $(document).on('click', '.contentNote', function (event) {
        window.location.href = $('.contentNoteLink', $(this)).val();
    });

    $("[id^='note_move_to_']").on('click', function (event) {

        event.preventDefault();
        var notebookTargetId = $(this).attr("id").replace('note_move_to_', '');
        if (notebookTargetId == $('#notebook_id').val()) {
            return;
        }

        var noteId = $('#note_id').val();
        $.ajax({
            type: "POST",
            url: '/quick-notes/note/move',
            data: {
                note_id: noteId,
                target_notebook_id: notebookTargetId
            },
            success: function (response) {
                window.location.href = '/quick-notes/note/' + notebookTargetId + '/' + noteId;
            }
        });
    });

    $(".noteListView").on('click', function (event) {
        var id = $(this).attr('id').replace('table_row_', '');
        $.ajax({
            type: "POST",
            data: {
                id: id
            },
            url: '/quick-notes/note/render',
            success: function (response) {
                $('#qn_note_list_content').html(response);

                alert($(window).height() - $('#qn_list_notes_view').height() - 140);
                $('#parentNoteContent').css('height', $(window).height() - $('#qn_list_notes_view').height() - 140 + 'px');
            }
        });
    });

        /* tag functionality */
    $(function () {
        $('#tags input').on('focusout', function () {
            var txt = this.value.replace(/[^a-zA-Z0-9\+\s\-\.\#]/g, ''); // allowed characters
            var element = $(this);
            if (txt) {
                $.ajax({
                    type: "POST",
                    url: '/quick-notes/note/add/tag',
                    data: {
                        value: txt.toLowerCase(),
                        id: $('#note_id').val()
                    },
                    success: function (response) {
                        if (response == 1) {
                            element.before('<span class="tag">' + txt.toLowerCase() + '</span>');
                        }

                        // refresh the tag list
                        $.ajax({
                            type: "POST",
                            data: {
                                notebook_id: $('#notebook_id').val()
                            },
                            url: '/quick-notes/tag/refresh',
                            success: function (response) {
                                $('#tagContent').html(response);
                            }
                        });
                    }
                });
            }
            this.value = "";
        }).on('keyup', function (e) {
            // if: comma,enter (delimit more keyCodes with | pipe)
            if (/(188|13)/.test(e.which)) $(this).focusout();

        });

        $('#tags').on('click', '.tag', function () {

            var element = $(this);

            $.ajax({
                type: "POST",
                url: '/quick-notes/tag/delete-by-note',
                data: {
                    tag_id: element.attr('data'),
                    note_id: $('#note_id').val()
                },
                success: function (response) {
                    element.remove();
                }
            });
        });
    });

    $('#menuNotebooks').mouseenter(function (event) {
        $('#menuNotebooks').css('cursor', 'pointer');
    });

    $('#menuNotes').mouseenter(function (event) {
        $('#menuNotes').css('cursor', 'pointer');
    });

    $('#menuTags').mouseenter(function (event) {
        $('#menuTags').css('cursor', 'pointer');
    });

    $('#menuNotebooks').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuNotebooks').is(':visible') && $('#contentMenuNotebooks').html()) {
            $('#contentMenuNotebooks').hide();
            return
        }

        $('#menuNotebooks').css('background-color', '#cccccc');
        $('#menu_top_user').css('background-color', '#003466');

        $('#contentUserHome').hide();

        $('#contentMenuNotebooks').css('left',$('#menuNotebooks').position().left);
        $('#contentMenuNotebooks').css('top', $('#menuNotebooks').position().top + 28);
        $('#contentMenuNotebooks').css('z-index', '500');
        $('#contentMenuNotebooks').css('padding', '4px');

        $('#contentMenuNotebooks').css('position', 'absolute');
        $('#contentMenuNotebooks').css('width', '200px');
        $('#contentMenuNotebooks').css('background-color', '#ffffff');
        $('#contentMenuNotebooks').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuNotebooks').show();
    });

    $('#qn_note_title').blur(function (event) {
        event.stopPropagation();
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: '/quick-notes/note/update/name',
            data: {
                id: $('#note_id').val(),
                summary: $('#qn_note_title').text()
            },
            success: function (response) {
                $('#qn_note_list_summary_' + $('#note_id').val()).html($('#qn_note_title').text());
            }
        });
    });
});

