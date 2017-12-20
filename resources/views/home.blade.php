@extends('layouts.admin-lte')

@section('content')
    <div class="home-index">
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>{{App\Models\User::all()->count()}}</h3>
                        <p>用户数量</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="{{ route('user.index') }}" class="small-box-footer">
                        所有用户 <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{App\Models\Knowledge::all()->count()}}</h3>
                        <p>知识数量</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <a href="{{ route('knowledge.index') }}" class="small-box-footer">
                        所有知识 <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{App\Models\Notice::all()->count()}}</h3>
                        <p>公告数量</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-send"></i>
                    </div>
                    <a href="{{ route('notice.index') }}" class="small-box-footer">
                        所有公告 <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{App\Models\FAQ::all()->count()}}</h3>
                        <p>FAQ数量</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-weixin"></i>
                    </div>
                    <a href="{{ route('faq.index') }}" class="small-box-footer">
                        所有FAQ <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">用户分布</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                            <canvas id="chart-area" style="height: 150px;"/></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">知识分布</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="chart-bar" style="height: 150px; width:100%"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">未分配的用户</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        @if(!empty($outOrganizationUser))
                            <table class="table table-striped table-bordered table-hover">
                                @foreach($outOrganizationUser as $item)
                                    <tr>
                                        <td><a href="{{ route('user.edit',['id' => $item->id]) }}">{{$item->name}}</a></td>
                                        <td class="text-right">{{$item->created_at}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">最新公告</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        @if($lastNotice->count() > 0)
                            <table class="table table-striped table-bordered table-hover">
                                @foreach($lastNotice as $item)
                                    <tr>
                                        <td><a href="{{ route('notice.edit',['id' => $item->id]) }}">{{$item->title}}</a></td>
                                        <td class="text-right">{{$item->hasOneAuthor!=null?$item->hasOneAuthor->name:''}}</td>
                                        <td class="text-right">{{$item->created_at}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">未回复的问题</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        @if($waitReplyFAQ->count() > 0)
                            <table class="table table-striped table-bordered table-hover">
                                @foreach($waitReplyFAQ as $item)
                                    <tr>
                                        <td><a href="{{ route('faq.edit',['id' => $item->id]) }}">{{$item->title}}</a></td>
                                        <td class="text-right">{{$item->hasOneASK!=null?$item->hasOneASK->name:''}}</td>
                                        <td class="text-right">{{date("Y-m-d H:i:s",$item->asked_at)}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset("AdminLTE/bower_components/chart.js/Chart.min.js")}}"></script>
    <script>
        $(function () {
            var pieData = JSON.parse('{!!json_encode($organizationDistribution,JSON_UNESCAPED_UNICODE)!!}');
            var areaChartCanvas = $("#chart-area").get(0).getContext("2d");
            new Chart(areaChartCanvas).Pie(pieData);


            var barData = {
                labels: JSON.parse('{!!json_encode($knowledgeDistribution['months'])!!}'),
                datasets: [
                    {
                        label: "知识数量",
                        fillColor: "#337ab7",
                        strokeColor: "#337ab7",
                        pointColor: "#337ab7",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: JSON.parse('{!!json_encode($knowledgeDistribution['value'])!!}')
                    }
                ]
            };

            var barChartCanvas = $("#chart-bar").get(0).getContext("2d");
            new Chart(barChartCanvas).Bar(barData);
        })
    </script>
@endsection
