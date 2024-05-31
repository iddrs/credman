<?php

namespace App\Http\Middleware;

use App\Models\Decreto;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecretoIsClosed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $decreto = Decreto::find($request->decreto_id);
        if(!$decreto){
            $decreto = Decreto::find($request->id);
        }

        if($decreto->fechado){
            return redirect()->route('decreto.show', ['id' => $decreto->id])->withErrors(['errors' => ['Este decreto está fechado.']]);
        }
        return $next($request);
    }
}
