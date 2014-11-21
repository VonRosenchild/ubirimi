CKEDITOR.disableAutoInline = true;
CKEDITOR.editorConfig = function (config) {
    config.disableAutoInline = true;
    if ($('#product_id').val() == PRODUCT_DOCUMENTADOR) {
        config.plugins = 'dialogui,dialog,about,a11yhelp,dialogadvtab,basicstyles,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,colorbutton,colordialog,templates,menu,contextmenu,div,toolbar,elementspath,list,indent,enterkey,entities,popup,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,format,htmlwriter,horizontalrule,iframe,wysiwygarea,image,smiley,justify,link,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,menubutton,scayt,stylescombo,tab,table,tabletools,undo,wsc';
        config.filebrowserUploadUrl = '/documentador/editor/upload-image';
        config.filebrowserImageWindowWidth = '640';
        config.filebrowserImageWindowHeight = '480';
        config.widthPc = 100;
        config.height = window.innerHeight - $('#doc_parent_editor').offset().top - 250;
    }
};

CKEDITOR.on('dialogDefinition', function (ev) {
    // Take the dialog name and its definition from the event
    // data.
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;
    var dialog = ev.data.definition.dialog;

    dialog.resize(600, 500);

    if (dialogName == 'image') {

        dialogDefinition.addContents({
            id: 'existing_image',
            label: 'Existing Images',
            accessKey: 'M',
            elements: [

                {
                    type: 'vbox',
                    expand: true,
                    padding: 3,

                    children: [
                        {
                            type: 'html',
                            id: 'dialog_page_name',
                            html: '',
                            onShow: function (data) {
                                var elementID = '#' + this.getInputElement().$.id;
                                $.ajax({
                                    type: "POST",
                                    url: '/documentador/editor/get-entity-images',
                                    data: {
                                    },
                                    success: function (response) {
                                        $(elementID).html(response);
                                    }
                                })
                            }
                        },
                        {
                            type: 'vbox',
                            id: 'anchorOptions',
                            align: 'left',
                            children: [
                                {
                                    type: 'text',
                                    id: 'dialog_page_link_location',
                                    style: 'width: 100%;',
                                    label: 'Link Location'
                                }
                            ]
                        }
                    ]
                }
            ]
        });
    }

    // Check if the definition is from the dialog we're interested on (the "Link" dialog).
    if (dialogName == 'link') {
        dialogDefinition.removeContents('advanced');
        dialogDefinition.removeContents('upload');

        dialogDefinition.addContents({
            id: 'search_page',
            label: 'Search Other Pages',
            accessKey: 'M',
            elements: [

                {
                    type: 'vbox',
                    expand: true,
                    padding: 3,
                    hights: ['98%', '2%'],
                    children: [
                        {
                            type: 'hbox',
                            id: 'anchorOptions',
                            widths: [ '100%', '15%', '10%'],
                            align: 'center',
                            padding: 3,
                            children: [
                                {
                                    type: 'text',
                                    id: 'dialog_page_name',
                                    style: 'width: 100%;'
                                },
                                {
                                    type: 'select',
                                    id: 'dialog_select_space',
                                    default: '-1',
                                    items: [
                                        ['All Spaces', -1]
                                    ],
                                    onShow: function (data) {
                                        var element_id = '#' + this.getInputElement().$.id;
                                        $.ajax({
                                            type: "POST",
                                            url: '/documentador/space/get-all',
                                            data: {
                                            },
                                            success: function (response) {
                                                var spaces = jQuery.parseJSON(response);
                                                for (var i = 0; i < spaces.length; i++) {
                                                    $(element_id).get(0).options[$(element_id).get(0).options.length] = new Option(spaces[i].name, spaces[i].id);
                                                }
                                            }
                                        })
                                    }
                                },
                                {
                                    type: 'button',
                                    id: 'anchorName',
                                    label: 'Search',
                                    onClick: function () {
                                        $.ajax({
                                            type: "POST",
                                            url: '/documentador/page/find',
                                            data: {
                                                space_id: dialog.getContentElement('search_page', 'dialog_select_space').getValue(),
                                                page: dialog.getContentElement('search_page', 'dialog_page_name').getValue()
                                            },
                                            success: function (response) {
                                                var idHTMLTable = dialog.getContentElement('search_page', 'dialog_page_search').domId;
                                                $('#' + idHTMLTable).html(response);
                                            }
                                        })
                                    }
                                }
                            ]
                        },
                        {
                            type: 'hbox',

                            align: 'left',
                            height: 440,
                            style: 'vertical-align:text-bottom;',
                            children: [
                                {
                                    type: 'html',
                                    id: 'dialog_page_search',
                                    html: ''
                                }
                            ]
                        }

                    ]
                },
                {
                    type: 'vbox',
                    id: 'anchorOptions',
                    align: 'left',
                    children: [
                        {
                            type: 'text',
                            id: 'dialog_page_link_location',
                            style: 'width: 100%;',
                            label: 'Link Location'
                        }
                    ]
                }

            ]
        });

        // Rewrite the 'onFocus' handler to always focus 'url' field.
        dialogDefinition.onFocus = function () {
            var urlField = this.getContentElement('info', 'url');
            urlField.select();
        };

        dialogDefinition.onLoad = function () {
            this.getContentElement("search_page", "dialog_page_link_location").disable();
        };
    }
});