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
        $printer = "EOM-200";
        $this->Ninguno();
    }
    if ($data['documento_factura'] == 1) {
        $printer = "EOM-200";
        $this->Ticket($data, $printer);
    }
    if ($data['documento_factura'] == 2) {
        // $this->Factura();
        $this->Ticket($data, $printer);
    }
}


public function Ninguno(){
  $this->AbreCaja();
}



public function Ticket($data, $printer){
    $doc = new Documentos();
    
  //  $img  = "C:/Appserv/www/impresiones/facturas/109/img/logo.jpg";
  
  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer -> initialize();
  


  //$printer -> setFont(Printer::FONT_B);
  
  $printer -> setTextSize(1, 1);
  $printer -> setLineSpacing(10);
  
  
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
 // $logo = EscposImage::load($img, false);
 // $printer->bitImage($logo);
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
//   $printer->text($data['empresa_nombre']);
  $printer->text("DELI-PIZZA");
  $printer->feed();
  $printer->text("Dirección Barrio el Centro, avenida las flores");
  $printer->feed();

  $printer->text("Nueva Esparta, La Unión ");
  $printer->feed();
  $printer->text("TELEFONO: 2282-3126 / 7503-7702");

  
  $printer->feed();
  $printer -> setJustification(Printer::JUSTIFY_LEFT);
  $printer->text("TICKET NUMERO: " . $data['no_factura']);

  
  
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
  

    $printer -> text($doc->DosCol("Efectivo " . $data['tipo_moneda'] . ":", 25, Helpers::Format($data['efectivo']), 10));
    $printer -> text($doc->DosCol("Cambio " . $data['tipo_moneda'] . ":", 25, Helpers::Format($data['cambio']), 10));
    
    
    $printer -> text("_______________________________________________");
    $printer->feed();
  
    

  
  $printer -> text("Fecha: " . $data['fecha']. "       Hora:" .$data['hora']);
  $printer->feed();
  //$printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 20));
  
  $printer->feed();
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
  if($data['mesa']['nombre_mesa'] != NULL){
    $printer -> text("Mesa: " . $data['mesa']['nombre_mesa']);
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
  $printer -> setJustification();
  
  
  $printer->feed();
  $printer->cut();
  $printer->pulse();
  $printer->close();

}


public function Factura(){
  $this->AbreCaja();
}



public function AbreCaja($datos){
  $printer = "CAJA";

  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer->pulse();
  $printer->close();
}









}// class