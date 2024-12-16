<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;

use App\Helpers;
use App\Fungsi\Crypt;

class VerifyTokenAppApi extends BaseVerifier {

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

		if ($cek == "OK"){
			return $next($request);
		}
		else{
			return Helpers::responseFailed('token APP mismatch '.$cek , null, 401);
		}
	}

	protected function appTokenMatch($request){
		$token  = $request->header('x-api-key');
		$app = config('app.key');

		//dd($app);

		// $C_signature = Crypt::Enkripsi("E", $app);
		// dd( $C_signature);


		if (!empty($token) && $token === $app){

            return "OK";
		}
		else{
			return "ERR APP KEY";
		}	
	}
}
