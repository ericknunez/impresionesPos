 <?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class Facturas {


/*
0 Ninguno
1 Ticket
2 Facturas
3 CCF
4 NS
*/

public function ImprimirFactura($data){
    // $data['documento_factura'] = 0; // maneja el tipo de documento a imprimir
    if ($data['documento_factura'] == 0) {
        $this->Ninguno();
    }
    if ($data['documento_factura'] == 1) {
        $printer = "EPSON"; 
        $this->Ticket($data, $printer);
    }
    if ($data['documento_factura'] == 2) {
        $printer = "FACTURA"; 
        $printer_ticket = "EPSON";   
        $this->Ticket($data, $printer_ticket);
        $this->Factura($data, $printer);
    }
}


public function Ninguno(){
  $this->AbreCaja();
}



public function Ticket($data, $print){
  $doc = new Documentos();

  $img  = "C:/Appserv/www/impresiones/facturas/11/img/logo.jpg";


  $connector = new WindowsPrintConnector($print);
  $printer = new Printer($connector);
  $printer -> initialize();




  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $logo = EscposImage::load($img, false);
  $printer->bitImage($logo);

  $printer -> setFont(Printer::FONT_B);
  // $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
  // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

  $printer -> setTextSize(1, 2);
  $printer -> setLineSpacing(75);


  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $printer->text("ORDEN DE COMPRA");


  /* Stuff around with left margin */
  $printer -> setJustification(Printer::JUSTIFY_LEFT);
  $printer->feed();
  /* Items */

  foreach ($data['productos'] as $producto) {

  $printer -> text($doc->Item($producto["cant"], substr($producto["producto"], 0, 38), Helpers::Format($producto["pv"]), Helpers::Format($producto["total"])));

  } 




  $printer->feed();


  $printer -> text($doc->DosCol("Total " . $data['tipo_moneda'] . ":", 38, Helpers::Format($data['total']), 15));


  $printer -> text($doc->DosCol("Efectivo " . $data['tipo_moneda'] . ":", 38, Helpers::Format($data['efectivo']), 15));

  //cambio
  $printer -> text($doc->DosCol("Cambio " . $data['tipo_moneda'] . ":", 38, Helpers::Format($data['cambio']), 15));


  $printer->feed();



  $printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 20));


  $printer->feed();
  $printer -> text("Cajero: " . $data['cajero']);

  $printer->feed();
  $printer->text("REF: " . $data['no_factura']);
  $printer -> setJustification();




  $printer->feed();
  $printer->cut();
  $printer->pulse();
  $printer->close();


}





