<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_reason"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:false, toolbar:toolbar_master_reason">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_reason_id'"            width="100" align="center" halign="center" sortable="true">Id</th>
            <th data-options="field:'m_reason_txt'"           accesskey=""width="200" align="left"   halign="center" sortable="true">Keterangan NG</th>
        </tr>
    </thead>    
</table>

<script type="text/javascript">
    var toolbar_master_reason = [{
        id      : 'master_reason-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterReasonCreate();}
    },{
        id      : 'master_reason-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterReasonUpdate();}
    },{
        id      : 'master_reason-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterReasonHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterReasonRefresh();}
    }];
    
    $('#grid-master_reason').datagrid({
        onLoadSuccess   : function(){
            $('#master_reason-delete').linkbutton('disable');
            $('#master_reason-edit').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_reason-delete').linkbutton('enable');
            $('#master_reason-edit').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_reason-delete').linkbutton('enable');
            $('#master_reason-edit').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterReasonUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/reason/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterReasonRefresh() {
        $('#master_reason-delete').linkbutton('disable');
        $('#master_reason-edit').linkbutton('disable');
        $('#grid-master_reason').datagrid('reload');
    }
    
    function masterReasonCreate() {
        $('#dlg-master_reason').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_reason').form('clear');
        url = '<?php echo site_url('master/reason/create'); ?>';
    }
    
    function masterReasonUpdate(){
        var row = $('#grid-master_reason').datagrid('getSelected');
        if(row){
            $('#dlg-master_reason').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_reason').form('load',row);
            url = '<?php echo site_url('master/reason/update'); ?>/' + row.m_reason_id;
        }
        else{
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterReasonSave(){
        $('#fm-master_reason').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success) 
                {
                    $('#dlg-master_reason').dialog('close');
                    masterReasonRefresh();
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
        
    function masterReasonHapus(){
        var row = $('#grid-master_reason').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Id \n'+row.m_reason_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/reason/delete'); ?>',{m_reason_id:row.m_reason_id},function(result){
                        if (result.success)
                        {
                            masterReasonRefresh();
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
    #fm-master_reason{
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
    .freason{
        margin-bottom:5px;
    }
    .freason label{
        display:inline-block;
        width:100px;
    }
    .freason input{
        display:inline-block;
        width:150px;
    }
</style>

<!-- ----------- -->
<div id="dlg-master_reason" class="easyui-dialog" style="width:450px; height:200px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_reason">
    <form id="fm-master_reason" method="post" novalidate>        
        <div class="freason">
            <label for="type">Keterangan NG</label>
            <input type="text" id="m_reason_txt" name="m_reason_txt" style="width:150px;" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_reason">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterReasonSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_reason').dialog('close')">Batal</a>
</div>

<!-- End of file v_reason.php -->
<!-- Location: ./application/views/master/v_reason.php -->