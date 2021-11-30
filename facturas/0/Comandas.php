 <?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class Comandas {


/*
0 Ninguno
1 Ticket
2 Facturas
3 CCF
4 NS
*/

public function ImprimirComanda($data){
    // $data['panel'] = 0; // maneja el tipo de panel a imprimir

    if ($data['panel'] == 1) {
        $printer = "COCINA";
        $panel = "COCINA";
        if ($data['tipo_impresion'] == 2) {
            $this->Comanda($data, $printer, $panel);
        }
        if ($data['tipo_impresion'] == 4) {
            $this->ComandaBorrada($data, $printer, $panel);
        }
    }
}




public function Comanda($data, $printer, $panel){
    $doc = new Documentos();
    
  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer -> initialize();
  
  $printer -> setJustification(Printer::JUSTIFY_LEFT);

  $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
  $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
  $printer -> text("COMANDA DE " . $panel);
  $printer -> selectPrintMode();
  $printer->feed();
  
  
  $printer -> setFont(Printer::FONT_B);
  
  $printer -> setTextSize(1, 2);
  $printer -> setLineSpacing(80);
  
  
  $printer -> text("____________________________________________________________");
  $printer->feed();
  

  foreach ($data['productos'] as $producto) {
        $printer -> text($producto['cant'] . " - " . $producto["producto"]); 

        
        foreach ($data['subOpcion'] as $opcion) {
            $printer -> text("*" . $opcion["nombre"]); 
        }
  }
  
   
  $printer -> text("____________________________________________________________");
  $printer->feed();
  
  $printer -> text("ORDEN NUMERO: " . $data['numero_documento']);
  $printer->feed();
  
  
  $printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 30));
  
  
  $printer -> text("Cajero: " . $data['cajero']);
  $printer->feed();
  
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
  
  // comentarios


  $printer->feed();
  $printer->cut();
  $printer->close();
  

}






public function ComandaBorrada($data, $printer, $panel){
    $doc = new Documentos();
    
  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer -> initialize();
  
  $printer -> setJustification(Printer::JUSTIFY_LEFT);

  $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
  $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
  $printer -> text("COMANDA ELIMINADA DE " . $panel);
  $printer -> selectPrintMode();
  $printer->feed();
  
  
  $printer -> setFont(Printer::FONT_B);
  
  $printer -> setTextSize(1, 2);
  $printer -> setLineSpacing(80);
  
  
  $printer -> text("____________________________________________________________");
  $printer->feed();
  

  foreach ($data['productos'] as $producto) {
        $printer -> text($producto['cant'] . " - " . $producto["producto"]); 

        
        foreach ($data['subOpcion'] as $opcion) {
            $printer -> text("*" . $opcion["nombre"]); 
        }
  }
  
   
  $printer -> text("____________________________________________________________");
  $printer->feed();
  
  $printer -> text("ORDEN NUMERO: " . $data['numero_documento']);
  $printer->feed();
  
  
  $printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 30));
  
  
  $printer -> text("Cajero: " . $data['cajero']);
  $printer->feed();
  
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
  
  // comentarios


  $printer->feed();
  $printer->cut();
  $printer->close();
  

}










}// class