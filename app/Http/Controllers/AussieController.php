<?php

namespace App\Http\Controllers;

use DB,View;

class AussieController extends Controller
{

    public $cookie;
    public $user_agent;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cookie = public_path().'/cookie_aussie.txt';
        $this->user_agent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0';
    }

    //注册账号
    public function getRegistration()
    {
        $this->client = new Client([
            'base_uri' => $this->domain,
            'cookies' => true,
            'allow_redirects' => true,
            'timeout'  => 60,
        ]);

        //访问首页，记录cookie
        $response = $this->client->request('GET', $this->domain, [
            'cookies' => $this->cookies,
            'headers' => [
                'User-Agent' => $this->user_agent
            ],
        ]);

        //访问注册页
        $response = $this->client->request('GET', $this->domain.'/lusc/register', [
            'cookies' => $this->cookies,
            'headers' => [
                'User-Agent' => $this->user_agent,
                'Referer' => $this->domain.'/lusc/login',
            ],
        ]);
        $body = $response->getBody(true);
        $html = HtmlDomParser::str_get_html($body);
        $form = [];
        foreach($html->find('input,submit') as $item){
            $form[$item->name] = $item->value;
        }
        print_r($form);

        /*
        _2b0e1a0a0c0a1b	I
        _2b0e1a0a0c0a1b-h	{…}
        0	x
        1	x
        _2b0e1a0a0d0a1a-h	x
        _2b0e1a0a0e0a1b	7
        _2b0e1a0a0e0a1b-h	x
        _2b0e1a0a0g1a1b0b
        _2b0e1a0a0g1a1b1b0	XIONG
        _2b0e1a0a0g1a1b2b0	XIPING
        _2b0e1a0a0g1a1b3b	15911006055
        _2b0e1a0a0g1a1b4b
        _2b0e1a0a0g1a1d0b0	mteddy@126.com
        _2b0e1a0a0g1a1d1b	mteddy@126.com
        _2b0e1a0a0g1a2b0a1
        _2b0e1a0a0g1a2b0b1
        _2b0e1a0a0g1a2b0c1
        _2b0e1a0a0g1a2b0d1	A
        _2b0e1a0a0g1a2b0e1
        _2b0e1a0a0g1a2b0f1
        _2b0e1a0a0g1a2b0g1
        _2b0e1a0a0h1a0b0b
        _2b0e1a0a0h1a0b1b0a0
        _2b0e1a0a0h1a0b3b0
        _2b0e1a0a0h1a1b0b	true
        _2b0e1a0a0h1a1c0a0b
        _2b0e1a0a0h1a1c0a1b
        _2b0e1a0a0h1a1c0a2b
        _2b0e1a0a0h1a1c0a3b	A
        _2b0e1a0a0h1a1c0a4b
        _2b0e1a0a0h1a1c0a5b
        _2b0e1a0a0h1a1c0a6b
        _2b0e1b0b0b	x
        cprofile_timings	interface_controls{time:4,result:1};html_start_load{time:14081,result:1};unload_load{time:14079,result:1};
        wc_s	1
        wc_t	f5c809f5-8ea1-47aa-93f0-6afcee1dc275
        */
    }

    public function index()
    {
        set_time_limit(0);
        $this->logout();
        $this->login();
        $this->continuation();
        //$this->account();
        //$this->touristVisa();
    }

    public function logout()
    {
        include_once(app_path().'/Tools/simple_html_dom.php');

        $url = 'https://online.immi.gov.au/lusc/logout';
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_COOKIEFILE => $this->cookie,
            CURLOPT_COOKIEJAR => $this->cookie
        ]);
        $content = curl_exec($ch);
        curl_close($ch);

        $html = str_get_html($content);
        $result = $html->find('h1',0)->innertext.' '.$html->find('section h1',0)->innertext.'.';
        echo $result;
    }

    public function login()
    {
        include_once(app_path().'/Tools/simple_html_dom.php');

        // 获取登录页的COOKIE和TOKEN
        $url = 'https://online.immi.gov.au/lusc/login';
        $header = [
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Content-Type:application/x-www-form-urlencoded',
            'Referer:https://online.immi.gov.au/lusc/login'
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_COOKIEJAR => $this->cookie
        ]);
        $content = curl_exec($ch);
        curl_close($ch);

        $html = str_get_html($content);

        $params = [
            'wc_t' => $html->find('input[name=wc_t]',0)->value,
            'wc_s' => $html->find('input[name=wc_s]',0)->value,
            '_2b0a0a3a1a' => '67218027@qq.com',//用户名
            '_2b0a0a3b1a' => 'Xjc123456',//密码
            '_2b0a0a4a1a0' => 'x',//登录按钮
            'cprofile_timings' => 'interface_controls{time:0,result:1};html_start_load{time:32,result:1};unload_load{time:35,result:1};'
        ];
        $url = 'https://online.immi.gov.au/lusc/'.$html->find('form',0)->getAttribute('data-wc-ajaxurl');


        // 提交登录表单
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 1,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_COOKIEFILE => $this->cookie,
            CURLOPT_COOKIEJAR => $this->cookie
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    public function continuation()
    {
        include_once(app_path().'/Tools/simple_html_dom.php');

        $url = 'https://online.immi.gov.au/lusc/login';
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 1,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_COOKIEFILE => $this->cookie,
            CURLOPT_COOKIEJAR => $this->cookie
        ]);
        $content = curl_exec($ch);
        curl_close($ch);
        $html = str_get_html($content);

        $params = [
            'wc_t' => $html->find('input[name=wc_t]',0)->value,
            'wc_s' => $html->find('input[name=wc_s]',0)->value,
            '_2b0a0b0e0b0a' => 'x',//继续按钮
            'cprofile_timings' => 'interface_controls{time:4,result:1};html_start_load{time:2437,result:1};unload_load{time:2432,result:1};'
        ];
        $header = [
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Content-Type:application/x-www-form-urlencoded',
            'Referer:https://online.immi.gov.au/lusc/login',
            'Connection:keep-alive',
            'Upgrade-Insecure-Requests:1',
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 1,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_COOKIEFILE => $this->cookie,
            CURLOPT_COOKIEJAR => $this->cookie
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    public function account()
    {
        $url = 'https://online.immi.gov.au/usm/services';
        $ch = curl_init();
        $header = [
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Content-Type:application/x-www-form-urlencoded',
            'Host:online.immi.gov.au'
        ];
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 1,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_COOKIEFILE => $this->cookie,
            CURLOPT_COOKIEJAR => $this->cookie
        ]);
        $content = curl_exec($ch);
        curl_close($ch);
        print_r($content);
    }

    public function touristVisa()
    {
        include_once(app_path().'/Tools/simple_html_dom.php');
        //第1步
        $url = 'https://online.immi.gov.au/elp/app?action=new&formId=VSS-AP-600';
        $header = [
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Content-Type:application/x-www-form-urlencoded',
            'Host:online.immi.gov.au'
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 1,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_COOKIEFILE => $this->cookie,
            CURLOPT_COOKIEJAR => $this->cookie
        ]);
        $content = curl_exec($ch);
        curl_close($ch);

        //第1步提交表单
        $html = str_get_html($content);

        $params = [
            'wc_t' => $html->find('input[name=wc_t]',0)->value,
            'wc_s' => $html->find('input[name=wc_s]',0)->value,
            $html->find('input[type=checkbox]',0)->name => 'true',//同意
            $html->find('button.wc-button[accesskey=N]',0)->name => 'x',//NEXT按钮
            'cprofile_timings' => 'interface_controls{time:5,result:1};html_start_load{time:5335,result:1};unload_load{time:5332,result:1};submit_load{time:9430,result:1};last_click_load_Visitor+Visa+(600){time:9439,result:1};'
        ];
        $header[] = 'Referer:'.$url;
        $url = 'https://online.immi.gov.au/elp/app';

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 1,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_COOKIEFILE => $this->cookie,
            CURLOPT_COOKIEJAR => $this->cookie
        ]);
        $content = curl_exec($ch);
        curl_close($ch);

        //第2步提交表单
        $html = str_get_html($content);
        $params = [
            'wc_t' => $html->find('input[name=wc_t]',0)->value,
            'wc_s' => $html->find('input[name=wc_s]',0)->value,
            $html->find('input[type=checkbox]',0)->name => 'true',//同意
            $html->find('button.wc-button[accesskey=N]',0)->name => 'x',//NEXT按钮
            'cprofile_timings' => 'interface_controls{time:5,result:1};html_start_load{time:5335,result:1};unload_load{time:5332,result:1};submit_load{time:9430,result:1};last_click_load_Visitor+Visa+(600){time:9439,result:1};'
        ];
        $header[] = 'Referer:'.$url;

        //俄罗斯

        exit;





        //header("Content-type:text/html;charset=utf-8");

        $url = $this->domain.'/';
        $header = [
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            //'Cookie:AspxAutoDetectCookieSupport=1;locale=zh-cn',
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_COOKIE => 'AspxAutoDetectCookieSupport=1; locale=zh-cn',
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_COOKIEJAR => $this->cookies,
            //CURLOPT_COOKIESESSION => true
        ]);
        $content = curl_exec($ch);
        curl_close($ch);
        $html = HtmlDomParser::str_get_html($content);

        //选择国家时，POST 提交后，__VIEWSTATE字段值会改变，这里重新获取
        $form = [
            '__EVENTARGUMENT' => $html->find('input[name=__EVENTARGUMENT]',0)->value,
            '__EVENTTARGET' => 'ctl00$phBody$Country',
            '__LASTFOCUS' => $html->find('input[name=__LASTFOCUS]',0)->value,
            '__VIEWSTATE' => $html->find('input[name=__VIEWSTATE]',0)->value,
            '__VIEWSTATEGENERATOR' => $html->find('input[name=__VIEWSTATEGENERATOR]',0)->value,
            'ctl00_MasterScriptManager_HiddenField' => 	$html->find('input[name=ctl00_MasterScriptManager_HiddenField]',0)->value,
            'ctl00$phBody$cddCountry_ClientState' => 'CHN:::CHINA:::',
            'ctl00$phBody$cddLanguage_ClientState' => '::::::',
            'ctl00$phBody$Country' => 'CHN',
            'ctl00$phBody$ddlLanguage' => '',
            'hiddenInputToUpdateATBuffer_CommonToolkitScripts'=>1,
        ];
        $form['ctl00_MasterScriptManager_HiddenField'] = str_replace(' ','+',urldecode(explode('_TSM_CombinedScripts_=', $html->find('script',6)->src)[1]));
        $header = [
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Referer:'.$this->domain.'/',
        ];
        $url = $this->domain.'/PetitionChoice.aspx';

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($form),
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_COOKIEFILE => $this->cookies,
            CURLOPT_COOKIEJAR => $this->cookies
        ]);
        $content = curl_exec($ch);
        curl_close($ch);
        $html = HtmlDomParser::str_get_html($content);

        $form = [
            '__EVENTARGUMENT' => $html->find('input[name=__EVENTARGUMENT]',0)->value,
            '__EVENTTARGET' => $html->find('input[name=__EVENTTARGET]',0)->value,
            '__LASTFOCUS' => $html->find('input[name=__LASTFOCUS]',0)->value,
            '__VIEWSTATE' => $html->find('input[name=__VIEWSTATE]',0)->value,
            '__VIEWSTATEGENERATOR' => $html->find('input[name=__VIEWSTATEGENERATOR]',0)->value,
            'ctl00_MasterScriptManager_HiddenField' => 	$html->find('input[name=ctl00_MasterScriptManager_HiddenField]',0)->value,
            'ctl00$phBody$btnNewApplicatio' => '填新的电子板签证申请表',
            'ctl00$phBody$cbConfirm' => 'on',
            'ctl00$phBody$cddCountry_ClientState' => 'CHN:::CHINA:::',
            'ctl00$phBody$cddLanguage_ClientState' => 'zh-cn:::中文+(CHINESE):::',
            'ctl00$phBody$Country' => 'CHN',
            'ctl00$phBody$ddlLanguage' => 'zh-cn',
            'hiddenInputToUpdateATBuffer_CommonToolkitScripts'=>1,
        ];
        $form['ctl00_MasterScriptManager_HiddenField'] = urldecode(explode('_TSM_CombinedScripts_=', $html->find('script',6)->src)[1]);

        //提交表单
        $header = [
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Referer:'.$this->domain.'/PetitionChoice.aspx',
        ];
        $url = $this->domain.'/PetitionChoice.aspx';
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($form),
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_COOKIEFILE => $this->cookies,
            CURLOPT_COOKIEJAR => $this->cookies
        ]);
        $content = curl_exec($ch);
        print_r(curl_getinfo($ch));
        curl_close($ch);
        print_r($content);
        exit;

        $url = $this->domain.'/LoginPage.aspx';
        $header = [
            'Referer:'.$this->domain.'/PetitionChoice.aspx?AspxAutoDetectCookieSupport=1',
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_COOKIEJAR => $this->cookies
        ]);
        $content = curl_exec($ch);
        print_r(curl_getinfo($ch));
        curl_close($ch);
        print_r($content);
    }
}
