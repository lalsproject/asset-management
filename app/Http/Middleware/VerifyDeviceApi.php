<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;

use App\Helpers;
use App\Model\M_Device;

class VerifyDeviceApi extends BaseVerifier {

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
			
			$request->attributes->add(['device' => $cek["device"]]);
			return $next($request);
		}
		else{
			return Helpers::responseFailed('Device mismatch ' , $cek, 401);
		}
	}

	protected function appTokenMatch($request){
		$device_token  = $request->input('device_token');
		$hardware_id  = $request->input('hardware_id');

		if (strlen($device_token) < 50) {
			return Array("status" => false, "pesan" => "ERR Device 1");
		}

		if (!empty($device_token) && !empty($hardware_id)){
			$device = M_Device::where('token', $device_token)
							->where('hardware_id', $hardware_id)
							->first();

			if (!empty($device)) {

				return Array("status" => true, "device" => $device);
			}

			return Array("status" => false, "pesan" => "ERR Device 2");
		}
		else{
			return Array("status" => false, "pesan" => "ERR Device 3");
		}	
	}
}
