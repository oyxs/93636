<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');

?>
<body>


<div class="table-list">
    <table width="100%">
        <thead>
        <tr>
            <th width="40">更新方式</th>
            <th width="1000">更新内容</th>
            <th width="100">提示</th>
            <th width="72">管理操作</th>
        </tr>
        </thead>
        <tbody>
        <form action="/index.php?m=admin&c=jiuyou&a=cdn_api" method="post">
            <tr class="con">
                <td align='center' >
                    <select name="type">
                        <option style="width:200px" value="0">更新目录</option>
                        <option style="width:200px" value="1">更新文件</option>
                    </select>
                </td>
                <td align='center' ><textarea name="p_data" clos="1000"rows="5" style="width: 70%;"></textarea></td>
                <td align='center' >更新目录以“/”结尾 </td>
                <td align='center' ><input type="submit" name="sub" value="提交" /> </td>
            </tr>
        </form>
    </table>
    <table width="100%">

        <tr>
            <th>返回码</th>
            <th>状态</th>
        </tr>
        <tr>
            <td  align='center' width="40%">200</td>
            <td  align='center' width="40%">请求成功 接口请求成功</td>
        </tr>
        <tr>
            <td  align='center' width="40%">999</td>
            <td  align='center' width="40%">登陆错误</td>
        </tr>
        <tr>
            <td  align='center' width="40%">997</td>
            <td  align='center' width="40%">域名错误</td>
        </tr>
        <tr>
            <td  align='center' width="40%">996</td>
            <td  align='center' width="40%">参数为空</td>
        </tr>
        <tr>
            <td  align='center' width="40%">998</td>
            <td  align='center' width="40%">访问频繁</td>
        </tr>
        <tr>
            <td  align='center' width="40%">995</td>
            <td  align='center' width="40%">访问频繁</td>
        </tr>
        <tr>
            <td  align='center' width="40%">998</td>
            <td  align='center' width="40%">MD5错误</td>
        </tr>
        <tr>
            <td  align='center' width="40%">994</td>
            <td  align='center' width="40%">重复提交</td>
        </tr>
        <tr>
            <td  align='center' width="40%">993</td>
            <td  align='center' width="40%">文本为空</td>
        </tr>
        <tr>
            <td  align='center' width="40%">992</td>
            <td  align='center' width="40%">文件过大</td>
        </tr>
        <tr>
            <td  align='center' width="40%">991</td>
            <td  align='center' width="40%">表单异常</td>
        </tr>
    </table>
    </tbody>
</div>


</body>