$('document').ready(function () {

    var selectedObjs;

    function moveSelected(ol, ot){
        console.log(selectedObjs);
        selectedObjs.each(function(){
            $this =$(this);
            var pos = $this.position();

            var l = $this.context.clientLeft;
            var t = $this.context.clientTop;

            $this.css('left', l+ol);
            $this.css('top', t+ot);
        })
    }

    $(".draggable").draggable({
        start: function(event, ui) {
            //get all selected...
            selectedObjs = $('.event_' + $(this).attr('id').replace('cal_event_', ''));
        },
        drag: function(event, ui) {
            var currentLoc = $(this).position();
            var orig = ui.originalPosition;

            var offsetLeft = currentLoc.left - orig.left;
            var offsetTop = currentLoc.top;

            moveSelected(offsetLeft, offsetTop);
        }
    });
    $(".droppable").droppable({

        drop: function(event, ui) {
            var elem = ui.draggable;
            elem.css('left', '');
            elem.css('top', '');
            $(this).append(elem);

            var elemId = elem.attr('id');
            var eventId = elemId.replace('cal_event_', '');

            var date = this.id.replace('calendar_day_', '');
            var day = date.split('_')[0];
            var month = date.split('_')[1];
            var year = date.split('_')[2];

            if (day < 10) {
                day = '0' + day;
            }
            if (month < 10) {
                month = '0' + month;
            }

            $.ajax({
                type: "POST",
                url: '/calendar/update-event-offset',
                data: {
                    id: eventId,
                    offset: year + '-' + month + '-' + day
                },
                success: function (response) {

                }
            });
        }
    });
});