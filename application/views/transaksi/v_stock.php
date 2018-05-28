<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">
    #fm-dialog_stock{
        margin:0;
        padding:20px 30px;
    }
    #dlg_btn-dialog_stock{
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
    <form id="fm-dialog_stock" method="post" novalidate buttons="#dlg_btn-dialog_stock">
        <div class="fitem">
            <label for="type">Proses</label>
            <input type="text" id="process" name="process" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/stock/getProses'); ?>',
                method:'get', valueField:'m_process_cat_id', textField:'m_process_cat_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="item" name="item" style="width:250px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/stock/getItem'); ?>',
                method:'get', valueField:'m_item_id', textField:'m_item_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Qty Pcs</label>
            <input type="text" id="qty" name="qty" style="width:150px;" class="easyui-numberbox" precision="0" required="true"/>
        </div>
    </form>

<!-- Dialog Button -->
<div id="dlg_btn-dialog_stock">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiStockSave();">OK</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Batal</a>
</div>


<script type="text/javascript">
    function transaksiStockSave(){
        $('#fm-dialog_stock').form('submit',{
            url: '<?php echo site_url('transaksi/stock/create'); ?>',
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                   // $('#dlg-dialog_stock').dialog('close');
                    transaksiStockRefresh();
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
    
    function transaksiStockRefresh(){
        $('#item').combobox('setValue', '');
        $('#qty').numberbox('setValue', '');
    }
    
    
</script>

<!-- End of file v_stock.php -->
<!-- Location: ./views/transaksi/v_stock.php -->
