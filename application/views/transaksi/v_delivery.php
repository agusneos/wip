<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">
    #fm-transaksi_delivery-upload{
        margin:0;
        padding:20px 30px;
    }
    #dlg_buttons-transaksi_delivery{
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


<form id="fm-transaksi_delivery-upload" method="post" enctype="multipart/form-data" novalidate>       
    <div class="fitem">
        <label for="type">File</label>
        <input id="upload_delv" name="upload_delv" class="easyui-filebox" required="true"/>
    </div> 
</form>

<!-- Dialog Button -->
<div id="dlg_buttons-transaksi_delivery">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiDeliveryUploadSave();">Upload</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close');">Batal</a>
</div>

<script type="text/javascript">
    function transaksiDeliveryUploadSave(){
        $('#fm-transaksi_delivery-upload').form('submit',{
            url: '<?php echo site_url('transaksi/delivery/upload'); ?>/',
            onSubmit: function(){   
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-transaksi_delivery-upload').dialog('close');
                    //transaksiDeliveryRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : result.total + ' ' +result.ok + ' ' + result.ng
                    });
                } 
                else {
                    var win = $.messager.show({
                        title   : 'Error',
                        msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Diupload !</div>'+result.error
                    });
                    win.window('window').addClass('bg-error');
                }
            }
        });
    }
    
    $('#upload_delv').filebox({
        accept: ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
    });
    
</script>

<!-- End of file v_delivery.php -->
<!-- Location: ./views/transaksi/v_delivery.php -->
