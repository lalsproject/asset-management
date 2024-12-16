<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;

use App\Helpers;
use App\Model\M_Api_key;

class VerifyTokenKeyApi extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */

	public function handle($request, Closure $next){
		//bind middleware to process segment doc
		$cek = $this->appTokenMatch($request);

		if ($cek["status"]){
            
            // dd($cek['device']);
			//$request["middleware_device"] = $cek["device"];
			
			$request->attributes->add(['token' => $cek["device"]]);
			return $next($request);
		}
		else{
			return Helpers::Response($request, false, $cek, $cek['pesan'], 200);
			// return Helpers::responseFailed('Token mismatch ' , $cek, 401);
		}
	}

	protected function appTokenMatch($request){
		$token  = $request->header('token');
		

		if (strlen($token) < 50) {
			return Array("status" => false, "pesan" => "ERR Token 1");
		}

		if (!empty($token)){
			$device = M_Api_key::where('token', $token)
							->where('aktif', 1)
							->first();

			if (!empty($device)) {

				return Array("status" => true, "device" => $device);
			}

			return Array("status" => false, "pesan" => "ERR Token 2");
		}
		else{
			return Array("status" => false, "pesan" => "ERR Token 3");
		}	
	}
}
