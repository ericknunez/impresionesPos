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
    
    $img  = "C:/laragon/www/impresiones/facturas/0/img/logo.png";
  
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
  
$printer->text("CARRETERA INTERNACIONAL KM 114, METAPAN");
// $printer->text($data['empresa_direccion']);

$printer->feed();
$printer->text("TELEFONO: 6062-3549");
// $printer->text("TELEFONO: " . $data['empresa_telefono']);
  
  $printer->feed();
  $printer->text("ORDEN NUMERO: " . $data['numero_documento']);
  
  
  $printer->feed();
  $printer->text("PRECUENTA");
  
  
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
    $printer -> text($doc->Item($producto['cant'], $producto["producto"], Helpers::Format($producto["pv"]), Helpers::Format($producto["total"]))); 
  }
  
   
  $printer -> text("____________________________________________________________");
  $printer->feed();
  
  
  
  $printer -> text($doc->DosCol("Sub Total " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['subtotal']), 20));
  
  
  
if ($data['propina']) {
  $printer -> text($doc->DosCol("Propina " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['propina']), 20));
}
  
  $printer -> setEmphasis(true);
  $printer -> text($doc->DosCol("Total " . $data['tipo_moneda'] . ":", 40, Helpers::Format($data['total']), 20));
  $printer -> setEmphasis(false);
  
  
  $printer -> text("____________________________________________________________");
  $printer->feed();
  
  
  
  $printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 30));
  
  
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


  

  $printer -> text("____________________________________________________________");
  $printer->feed();
  
  
  $printer->feed();
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $printer -> text("GRACIAS POR SU PREFERENCIA...");
  $printer -> setJustification();
  
  
  $printer->feed();
  $printer->cut();
  $printer->close();
  

}















}// class