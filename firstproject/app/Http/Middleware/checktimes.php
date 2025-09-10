<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;  
use App\Mail\SlowRequest;
use Illuminate\Support\Facades\Mail;

class checktimes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * 
     * 
     * 
     */


     protected $slowRequestThreshold = 9000; // ms
     protected $slowDbThreshold      = 9000; // ms

    public function handle(Request $request, Closure $next)
    {

        
        
        $start = microtime(true);

        //  Track slow DB queries
        DB::listen(function ($query) {
            if ($query->time > $this->slowDbThreshold) {
                Log::warning('Slow DB query detected', [
                    'sql'      => $query->sql,
                    'bindings' => $query->bindings,
                    'time_ms'  => $query->time,
                ]);
                
            }
        });

        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            Log::error('Exception occurred', [
                'type'    => get_class($e),
                'url'     => $request->fullUrl(),
                'method'  => $request->method(),
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            throw $e;
        }

        



        $duration = (microtime(true) - $start) * 1000; // ms
        $status   = $response->getStatusCode();
        

        if ($duration > $this->slowRequestThreshold) {
            Log::warning('Slow request detected', [
                'url'         => $request->fullUrl(),
                'method'      => $request->method(),
                'status'      => $status,
                'duration_ms' => (int) $duration,
            ]);

          //  Mail::to('lujain.darwazeh123@gmail.com')->send(new SlowRequest($request->fullUrl(), (int) $duration));






        }
        

        if ($status >= 400) {
            if ($status == 404) {
                Log::warning('Not Found detected', [
                    'url'    => $request->fullUrl(),
                    'method' => $request->method(),
                    'status' => $status,
                ]);
            } elseif ($status == 408) {
                Log::warning('Request Timeout detected', [
                    'url'    => $request->fullUrl(),
                    'method' => $request->method(),
                    'status' => $status,
                    'duration_ms' => (int) $duration,
                ]);
            } elseif ($status >= 500) {
                Log::error('Server Error detected', [
                    'url'    => $request->fullUrl(),
                    'method' => $request->method(),
                    'status' => $status,
                    'duration_ms' => (int) $duration,
                ]);
            } else {
                Log::warning('Client Error detected', [
                    'url'    => $request->fullUrl(),
                    'method' => $request->method(),
                    'status' => $status,
                ]);
            }
        }

        return $response;
    }
}
