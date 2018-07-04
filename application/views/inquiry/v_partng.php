<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-inquiry_partng"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_inquiry_partng">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_item_id'"            width="80"  align="center" halign="center" sortable="true">Kode Barang</th>
            <th data-options="field:'m_item_name'"          width="200" align="left"   halign="center" sortable="true">Nama Barang</th>
            <th data-options="field:'m_item_ext_id'"        width="80"  align="center" halign="center" sortable="true">Kode Saga</th>
        </tr>
    </thead>    
</table>

<script type="text/javascript">
    var toolbar_inquiry_partng = [{
        id      : 'inquiry_partng-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterItemCreate();}
    },{
        id      : 'inquiry_partng-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterItemUpdate();}
    },{
        id      : 'inquiry_partng-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterItemHapus();}
    },{
        id      : 'inquiry_partng-upload',
        text    : 'Upload',
        iconCls : 'icon-upload',
        handler : function(){masterItemUpload();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterItemRefresh();}
    }];
    
    $('#grid-inquiry_partng').datagrid({
        onLoadSuccess   : function(){
            $('#inquiry_partng-edit').linkbutton('disable');
            $('#inquiry_partng-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#inquiry_partng-edit').linkbutton('enable');
            $('#inquiry_partng-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#inquiry_partng-edit').linkbutton('enable');
            $('#inquiry_partng-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterItemUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/item/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterItemRefresh() {
        $('#inquiry_partng-edit').linkbutton('disable');
        $('#inquiry_partng-delete').linkbutton('disable');
        $('#grid-inquiry_partng').datagrid('reload');
    }

    function masterItemCreate() {
        $('#dlg-inquiry_partng').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-inquiry_partng').form('clear');
        url = '<?php echo site_url('master/item/create'); ?>';
        $('#m_item_id').numberbox('enable');
    }
    
    function masterItemUpdate() {
        var row = $('#grid-inquiry_partng').datagrid('getSelected');
        if(row){
            $('#dlg-inquiry_partng').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-inquiry_partng').form('load',row);
            url = '<?php echo site_url('master/item/update'); ?>/' + row.m_item_id;
            $('#m_item_id').numberbox('disable');
        }
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterItemSave(){
        $('#fm-inquiry_partng').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-inquiry_partng').dialog('close');
                    masterItemRefresh();
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
        
    function masterItemHapus(){
        var row = $('#grid-inquiry_partng').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Item \n'+row.m_item_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/item/delete'); ?>',{m_item_id:row.m_item_id},function(result){
                        if (result.success) {
                            masterItemRefresh();
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

    function masterItemUpload(){
        $('#dlg-inquiry_partng-upload').dialog({modal: true}).dialog('open').dialog('setTitle','Upload File');
        $('#fm-inquiry_partng-upload').form('reset');
        urls = '<?php echo site_url('master/item/upload'); ?>/';
    }
    
    function masterItemUploadSave(){
        $('#fm-inquiry_partng-upload').form('submit',{
            url: urls,
            onSubmit: function(){   
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-inquiry_partng-upload').dialog('close');
                    masterItemRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : result.total + ' ' +result.ok + ' ' + result.ng
                    });
                } 
                else  {
                    $.messager.show({
                        title   : 'Error',
                        msg     : 'Upload Data Gagal'
                    });
                }
            }
        });
    }
    
    $('#fileb').filebox({
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
    #fm-inquiry_partng{
        margin:0;
        padding:10px 30px;
    }
    #fm-inquiry_partng-upload{
        margin:0;
        padding:10px 30px;
    }
    #fm-inquiry_partng-valid_bom_route{
        margin:0;
        padding:10px 30px;
    }
    #fm-inquiry_partng-proses_entry{
        margin:0;
        padding:10px 30px;
    }
    #fm-inquiry_partng-proses_copy{
        margin:0;
        padding:10px 30px;
    }
    #fm-inquiry_partng-bom_entry{
        margin:0;
        padding:10px 30px;
    }
    #fm-inquiry_partng-bom_copy{
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

<div id="dlg-inquiry_partng-upload" class="easyui-dialog" style="width:400px; height:150px; padding: 10px 20px" closed="true" buttons="#dlg_buttons-inquiry_partng-upload">
    <form id="fm-inquiry_partng-upload" method="post" enctype="multipart/form-data" novalidate>       
        <div class="fitem">
            <label for="type">File</label>
            <input id="fileb" name="fileb" class="easyui-filebox" required="true"/>
        </div> 
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg_buttons-inquiry_partng-upload">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterItemUploadSave();">Upload</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-inquiry_partng-upload').dialog('close');">Batal</a>
</div>


<!-- ----------- -->
<div id="dlg-inquiry_partng" class="easyui-dialog" style="width:800px; height:360px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-inquiry_partng">
    <form id="fm-inquiry_partng" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Kode Barang</label>
            <input type="text" id="m_item_id" name="m_item_id" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="m_item_name" name="m_item_name" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Kode Saga</label>
            <input type="text" id="m_item_ext_id" name="m_item_ext_id" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-inquiry_partng">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterItemSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-inquiry_partng').dialog('close')">Batal</a>
</div>

<!-- End of file v_item.php -->
<!-- Location: ./application/views/master/v_item.php -->