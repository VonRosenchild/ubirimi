$('document').ready(function () {

    var curColourIndex = 1, maxColourIndex = 24, nextColour = function () {
        var R, G, B;
        R = parseInt(128 + Math.sin((curColourIndex * 3 + 0) * 1.3) * 128);
        G = parseInt(128 + Math.sin((curColourIndex * 3 + 1) * 1.3) * 128);
        B = parseInt(128 + Math.sin((curColourIndex * 3 + 2) * 1.3) * 128);
        curColourIndex = curColourIndex + 1;
        if (curColourIndex > maxColourIndex) curColourIndex = 1;
        return "rgb(" + R + "," + G + "," + B + ")";
    };

    window.jsPlumbDemo = {

        init: function () {

            jsPlumb.importDefaults({
                Endpoint: ["Dot", {radius: 2}],
                ConnectionsDetachable: false,
                ConnectionOverlays: [
                    [ "Arrow", {
                        location: 1,
                        id: "arrow",
                        length: 14,
                        foldback: 0.8
                    } ],
                    [ "Label", { label: "New transition", id: "label" }]
                ]
            });

            jsPlumb.draggable(jsPlumb.getSelector(".node"), {containment: "parent"});

            jsPlumbDemo.initEndpoints(nextColour);
            jsPlumb.makeTarget(jsPlumb.getSelector(".node"), {
                anchor: "Continuous",
                connectionsDetachable: false
            });
        }
    };
    jsPlumbDemo.initEndpoints = function (nextColour) {
        $(".ep").each(function (i, e) {
            var p = $(e).parent();
            jsPlumb.makeSource($(e), {
                parent: p,
                anchor: "Continuous",

                connector: [ "Flowchart", { gap: 0, cornerRadius: 10, alwaysRespectStubs: true } ],
                connectorStyle: {
                    lineWidth: 1,
                    strokeStyle: "#deea18",
                    joinstyle: "round",
                    outlineColor: "#EAEDEF",
                    outlineWidth: 1
                },
                maxConnections: -1,
                connectionsDetachable: false
            });
        });
    };

    jsPlumb.bind("ready", function () {
        // chrome fix.
        document.onselectstart = function () {
            return false;
        };

        var newMode = jsPlumb.setRenderMode(jsPlumb.SVG);
        jsPlumbDemo.init();

        if (!$('#workflow_id').val() || !$('#buttons_workflow').length) {
            return;
        }

        $.ajax({
            type: "POST",
            url: '/yongo/administration/ajax/workflow/get-project-workflow',
            data: {
                id: $('#workflow_id').val()
            },
            success: function (response) {
                var obj_result = jQuery.parseJSON(response);
                var values = obj_result.values;
                var positions = obj_result.positions;
                var offset_top = $('#buttons_workflow').position().top + 50;

                $.each(positions, function (key, value) {
                    if (value.top_position) {
                        $('#node_status_' + value.workflow_step_id).offset({top: value.top_position + offset_top, left: value.left_position});
                    }
                });

                $.each(values, function (key, value) {
                    var conn_init = jsPlumb.connect({
                        source: "node_status_" + value.ws1id,
                        target: "node_status_" + value.ws2id,
                        detachable: false
                    });
                    var transitionName = value.transition_name;
                    if (transitionName == null)
                        transitionName = '';

                    conn_init.getOverlay("label").setLabel('<span class="settings_transition" id="label_transition_' + value.ws1id + '_' + value.ws2id + '">' + transitionName + '<img width="16px" src="/img/settings.png" /></span>');
                });
                $('#initial_rendering').val(0);
            }
        });
    });

    jsPlumb.bind("connection", function (info, originalEvent) {
        var firstStepId = $('#first_step').val();

        var conn = info.connection;

        if (jsPlumb.getConnections({source: 'node_status_' + firstStepId}).length > 1) {
            jsPlumb.detach(conn);
            alert('You can not create more than one transition for the initial step of creating an issue.')
        } else if (jsPlumb.getConnections({source: conn.sourceId, target: 'node_status_' + firstStepId}).length >= 1) {
            alert('This type of transition can not be made.');
            jsPlumb.detach(conn);
        } else {

            conn.detachable = false;
            conn.getOverlay("label").setLabel('<img id="label_transition_' + conn.sourceId + '_' + conn.targetId + '" class="settings_transition" width="16px" src="/img/settings.png" />');

            // save the transition to the database
            var workflow_id = $('#workflow_id').val();

            var IdFrom = conn.sourceId.replace('node_status_', '');
            var IdTo = conn.targetId.replace('node_status_', '');
            var initial_rendering = $('#initial_rendering').val();
            if (initial_rendering == 0) {

                $.ajax({
                    type: "POST",
                    url: '/yongo/administration/ajax/workflow/save-transition-data',
                    data: {
                        project_workflow_id: workflow_id,
                        id_from: IdFrom,
                        id_to: IdTo,
                        name: 'New Transition'
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            }
        }
    });

    $('#btnSaveWorkflow').on('click', function (event) {
        event.preventDefault();
        var positions = [];
        var index = 0;
        $("[id^='node_status_']").each(function () {
            var id = $(this).attr("id").replace('node_status_', '');
            var pos = $('#' + this.id).position();
            positions[index++] = id + '###' + pos.top + '###' + pos.left;
        });

        $.ajax({
            type: "POST",
            url: '/yongo/administration/workflow/save',
            data: {
                id: $('#workflow_id').val(),
                positions: positions
            },
            success: function (response) {
                document.location.href = '/yongo/administration/workflows';
            }
        });
    });
});