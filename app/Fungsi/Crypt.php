<?php 
namespace App\Fungsi;


class Crypt
{
   
 
    Public static function dec2hex($number)
	{
	    $hexvalues = array('0','1','2','3','4','5','6','7',
	               '8','9','A','B','C','D','E','F');
	    $hexval = '';
	     while($number != '0')
	     {
	        $hexval = $hexvalues[bcmod($number,'16')].$hexval;
	        $number = bcdiv($number,'16',0);
	    }
	    return $hexval;
	}

	Public static function strToHex($string)
	{
	    $hex='';
	    for ($i=0; $i < strlen($string); $i++)
	    {
	        $hex .= dechex(ord($string[$i]));
	    }
	    return $hex;
	}

	static function hextostr($hex)
	{
	    $string='';
	    for ($i=0; $i < strlen($hex)-1; $i+=2)
	    {
	        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
	    }
	    return $string;
	}

	Public static function Enkripsi($aksi,$src)
	{
		$sumber = $src;

		$K = "Data";
		$L = "Persada";
		$M = "Aset";

		$kunci0 = $K;
		$kunci1 = $L;
		$kunci2 = $M;
		
		$jr = strlen($sumber) / strlen($M) + 2;  /* $L dipilih karena paling pendek */

		for ($i=1;$i<$jr ;$i++){
			$kunci0 = $kunci0.$K;
			$kunci1 = $kunci1.$L;
			$kunci2 = $kunci2.$M;
		}
		$K= $kunci0;
		$L= $kunci1;
		$M= $kunci2;
		

	   If ($aksi == "E") {
			    $Tujuan = "";

	            $Kunci0 =  Chr(Rand(50,150));
			    $Kunci2 = self::strtohex($Kunci0 ); 
			   
				If (strlen($Kunci2) == 1) {
					$Kunci2 = "0".$Kunci2;
				}

				$Tujuan = $Kunci2;
				$Kunci2 = "";
			   
				$jr = strlen($sumber) + 1;
				for ($i=1;$i<$jr ;$i++){

				   $d1=substr($sumber, $i - 1, 1);
				   $d2=substr($K, $i - 1, 1);
				   $d3=substr($L, $i - 1, 1);
				   $d4=substr($M, $i - 1, 1);
				   $d5=$Kunci0;
				   
				   $Kunci1 = ((($d1 ^$d2) ^ $d3) ^ $d4) ^ $d5;
	               $Kunci2 = "";
				   $Kunci2 = self::strtohex($Kunci1);
				   
				   If (strlen($Kunci2) == 1) {
						$Kunci2 = "0".$Kunci2;
					}

					$Tujuan = $Tujuan.$Kunci2;
	                $Kunci0 = $Kunci1;
					$Kunci1 = "";
	            }
			   
				$K0 = "";
	            $K1 = "";

				
	            $j = strlen($Tujuan);

	            for ($i=0; $i < $j; $i++){
	                $k = $i % 2;
					$K2=substr($Tujuan, $i, 1);

	                if ($k == 0) {
	                    $K0 = $K0.$K2;
	                } else  {
	                    $K1 = $K1.$K2;
	                }
	            }


	            $Tujuan = $K1.$K0;
	            /*$Tujuan = $bantu; */
			   
		} else {
			   $Tujuan = "";
				$Kunci2 = "";
				
				
				
				
				$j = strlen($sumber);
	            $j = $j / 2;
	            $j =  $j;
	            $K0 = substr($sumber, $j);
	            $K1 = substr($sumber, 0, $j);
	            $K3 = "";

	            //Gabungkan kembali sesuai pola

	            for ($i=0; $i < $j; $i++){

					$x0 = substr($K1, $i, 1);
					$x1 = substr($K0, $i, 1);
					
				
	                $K3 = $K3.$x1.$x0;
	            }
		   
			   $jr = (strlen($K3)/2)+1;
			   
			   for ($i=1;$i<$jr ;$i++){
				$K1 = substr($K3, ($i - 1) * 2, 2);
				$K2  = self::hextostr($K1);
	            $Kunci2 = $Kunci2.$K2;
			   }
			   
			   $jr = strlen($Kunci2) ;
			   for ($i=1;$i<$jr ;$i++){

				   $d1=substr($Kunci2, strlen($Kunci2) - $i, 1);
				   $d2=substr($Kunci2, strlen($Kunci2) - $i - 1, 1);
				   $d3=substr($K, strlen($Kunci2) - $i - 1, 1);
				   $d4=substr($L, strlen($Kunci2) - $i - 1, 1);
				   $d5=substr($M, strlen($Kunci2) - $i - 1, 1);

				   $Kunci1 = ((($d1 ^ $d2) ^ $d3) ^ $d4) ^ $d5;
				   
					$Tujuan = $Tujuan.$Kunci1;
					$Kunci0 = $Kunci1;
					$Kunci1 = "";
				}

	            $Kunci0 = "";

				$jr = strlen($Tujuan) + 1;
			   for ($i=1;$i<$jr ;$i++){
					$u = substr($Tujuan, strLen($Tujuan) - $i , 1);
	                $Kunci0 = $Kunci0.$u;
				}
				$Tujuan = $Kunci0;
		
		}
		
	    return $Tujuan;
	}
}
