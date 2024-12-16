<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;

use App\Helpers;
use App\Fungsi\Crypt;

class VerifySignatureApi extends BaseVerifier {

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


        $signature  = $request->input('signature');
        $d_signature = Crypt::Enkripsi("D", $signature);

        $tanggal  = $request->input('tanggal');
        $kategori  = $request->input('kategori');
        $a_signature = $tanggal."#".$kategori;

        if ($d_signature == $a_signature) {
            /*digunakan live. Signature digenerate setiap ada request dan menghasilkan string yang tidak pernah sama*/
            return "OK";
        } elseif ($d_signature == $app) {
            /*digunakan untuk development supaya signature tidak berubah */
            /* Matikan saat production */
            return "OK";
        }

        return "ERR SIGNATURE";
	}
}
