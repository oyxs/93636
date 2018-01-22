    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="/statics/ea/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="/statics/ea/themes/icon.css">
        <link rel="stylesheet" type="text/css" href="/statics/ea/demo.css">
        <script type="text/javascript" src="/statics/ea/jquery.min.js"></script>
        <script type="text/javascript" src="/statics/ea/jquery.easyui.min.js"></script>
        <style>

            .wait {
                 background:repeat scroll 0 0 ;
                 background-color:#C2C2C2;
                height: 8000px;
                left: 0;
                position: absolute;
                top: 0;
                width: 100%;
                z-index: 999999998;
                 filter:alpha(opacity=50); /*背景透明*/
                  -moz-opacity:0.5;  
                  -khtml-opacity: 0.5;  
                  opacity: 0.5;  
            }
            .wait img {
                 left: 60%;
                margin-left: -200px;
                position: fixed;
                top: 45%;
                z-index: 999999999;
            }
        </style>
    </head>
    <body>
        <div id="lightbox1"  style="display: none;" class="wait">
            <img src="/statics/ea/themes/123.gif" />
        </div>
        <div>
        <div class="bd">
        <form id="addGoodform" method="post" style="margin: 0; padding: 0">
        <table>
            <tr >
                <td colspan="7" >
                    <a href="javascript:menuJS.editUser('add')" class="easyui-linkbutton" data-options="iconCls:'icon-add'">新增</a>
                    <a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-edit'" onclick="javascript:menuJS.editUser('edit')">修改</a>
                    <a href="#" class="easyui-linkbutton" onclick="javascript:menuJS.removemenu()" data-options="iconCls:'icon-remove'">删除</a>
                    <a href="javascript:menuJS.reloadmenu_fw();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'"  style="width:130px">刷新服务号菜单</a>
                </td>
            </tr>
        </table>
        </form>
        </div>

        </div>
        <table id="ss" class="easyui-datagrid" title="自定义菜单" style="width:100%;height:500px"
            data-options="singleSelect:true,collapsible:true,url:'/index.php?m=weixin&c=menu&a=wx_menu_list',method:'get',sortName : 'id',sortOrder : 'asc'">
        <thead>
            <tr>
                <th data-options="field:'id',width:80,align:'center'">ID</th>
                <th data-options="field:'listorder',width:80,align:'center'">排序</th>
                <th data-options="field:'name',width:150,align:'center'">菜单名字</th>
                <th data-options="field:'typeName',width:120,align:'center'">菜单类型</th>
                <th data-options="field:'type',width:80,align:'center',hidden:true">type</th>
                <th data-options="field:'url',width:400,align:'center'">跳转链接</th>
                <th data-options="field:'munukey',width:120,align:'center'">点击事件名称</th>
                <th data-options="field:'lanwei',width:80,align:'center',hidden:true">lanwei</th>
                <th data-options="field:'lanwei2',width:100,align:'center'">所属栏位</th>
                <th data-options="field:'level',width:80,align:'center'">菜单级别</th>
                <th data-options="field:'username',width:150,align:'center'">编辑者</th>
                <th data-options="field:'newtime',width:200,align:'center'">创建时间</th>
                <th data-options="field:'updatetime',width:200,align:'center'">更新时间</th>
                
        </thead>
    </table>

        <div class="pageNext"></div>

        <!-- 编辑弹出框 -->
        <div id="dlg" class="easyui-dialog"
            style="width: 600px; height: 350px; padding: 10px 20px" closed="true"
            buttons="#dlg-buttons">
        <form id="fm" method="post" novalidate >
        <table cellpadding="5" align="center">
        <input type="hidden" id="id" name="id">
            <tr>
                <td>菜单名称:</td>
                <td><input class="textbox" type="text" id="name"
                    name="name" data-options="required:true"></input></td>
            </tr>
            <tr>
                <td>菜单类型:</td>
                <td><select class="easyui-combobox" name="type" id="type">
                    <option value="view">跳转URL</option>
                    <option value="click">点击推事件</option>
                </select></td>
            </tr>
            <tr>
                <td>菜单栏位:</td>
                <td><select class="easyui-combobox" name="lanwei" id="lanwei">
                    <option value='1'>第一栏</option>
                    <option value='2'>第二栏</option>
                    <option value='3'>第三栏</option>
                </select></td>
            </tr>
                <tr>
                    <td>菜单级别:</td>
                    <td><select class="easyui-combobox" name="level" id="level">
                        <option value='1'>一级菜单</option>
                        <option value='2'>二级菜单</option>
                    </select></td>
                </tr>
            <tr>
                <td>key:</td>
                <td><input class="textbox" name="munukey" id="munukey"
                    data-options="required:true" type="text"></input></td>
            </tr>
            <tr>
                <td>url:</td>
                <td><input class="textbox" name="url" id="url"
                    data-options="required:true" type="text"></input></td>
            </tr>
            <tr>
                <td>排序:</td>
                <td><input class="textbox" name="listorder" id="listorder"
                    data-options="required:true" type="text"></input>(排序值大的优先级大)</td>
            </tr>
        </table>
        </form>
        </div>
        <div id="dlg-buttons">
        <input type="submit"
            name="Submit" value="保存" class="easyui-linkbutton c6"
            iconCls="icon-ok" onclick="javascript:menuJS.saveUser();" style="width: 90px">
         <a href="javascript:void(0)"
            class="easyui-linkbutton" iconCls="icon-cancel"
            onclick="javascript:$('#dlg').dialog('close')" style="width: 90px">关闭</a>
        </div>
     <script type="text/javascript" src="/phpcms/modules/weixin/js/menu.js"></script>
    </body>
    </html>