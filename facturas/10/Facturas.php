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
        if ($data['caja'] == 1) {
          $printer = "LR200";   
        } 
        if ($data['caja'] == 2) {
            $printer = "TICKET2"; 
        } 
        $this->Ticket($data, $printer);
    }
    if ($data['documento_factura'] == 2) {
          if ($data['caja'] == 1) {
            $printer = "EPSON TM-U220 Receipt";   
          } 
          if ($data['caja'] == 2) {
              $printer = "FACTURAS2"; 
          } 
        $this->Factura($data, $printer);
    }
}


public function Ninguno(){
  $this->AbreCaja();
}



public function Ticket($data, $print){
  $doc = new Documentos();

  $img  = "C:/Appserv/www/impresiones/facturas/10/img/sp.jpg";


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


  $printer -> text($doc->DosCol("Total " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['total']), 20));


  $printer -> text($doc->DosCol("Efectivo " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['efectivo']), 20));

  //cambio
  $printer -> text($doc->DosCol("Cambio " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['cambio']), 20));


  $printer->feed();



  $printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 30));


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

  $connector = new WindowsPrintConnector($print);
  $printer = new Printer($connector);
  $printer -> initialize();
  
  $printer -> setFont(Printer::FONT_B);
  // $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
  // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
  
  $printer -> setTextSize(1, 2);
  $printer -> setLineSpacing(80);
  
  
  // $printer -> setJustification(Printer::JUSTIFY_CENTER);
  // $logo = EscposImage::load($img, false);
  // $printer->bitImage($logo);
  
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $printer->text($data['empresa']['empresa_nombre']);
  
  // $printer -> setJustification(Printer::JUSTIFY_LEFT);
  
  $printer->feed();
  $printer->text($data['empresa']['empresa_giro']);
  
  $printer->feed();
  $printer->text($data['empresa']['empresa_direccion']);
  
  
  $printer->feed();
  $printer->text("Propietario: " . $data['empresa']['empresa_propietario']);
  
  
  /////////////////////
  $printer->feed();
  $printer->text("Email: " . $data['empresa']['empresa_email']);
  
  $printer->feed();
  $printer->text("RTN: " . $data['empresa']['empresa_nit']);
  
  
  $printer->feed();
  $printer->text("Factura Numero: 000-001-01-" . Helpers::NFactura($data['no_factura']));
  
  $printer->feed();
  $printer->text("Fact. Inicial: 000-001-01-00400001");

  $printer->feed();
  $printer->text("Fact. Final:  000-001-01-00850000");
  
  $printer->feed();
  $printer->text("Fecha Limite: 14-02-2022");

  $printer->feed();
  $printer->text("Datos del Adquiriente Exonerado:");
  
  $printer->feed();
  $printer->text("NO. OCE:");
  
  $printer->feed();
  $printer->text("NO. REG EXON:");
  
  $printer->feed();
  $printer->text("NO. CARNET DIPL:");
  
  $printer->feed();
  $printer->text("NO. SAG:");
  
  /* Stuff around with left margin */
  $printer->feed();
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $printer -> text("____________________________________________________________");
  $printer -> setJustification(Printer::JUSTIFY_LEFT);
  $printer->feed();
  /* Items */
  
  $printer -> setJustification(Printer::JUSTIFY_LEFT);
  $printer -> setEmphasis(true);
  $printer -> text($doc->Item("Cant", 'Producto', 'Precio', 'Total'));
  $printer -> setEmphasis(false);
  
  
      foreach ($data['productos'] as $producto) {
  
  $printer -> text($doc->Item($producto["cant"], substr($producto["producto"], 0, 38), $producto["pv"], $producto["total"]));
  
  }
  
  
  
  
  $printer -> text("____________________________________________________________");
  $printer->feed();
  
  
  $printer -> text($doc->DosCol("Sub Total " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['subtotal']), 20));
  
  
  $printer -> text($doc->DosCol("15% Impu " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['impuestos']), 20));

  $printer -> text($doc->DosCol("18% Impu " . $data['tipo_moneda'] . ":", 40, Helpers::Format(0), 20));

  $printer -> text($doc->DosCol("Decuentos y Rebajas " . $data['tipo_moneda'] . ":", 40, Helpers::Format(0), 20));
  
  
  $printer -> text($doc->DosCol("TOTAL " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['total']), 20));
  
  
  
  $printer -> text("____________________________________________________________");
  $printer->feed();
  
  
  
  $printer -> text($doc->DosCol("Efectivo " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['efectivo']), 20));
  
  //cambio
  $printer -> text($doc->DosCol("Cambio " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['cambio']), 20));
  
  
  $printer -> text("____________________________________________________________");
  $printer->feed();
  
  $printer -> text("G=Articulo Gravado  E= Artculo Exento");
 
  
  $printer->feed();
  $printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 30));
  

  $printer -> text("CAI:");

  $printer -> text("B9CC25-15D031-F242AB-106341-2E2CEF-1C");

  
  

  $printer->feed();
  $printer -> text("Cajero: " . $data['cajero']);
  
  $printer->feed();
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $printer -> text("GRACIAS POR SU COMPRA...");
  $printer -> setJustification();
  
  
  $printer->feed();
  $printer->cut();
  $printer->pulse();
  $printer->close();

}



public function AbreCaja($data){
  if ($data['caja'] == 1) {
    $printer = "LR200";   
  } 
  if ($data['caja'] == 2) {
      $printer = "TICKET2"; 
  } 
  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer->pulse();
  $printer->close();
}









}// class