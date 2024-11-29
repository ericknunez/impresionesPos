 <?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class Gastos {


/*
    Corte de caja
*/


public function GastosPrint($data, $printer){
    $doc = new Documentos();

    $connector = new WindowsPrintConnector($printer);
    $printer = new Printer($connector);
    $printer -> initialize();
    
    $printer -> setFont(Printer::FONT_B);
    
    $printer -> setTextSize(1, 2);
    $printer -> setLineSpacing(80);
    
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    
    $printer -> text("GASTOS");
    
    /* Stuff around with left margin */
    $printer->feed();
    $printer -> text("_______________________________________________________");
    $printer->feed();
    /* Items */
    
    
    
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer -> setEmphasis(false);
    
    
    $total = 0;
    $moneda = "$";
   foreach ($data['gastos'] as $gasto) {
        if($gasto['edo'] == 1){
            $printer -> text($doc->Item( $gasto['nombre'], "", "", $moneda.Helpers::Dinero($gasto['cantidad']))); 
            $total = $total + $gasto['cantidad'];
                }
    }
    
    
    
    $printer -> text("_______________________________________________________");
    $printer->feed();
        
    $printer -> text($doc->DosCol("TOTAL GASTOS: ", 40, $moneda.Helpers::Dinero($total), 10));
    $printer->feed();

    $printer -> text("Fecha: " . date("d-m-Y H:i:s"));
    $printer->feed();
  
    $printer -> text("Cajero: " . $data['cajero']);
    $printer->feed();
    $printer->feed();
    
    $printer->cut();
    $printer->close();
      

}



}// class