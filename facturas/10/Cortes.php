 <?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class CorteDeCaja {


/*
    Corte de caja
*/


public function CortePrint($data, $printer){
    $doc = new Documentos();

    $connector = new WindowsPrintConnector($printer);
    $printer = new Printer($connector);
    $printer -> initialize();
    
    $printer -> setFont(Printer::FONT_B);
    
    $printer -> setTextSize(1, 2);
    $printer -> setLineSpacing(80);
    
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    
    $printer -> text("RESUMEN DE CORTE DE CAJA");
    $printer->feed();
    
    
    $printer -> text("_______________________________________________________");
    $printer->feed();
    
    $printer -> text("CAJERO: " . $data['cajero']);
    $printer->feed();
    $printer -> text($doc->DosCol("CAJA: ", 40, $data['caja'], 10));
    $printer->feed();

       
    
    $printer -> text($doc->DosCol("TOTAL DE VENTA: ", 40, Helpers::Dinero($data['total_venta']), 10));
    


      
    $printer -> text("_______________________________________________________");
    $printer->feed();
    
    

    
    
    
    $printer -> text($doc->DosCol("GASTOS REGISTRADOS: ", 40, Helpers::Dinero($data['gastos']), 10));
    
    
    $printer -> text($doc->DosCol("REMESAS: ", 40, Helpers::Dinero($data['remesas']), 10));


    $printer -> text($doc->DosCol("ABONOS: ", 40, Helpers::Dinero($data['abonos']), 10));
    
    
    $printer -> text("__________________________________________________");
    $printer->feed();
    
    
    
    
    $printer -> text($doc->DosCol("DINERO EN APERTURA: ", 40, Helpers::Dinero($data['efectivo_inicial']), 10));
    
    $printer -> text($doc->DosCol("EFECTIVO INGRESADO: ", 40, Helpers::Dinero($data['efectivo_final']), 10));

    $printer -> text($doc->DosCol("EFECTIVO DEBIDO: ", 40, Helpers::Dinero($data['total_efectivo']), 10));

    
    
    $printer -> text($doc->DosCol("DIFERENCIA: ", 40, Helpers::Dinero($data['diferencia']), 10));
    
    $printer -> text("_______________________________________________________");
    $printer->feed();
    
    
    
    
    
    $printer->feed();
    $printer->cut();
    $printer->close();
      

}


public function CorteZ($data, $printer){

}



public function ReporteDiario($data, $printer){


  $txt1   = "17"; 
  $txt2   = "10";
  $txt3   = "15";
  $txt4   = "8";
  $n1   = "18";
  $n2   = "24";
  $n3   = "21";
  $n4   = "10";
  
    
  
      $handle = printer_open($printer);
      printer_set_option($handle, PRINTER_MODE, "RAW");
  
      printer_start_doc($handle, "Mi Documento");
      printer_start_page($handle);
  
      $font = printer_create_font("Arial", $txt1, $txt2, PRINTER_FW_NORMAL, false, false, false, 0);
      printer_select_font($handle, $font);
  
  $oi=0;
  //// comienza la factura
  printer_draw_text($handle, $data['empresa']['empresa_nombre'], 110, $oi);
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "Venta de pollo frito en piezas, Papas fritas", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "y ensaladas, etc", 120, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Bo. El centro 1/2 Cdra al Este", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "del Elektra, Choluteca, Honduras.", 0, $oi);
  
  //printer_draw_text($handle, $_SESSION['config_direccion'], 0, $oi);
  // $oi=$oi+$n1;
  // printer_draw_text($handle, Helpers::Pais($_SESSION['config_pais']), 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Propietario: " .$data['empresa']['empresa_propietario'], 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Email: " . $data['empresa']['empresa_email'], 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "RTN: " .  $data['empresa']['empresa_nit'], 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Tel: " . $data['empresa']['empresa_telefono'], 0, $oi);
  $oi=$oi+$n1;
  
  

          
  $oi=$oi+$n1;
  printer_draw_text($handle, "Fact. Inicial: " . Helpers::NFactura($data['factura_inicial']), 0, $oi);
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "Fact. Final: " . Helpers::NFactura($data['factura_final']), 0, $oi);
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "FACTURAS: " .  $data['facturas_cantidad'], 0, $oi);
  
  
  $oi=$oi+$n2;
      printer_draw_text($handle, "____________________________________", 0, 220);
      //consulta cuantos productos imprimir
      $oi=250;
      printer_draw_text($handle, $data['fecha'], 15, $oi);
  
      $oi=$oi+30;
      printer_draw_text($handle, "EXENTO:  " . Helpers::Dinero(0), 10, $oi);
  
      $oi=$oi+30;
      printer_draw_text($handle, "GRAVADO:  " . Helpers::Dinero($data['subtotal']), 10, $oi);
  
      $oi=$oi+30;
      printer_draw_text($handle, "SUBTOTAL:  " . Helpers::Dinero($data['subtotal']), 10, $oi);
  
      $oi=$oi+30;
      printer_draw_text($handle, "ISV:  " . Helpers::Dinero($data['impuestos']), 10, $oi);
  
      $oi=$oi+30;
      printer_draw_text($handle, "____________________________________", 0, $oi);
      $oi=$oi+30;
      printer_draw_text($handle, "TOTAL:  " . Helpers::Dinero($data['total']), 10, $oi);
      printer_delete_font($font);
  
  
      //////////////////
      $oi=$oi+30;
      printer_draw_text($handle, "Cajero: " . $data['cajero'], 20, $oi);
  
  
      $oi=$oi+30;
      printer_draw_text($handle, "Total Eliminadas: " . $data['eliminadas'], 20, $oi);
        
  
      $oi=$oi+$n1+$n2;
      printer_draw_text($handle, ".", NULL, $oi);
      printer_write($handle, chr(27).chr(112).chr(48).chr(55).chr(121)); //enviar pulso
      
      
      printer_end_page($handle);
      printer_end_doc($handle, 20);
      printer_close($handle);
  
  
  
  
  }   // termina reporte diario
  
  















}// class