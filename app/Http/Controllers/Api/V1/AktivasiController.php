<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\M_Device;

use App\Helpers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

use DB;
use Hash;


class AktivasiController extends Controller
{
    public function aktivasi(Request $request)
    {
        try {
            $otp = $request->input('otp');
            $hardware_id = $request->input('hardware_id');

            $device = M_Device::where('otp', $otp)->first();
            if (empty($device)) {
                return Helpers::Response($request, false, "", "Kode tidak ditemukan", 200);
            }

            if (strlen($device->token) > 5) {
                return Helpers::Response($request, false, "", "Kode sudah dipakai oleh device lain", 200);
            }

            $token = Helpers::rndstr(100);
            $device->token = $token;            
            $device->hardware_id = $hardware_id;            
            $device->save();

            return Helpers::Response($request, true, $device, "OK", 200);

        } catch(\Exception $exception){
            DB::rollBack();
            return Helpers::Response($request, false, $exception, "Terdapat kegagalan transaksi", 200);
        }

        return $rv;
    }
}
