</head>

<body class="gray-bg">
<div class="wrapper wrapper-content">


</div>
<?php $this->load->view('common/js');?>

<!-- Flot -->
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/flot/jquery.flot.js"></script>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/flot/jquery.flot.spline.js"></script>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/flot/jquery.flot.resize.js"></script>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/flot/jquery.flot.pie.js"></script>
<script src="<?php echo FRONT_JS_URL;?>/hplus/plugins/flot/jquery.flot.symbol.js"></script>

<script>
    $(document).ready(function () {
        //stop
        var data2 = [
            <?php echo implode(",", $data2);?>
        ];
        //running
        var data3 = [
            <?php echo implode(",", $data3);?>
        ];


        var dataset = [
            {
                label: "运行的设备",
                data: data3,
                color: "#1ab394",
                bars: {
                    show: true,
                    align: "center",
                    barWidth: 24 * 60 * 60 * 600,
                    lineWidth: 0
                }

            }, {
                label: "关闭的设备",
                data: data2,
                yaxis: 2,
                color: "#464f88",
                lines: {
                    lineWidth: 1,
                    show: true,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0.2
                        }, {
                            opacity: 0.2
                        }]
                    }
                },
                splines: {
                    show: false,
                    tension: 0.6,
                    lineWidth: 1,
                    fill: 0.1
                },
            }
        ];


        var options = {
            xaxis: {
                mode: "time",
                tickSize: [3, "day"],
                tickLength: 0,
                axisLabel: "Date",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Arial',
                axisLabelPadding: 10,
                color: "#838383"
            },
            yaxes: [{
                position: "left",
                max: 1070,
                color: "#838383",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Arial',
                axisLabelPadding: 3
            }, {
                position: "right",
                clolor: "#838383",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: ' Arial',
                axisLabelPadding: 67
            }
            ],
            legend: {
                noColumns: 1,
                labelBoxBorderColor: "#000000",
                position: "nw"
            },
            grid: {
                hoverable: false,
                borderWidth: 0,
                color: '#838383'
            }
        };

        function gd(year, month, day) {
            return new Date(year, month - 1, day).getTime();
        }

        var previousPoint = null,
            previousLabel = null;

        $.plot($("#flot-dashboard-chart"), dataset, options);


    });
</script>
