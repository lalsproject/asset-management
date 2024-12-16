<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;

use App\Helpers;
use App\User;

class VerifyTokenUserApi extends BaseVerifier {

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

			//https://stackoverflow.com/questions/30212390/laravel-middleware-return-variable-to-controller			
			$request->attributes->add(['user' => $cek["user"]]);
			return $next($request);
		}
		else{
			return Helpers::responseFailed('Device mismatch ' , $cek, 401);
		}
	}

	protected function appTokenMatch($request){
		$token  = $request->input('token');

		if (strlen($token) < 50) {
			return Array("status" => false, "pesan" => "ERR User 1");
		}

		if (!empty($token)) {
			$user = User::where('remember_token', $token)
							->first();

			if (!empty($user)) {

				return Array("status" => true, "user" => $user);
			}

			return Array("status" => false, "pesan" => "ERR User 2");
		}
		else{
			return Array("status" => false, "pesan" => "ERR User 3");
		}	
	}
}
