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
    $printer = "LR2000";
    if ($data['documento_factura'] == 0) {
        $this->Ninguno();
    }
    if ($data['documento_factura'] == 1) {
        $this->Ticket($data, $printer);
    }
    if ($data['documento_factura'] == 2) {
        $this->Factura();
    }
}


public function Ninguno(){
  $this->AbreCaja();
}



public function Ticket($data, $printer){
    $doc = new Documentos();
    
    $img  = "C:/laragon/www/impresiones/facturas/104/img/logo.jpg";
  
  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer -> initialize();
  
  $printer -> setFont(Printer::FONT_B);
  
  $printer -> setTextSize(1, 2);
  $printer -> setLineSpacing(80);
  
  
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $logo = EscposImage::load($img, false);
  $printer->bitImage($logo);
  $printer -> setJustification(Printer::JUSTIFY_LEFT);
//   $printer->text($data['empresa_nombre']);
  
  $printer->text("Colonia Guadalupe, Fte a Escuela Federada, Metapan");
  // $printer->text($data['empresa_direccion']);
  
  $printer->feed();
  $printer->text("TELEFONO: 7290 0161");
  // $printer->text("TELEFONO: " . $data['empresa_telefono']);
  
  $printer->feed();
  $printer->text("TICKET NUMERO: " . $data['numero_documento']);

  
  
  /* Stuff around with left margin */
  $printer->feed();
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $printer -> text("________________________________________________________");
  $printer -> setJustification(Printer::JUSTIFY_LEFT);
  $printer->feed();
  /* Items */
  
  $printer -> setJustification(Printer::JUSTIFY_LEFT);
  $printer -> setEmphasis(true);
  $printer -> text($doc->Item("Cant", 'Producto', 'Precio', 'Total'));
  $printer -> setEmphasis(false);
  
  

  foreach ($data['productos'] as $producto) {
        $printer -> text($doc->Item($producto['cant'], $producto["producto"], Helpers::Format($producto["pv"]), Helpers::Format($producto["total"]))); 
  }
  
   
  $printer -> text("________________________________________________________");
  $printer->feed();
  
  
  
  $printer -> text($doc->DosCol("Sub Total " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['total']), 10));
  
  
  
  if ($data['propina_cant']) {
    $printer -> text($doc->DosCol("Propina " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['propina_cant']), 10));
  }

  $printer -> setEmphasis(true);
  $printer -> text($doc->DosCol("Total " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['propina_cant'] + $data['total']), 10));
  $printer -> setEmphasis(false);
  
  
  
  $printer -> text("________________________________________________________");
  $printer->feed();
  

    $printer -> text($doc->DosCol("Efectivo " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['efectivo']), 10));
    $printer -> text($doc->DosCol("Cambio " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['cambio']), 10));
    
    
    $printer -> text("________________________________________________________");
    $printer->feed();
  
    

  
  
  $printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 20));
  
  
  $printer -> text("Cajero: " . $data['cajero']);
  $printer->feed();
  

  if($data['cliente_nombre'] != NULL){
    $printer -> text("Cliente: " . $data['cliente_nombre']);
    $printer->feed();
  }
  if($data['cliente_direccion'] != NULL){
    $printer -> text($data['cliente_direccion']);
    $printer->feed();
  }
  if($data['cliente_telefono'] != NULL){
    $printer -> text("Telefono: " . $data['cliente_telefono']);
    $printer->feed();
  }
  
  // datos del cliente delivery
  
  
  // nombre de mesa
  if($data['nombre_mesa'] != NULL){
    $printer -> text("Mesa: " . $data['nombre_mesa']);
     $printer->feed();
  }
  
  
// llevar o comer aqui
if($data['llevar_aqui'] != NULL){
  if ($data['llevar_aqui'] == 1) {
    $tipo = "LLevar";
  } else {
    $tipo = "Comer Aqui";
  }
  $printer -> text( $tipo);
   $printer->feed();
}


  

  $printer -> text("________________________________________________________");
  $printer->feed();
  
  
  $printer->feed();
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $printer -> text("GRACIAS POR SU PREFERENCIA...");
  $printer -> setJustification();
  
  
  $printer->feed();
  $printer->cut();
  $printer->close();
  

}


public function Factura(){
  $this->AbreCaja();
}



public function AbreCaja($datos){
  $printer = "IMPRESORA";

  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer->pulse();
  $printer->close();
}









}// class