<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">
    #fm-dialog_delivery{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_delivery{
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
    <form id="fm-dialog_delivery" method="post" novalidate buttons="#dlg_btn-dialog_delivery">
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="item" name="item" style="width:250px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/delivery/getItem'); ?>',
                method:'get', valueField:'m_item_id', textField:'m_item_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Qty Pcs</label>
            <input type="text" id="qty" name="qty" style="width:150px;" class="easyui-numberbox" required="true" precision="0"/>
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_delivery">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiDeliverySave();">OK</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Batal</a>
</div>


<script type="text/javascript">
    function transaksiDeliverySave(){
        $('#fm-dialog_delivery').form('submit',{
            url: '<?php echo site_url('transaksi/delivery/create'); ?>',
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#fm-dialog_delivery').form('clear');
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
    
</script>

<!-- End of file v_delivery.php -->
<!-- Location: ./views/transaksi/v_delivery.php -->
