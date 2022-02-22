<?php
class Helpers{

    public function __construct(){

    } 



    static public function Pais($string) {
        if($string == "1") return 'El Salvador';
        if($string == "2") return 'Honduras';
        if($string == "3") return 'Guatemala';
    }


 static public function TipoPago($string) {
    if($string == "1") return 'Efectivo';
    if($string == "2") return 'Tarjeta';
    if($string == "3") return 'Credito';
    }



    static public function Mayusculas($nombre){
        return ucwords(strtolower($nombre));
    }

    static public function MayusInicial($nombre){
    return ucfirst(strtolower($nombre));
    }


   static  public function Dinero($numero){  
        $format= $_SESSION['config_moneda_simbolo'] ." " . number_format($numero,2,'.',',');
        return $format;
     } 


    public function Format($numero){ 
        $format=number_format($numero,2,'.',',');
        return $format;
     } 


    public function Format4D($numero){ 
        $format=number_format($numero,4,'.',',');
        return $format;
     } 


    static public function Entero($numero){ 
        $format=intval($numero);
        return $format;
     } 

    
    static public function STotal($numero, $impuestos){  
        $imp = ($impuestos / 100)+1;
        $st = $numero / $imp;
        return $st;
     } 


    static public function Impuesto($numero, $impuestos){  
        $imp = $impuestos / 100;
        return $numero * $imp;
    } 


    static public function Propina($numero){ 
        $num = $_SESSION['config_propina'] / 100;
        $propina = $numero * $num;
        return $propina;
    }


    static public function PropinaTotal($numero){ 
        $num = $_SESSION['config_propina'] / 100;
        $propina = $numero * $num;
        $numer = $propina + $numero;
        return $numer;
    }


    static public function Descuento($numero){ 
        $num = $_SESSION['descuento'] / 100;
        $descuento = $numero * $num;
        return $descuento;
    }

    static public function DescuentoTotal($numero){ 
        $num = $_SESSION['descuento'] / 100;
        $descuento = $numero * $num;
        $numer = $numero - $descuento;
        return $numer;
    }

    static public function DescuentoTotalCot($numero){ 
        $num = $_SESSION['descuento_cot'] / 100;
        $descuento = $numero * $num;
        $numer = $numero - $descuento;
        return $numer;
    }

   static  public function NFactura($numero){ 
        $numero1=str_pad($numero, 8, "0", STR_PAD_LEFT);
        $format="000-001-01-$numero1";
        return $format;
     } 




public static function HashId(){
  $id = rand(1,999999999) . "-" . date("d-m-Y-H:i:s") . rand(1,999999999);
  $iden = sha1($id);
  $hash = substr($iden,0,10);
  return $hash;
}


public static function TimeId(){
  $id = strtotime(date("Y-m-d H:i:s"));
  return $id;
}








} // class
?>