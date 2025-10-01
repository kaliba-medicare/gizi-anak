var options = {
        series: [
            {
                name: "Net Profit",
                data: [19, 36, 24, 20, 34, 24, 11, 36, 24, 15, 21, 28],
            },
            {
                name: "Revenue",
                data: [7, 12, 10, 12, 11, 10, 13, 10, 12, 8, 13, 13],
            },
        ],
        chart: {
            type: "bar",
            height: 350,
            stacked: !0,
            toolbar: { show: !1 },
            zoom: { enabled: !0 },
        },
        plotOptions: { bar: { horizontal: !1, columnWidth: "42%" } },
        dataLabels: { enabled: !1 },
        legend: { show: !1 },
        xaxis: {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "July",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Des",
            ],
        },
        colors: ["#0c768a", "#daeaee"],
        fill: { opacity: 1 },
    },
chart = new ApexCharts(document.querySelector("#column-chart"), options);
chart.render();
options = {
    series: [40405, 15552, 19824],
    labels: ["Normal", "Stunting", "Underweight"],
    chart: { type: "donut", height: 350 },
    plotOptions: {
        pie: {
            size: 100,
            offsetX: 0,
            offsetY: 0,
            donut: {
                size: "77%",
                labels: {
                    show: !0,
                    name: { show: !0, fontSize: "18px", offsetY: -5 },
                    value: {
                        show: !0,
                        fontSize: "24px",
                        color: "#343a40",
                        fontWeight: 500,
                        offsetY: 10,
                        formatter: function (e) {
                            return "$" + e;
                        },
                    },
                    total: {
                        show: !0,
                        fontSize: "16px",
                        label: "Total",
                        color: "#9599ad",
                        fontWeight: 400,
                        formatter: function (e) {
                            return (
                                "$" +
                                e.globals.seriesTotals.reduce(function (e, o) {
                                    return e + o;
                                }, 0)
                            );
                        },
                    },
                },
            },
        },
    },
    dataLabels: { enabled: !1 },
    legend: { show: !0, position: "bottom" },
    yaxis: {
        labels: {
            formatter: function (e) {
                return "$" + e;
            },
        },
    },
    stroke: { lineCap: "round", width: 2 },
    colors: ["#0c768a", "#38c786", "#daeaee"],
};
(chart = new ApexCharts(
    document.querySelector("#donut-chart"),
    options
)).render();

