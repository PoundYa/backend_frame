<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">增加</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
            </td>
            <td style="white-space:nowrap;">
                <input id="key" class="mini-textbox" emptyText="请输入开发者名称" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/developer/manage", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true"  pageSize="20"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>            
            <div field="name" width="120" headerAlign="center" allowSort="true">姓名</div>
            <div field="email" width="120" headerAlign="center" allowSort="true">电子邮件</div>            
            <div field="phone" width="120" headerAlign="center" allowSort="true">电话</div>
            <div field="qq" width="120" headerAlign="center" allowSort="true">QQ</div>
            <div field="job" width="120" headerAlign="center" renderer="onJobRender" allowSort="true">类型</div>
            <div field="add" width="120" headerAlign="center" allowSort="true">注册时间</div>
            <div field="last_login" width="120" headerAlign="center" allowSort="true">最后登录</div>
            <div field="id" width="60" headerAlign="center" renderer="onManageRender">登录管理</div>
            <div field="r_manager" width="120" headerAlign="center" allowSort="true">客服</div>
            <div field="actived" width="80" headerAlign="center" renderer="onStatusRender" allowSort="true">状态</div>            
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("add", "desc");
    function add() {

        mini.open({
            url: "<?php echo URL::site("bms/developer/manage/edit", TRUE); ?>",
            title: "录入开发者", width: 600, height: 480,
            onload: function() {
                var iframe = this.getIFrameEl();
                var data = {action: "new"};
                iframe.contentWindow.SetData(data);
            },
            ondestroy: function(action) {
                grid.reload();
            }
        });
    }
    function edit() {

        var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "<?php echo URL::site("bms/developer/manage/edit", TRUE); ?>",
                title: "编辑开发者", width: 600, height: 480,
                onload: function() {
                    var iframe = this.getIFrameEl();
                    var data = {action: "edit", id: row.id};
                    iframe.contentWindow.SetData(data);
                },
                ondestroy: function(action) {
                    grid.reload();
                }
            });
        } else {
            alert("请选中一条记录");
        }

    }
    function remove() {

        var rows = grid.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定删除选中记录？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                    url: "<?php echo URL::site("bms/developer/manage", TRUE) . "?method=delete&id="; ?>" + id,
                    success: function(text) {
                        grid.reload();
                    },
                    error: function() {
                    }
                });
            }
        } else {
            alert("请选中一条记录");
        }
    }
    function search() {
        var key = mini.get("key").getValue();
        grid.load({key: key});
    }
    function onKeyEnter(e) {
        search();
    }
    /////////////////////////////////////////////////    
    var Adtype = [{id: "proxy", text: '代理客户'}, {id: "direct", text: '直接客户'}];
    function onCopetypeRender(e) {
        for (var i = 0, l = Adtype.length; i < l; i++) {
            var g = Adtype[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var Status = [{id: "pendding", text: '未验证'}, {id: "actived", text: '已验证'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var jobs = [{id: "company", text: '公司'}, {id: "personal", text: '个人开发者'}];
    function onJobRender(e) {
        for (var i = 0, l = jobs.length; i < l; i++) {
            var g = jobs[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    function onManageRender(e) {
        var url = "<?php echo URL::site("bms/developer/manage", TRUE); ?>?method=login&id="+e.value;
        return "<a target=\"_blank\" href=\"" + url + "\">登录管理</a>";
    }
</script>