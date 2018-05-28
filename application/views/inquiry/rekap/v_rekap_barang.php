<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<table id="grid-inquiry_rekap_barang"
    data-options="pageSize:50, multiSort:false, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'t_proc_item'"          width="100"  align="center" halign="center" sortable="false">Kode Barang</th>
            <th data-options="field:'m_item_name'"          width="100"  align="center" halign="center" sortable="false">Nama Barang</th>
            <th data-options="field:'m_process_cat_name'"   width="100"  align="center" halign="center" sortable="false">Proses</th>
            <th data-options="field:'t_proc_qty'"           width="100"  align="center" halign="center" sortable="false">Qty</th>
            <th data-options="field:'a_user_name'"          width="100"  align="center" halign="center" sortable="false">Operator</th>
            <th data-options="field:'t_proc_time'"          width="100"  align="center" halign="center" sortable="false">Tanggal Input</th>
        </tr>
    </thead>    
</table>


<script type="text/javascript">

    $('#grid-inquiry_rekap_barang').datagrid({
        url             : '<?php echo site_url('inquiry/rekap/showRekapBarang'); ?>?grid=true&rekap_proses='+rekap_proses+'&rekap_tgl_from='+rekap_tgl_from+'&rekap_tgl_to='+rekap_tgl_to});
        
</script>

<!-- End of file v_rekap_barang.php -->
<!-- Location: ./views/inquiry/rekap/v_rekap_barang.php -->
