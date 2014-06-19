$('document').ready(function () {

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

    var treeBrowserVisible = $('#page_tree_browser_visible').val();

    if (treeBrowserVisible) {
        var spaceId = $('#space_id').val();

        $.ajax({
            type: "POST",
            url: '/documentador/space/get-top-pages',
            data: {
                id: spaceId
            },
            success: function (response) {
                var childrenJSON = jQuery.parseJSON(response);
                if (!childrenJSON.length)
                    return;

                var childrenArray = [];
                var childrenTree = [];

                var pagesAdded = [];

                // add root node
                var parentNode = {
                    title: childrenJSON[0].title,
                    isFolder: true,
                    url: childrenJSON[0].url,
                    href: childrenJSON[0].url,
                    key: childrenJSON[0].key
                };

                pagesAdded.push(childrenJSON[0].key);

                for (var i = 1; i < childrenJSON.length; i++) {
                    if (childrenJSON[i].parent) {
                        var isFolder = childrenJSON[i].isFolder ? true : false;
                        var child = {
                            title: childrenJSON[i].title,
                            isFolder: isFolder,
                            url: childrenJSON[i].url,
                            key: childrenJSON[i].key
                        };
                        if (isFolder)
                            child.isLazy = true;
                        childrenArray.push(child);
                        pagesAdded.push(childrenJSON[i].key);
                    }
                }
                if (childrenArray.length)
                    parentNode.children = childrenArray;
                childrenTree.push(parentNode);

                for (var i = 0; i < childrenJSON.length; i++) {

                    if (childrenJSON[i].parent == 0 && pagesAdded.indexOf(childrenJSON[i].key) == -1) {

                        var node = {
                            title: childrenJSON[i].title,
                            isFolder: childrenJSON[i].isFolder,
                            url: childrenJSON[i].url,
                            href: childrenJSON[i].url,
                            key: childrenJSON[i].key
                        };
                        childrenTree.push(node);
                    }
                }

                $("#page_tree_browser").dynatree({
                    title: "Dynatree", // Tree's name (only used for debug outpu)
                    minExpandLevel: 2, // 1: root node is not collapsible
                    imagePath: null, // Path to a folder containing icons. Defaults to 'skin/' subdirectory.
                    initId: null, // Init tree structure from a <ul> element with this Id.
                    initAjax: null, // Ajax options used to initialize the tree strucuture.
                    autoFocus: true, // Set focus to first child, when expanding or lazy-loading.
                    keyboard: true, // Support keyboard navigation.
                    persist: false, // Persist expand-status to a cookie
                    autoCollapse: false, // Automatically collapse all siblings, when a node is expanded.
                    clickFolderMode: 3, // 1:activate, 2:expand, 3:activate and expand
                    activeVisible: true, // Make sure, active nodes are visible (expanded).
                    checkbox: false, // Show checkboxes.
                    selectMode: 1, // 1:single, 2:multi, 3:multi-hier
                    fx: null, // Animations, e.g. null or { height: "toggle", duration: 200 }
                    noLink: false, // Use <span> instead of <a> tags for all nodes
                    // Low level event handlers: onEvent(dtnode, event): return false, to stop default processing
                    onClick: null, // null: generate focus, expand, activate, select events.
                    onDblClick: null, // (No default actions.)
                    onKeydown: null, // null: generate keyboard navigation (focus, expand, activate).
                    onKeypress: null, // (No default actions.)
                    onFocus: null, // null: set focus to node.
                    onBlur: null, // null: remove focus from node.

                    // Pre-event handlers onQueryEvent(flag, dtnode): return false, to stop processing
                    onQueryActivate: null, // Callback(flag, dtnode) before a node is (de)activated.
                    onQuerySelect: null, // Callback(flag, dtnode) before a node is (de)selected.
                    onQueryExpand: null, // Callback(flag, dtnode) before a node is expanded/collpsed.

                    // High level event handlers
                    onPostInit: null, // Callback(isReloading, isError) when tree was (re)loaded.
                    onActivate: function (node) {
                        if (node.data.url)
                            window.location.href = node.data.url;

                    },
                    onDeactivate: null, // Callback(dtnode) when a node is deactivated.
                    onSelect: null, // Callback(flag, dtnode) when a node is (de)selected.
                    onExpand: null, // Callback(flag, dtnode) when a node is expanded/collapsed.
                    onLazyRead: function (node) {
                        node.appendAjax({url: "/documentador/get-child-pages",
                            data: {
                                key: node.data.key,
                                target: 'tree',
                                mode: "all"
                            },
                            success: function (node) {
                                // Called after nodes have been created and the waiting icon was removed.
                                // 'this' is the options for this Ajax request
                            },
                            error: function (node, XMLHttpRequest, textStatus, errorThrown) {
                                // Called on error, after error icon was created.
                            },
                            cache: false // Append random '_' argument to url to prevent caching.
                        });
                    },
                    onCustomRender: null, // Callback(dtnode) before a node is rendered. Return a HTML string to override.
                    onCreate: null, // Callback(dtnode, nodeSpan) after a node was rendered for the first time.
                    onRender: null, // Callback(dtnode, nodeSpan) after a node was rendered.
                    postProcess: null, // Callback(data, dataType) before an Ajax result is passed to dynatree.

                    // Drag'n'drop support
                    dnd: {
                        // Make tree nodes draggable:
                        onDragStart: function (node) {

                            return false;
                        },

                        onDragStop: null, // Callback(sourceNode)
                        // Make tree nodes accept draggables
                        autoExpandMS: 1000, // Expand nodes after n milliseconds of hovering.
                        preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
                        onDragEnter: function (node, sourceNode) {

                            return true
                        },
                        onDragOver: function (node, sourceNode, hitMode) {
                            /** Return false to disallow dropping this node.
                             *
                             */
                            // Prevent dropping a parent below it's own child
                            if (node.isDescendantOf(sourceNode)) {
                                return false;
                            }
                            // Prohibit creating childs in non-folders (only sorting allowed)
                            if (!node.data.isFolder && hitMode === "over") {
                                return "after";
                            }
                        },
                        onDrop: function (node, sourceNode, hitMode, ui, draggable) {

                            sourceNode.move(node, hitMode);

                            $.ajax({
                                type: "POST",
                                url: '/documentador/update-entity-parent',
                                data: {
                                    entity_id: sourceNode.data.key,
                                    parent_id: node.data.key
                                },
                                success: function (response) {
                                    // expand the drop target
                                    sourceNode.expand(true);
                                }
                            });

                        },
                        onDragLeave: function (node, sourceNode) {
                        }
                    },
                    ajaxDefaults: { // Used by initAjax option
                        cache: false, // false: Append random '_' argument to the request url to prevent caching.
                        timeout: 0, // >0: Make sure we get an ajax error for invalid URLs
                        dataType: "json" // Expect json format and pass json object to callbacks.
                    },
                    strings: {
                        loading: "Loading…",
                        loadError: "Load error!"
                    },
                    children: childrenTree,
                    generateIds: false, // Generate id attributes like <span id='dynatree-id-KEY'>
                    idPrefix: "dynatree-id-", // Used to generate node id's like <span id="dynatree-id-<key>">.
                    keyPathSeparator: "/", // Used by node.getKeyPath() and tree.loadKeyPath().
                    cookieId: "dynatree", // Choose a more unique name, to allow multiple trees.
                    cookie: {
                        expires: null // Days or Date; null: session cookie
                    },
                    // Class names used, when rendering the HTML markup.
                    // Note: if only single entries are passed for options.classNames, all other
                    // values are still set to default.
                    classNames: {
                        container: "dynatree-container",
                        node: "dynatree-node",
                        folder: "dynatree-folder",

                        empty: "dynatree-empty",
                        vline: "dynatree-vline",
                        expander: "dynatree-expander",
                        connector: "dynatree-connector",
                        checkbox: "dynatree-checkbox",
                        nodeIcon: "dynatree-icon",
                        title: "dynatree-title",
                        noConnector: "dynatree-no-connector",

                        nodeError: "dynatree-statusnode-error",
                        nodeWait: "dynatree-statusnode-wait",
                        hidden: "dynatree-hidden",
                        combinedExpanderPrefix: "dynatree-exp-",
                        combinedIconPrefix: "dynatree-ico-",
                        hasChildren: "dynatree-has-children",
                        active: "dynatree-active",
                        selected: "dynatree-selected",
                        expanded: "dynatree-expanded",
                        lazy: "dynatree-lazy",
                        focused: "dynatree-focused",
                        partsel: "dynatree-partsel",
                        lastsib: "dynatree-lastsib"
                    },
                    debugLevel: 1 // 0:quiet, 1:normal, 2:debug
                });
            }
        });
    }

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