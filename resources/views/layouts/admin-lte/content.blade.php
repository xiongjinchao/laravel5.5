<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{$page['title'] or '系统面板'}}
            <small>{{$page['subTitle'] or ''}}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-home"></i> 首页</a></li>
            @if(!empty($page['breadcrumb']))
                @foreach(array_merge($page['breadcrumb'],!empty($breadcrumb)?$breadcrumb:[]) as $key => $item)
                    @if($item['label'] !='')
                        <li {{$key == count($page['breadcrumb'])-1 ? 'class="active"':''}}><a href="{{$item['url'] or '#'}}">{{$item['label']}}</a></li>
                    @endif
                @endforeach
            @endif
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid" style="min-height:820px;">

        @if (request()->session()->has('success') || request()->session()->has('error'))
            <div class="callout callout-{{request()->session()->has('success')?'info':'danger'}}">
                <h4>操作提醒</h4>
                {{request()->session()->has('success') ? request()->session()->get('success'):request()->session()->get('error')}}
            </div>
        @endif
        <!--------------------------
          | Your Page Content Here |
          -------------------------->
        @yield('content')

    </section>
    <!-- /.content -->
</div>