public function Factura($data, $print){
  $doc = new Documentos();

  $txt1   = "17"; 
  $txt2   = "10";
  $txt3   = "15";
  $txt4   = "8";
  $n1   = "18";
  $n2   = "24";
  $n3   = "21";
  $n4   = "10";
  
  
  
  $handle = printer_open($print);
  printer_set_option($handle, PRINTER_MODE, "RAW");
  
  printer_start_doc($handle, "Mi Documento");
  printer_start_page($handle);
  
  
  $font = printer_create_font("Arial", $txt1, $txt2, PRINTER_FW_NORMAL, false, false, false, 0);
  printer_select_font($handle, $font);
  
  
  //// comienza la factura
  printer_draw_text($handle, $data['empresa']['empresa_nombre'], 110, $oi);
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "Venta de pollo frito en piezas, Papas fritas", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "y ensaladas, etc", 120, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Mercado Concepcion, 1/2 cdra al sur", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "de farmacia San Jose, Choluteca,", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Honduras.", 0, $oi);
  
  //printer_draw_text($handle, $_SESSION['config_direccion'], 0, $oi);
  // $oi=$oi+$n1;
  // printer_draw_text($handle, Helpers::Pais($_SESSION['config_pais']), 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Propietario: Oscar Alexander", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "             Osorio Ramirez", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Email: " . $data['empresa']['empresa_email'], 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "RTN: " .  $data['empresa']['empresa_nit'], 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Tel: " . $data['empresa']['empresa_telefono'], 0, $oi);
  $oi=$oi+$n1;
  

  printer_draw_text($handle, "Factura Numero: " . Helpers::NFactura($data['no_factura']), 0, $oi);
  

  $oi=$oi+$n1;
  printer_draw_text($handle, "Fact. Inicial: 000-001-01-00059001", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Fact. Final:  000-001-01-00074000", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Fecha Limite: 23-04-2025", 0, $oi);
  ////////////////
  ///
  
  if ($data['cliente']['cliente']) {
    $oi=$oi+$n3;
    printer_draw_text($handle, "Cliente: " . $data['cliente']['cliente'], 0, $oi); 
    $oi=$oi+$n1;
    printer_draw_text($handle, "RTN: " . $data['cliente']['documento'], 0, $oi);     
  }
  
  /// nuevos datos exonerados
  $oi=$oi+$n1;
  printer_draw_text($handle, "Datos del Adquiriente Exonerado:", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "NO. OCE:", 0, $oi);
  printer_draw_text($handle, $_SESSION["nooce"], 232, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "NO. REG EXON:", 0, $oi);
  printer_draw_text($handle, $_SESSION["regexon"], 232, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "NO. CARNET DIPL:", 0, $oi);
  printer_draw_text($handle, $_SESSION["nocarnet"], 232, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "NO. SAG:", 0, $oi);
  printer_draw_text($handle, $_SESSION["nosag"], 232, $oi);
  ///
  ///
  ///
  ///
  $oi=$oi+$n2;
  printer_draw_text($handle, "____________________________________", 0, $oi);
  $oi=$oi+$n1;
  printer_draw_text($handle, "Cant.", 0, $oi);
  printer_draw_text($handle, "Descripcion", 60, $oi);
  printer_draw_text($handle, "P/U", 240, $oi);
  printer_draw_text($handle, "Total", 320, $oi);
  $oi=$oi+$n1+$n3;
  printer_draw_text($handle, "____________________________________", 0, $oi);
  
  
  
  foreach ($data['productos'] as $producto) {
  
    
  
            $oi=$oi+$n1;
            printer_draw_text($handle, $producto["cant"], 0, $oi);
            printer_draw_text($handle, $producto["producto"], 30, $oi);
            printer_draw_text($handle, $producto["pv"], 240, $oi);
            printer_draw_text($handle, $producto["total"], 320, $oi);
  
            $g="G";
  
            printer_draw_text($handle, $g, 385, $oi);

    }


  $oi=$oi+$n3+$n1;
  printer_draw_text($handle, "Sub Total " . $data['tipo_moneda'] . ":", 185, $oi);
  printer_draw_text($handle, Helpers::Format($data['subtotal']), 320, $oi);
  
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "15% Impu. " . $data['tipo_moneda'] . ":", 175, $oi);
  printer_draw_text($handle, Helpers::Format($data['impuestos']), 320, $oi);
  
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "18% Impu. ", 175, $oi);
  printer_draw_text($handle, Helpers::Format(0), 320, $oi);
  
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "Descuentos y Rebajas. ", 100, $oi);
  printer_draw_text($handle, Helpers::Format(0), 320, $oi);
  
  
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "Total " . $data['tipo_moneda'] . ":", 232, $oi);
  printer_draw_text($handle, Helpers::Format($data['total']), 320, $oi);
  
  $oi=$oi+$n2;
  printer_draw_text($handle, "____________________________________", 0, $oi);
  

  
  $oi=$oi+$n1;
  printer_draw_text($handle, "Efectivo " . $data['tipo_moneda'] . ":", 160, $oi);
  printer_draw_text($handle, Helpers::Format($data['efectivo']), 320, $oi);
  
  //cambio
  $oi=$oi+$n1;
  printer_draw_text($handle, "Cambio " . $data['tipo_moneda'] . ":", 162, $oi);
  printer_draw_text($handle, Helpers::Format($data['cambio']), 320, $oi);
  
  $oi=$oi+$n2;
  printer_draw_text($handle, "___________________________________", 0, $oi);
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "G=Articulo Gravado  E= Artculo Exento", 0, $oi);
  
  
  
  $oi=$oi+$n1;
  printer_draw_text($handle, $data['fecha'], 0, $oi);
  printer_draw_text($handle, $data['hora'], 232, $oi);
  
  
  
  // comienza cai
  $font = printer_create_font("Arial", $txt3, $txt4, PRINTER_FW_NORMAL, false, false, false, 0);
  printer_select_font($handle, $font);
  $oi=$oi+$n1;
  printer_draw_text($handle, "CAI:", 0, $oi);
  $oi=$oi+$n1;
  
  printer_draw_text($handle, "D00786-5FA5FE-0D468E-AFE331-5E10C6-90", 0, $oi);
  printer_delete_font($font);
  ///// termina cai
  
  
  ///// crea de nuevo fuente
  $font = printer_create_font("Arial", $txt1, $txt2, PRINTER_FW_NORMAL, false, false, false, 0);
  printer_select_font($handle, $font);
  //////////////////
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "Cajero: " . $data['cajero'], 25, $oi);
  
  
  $oi=$oi+$n1+$n4;
  printer_draw_text($handle, "GRACIAS POR SU COMPRA...", 50, $oi);
  printer_delete_font($font);
  
  $oi=$oi+$n1+$n2;
  printer_draw_text($handle, ".", NULL, $oi);
  printer_write($handle, chr(27).chr(112).chr(48).chr(55).chr(121)); //enviar pulso
  
  
  
  ///
  printer_end_page($handle);
  printer_end_doc($handle);
  printer_close($handle);
  

}



public function AbreCaja($data){

  $printer = "EPSON"; 

  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer->pulse();
  $printer->close();
}









}// class
