<?php

namespace App\Http\Controllers;

use App\Models\Knowledge;
use App\Models\Notice;
use App\Models\FAQ;
use Illuminate\Http\Request;
use \App\Models\User;
use Illuminate\Http\Response;
use DB,View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        View::share('page',[
            'title' => '系统面板',
            'subTitle' => '',
            'breadcrumb' => [
                ['url' => '#','label' => '系统面板' ]
            ]
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'outOrganizationUser' => User::getList(['status_out_organization' => User::STATUS_OUT_ORGANIZATION],5),
            'organizationDistribution' => User::getDistribution(),
            'knowledgeDistribution' => Knowledge::getDistribution(),
            'lastNotice' => Notice::orderBy('id','DESC')->get()->take(5),
            'waitReplyFAQ' => FAQ::where('status','=',FAQ::STATUS_NOT_REPLY)->orderBy('id','DESC')->get()->take(5),
        ];
        return view('home', $data);
    }

    //AJAX 获取页面头部的消息
    public function notice()
    {
        return \response()->json([
            'status'=>'SUCCESS',
            'data' => [
                'outOrganizationUser' => [
                    'count' => User::getList(['status_out_organization' => User::STATUS_OUT_ORGANIZATION],1000)->count(),
                    'list' => User::getList(['status_out_organization' => User::STATUS_OUT_ORGANIZATION],1000),
                    'link' => route('user.index'),
                ],
                'waitAuditKnowledge' => [
                    'count' => Knowledge::where('status','=',Knowledge::STATUS_WAIT_AUDIT)->orderBy('id','DESC')->count(),
                    'list' => Knowledge::where('status','=',Knowledge::STATUS_WAIT_AUDIT)->orderBy('id','DESC')->get()->take(5),
                    'link' => route('knowledge.index'),
                ],
                'waitReplyFAQ' => [
                    'count' => FAQ::where('status','=',FAQ::STATUS_NOT_REPLY)->orderBy('id','DESC')->count(),
                    'list' => FAQ::where('status','=',FAQ::STATUS_NOT_REPLY)->orderBy('id','DESC')->get()->take(5),
                    'link' => route('faq.index').'?status='.FAQ::STATUS_NOT_REPLY,
                ],
            ]
        ]);
    }
}
