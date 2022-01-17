 <?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class Comandas {


public function ImprimirComanda($data){
    // $data['panel'] = 0; // maneja el tipo de panel a imprimir

    if ($data['panel'] == 1) {
        $printer = "PRINTER-COMANDAS";
        $panel = "COCINA";
        if ($data['tipo_impresion'] == 2) {
            $this->Comanda($data, $printer, $panel);
        }
        if ($data['tipo_impresion'] == 4) {
            $this->ComandaBorrada($data, $printer, $panel);
        }
    }

    if ($data['panel'] == 2) {
      $printer = "PRINTER-BAR";
      $panel = "BAR";
      if ($data['tipo_impresion'] == 2) {
          // $this->Comanda($data, $printer, $panel);
          $this->ComandaBar($data, $printer);
      }
      if ($data['tipo_impresion'] == 4) {
          // $this->ComandaBorrada($data, $printer, $panel);
          $this->ComandaBarBorrada($data, $printer);
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
  
  
  $printer -> setFont(Printer::FONT_B);
  
  $printer -> setTextSize(1, 2);
  $printer -> setLineSpacing(80);
  
  
  $printer -> text("________________________________________________________");
  $printer->feed();
  

  foreach ($data['productos'] as $producto) {
        $printer -> text($producto['cant'] . " - " . $producto["producto"]); 
        if ($data['subOpcion']) {
            $i = 0;
            foreach ($data['subOpcion'] as $opcion) {
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
  
  $printer -> setTextSize(1, 2);
  $printer -> setLineSpacing(80);
  
  
  $printer -> text("_______________________________________________________");
  $printer->feed();
  

  foreach ($data['productos'] as $producto) {
        $printer -> text($producto['cant'] . " - " . $producto["producto"]); 
        if ($data['subOpcion']) {
            $i = 0;
            foreach ($data['subOpcion'] as $opcion) {
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







public function ComandaBar($data, $print){

$txt1   = "31"; 
$txt2   = "11";
$txt3   = "0";
$txt4   = "0";
$n1   = "40";
$n2   = "60";
$n3   = "0";
$n4   = "0";


$col1 = 0;
$col2 = 30;
$col3 = 340;
$col4 = 440;
$col5 = 500;
// $print


$handle = printer_open($print);
printer_set_option($handle, PRINTER_MODE, "RAW");

printer_start_doc($handle, "Mi Documento");
printer_start_page($handle);


$font = printer_create_font("Arial", $txt1, $txt2, PRINTER_FW_NORMAL, false, false, false, 0);
printer_select_font($handle, $font);


$oi="60";
printer_draw_text($handle, "COMANDA DE BAR", 80, $oi);



foreach ($data['productos'] as $producto) {
  $oi=$oi+$n1;
  printer_draw_text($handle, $producto['cant'], 0, $oi);
  printer_draw_text($handle, $producto["producto"], 40, $oi);

  if ($data['subOpcion']) {
      $i = 0;
      foreach ($data['subOpcion'] as $opcion) {
        $oi=$oi+$n1;
        printer_draw_text($handle, $opcion["nombre"], 0, $oi);
        $i++;
      }
  }
}
 



$oi=$oi+$n1;
printer_draw_text($handle, "ORDEN NUMERO: " . $data['numero_documento'], 25, $oi);



// llevar o comer aqui
if($data['llevar_aqui'] != NULL){
  if ($data['llevar_aqui'] == 1) {
    $tipo = "LLevar";
  } else {
    $tipo = "Comer Aqui";
  }
}




$oi=$oi+$n2;
if($data['llevar_aqui'] != NULL){
  if ($data['llevar_aqui'] == 1) {
    $tipo = "LLevar";
  } else {
    $tipo = "Comer Aqui";
  }
printer_draw_text($handle, $tipo, 25, $oi);
}
printer_draw_text($handle, "MESA: " . $data['nombre_mesa'], 245, $oi);


$font = printer_create_font("Arial", $txt3, $txt4, PRINTER_FW_NORMAL, false, false, false, 0);
printer_select_font($handle, $font);

$oi=$oi+$n2;
printer_draw_text($handle, $data['fecha'], 0, $oi);
printer_draw_text($handle, $data['hora'], 300, $oi);


$oi=$oi+$n1;
printer_draw_text($handle, "Mesero: " . $data['cajero'], 25, $oi);



$oi=$oi+$n1;
printer_draw_text($handle, ".", 25, $oi);


printer_end_page($handle);
printer_end_doc($handle);
printer_close($handle);

}







public function ComandaBarBorrada($data, $print){

  $txt1   = "31"; 
  $txt2   = "11";
  $txt3   = "0";
  $txt4   = "0";
  $n1   = "40";
  $n2   = "60";
  $n3   = "0";
  $n4   = "0";
  
  
  $col1 = 0;
  $col2 = 30;
  $col3 = 340;
  $col4 = 440;
  $col5 = 500;
  // $print
  
  

$handle = printer_open($print);
printer_set_option($handle, PRINTER_MODE, "RAW");

printer_start_doc($handle, "Mi Documento");
printer_start_page($handle);


$font = printer_create_font("Arial", $txt1, $txt2, PRINTER_FW_NORMAL, false, false, false, 0);
printer_select_font($handle, $font);
  
  
  $oi="60";
  printer_draw_text($handle, "COMANDA DE BAR ELIMINADA", 80, $oi);
  
  
  
  foreach ($data['productos'] as $producto) {
    $oi=$oi+$n1;
    printer_draw_text($handle, $producto['cant'], 0, $oi);
    printer_draw_text($handle, $producto["producto"], 40, $oi);
  
    if ($data['subOpcion']) {
        $i = 0;
        foreach ($data['subOpcion'] as $opcion) {
          $oi=$oi+$n1;
          printer_draw_text($handle, $opcion["nombre"], 0, $oi);
          $i++;
        }
    }
  }
   
  
  
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "ORDEN NUMERO: " . $data['numero_documento'], 25, $oi);
  
  
  
  // llevar o comer aqui
  if($data['llevar_aqui'] != NULL){
    if ($data['llevar_aqui'] == 1) {
      $tipo = "LLevar";
    } else {
      $tipo = "Comer Aqui";
    }
  }
  
  
  
  
  $oi=$oi+$n2;
  if($data['llevar_aqui'] != NULL){
    if ($data['llevar_aqui'] == 1) {
      $tipo = "LLevar";
    } else {
      $tipo = "Comer Aqui";
    }
  printer_draw_text($handle, $tipo, 25, $oi);
  }
  printer_draw_text($handle, "MESA: " . $data['nombre_mesa'], 245, $oi);
  
  
  $font = printer_create_font("Arial", $txt3, $txt4, PRINTER_FW_NORMAL, false, false, false, 0);
  printer_select_font($handle, $font);
  
  $oi=$oi+$n2;
  printer_draw_text($handle, $data['fecha'], 0, $oi);
  printer_draw_text($handle, $data['hora'], 300, $oi);
  
  
  $oi=$oi+$n1;
  printer_draw_text($handle, "Mesero: " . $data['cajero'], 25, $oi);
  
  
  
  $oi=$oi+$n1;
  printer_draw_text($handle, ".", 25, $oi);
  
  
  printer_end_page($handle);
  printer_end_doc($handle);
  printer_close($handle);
  
  }
  

  






}// class