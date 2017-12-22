<?php namespace App\Http\Middleware;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Request,Response;

class EntrustPermission extends \Zizaco\Entrust\Middleware\EntrustPermission
{
    const DELIMITER = '|';

    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @param  $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if (!is_array($permissions)) {
            $permissions = explode(self::DELIMITER, $permissions);
        }
        if(!empty($permissions)) {
            foreach ($permissions as $key => $item) {
                $controller = explode('@', $item)[0];
                $action = explode('@', $item)[1];
                $permissions[$key] = $controller . '@' . str_replace(['store', 'edit'], ['create', 'update'], $action);
            }
        }

        if ($request->user()->id>1 && ($this->auth->guest() || !$request->user()->can($permissions))) {
            if(Request::ajax()){
                return Response::json(['status' => 'error','message'=>'（#403）抱歉，您没有权限访问']);
            }else{
                return abort(403, '（#403）抱歉，您没有权限访问');
            }
        }

        return $next($request);
    }
}
