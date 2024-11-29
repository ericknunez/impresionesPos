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
    
    $img  = "C:/Appserv/www/impresiones/facturas/123/img/logo.png";
  
    $connector = new WindowsPrintConnector($printer);
    $printer = new Printer($connector);
    $printer -> initialize();
    
  
  
   $printer -> setFont(Printer::FONT_A);
    
    $printer -> setTextSize(2, 2);
    $printer -> setLineSpacing(80);
    
    
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $logo = EscposImage::load($img, false);
    $printer->bitImage($logo);
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> setFont(Printer::FONT_A); 
    $printer -> setTextSize(1, 1);
    $printer -> setLineSpacing(80);
    $printer->feed();
    $printer->text("Dirección: 3a Av. Nte. Plaza La Constitucion ");
    $printer->feed();
    $printer->text("frente a Parque Central de Metapán, Local # 13");
    $printer->feed();
    $printer->text("TELEFONO: 7238-2280");
  $printer->feed();
  $printer -> setJustification(Printer::JUSTIFY_LEFT);
  $printer->text("ORDEN NUMERO: " . $data['numero_documento']);
  
  
  $printer->feed();
  $printer->text("PRECUENTA");
  
  
  /* Stuff around with left margin */
  $printer->feed();
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $printer -> text("_______________________________________________");
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
  
   
  $printer -> text("_______________________________________________");
  $printer->feed();
  
  
  
  $printer -> text($doc->DosCol("Sub Total " . $data['tipo_moneda'] . ":", 25, Helpers::Format($data['total']), 10));
  
  
  
  if ($data['propina_cant']) {
    $printer -> text($doc->DosCol("Propina " . $data['tipo_moneda'] . ":", 25, Helpers::Format($data['propina_cant']), 10));
  }

  $printer -> setEmphasis(true);
  $printer -> text($doc->DosCol("Total " . $data['tipo_moneda'] . ":", 25, Helpers::Format($data['propina_cant'] + $data['total']), 10));
  $printer -> setEmphasis(false);
  
  
  $printer -> text("_______________________________________________");
  $printer->feed();
  
  
  $printer -> text("Fecha: " . $data['fecha']. "       Hora:" .$data['hora']);
  //$printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 20));
  
  $printer->feed();
  $printer -> text("Cajero: " . $data['cajero']);
  $printer->feed();
  

  if($data['tipo_servicio'] == 3){
    $printer -> text("Cliente: " . $data['cliente_nombre']);
    $printer->feed();
  }
  if($data['tipo_servicio'] == 3){
    $printer -> text($data['cliente_direccion']);
    $printer->feed();
  }
  if($data['tipo_servicio'] == 3){
    $printer -> text("Telefono: " . $data['cliente_telefono']);
    $printer->feed();
  }
  
  // datos del cliente delivery
  
  
  // nombre de mesa
  if($data['mesa']['nombre_mesa'] != NULL){
    $printer -> text(" " . $data['mesa']['nombre_mesa']);
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


  

  $printer -> text("_______________________________________________");
  $printer->feed();
  
  
  $printer->feed();
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  $printer -> text("GRACIAS POR SU PREFERENCIA...");
  $printer->feed();
  $printer -> text("VUELVA PRONTO");
  $printer->feed();
  $printer->feed();
  $printer -> setJustification();
  
  
  $printer->feed();
  $printer->cut();
  $printer->close();
  

}















}// class