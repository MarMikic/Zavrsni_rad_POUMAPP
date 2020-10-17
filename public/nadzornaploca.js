Highcharts.chart('graf1', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Broj učenika po klasama'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        series: {
            cursor: 'pointer',
            point: {
                events: {
                    click: function () {
                        location.href = '/klasa/promjena?sifra=' + this.options.sifra;
                    }
                }
            }
        },
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
            }
        }
    },
    series: [{
        name: 'Klase',
        colorByPoint: true,
        data: podaci
    }]
});




graf2funkcija();