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
            var chartData = [];
            $.each(response, function(i, item) {
                chartData.push([item.assignee_name, item.issues_count]);
            });

            $('#chartContainer').highcharts({
                chart: {
                    type: chartType
                },
                title: {
                    text: 'Issues per assignee'
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '# Issues'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: 'Number of issues: <b>{point.y:.1f}</b>'
                },
                series: [{
                    name: 'Population',
                    data: chartData,
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        align: 'center',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
            });
        }
    });
});