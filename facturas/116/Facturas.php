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
        $printer = "TICKET";
        $this->Ticket($data, $printer);
    }
    if ($data['documento_factura'] == 2) {
        $printer = "EPSON LX-350";
        $this->Factura($data, $printer);
    }
	if ($data['documento_factura'] == 3) {
        $printer = "EPSON LX-350";
        $this->CreditoFiscal($data, $printer);
    }
}


public function Ninguno(){
  $this->AbreCaja();
}



public function Ticket($data, $printer){
    $doc = new Documentos();
    
   // $img  = "C:/laragon/www/impresiones/facturas/109/img/logo.jpg";
  
  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer -> initialize();
  
  $printer->pulse();

  $printer -> setFont(Printer::FONT_B);
  
  $printer -> setTextSize(1, 2);
  $printer -> setLineSpacing(80);
  
  
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
  //$logo = EscposImage::load($img, false);
  //$printer->bitImage($logo);
  $printer -> setJustification(Printer::JUSTIFY_CENTER);
//   $printer->text($data['empresa_nombre']);

  $printer->text("LA RINCONCHITA");
  $printer->feed();
  
  $printer->text("Avenida 2 de Abril Norte y 6a calle poniente #14");
  $printer->feed();

  $printer->text("Chalchuapa");
  $printer->feed();

  $printer->text("TELEFONO: 7547-8651 o 2408-0653" . $data['empresa_telefono']);

  
  $printer->feed();
  $printer->text("TICKET NUMERO: " . $data['no_factura']);

  
  
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


public function Factura($data, $print){
  $doc = new Documentos();

  $txt1   = "10"; 
  $txt2   = $txt1/1.6;
  $txt3   = "15";
  $txt4   = "8";
  $n1   = "15";
  $n2   = "24";
  $n3   = "21";
  $n4   = "10";
  
  
  
  $handle = printer_open($print);
  printer_set_option($handle, PRINTER_MODE, "RAW");
  
  printer_start_doc($handle, "Mi Documento");
  printer_start_page($handle);
  
  
  $font = printer_create_font("Verdana", $txt1, $txt2, PRINTER_FW_NORMAL, false, false, false, 0);
  printer_select_font($handle, $font);

  $oi=60;
  $oi=$oi+$n1;
  printer_draw_text($handle, $data['fecha'], 450, $oi+10);
  $oi=$oi+$n1+$n1;
  printer_draw_text($handle, strtoupper($data['mesa']['nombre_mesa']), 90, $oi-5);
  $oi=$oi+$n1+$n1;
  printer_draw_text($handle, strtoupper($data['cliente']['direccion']), 90, $oi);
  $oi=$oi+$n1+$n1;
  printer_draw_text($handle, $data['cliente']['documento'], 145, $oi);
   
  $oi=170;
  foreach ($data['productos'] as $producto) {
    
    $letras = strtoupper($producto["producto"]);
    $primertexto = substr($letras, 0, 34);
    $segundotexto = substr($letras, 34, 200 );

            $oi=$oi+$n1;
            printer_draw_text($handle, $producto["cant"], 100, $oi);
            printer_draw_text($handle, $primertexto, 140, $oi);
            printer_draw_text($handle, Helpers::Format( $producto['pv']), 380, $oi);
            printer_draw_text($handle, Helpers::Format($producto["total"]), 550, $oi);
            if (!empty($segundotexto)) {
              $oi=$oi+$n1;
              printer_draw_text($handle, $segundotexto, 140, $oi);
             }
      
    }

  $oi=420;
  $oi=$oi+$n3+$n1;
  printer_draw_text($handle, Dinero::DineroEscrito($data['total']+$data['propina_cant']), 70, $oi+$n1+5);
  printer_draw_text($handle, Helpers::Format($data['total']), 550, $oi+$n1+5);

  
  $oi=$oi+$n1+$n1+$n1+$n1+$n1+$n1+$n1;
  printer_draw_text($handle, Helpers::Format($data['propina_cant']), 550, $oi-$n1);
  printer_draw_text($handle, Helpers::Format($data['total']+$data['propina_cant']), 550, $oi);
  

  
  
  if ($data['caja'] == 1) {
  printer_write($handle, chr(27).chr(112).chr(48).chr(55).chr(121)); //enviar pulso
  }



  
  printer_end_page($handle);
  printer_end_doc($handle);
  printer_close($handle);
  

}



public function CreditoFiscal($data, $print){
  $doc = new Documentos();

  $txt1   = "15"; 
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
  
  $oi=100;
  $oi=$oi+$n1;
  printer_draw_text($handle, $data['fecha'], 380, $oi+$n1+$n1);
  $oi=$oi+$n1+$n1;
  printer_draw_text($handle, $data['cliente']['cliente'], 90, $oi);
  $oi=$oi+$n1+$n1;
  printer_draw_text($handle, $data['cliente']['direccion'], 90, $oi);
  $oi=$oi+$n1+$n1;
  printer_draw_text($handle, $data['cliente']['documento'], 130, $oi);
   
  $oi=300;
  foreach ($data['productos'] as $producto) {
  
    
  
            $oi=$oi+$n1;
            printer_draw_text($handle, $producto["cant"], 100, $oi);
            printer_draw_text($handle, $producto["producto"], 140, $oi);
            printer_draw_text($handle, $producto['pv'], 330, $oi);
            printer_draw_text($handle, $producto["total"], 475, $oi);
  

    }

  $oi=615;
  $oi=$oi+$n3+$n1;
  printer_draw_text($handle, Dinero::DineroEscrito($data['total']+$data['propina_cant']), 90, $oi);
  printer_draw_text($handle, Helpers::Format($data['subtotal']), 475, $oi);
  printer_draw_text($handle, Helpers::Format($data['impuestos']), 475, $oi+$n1);
  printer_draw_text($handle, Helpers::Format($data['total']), 475, $oi+$n1+$n1);

  
  $oi=$oi+$n1+$n1+$n1+$n1+$n1+$n1+$n1+$n1+$n1+$n1;
  printer_draw_text($handle, Helpers::Format($data['propina_cant']), 475, $oi-$n1);
  printer_draw_text($handle, Helpers::Format($data['total']+$data['propina_cant']), 475, $oi);
  

  
  
  if ($data['caja'] == 1) {
  printer_write($handle, chr(27).chr(112).chr(48).chr(55).chr(121)); //enviar pulso
  }



  
  printer_end_page($handle);
  printer_end_doc($handle);
  printer_close($handle);
  

}



public function AbreCaja($datos){
  $printer = "TICKET";

  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer->pulse();
  $printer->close();
}









}// class