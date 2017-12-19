<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>知识标题</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-header"></i>
                </div>
                <input class="form-control" name="title" placeholder="请输入知识标题" value="{{$faq->title}}">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>所属目录</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-sitemap"></i>
                </div>
                <select class="form-control select2" name="category_id" style="width: 100%;">
                    <option value="0">请选择</option>
                    @foreach($knowledgeCategories as $key=>$item)
                        <option value="{{$key}}">{{$item}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>所属国家</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-globe"></i>
                </div>
                <select class="form-control select2" name="country_id" style="width: 100%;">
                    <option value="0">请选择</option>
                    @foreach($countries as $item)
                        <option {{$item['audit'] == 1?'disabled="disabled"':''}} value="{{$item['id']}}">{{$item['country'].' '.$item['country_name_en'].''}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>内容</label>
            <textarea id="knowledge-content" name="content" rows="3" placeholder="请输入知识内容" style="height:300px;">{{$faq->answer}}</textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>
                上传附件
                <input type="hidden" name="upload_id">
            </label>
            <div class="file-loading">
                <input id="input-file" name="upfile" type="file" multiple>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>知识标签</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-tags"></i>
                </div>
                <input class="form-control" name="tags" placeholder="请输入标签，多个标签使用英文,分隔">
            </div>
            <span class="help-block">多个标签使用英文,分隔</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>知识可见</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-sitemap"></i>
                </div>
                <select class="form-control select2" name="organization_id" style="width: 100%;">
                    @foreach($organizations as $key=>$item)
                        <option value="{{$key}}">{{$item}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>