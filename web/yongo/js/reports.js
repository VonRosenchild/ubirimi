$('document').ready(function () {

    var statisticType = $('#statistic_type').val();
    var chartType = $('#chart_type').val();

    $.ajax({
        type: "POST",
        url: '/yongo/project/report/data',
        data: {
            project_id: $('#project_id').val(),
            type: 'assignee'

        },
        success: function (response) {
            var chartData = [['Assignees', 'Number of issues']];



            $.each(response, function(i, item) {

                chartData.push([item.assignee_name, item.issues_count]);
            });

            var data = google.visualization.arrayToDataTable(chartData);

            var options = {
                title: 'Issues per Assignee',
                pieHole: 0.2
            };

            if ('column' == chartType) {
                var chart = new google.visualization.ColumnChart(document.getElementById('charContainer'));
            } else if ('pie' == chartType) {
                var chart = new google.visualization.PieChart(document.getElementById('charContainer'));
            }

            chart.draw(data, options);
        }
    });
});