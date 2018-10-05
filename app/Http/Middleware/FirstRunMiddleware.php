<?php

namespace app\Http\Middleware;

use App\Models\Organiser;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
class FirstRunMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    protected $except_urls = [
        'api_v2/*'
      
    ];

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    public function handle($request, Closure $next)
    {
        /*
         * If there are no organisers then redirect the user to create one
         * else - if there's only one organiser bring the user straight there.
         */
        
        if ($this->auth->check()) {
            //var_dump(Auth::user()->role);die();
            if(Auth::user()->role==1){
                if (!$request->is('*/attendees/*')){
                    return new RedirectResponse( route( 'TheExhibitorDashboard' ) );
                }
            }
        }

        if ($request->is('exhibitors/*')){
            $response = $next($request);
            return $response;
        }
        if (Organiser::scope()->count() === 0 && !($request->route()->getName() == 'showCreateOrganiser') && !($request->route()->getName() == 'postCreateOrganiser')) {
            return redirect(route('showCreateOrganiser', [
                'first_run' => '1',
            ]));
        } elseif (Organiser::scope()->count() === 1 && ($request->route()->getName() == 'showSelectOrganiser')) {
            return redirect(route('showOrganiserDashboard', [
                'organiser_id' => Organiser::scope()->first()->id,
            ]));
        }

        $response = $next($request);

        return $response;
    }
}
