                                                                                  
 <?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class Comandas {

public function Pulso($print){

  
  $handle = printer_open($print);
  printer_set_option($handle, PRINTER_MODE, "RAW");
  
  printer_start_doc($handle, "Mi Documento");
  printer_start_page($handle);
  
  printer_write($handle, chr(27).chr(112).chr(48).chr(55).chr(121)); //enviar pulso

  
  printer_end_page($handle);
  printer_end_doc($handle);
  printer_close($handle);
  

}


public function ImprimirComanda($data){
    // $data['panel'] = 0; // maneja el tipo de panel a imprimir

    if ($data['panel'] == 1) {
        $printer = "COCINASS";
        $panel = "COCINA";
        if ($data['tipo_impresion'] == 2) {
            $this->Comanda($data, $printer, $panel);
        }
        if ($data['tipo_impresion'] == 4) {
            $this->ComandaBorrada($data, $printer, $panel);
        }
    }

    if ($data['panel'] == 2) {
      $printer = "BARCHITO";
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

    if ($data['panel'] == 3) {
      $printer = "COCINASS";
      $panel = "PUPUSERIA";
      if ($data['tipo_impresion'] == 2) {
          $this->Comanda($data, $printer, $panel);
      }
      if ($data['tipo_impresion'] == 4) {
          $this->ComandaBorrada($data, $printer, $panel);
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
$printer->feed();
$printer->feed();
// nombre de mesa
if($data['mesa']['nombre_mesa'] != NULL){
  $printer -> text("Mesa: " . $data['mesa']['nombre_mesa']);
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
    $tipo = "";
  }
  $printer -> text( $tipo);
  $printer->feed();
}
$printer -> text("COMANDA DE " . $panel);
$printer->feed();
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> selectPrintMode();


$printer -> setFont(Printer::FONT_B);

$printer -> setTextSize(1, 2);
$printer -> setLineSpacing(80);


$printer -> text("________________________________________________________");
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

 
$printer -> text("________________________________________________________");
$printer->feed();

$printer -> text("ORDEN NUMERO: " . $data['numero_documento']);
$printer->feed();


$printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 20));


$printer -> text("Cajero: " . $data['cajero']);
$printer->feed();

// comentarios
if($data['mesa']['comentario'] != NULL){
  $printer -> text("Comentario: " . $data['mesa']['comentario']);
  $printer->feed();
}

if($data['tipo_servicio'] == 3){
  $printer -> text("Cliente: " . $data['cliente_nombre']);
  $printer->feed();
}



$printer->feed();
$printer->feed();
$printer->feed(5);
$printer->cut(5);
$printer->close();
$this->Pulso($printer);


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
// nombre de mesa
if($data['mesa']['nombre_mesa'] != NULL){
  $printer -> text("Mesa: " . $data['mesa']['nombre_mesa']);
  $printer->feed();
}
// llevar o comer aqui
if($data['llevar_aqui'] != NULL){
  if ($data['tipo_servicio'] == 3 && $data['llevar_aqui'] == 1) {
    $tipo = "DOMICILIO";
  } 
  else if ($data['llevar_aqui'] == 1) {
    $tipo = "LLEVAR";
  } else {
    $tipo = "COMER AQUI";
  }
  $printer -> text( $tipo);
  $printer->feed();
}
$printer->feed();
$printer -> setEmphasis(false);
$printer -> text("COMANDA DE " . $panel);
$printer -> setJustification(Printer::JUSTIFY_LEFT);

$printer -> selectPrintMode();
$printer->feed();


$printer -> setFont(Printer::FONT_B);

$printer -> setTextSize(1, 2);
$printer -> setLineSpacing(80);


$printer -> text("_______________________________________________________");
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

 
$printer -> text("_______________________________________________________");
$printer->feed();

$printer -> text("ORDEN NUMERO: " . $data['numero_documento']);
$printer->feed();


$printer -> text($doc->DosCol($data['fecha'], 30, $data['hora'], 20));


$printer -> text("Cajero: " . $data['cajero']);
$printer->feed();

// comentarios
if($data['mesa']['comentario'] != NULL){
  $printer -> text("Comentario: " . $data['mesa']['comentario']);
  $printer->feed();
}

if($data['tipo_servicio'] == 3){
  $printer -> text("Cliente: " . $data['cliente_nombre']);
  $printer->feed();
}

$printer->feed();
$printer->cut();
$printer->close();



}


  






}// class