<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?php
    /* Mengambil query report*/
    foreach($report as $result){
        $proses[] = $result->m_process_cat_name; //ambil bulan
        $value[] = (float) $result->Qty; //ambil nilai
    }
    /* end mengambil query*/
     
?>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Work In Process'
        },
        xAxis: {
            categories: <?php echo json_encode($proses);?>,
            crosshair: true
        },
        yAxis: {
            //min: 0,
            title: {
                text: 'Pcs'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:,.0f} Pcs</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Proses',
            data: <?php echo json_encode($value);?>

        }]
    });
});
</script>
<!-- End of file v_dashboard.php -->
<!-- Location: ./application/views/v_dashboard.php -->