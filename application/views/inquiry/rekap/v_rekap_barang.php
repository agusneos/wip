<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<table id="grid-inquiry_rekap_barang"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:inquiry_rekap_barang">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'t_proc_id'"            width="100"  align="center" halign="center" sortable="true">Record ID</th>
            <th data-options="field:'t_proc_item'"          width="100"  align="center" halign="center" sortable="true">Kode Barang</th>
            <th data-options="field:'m_item_name'"          width="100"  align="center" halign="center" sortable="true">Nama Barang</th>
            <th data-options="field:'m_process_cat_name'"   width="100"  align="center" halign="center" sortable="true">Proses</th>
            <th data-options="field:'t_proc_qty'"           width="100"  align="center" halign="center" sortable="true">Qty</th>
            <th data-options="field:'a_user_name'"          width="100"  align="center" halign="center" sortable="true">Operator</th>
            <th data-options="field:'t_proc_time'"          width="100"  align="center" halign="center" sortable="true">Tanggal Input</th>
        </tr>
    </thead>    
</table>


<script type="text/javascript">
    var inquiry_rekap_barang = [{
        id      : 'inquiry_rekap-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){inquiryRekapHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){$('#grid-inquiry_rekap_barang').datagrid('reload');}
    }];
    $('#grid-inquiry_rekap_barang').datagrid({
        view            :scrollview,
        remoteFilter    :true,
        url             : '<?php echo site_url('inquiry/rekap/showRekapBarang'); ?>?grid=true&rekap_proses='+rekap_proses+'&rekap_tgl_from='+rekap_tgl_from+'&rekap_tgl_to='+rekap_tgl_to})
    .datagrid('enableFilter');
    
    function inquiryRekapHapus(){
        var row = $('#grid-inquiry_rekap_barang').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Id \n'+row.t_proc_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('inquiry/rekap/delete'); ?>',{t_proc_time:row.t_proc_time},function(result){
                        if (result.success) {
                            $('#grid-inquiry_rekap_barang').datagrid('reload');
                            $.messager.show({
                                title   : 'Info',
                                msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Dihapus</div>'
                            });
                        }
                        else{
                            $.messager.show({
                                title   : 'Error',
                                msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Dihapus !</div>'+result.error
                            });
                        }
                    },'json');
                }
            });
            win.find('.messager-icon').removeClass('messager-question').addClass('messager-warning');
            win.window('window').addClass('bg-warning');
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
</script>

<!-- End of file v_rekap_barang.php -->
<!-- Location: ./views/inquiry/rekap/v_rekap_barang.php -->
