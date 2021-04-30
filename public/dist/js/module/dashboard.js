$(function () {
    'use strict'
    let jsonData = $('#donutChart').data('value');
    let labels = [];
    let data = [];
    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    $.each(jsonData, function (key, value) {
        labels.push(key);
    });
    $.each(jsonData, function (key, value) {
        data.push(value);
    });
    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    let donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    let donutData        = {
        labels: labels,
        datasets: [
            {
                data: data,
                backgroundColor : [
                    '#f56954',
                    '#00a65a',
                    '#f39c12',
                    '#FCF3CF',
                    '#00c0ef',
                    '#3c8dbc',
                    '#d2d6de',
                    '#909497',
                    '#ABEBC6',
                    '#2E4053',
                    '#C39BD3',
                ],
            }
        ]
    }
    let donutOptions     = {
        maintainAspectRatio : false,
        responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: donutData,
        options: donutOptions
    })
})

$(function () {
    $("#projectTable").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": [
            { extend: 'copy', className: 'btn-default' },
            { extend: 'pdf', className: 'btn-default' },
            { extend: 'excel', className: 'btn-default' },
            { extend: 'colvis', className: 'btn-default' },
        ]
    }).buttons().container().appendTo('#projectTable_wrapper .col-md-6:eq(0)');
});


