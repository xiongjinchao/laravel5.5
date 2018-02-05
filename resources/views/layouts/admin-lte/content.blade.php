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
        @if(request()->hasSession())
            @if (request()->session()->has('success') || request()->session()->has('error'))
                <div class="main-callout callout callout-{{request()->session()->has('error')?'danger':'info'}}">
                    <h4><i class="fa fa-lightbulb-o"></i> 操作提醒</h4>
                    {{request()->session()->has('error') ? request()->session()->get('error'):request()->session()->get('success')}}
                </div>
            @endif
        @endif

        @if (isset($errors) && $errors->any())
            <div class="callout callout-danger">
                <h4><i class="fa fa-lightbulb-o"></i> 操作提醒</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!--------------------------
          | Your Page Content Here |
          -------------------------->
        @yield('content')

    </section>
    <!-- /.content -->
</div>