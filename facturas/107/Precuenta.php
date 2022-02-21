 <?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class Precuenta {


/*
    Precuenta del ciente
*/


public function PrecuentaPrint($data, $printer){
    $doc = new Documentos();

    $txt1   = "17"; 
    $txt2   = "10";
    $txt3   = "15";
    $txt4   = "8";
    $n1   = "18";
    $n2   = "24";
    $n3   = "21";
    $n4   = "10";
    
    
    $col1 = 0;
    $col2 = 30;
    $col3 = 250;
    $col4 = 330;
    $col5 = 330;
    
    
    
    $handle = printer_open($printer);
    printer_set_option($handle, PRINTER_MODE, "RAW");
    
    printer_start_doc($handle, "Mi Documento");
    printer_start_page($handle);
    
    $font = printer_create_font("Arial", $txt1, $txt2, PRINTER_FW_NORMAL, false, false, false, 0);
    printer_select_font($handle, $font);
    
    
    
    //// comienza la factura
    printer_draw_text($handle, "RESTAURANTE Y HOSTAL", 50, $oi);
    $oi=$oi+$n1;
    printer_draw_text($handle, "BUENOS AIRES", 98, $oi);
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "El Paraiso Ataco", 100, $oi);
    $oi=$oi+$n1;
    printer_draw_text($handle, "2a Av. Norte, Finca Buenos Aires", 0, $oi);
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "Tel: 2450-5377", 0, $oi);
    $oi=$oi+$n1;
    
    //$numero1=str_pad($numero, 8, "0", STR_PAD_LEFT);
    //$numero1="000-001-01-$numero1";
    printer_draw_text($handle, "Factura Numero: " . $data['numero_documento'], 0, $oi);
    
    
    
    $oi=$oi+$n2;
    printer_draw_text($handle, "____________________________________", 0, $oi);
    $oi=$oi+$n1;
    printer_draw_text($handle, "Cant.", 55, $oi);
    printer_draw_text($handle, "Descripcion", $col2, $oi);
    printer_draw_text($handle, "P/U", $col3, $oi);
    printer_draw_text($handle, "Total", $col4, $oi);
    
    $oi=$oi+$n1+$n3;
    printer_draw_text($handle, "____________________________________", 0, $oi);
    
    
  
    foreach ($data['productos'] as $producto) {
    
              $oi=$oi+$n1;
              printer_draw_text($handle, $producto['cant'], $col1, $oi);
              printer_draw_text($handle, $producto['producto'], $col2, $oi);
              printer_draw_text($handle, Helpers::Format($producto["pv"]), $col3, $oi);
              printer_draw_text($handle, Helpers::Format($producto["total"]), $col4, $oi);

    }
    

    
    $oi=$oi+$n2;
    printer_draw_text($handle, "Propina " . $data['tipo_moneda'] . " : ", 160, $oi);
    printer_draw_text($handle, Helpers::Format($data['propina_cant']),$col4, $oi);
 
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "Total " . $data['tipo_moneda'] . ":", 165, $oi);
    printer_draw_text($handle, Helpers::Format($data['propina_cant'] + $data['total']), $col4, $oi);
    
    $oi=$oi+$n2;
    printer_draw_text($handle, "____________________________________", 0, $oi);
    

    
    
    $oi=$oi+$n1;
    printer_draw_text($handle, $data['fecha'], 50, $oi);
    printer_draw_text($handle, $data['hora'], 200, $oi);
    
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "Cajero: " . $data['cajero'], 25, $oi);
    
    if($data['mesa']['nombre_mesa'] != NULL){
        $oi=$oi+$n1;
        printer_draw_text($handle, "Mesa: " . $data['mesa']['nombre_mesa'], 25, $oi);
        
    }
    
    
    $oi=$oi+$n1+$n4;
    printer_draw_text($handle, "Nuestros precios incluyen IVA", 50, $oi);
    
    $oi=$oi+$n1+$n4;
    printer_draw_text($handle, "GRACIAS POR SU COMPRA...", 50, $oi);
    
    $oi=$oi+$n1+$n2;
    printer_draw_text($handle, ".", NULL, $oi);
    
    
    // printer_write($handle, chr(27).chr(112).chr(48).chr(55).chr(121)); //enviar pulso
    printer_delete_font($font);
    
    ///
    printer_end_page($handle);
    printer_end_doc($handle);
    printer_close($handle);
    
    

}















}// class