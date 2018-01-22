
var menuJS = {
	editUser : function(type) {
		if (type == 'add') {
			$('#dlg').dialog('open').dialog('setTitle', '添加菜单');
			$('#fm').form('clear');
		}
		if (type == 'edit') {
			// 获取单击行的数据-内容
			var row = $('#ss').datagrid('getSelected');
			if (row) {
				$('#dlg').dialog('open').dialog('setTitle', '修改菜单');
				$('#fm').form('load', row);//
				$('#type').combobox('setValue', row.type);// 插入下拉框的值
			} else {
				alert('请选择要编辑的行');
			}
		}
	},
	removemenu:function() {
		//获取单击行的数据-内容
		var row = $('#ss').datagrid('getSelected');
		if(row){
			 var id=row.id;
			 var checkFlag = window.confirm("是否要删除该记录？");
			 if(checkFlag==true){
				 var nNode = document.getElementById("lightbox1");
				 nNode.style.display = "";
			$.post("/index.php?m=weixin&c=menu&a=remove_menu", {
				"ids" : id
			}, function(result) {
				if(result=='remove ok'){
					alert("系统提示：删除成功！");
					 nNode.style.display='none';
					$('#ss').datagrid('reload',{});
				}else {
					alert("系统提示：删除失败！");
					 nNode.style.display='none';
					$('#ss').datagrid('reload',{});
				}
			});
	 }
		}else {
			alert('请选择要删除的行');
		}
		
	},

	editMenu : function() {
		// 获取单击行的数据-内容
		var id = $('#id').val();
		var name = $('#name').val();
		var menukey = $('#munukey').val();
		var menuurl = $('#url').val();
		var listorder = $('#listorder').val();
		var menutype = $('#type').combobox('getValue');
		var menulevel = $('#mstatus').combobox('getValue');

	},
	/**
	 * 菜单新增
	 */
	saveUser : function() {
		$('#fm').form('submit', {
			url : "/index.php?m=weixin&c=menu&a=save_menu",
			onSubmit : function() {
				return $(this).form('validate');
			},
			success : function(result) {
				if(result == '1'){
					 alert("系统提示：菜单名称不能为空！");
				}
				if(result == '2'){
					 alert("系统提示：菜单类型不能为空！");
				}
				if(result == '3'){
					 alert("系统提示：Key不能为空！");
				}
				if(result == '4'){
					 alert("系统提示：URL不能为空！");
				}
				if (result == '5') {
					alert("系统提示：菜单栏位不能为空！");
				}
				if (result == '6') {
					alert("系统提示：菜单级别不能为空！");
				}
				if (result == 'OK') {
					 alert("系统提示：操作成功！");$('#dlg').window('close');
					  $('#ss').datagrid('reload',{});
				}

			}
		});
	},
	/**
	 * 刷新菜单
	 */
	reloadmenu_fw : function(){
		var checkFlag = window.confirm("确定要刷新该菜单吗？");
		if(checkFlag==true){
			var nNode = document.getElementById("lightbox1");
			nNode.style.display = "";
			$.post("/index.php?m=weixin&c=wx&fun=reload_menu", {
				
			}, function(data) {
				if(data != '失败'){
					alert("系统提示：刷新成功！");
					nNode.style.display='none';
					$('#ss').datagrid('reload',{});
				}else{
					alert("系统提示：刷新失败！");
					nNode.style.display='none';
					
				}
					
						
			});
		}
	}
}
