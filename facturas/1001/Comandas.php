
 <?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class Comandas {


public function ImprimirComanda($data){
    // $data['panel'] = 0; // maneja el tipo de panel a imprimir

    if ($data['panel'] == 1) {
        $printer = "EOM-POS";
        $panel = "COCINA";
        if ($data['tipo_impresion'] == 2) {
            $this->Comanda($data, $printer, $panel);
        }
        if ($data['tipo_impresion'] == 4) {
            $this->ComandaBorrada($data, $printer, $panel);
        }
    }

    if ($data['panel'] == 2) {
      $printer = "LR2000";
      $panel = "BAR";
      if ($data['tipo_impresion'] == 2) {
          $this->Comanda($data, $printer, $panel);
          // $this->Comanda($data, $printer);
      }
      if ($data['tipo_impresion'] == 4) {
           $this->ComandaBorrada($data, $printer, $panel);
          // $this->ComandaBorrada($data, $printer);
      }
    }

    /// DECLARAR EL PANEL DOS AQUI ABAJO CON LA IMPRESORA Y EL NOMBRE DEL PANEL

}




public function Comanda($data, $printer, $panel){
  $doc = new Documentos();
  
$connector = new WindowsPrintConnector($printer);
$printer = new Printer($connector);
$printer -> initialize();


$printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("COMANDA DE " . $panel);
$printer->feed();
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> selectPrintMode();


//$printer -> setFont(Printer::FONT_B);

$printer -> setTextSize(1, 2);
$printer -> setLineSpacing(20);


$printer -> text("______________________________________________");
$printer->feed();


foreach ($data['productos'] as $producto) {
      $printer -> text($producto['cant'] . " - " . $producto["producto"]); 
      if ($producto['subOpcion']) {
          $i = 0;
          foreach ($producto['subOpcion'] as $opcion) {
            $printer->feed();
            $printer -> text("*" . $opcion["nombre"]); 
            $i++;
          }
      }
$printer->feed();
}

 
$printer -> text("______________________________________________");
$printer->feed();

$printer -> text("ORDEN NUMERO: " . $data['numero_documento']);
$printer->feed();

$printer -> text("Fecha: " . $data['fecha']. "       Hora:" .$data['hora']);
//$printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 20));

$printer->feed();
$printer -> text("Cajero: " . $data['cajero']);
$printer->feed();

// nombre de mesa
if($data['mesa']['nombre_mesa'] != NULL){
  $printer -> text(" " . $data['mesa']['nombre_mesa']);
  $printer->feed();
}

if($data['mesa']['comentario'] != NULL){
  $printer -> text("Comentario: " . $data['mesa']['comentario']);
  $printer->feed();
}

// llevar o comer aqui
if($data['tipo_servicio']  != NULL){
  if ($data['tipo_servicio'] == 3) {
    $tipo = "DOMICILIO";
  } 
  else if ($data['tipo_servicio'] == 2) {
    $tipo = "COMER AQUI";
  } else {
    $tipo = "LLEVAR";
  }

  $printer -> text( $tipo);
   $printer->feed();
}

if($data['tipo_servicio'] == 3){
  $printer -> text("Cliente: " . $data['cliente_nombre']);
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




$printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> setEmphasis(true);
$printer -> text("ELIMINADA");
$printer->feed();
$printer -> setEmphasis(false);
$printer -> text("COMANDA DE " . $panel);
$printer -> setJustification(Printer::JUSTIFY_LEFT);

$printer -> selectPrintMode();
$printer->feed();


$printer -> setFont(Printer::FONT_B);

$printer -> setTextSize(2, 2);
$printer -> setLineSpacing(40);


$printer -> text("______________________________________________");
$printer->feed();


foreach ($data['productos'] as $producto) {
      $printer -> text($producto['cant'] . " - " . $producto["producto"]); 
      if ($producto['subOpcion']) {
          $i = 0;
          foreach ($producto['subOpcion'] as $opcion) {
            $printer->feed();
            $printer -> text("*" . $opcion["nombre"]); 
            $i++;
          }
      }
$printer->feed();
}

 
$printer -> text("______________________________________________");
$printer->feed();

$printer -> text("ORDEN NUMERO: " . $data['numero_documento']);
$printer->feed();

$printer -> text("Fecha: " . $data['fecha']. "       Hora:" .$data['hora']);
//$printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 10));


$printer -> text("Cajero: " . $data['cajero']);
$printer->feed();

// nombre de mesa
// nombre de mesa
if($data['mesa']['nombre_mesa'] != NULL){
  $printer -> text(" " . $data['mesa']['nombre_mesa']);
  $printer->feed();
}

if($data['mesa']['comentario'] != NULL){
  $printer -> text("Comentario: " . $data['mesa']['comentario']);
  $printer->feed();
}

// llevar o comer aqui
if($data['tipo_servicio']  != NULL){
  if ($data['tipo_servicio'] == 3) {
    $tipo = "DOMICILIO";
  } 
  else if ($data['tipo_servicio'] == 2) {
    $tipo = "COMER AQUI";
  } else {
    $tipo = "LLEVAR";
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