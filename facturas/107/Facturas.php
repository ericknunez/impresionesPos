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
    $printer = "EPSON TM-U220 Receipt";
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
    

    $txt1   = "17"; 
    $txt2   = "10";
    $txt3   = "15";
    $txt4   = "8";
    $n1   = "18";
    $n2   = "24";
    $n3   = "21";
    $n4   = "10";
    
    
    $col1 = 0;
    $col2 = 30;
    $col3 = 250;
    $col4 = 330;
    $col5 = 330;
    
    
    
    
    $handle = printer_open($printer);
    printer_set_option($handle, PRINTER_MODE, "RAW");
    
    printer_start_doc($handle, "Mi Documento");
    printer_start_page($handle);
    
    
    $font = printer_create_font("Arial", $txt1, $txt2, PRINTER_FW_NORMAL, false, false, false, 0);
    printer_select_font($handle, $font);
    
    
    //// comienza la factura
    printer_draw_text($handle, "RESTAURANTE Y HOSTAL", 50, $oi);
    $oi=$oi+$n1;
    printer_draw_text($handle, "BUENOS AIRES", 98, $oi);
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "El Paraiso Ataco", 100, $oi);
    $oi=$oi+$n1;
    printer_draw_text($handle, "2a Av. Norte, Finca Buenos Aires", 0, $oi);
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "Tel: 2450-5034", 0, $oi);
    
    
    //$numero1=str_pad($numero, 8, "0", STR_PAD_LEFT);
    //$numero1="000-001-01-$numero1";
    //
    $oi=$oi+$n1;
    printer_draw_text($handle, "CONTRIBUYENTE: ", 0, $oi);
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "SAIDA MARIA PUENTES DE RIVAS", 10, $oi);
    
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "NIT: 0110-240581-101-5", 0, $oi);
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "NRC: 240124-4", 0, $oi);
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "GIRO:Restaurantes y Actividades ", 0, $oi);
    
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "De alojamiento Para Estancias cortas", 0, $oi);
    
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "TICKET NUMERO: " . $data['numero_documento'], 0, $oi);
    
    $oi=$oi+$n1+5;
    printer_draw_text($handle, "Autorizacion: ASC-15041-036616-2021", 0, $oi);
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "Del: 21NA0010000111", 0, $oi);
    $oi=$oi+$n1;
    printer_draw_text($handle, "Al: 21NA00100001115000", 0, $oi);
    
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "Fecha de autorizacion: 08-01-2021", 0, $oi);
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "CAJA: 1", 0, $oi);
    
    ///
    $oi=$oi+$n2;
    printer_draw_text($handle, "____________________________________", 0, $oi);
    $oi=$oi+$n1;
    printer_draw_text($handle, "Cant.", 0, $oi);
    printer_draw_text($handle, "Descripcion", 60, $oi);
    printer_draw_text($handle, "P/U", 240, $oi);
    printer_draw_text($handle, "Total", 320, $oi);
    $oi=$oi+$n1+$n3;
    printer_draw_text($handle, "____________________________________", 0, $oi);
    
    

    foreach ($data['productos'] as $producto) {
     
              $oi=$oi+$n1;
              printer_draw_text($handle, $producto['cant'], $col1, $oi);
              printer_draw_text($handle, $producto['producto'], $col2, $oi);
              printer_draw_text($handle, Helpers::Format($producto["pv"]), $col3, $oi);
              printer_draw_text($handle, Helpers::Format($producto["total"]), $col4, $oi);
    
      } 
    
    
    $oi=$oi+$n3+$n1;
    printer_draw_text($handle, "Sub Total " . $data['tipo_moneda'] . ":", 185, $oi);
    printer_draw_text($handle, Helpers::Format($data['subtotal']), 320, $oi);
    
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "IVA. " . $data['tipo_moneda'] . ":", 175, $oi);
    printer_draw_text($handle, Helpers::Format($data['imp']), 320, $oi);
    
    
    $oi=$oi+$n2;
    printer_draw_text($handle, "Propina " . $data['tipo_moneda'] . ":", 160, $oi);
    printer_draw_text($handle, Helpers::Format($data['propina']),$col4, $oi);
 
    
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "Total " . $data['tipo_moneda'] . ":", 232, $oi);
    printer_draw_text($handle, Helpers::Format($data['total']), 320, $oi);
    
    $oi=$oi+$n2;
    printer_draw_text($handle, "____________________________________", 0, $oi);
    

    
    $oi=$oi+$n1;
    printer_draw_text($handle, "Efectivo " . $data['tipo_moneda'] . ":", 160, $oi);
    printer_draw_text($handle, Helpers::Format($data['efectivo']), 320, $oi);
    

    
    $oi=$oi+$n1;
    printer_draw_text($handle, "Cambio " . $data['tipo_moneda'] . ":", 162, $oi);
    printer_draw_text($handle, Helpers::Format($data['cambio']), 320, $oi);
    
    $oi=$oi+$n2;
    printer_draw_text($handle, "___________________________________", 0, $oi);
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "G=Articulo Gravado", 0, $oi);
    
    
    
    $oi=$oi+$n1;
    printer_draw_text($handle, $data['fecha'], 0, $oi);
    printer_draw_text($handle, $data['hora'], 232, $oi);
    
    
    
    
    ///// crea de nuevo fuente
    $font = printer_create_font("Arial", $txt1, $txt2, PRINTER_FW_NORMAL, false, false, false, 0);
    printer_select_font($handle, $font);
    //////////////////
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "SERIE: MJ04HH0", 0, $oi);
    
    
    $oi=$oi+$n1;
    printer_draw_text($handle, "Cajero: " . $data['cajero'], 25, $oi);
    

    
    
    // $oi=$oi+$n1+$n4;
    // printer_draw_text($handle, "Nuestros precios incluyen IVA", 50, $oi);
    
    $oi=$oi+$n1+$n4;
    printer_draw_text($handle, "GRACIAS POR SU COMPRA...", 50, $oi);
    
    $oi=$oi+$n1+$n2;
    printer_draw_text($handle, ".", NULL, $oi);
    printer_write($handle, chr(27).chr(112).chr(48).chr(55).chr(121)); //enviar pulso
    printer_delete_font($font);
    
    
    
    ///
    printer_end_page($handle);
    printer_end_doc($handle);
    printer_close($handle);
     
    
}


public function Factura(){
  $this->AbreCaja();
}



public function AbreCaja($datos){
  $printer = "EPSON TM-U220 Receipt";

  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector);
  $printer->pulse();
  $printer->close();
}









}// class