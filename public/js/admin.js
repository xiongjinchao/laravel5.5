
//POST 退出登录
$(".logout-link").on('click',function(e){
    e.preventDefault();
    $("#logout-form").submit();
});

//SIDEBAR 当前菜单
$(".sidebar-menu a").each(function(i,item){
    if(($(item).attr('href')+'/').indexOf($(".sidebar-menu").attr('data-current-controller')) >= 0){
        $(item).parents('li').addClass('active');
    }
});

//列表中删除按钮
$(".content").on('click','.btn-delete',function(e){
    e.preventDefault();
    var that = $(this);
    var html =
        '<div class="modal fade" id="modal-delete">'
        +'<div class="modal-dialog">'
        +'<div class="modal-content">'
        +'<div class="modal-header bg-yellow">'
        +'<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
        +'<span aria-hidden="true">&times;</span></button>'
        +'<h4 class="modal-title">确认提示</h4>'
        +'</div>'
        +'<div class="modal-body">'
        +'<p>删除记录后数据无法恢复，确认删除该记录？</p>'
        +'</div>'
        +'<div class="modal-footer">'
        +'<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> 放弃</button>'
        +'<button type="button" class="btn btn-warning pull-right confirm-delete"><i class="fa fa-check"></i> 确认</button>'
        +'</div>'
        +'</div>'
        +'</div>'
        +'</div>';
    if($("#modal-delete").length  == 0) {
        $("body").append(html);
    }
    $('#modal-delete').modal();
    $('#modal-delete .confirm-delete').on('click',function(){
        that.closest('form').submit();
    });
});

//提示层间隔2S消失
setTimeout(function(){
    $(".container-fluid .callout-info,.container-fluid .callout-danger").fadeOut();
},2000);