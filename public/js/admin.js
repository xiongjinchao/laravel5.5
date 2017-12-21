//AJAX 填充头部的铃铛提醒数据
$.get('/home/notice',{},function(result){
    if(result.status == 'SUCCESS'){
        if(result.data.outOrganizationUser.count >0)
        {
            $("#notice-out-organization-user span.label").text(result.data.outOrganizationUser.count);
            $("#notice-out-organization-user li.header").text('有 '+result.data.outOrganizationUser.count+' 位用户没有分配到组织架构中');
            $("#notice-out-organization-user li.footer").html('<a href="'+result.data.outOrganizationUser.link+'">查看所有用户</a>');
            $("#notice-out-organization-user .menu").html('');
            $.each(result.data.outOrganizationUser.list.data,function(i,item){
                $("#notice-out-organization-user .menu").append('<li><a href="/user/'+item.id+'/edit"><span class="pull-right">'+item.created_at+'</span><i class="fa fa-user text-primary"></i> '+item.name+'</a></li>')
            })
        }else{
            $("#notice-out-organization-user").remove();
        }

        if(result.data.waitAuditKnowledge.count >0)
        {
            $("#notice-wait-audit-knowledge span.label").text(result.data.waitAuditKnowledge.count);
            $("#notice-wait-audit-knowledge li.header").text('有 '+result.data.waitAuditKnowledge.count+' 篇知识等待审核');
            $("#notice-wait-audit-knowledge li.footer").html('<a href="'+result.data.waitAuditKnowledge.link+'">查看所有知识</a>');
            $("#notice-wait-audit-knowledge .menu").html('');
            $.each(result.data.waitAuditKnowledge.list,function(i,item){
                $("#notice-wait-audit-knowledge .menu").append('<li><a href="/knowledge/'+item.id+'/edit"><i class="fa fa-file-text text-primary"></i> '+item.title+'</a></li>')
            })
        }else{
            $("#notice-wait-audit-knowledge").remove();
        }

        if(result.data.waitReplyFAQ.count >0)
        {
            $("#notice-wait-reply-faq span.label").text(result.data.waitReplyFAQ.count);
            $("#notice-wait-reply-faq li.header").text('有 '+result.data.waitReplyFAQ.count+' 篇FAQ尚未回复');
            $("#notice-wait-reply-faq li.footer").html('<a href="'+result.data.waitReplyFAQ.link+'">查看未回复的FAQ</a>');
            $("#notice-wait-reply-faq .menu").html('');
            $.each(result.data.waitReplyFAQ.list,function(i,item){
                $("#notice-wait-reply-faq .menu").append('<li><a href="/faq/'+item.id+'/edit"><i class="fa fa-question-circle text-primary"></i> '+item.title+'</a></li>')
            })
        }else{
            $("#notice-wait-reply-faq").remove();
        }
    }
});
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
    var action = $(this).attr('href');
    var that = $(this);
    var html =
        '<div class="modal fade" id="modal-delete">'
        +'<div class="modal-dialog">'
        +'<div class="modal-content">'
        +'<div class="modal-header bg-yellow">'
        +'<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
        +'<span aria-hidden="true">&times;</span></button>'
        +'<h4 class="modal-title"><i class="fa fa-lightbulb-o"></i> 确认提示</h4>'
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
        that.closest('form').attr('action',action).submit();
    });
});

//提示层间隔2S消失
setTimeout(function(){
    $(".container-fluid .main-callout").fadeOut();
},2000);