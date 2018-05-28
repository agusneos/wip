<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">
    #fm-dialog_proses{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_proses{
        margin:0;
        padding:10px 100px;
    }
    .ftitle{
        font-size:14px;
        font-weight:bold;
        padding:5px 0;
        margin-bottom:10px;
        border-bottom:1px solid #ccc;
    }
    .fitem{
        margin-bottom:5px;
    }
    .fitem label{
        display:inline-block;
        width:100px;
    }
</style>
<!-- Form -->
    <form id="fm-dialog_proses" method="post" novalidate buttons="#dlg_btn-dialog_proses">
        <div class="fitem">
            <label for="type">Proses</label>
            <input type="text" id="process_1" name="process_1" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/proses/getProses'); ?>',
                method:'get', valueField:'m_process_cat_id', textField:'m_process_cat_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Proses Sebelumnya</label>
            <input type="text" id="process_2" name="process_2" style="width:150px;" class="easyui-combobox" 
                data-options="url:'<?php echo site_url('transaksi/proses/getProsesBefore'); ?>',
                method:'get', valueField:'m_process_cat_id', textField:'m_process_cat_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="proc_item" name="proc_item" style="width:250px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/proses/getItem'); ?>',
                method:'get', valueField:'m_item_id', textField:'m_item_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Qty Pcs OK</label>
            <input type="text" id="qty_ok" name="qty_ok" style="width:150px;" class="easyui-numberbox" precision="0" />
        </div>
        <div class="fitem">
            <label for="type">Qty Pcs NG</label>
            <input type="text" id="qty_ng" name="qty_ng" style="width:150px;" class="easyui-numberbox" precision="0" />
        </div>
        <div class="fitem">
            <label for="type">Note</label>
            <input type="text" id="reason" name="reason" style="width:150px;" class="easyui-combobox"
                data-options="url:'<?php echo site_url('transaksi/proses/getReason'); ?>',
                method:'get', valueField:'m_reason_id', textField:'m_reason_txt', panelHeight:'150'"/>
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_proses">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiProsesSave();">OK</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Batal</a>
</div>


<script type="text/javascript">
    function transaksiProsesSave(){
        $('#fm-dialog_proses').form('submit',{
            url: '<?php echo site_url('transaksi/proses/create'); ?>',
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                   // $('#dlg-dialog_proses').dialog('close');
                    transaksiProsesRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Disimpan</div>'
                    });
                }
                else {
                    var win = $.messager.show({
                        title   : 'Error',
                        msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Disimpan !</div>'+result.error
                    });
                    win.window('window').addClass('bg-error');
                }
            }
        });
    }
    
    function transaksiProsesRefresh(){
        $('#process_2').combobox('setValue', '');
        $('#proc_item').combobox('setValue', '');
        $('#qty_ok').numberbox('setValue', '');
        $('#qty_ng').numberbox('setValue', '');
        $('#reason').combobox('setValue', '');
    }
    
    
</script>

<!-- End of file v_proses.php -->
<!-- Location: ./views/transaksi/v_proses.php -->
