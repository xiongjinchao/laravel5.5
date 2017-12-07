<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th style="width: 10px">#</th>
            <th>标题</th>
            <th>状态</th>
            <th>知识目录</th>
            <th>国家</th>
            <th>操作人</th>
            <th>操作时间</th>
            <th>浏览</th>
            <th>收藏</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @if($knowledge->count() > 0)
            @foreach($knowledge as $key=>$item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item->title}}</td>
                    <td>{{$item->status>0?$item->getStatusOptions($item->status):''}}</td>
                    <td>{{\App\Models\KnowledgeCategory::getKnowledgeCategoryPath($item->category_id)}}</td>
                    <td>{{$item->country_id>0?$item->getCountryOptions($item->country_id):''}}</td>
                    <td>{{$item->hasOneUser!=null?$item->hasOneUser->name:''}}</td>
                    <td>{{$item->updated_at}}</td>
                    <td>{{$item->hit}}</td>
                    <td>{{$item->enshrine}}</td>
                    <td class="operation">
                        <a class="btn btn-sm btn-primary" href="{{ route('knowledge.copy',[$item->id]) }}" title="复制"><i class="fa fa-copy"></i> 复制</a>
                        <a class="btn btn-sm btn-info" href="{{ route('knowledge.edit',[$item->id]) }}" title="更新"><i class="fa fa-edit"></i> 更新</a>
                        <form action="{{ route('knowledge.destroy',[$item->id]) }}" method="POST" style="display: inline">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <a class="btn btn-sm btn-danger btn-delete" href="{{ route('knowledge.destroy',[$item->id]) }}" title="删除"><i class="fa fa-trash"></i> 删除</a>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="clearfix">
    {{$knowledge->links()}}
</div>