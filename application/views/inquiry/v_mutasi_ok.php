<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<table id="pg-inquiry_wip_ok" data-options="rownumbers:true" style="width:700px;height:300px"></table>

<script type="text/javascript">
$('#pg-inquiry_wip_ok').pivotgrid({
    //title:'WIP ALL',
    toolbar:[{
        text:'Layout',
        handler:function(){
            $('#pg-inquiry_wip_ok').pivotgrid('layout');
        }
    }],
    url:'<?php echo site_url('inquiry/mutasi/indexOk'); ?>?grid=true',
    method:'get',
    pivot:{
        rows:['m_item_name'],
        columns:['m_process_cat_name','t_proc_stat'],
        filters:['m_item_name'],
        values:[
            {field:'Qty',op:'sum'}
        ]
    },
    forzenColumnTitle:'<span style="font-weight:bold">Item Name</span>',
    valuePrecision:0,
    fit:true,
    //showFooter:true,
    fitColumns:true,
    valueStyler:function(value,row,index){
        if (/Qty$/.test(this.field) && value<0){
                return 'background:#ffcccc';
        }
    },
    valueFormatter:function(value,row,index){
        return accounting.formatMoney(value, '', 0, '.', ',');
    }
});
/*
$('#pg-inquiry_wip_ok').pivotgrid('reloadFooter',[
	{name: 'OK', qty: 60000}
]);*/
</script>

<!-- End of file v_mutasi_ok.php -->
<!-- Location: ./views/inquiry/v_mutasi_ok.php -->
