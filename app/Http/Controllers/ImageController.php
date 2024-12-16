<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Auth;
use App\Helpers;


class ImageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageCrop()
    {
        return view('imageCrop');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageCropPost(Request $request)
    {

        try {
            $id = Auth::user()->id;


            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);


            $data = base64_decode($data);
            $image_name= "Avatar_".$id.'.jpg';
            $path = public_path() . "/img/avatar/" . $image_name;


            file_put_contents($path, $data);


            return Helpers::responseJson(true, "", "OK" );

        } catch(\Exception $exception){
            return Helpers::responseJson(false, "", "Terdapat kesalahan proses" );
        }

    }
}