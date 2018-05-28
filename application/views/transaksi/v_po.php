<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    $.extend($.fn.datebox.defaults,{
        formatter:function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
        },
        parser:function(s){
            if (!s) return new Date();
            var ss = (s.split('-'));
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d);
            } else {
                return new Date();
            }
        }
    });
</script>
<!-- Data Grid -->
<table id="grid-transaksi_po"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:false, toolbar:toolbar_transaksi_po">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'t_po_item'"            width="100" align="center" halign="center" sortable="true">Kode HMP</th>
            <th data-options="field:'m_item_name'"          width="100" align="center" halign="center" sortable="true">Nama Barang</th>
            <th data-options="field:'m_item_ext_id'"        width="100" align="center" halign="center" sortable="true">Kode STS</th>
            <th data-options="field:'t_po_qty'"             width="200" align="left"   halign="center" sortable="true">Qty Pcs</th>
            <th data-options="field:'t_po_date'"            width="200" align="left"   halign="center" sortable="true">Qty Pcs</th>
        </tr>
    </thead>    
</table>

<script type="text/javascript">
    var toolbar_transaksi_po = [{
        id      : 'transaksi_po-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiPoCreate();}
    },{
        id      : 'transaksi_po-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){transaksiPoUpdate();}
    },{
        id      : 'transaksi_po-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){transaksiPoHapus();}
    },{
        id      : 'transaksi_po-upload',
        text    : 'Upload',
        iconCls : 'icon-upload',
        handler : function(){transaksiPoUpload();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){transaksiPoRefresh();}
    }];
    
    $('#grid-transaksi_po').datagrid({
        onLoadSuccess   : function(){
            $('#transaksi_po-delete').linkbutton('disable');
            $('#transaksi_po-edit').linkbutton('disable');
        },
        onSelect        : function(){
            $('#transaksi_po-delete').linkbutton('enable');
            $('#transaksi_po-edit').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#transaksi_po-delete').linkbutton('enable');
            $('#transaksi_po-edit').linkbutton('enable');
        },
        onDblClickRow   : function(){
            transaksiPoUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('transaksi/po/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function transaksiPoRefresh() {
        $('#transaksi_po-delete').linkbutton('disable');
        $('#transaksi_po-edit').linkbutton('disable');
        $('#grid-transaksi_po').datagrid('reload');
    }
    
    function transaksiPoCreate() {
        $('#dlg-transaksi_po').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-transaksi_po').form('clear');
        url = '<?php echo site_url('transaksi/po/create'); ?>';
        $('#t_po_item').combobox('readonly',false);
    }
    
    function transaksiPoUpdate(){
        var row = $('#grid-transaksi_po').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_po').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_po').form('load',row);
            url = '<?php echo site_url('transaksi/po/update'); ?>/' + row.t_po_item;
            $('#t_po_item').combobox('readonly',true);
        }
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiPoSave(){
        $('#fm-transaksi_po').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success) 
                {
                    $('#dlg-transaksi_po').dialog('close');
                    transaksiPoRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Disimpan</div>'
                    });
                }
                else
                {
                    var win = $.messager.show({
                        title   : 'Error',
                        msg     : '<div class="messager-icon messager-error"></div><div>Data Gagal Disimpan !</div>'+result.error
                    });
                    win.window('window').addClass('bg-error');
                }
            }
        });
    }
        
    function transaksiPoHapus(){
        var row = $('#grid-transaksi_po').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Id \n'+row.t_po_item+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/po/delete'); ?>',{t_po_item:row.t_po_item},function(result){
                        if (result.success)
                        {
                            transaksiPoRefresh();
                            $.messager.show({
                                title   : 'Info',
                                msg     : '<div class="messager-icon messager-info"></div><div>Data Berhasil Dihapus</div>'
                            });
                        }
                        else
                        {
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
    
    function transaksiPoUpload(){
        $('#dlg-transaksi_po-upload').dialog({modal: true}).dialog('open').dialog('setTitle','Upload File');
        $('#fm-transaksi_po-upload').form('reset');
        urls = '<?php echo site_url('transaksi/po/upload'); ?>/';
    }
    
    function transaksiPoUploadSave(){
        $('#fm-transaksi_po-upload').form('submit',{
            url: urls,
            onSubmit: function(){   
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-transaksi_po-upload').dialog('close');
                    transaksiPoRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : result.total + ' ' +result.ok + ' ' + result.ng
                    });
                } 
                else {
                    $.messager.show({
                        title   : 'Error',
                        msg     : 'Upload Data Gagal'
                    });
                }
            }
        });
    }
    
    $('#upload_po').filebox({
        accept: ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
    });
        
</script>

<style type="text/css">
    .bg-error{ 
        background: red;
    }
    .bg-error .panel-title{
        color:#fff;
    }
    .bg-warning{ 
        background: yellow;
    }
    .bg-warning .panel-title{
        color:#000;
    }
    #fm-transaksi_po{
        margin:0;
        padding:10px 30px;
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
    .fitem input{
        display:inline-block;
        width:150px;
    }
</style>

<div id="dlg-transaksi_po-upload" class="easyui-dialog" style="width:400px; height:150px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-transaksi_po-upload">
    <form id="fm-transaksi_po-upload" method="post" enctype="multipart/form-data" novalidate>       
        <div class="fitem">
            <label for="type">File</label>
            <input id="upload_po" name="upload_po" class="easyui-filebox" required="true"/>
        </div> 
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-transaksi_po-upload">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiPoUploadSave();">Upload</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_po-upload').dialog('close');">Batal</a>
</div>

<!-- ----------- -->
<div id="dlg-transaksi_po" class="easyui-dialog" style="width:550px; height:300px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_po">
    <form id="fm-transaksi_po" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="t_po_item" name="t_po_item" style="width:250px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/po/getItem'); ?>',
                method:'get', valueField:'m_item_id', textField:'m_item_name', 
                onSelect: function(rec){
                    $('#code_hmp').numberbox('setValue', rec.m_item_id);
                    $('#code_sts').numberbox('setValue', rec.m_item_ext_id);
                },panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Kode HMP</label>
            <input type="text" id="code_hmp" name="code_hmp" style="width:150px;" class="easyui-numberbox" precision="0" readonly="true"/>
        </div>
        <div class="fitem">
            <label for="type">Kode STS</label>
            <input type="text" id="code_sts" name="code_sts" style="width:150px;" class="easyui-numberbox" precision="0" readonly="true"/>
        </div>
        <div class="fitem">
            <label for="type">Qty Pcs</label>
            <input type="text" id="t_po_qty" name="t_po_qty" style="width:150px;" class="easyui-numberbox" precision="0" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Tanggal Delv</label>
            <input type="text" id="t_po_date" name="t_po_date" style="width:150px;" class="easyui-datebox" precision="0" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_po">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiPoSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_po').dialog('close')">Batal</a>
</div>

<!-- End of file v_po.php -->
<!-- Location: ./application/views/transaksi/v_po.php -->