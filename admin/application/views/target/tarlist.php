<?php $this->load->view('_header');?>
<style>
    .page-header {
        margin: 10px 0 20px 0;
        font-size: 22px;
        display: block;
        clear: both;
    }
    .col-sm-4 {
        width: 20%;
    }
    .col-md-3 {
        float: left;
        height: 15px;
        display: block;
        margin-bottom:20px;
    }
    .col-md-3:hover{cursor:pointer;}
    .row{clear:both}
    .bgb{margin-bottom:20px;}
</style>
<div class="tab-pane active" id="fa-icons">
    <section id="new">
        <div class="mvctool bgb">
            <a id="btnConfirm" style="float: left;" class="l-btn l-btn-plain">
                <span class="l-btn-left"><span class="l-btn-text fa fa-save" style="font-size:14px">
                </span><span style="font-size:12px">确定</span></span></a>
            <div class="datagrid-btn-separator"></div>
            <a id="btnReturn" style="float: left;" class="l-btn l-btn-plain">
                <span class="l-btn-left"><span class="l-btn-text fa fa-reply" style="font-size:14px">
                </span><span style="font-size:12px">返回</span></span></a>
        </div>
        <div class="row fontawesome-icon-list">
            <?php foreach ($tarlist as $v):?>
            <div class="col-md-3 col-sm-4"><input type="checkbox" name="tar" data-name="<?php echo $v['name']?>" value="<?php echo $v['id']?>" <?php echo in_array($v['id'],$tars)?'checked="checked"':''?> /> <?php echo $v['name']?></div>
            <?php endforeach;?>
        </div>
    </section>
</div>
<script>
    $("#btnConfirm").click(function(){
        var ids = [];
        var names = [];

        $('input[name="tar"]:checked').each(function(){
            ids.push($(this).val());
            names.push($(this).data("name"));
        });

        window.parent.selectTar(ids,names);
    });

    $("#btnReturn").click(function () {
        window.parent.closetarlist();
    });
</script>
<?php $this->load->view('_footer');?>