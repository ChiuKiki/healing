<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = $request->path();

        if (!config('app.debug') && $this->startWith($path, 'auth/fake')) return response('', 403);
        if ($this->startWith($path, 'auth/') || $this->startWith($path, 'test/'))
            return $next($request);

        if (!$request->session()->has('user')) {
            if ($this->startWith($path, 'api/')) {
                return response(array('error' => 'Unauthorized'), 401);
            } else {
                $request->merge(array('redirect' => $request->url()));
                return redirect('auth/jump');
            }
        }

        $user = $request->session()->get('user');
        if (!$user && $this->startwith($path, 'api/')) 
            return response(array('error' => 'Not registered'), 403);
        return $next($request);
    }

    private function startWith($input, $target) {
        $len = strlen($target);
        return (substr($input, 0, $len) === $target);
    }

}
