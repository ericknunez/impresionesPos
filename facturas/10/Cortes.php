 <?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class CorteDeCaja {


/*
    Corte de caja
*/


public function CortePrint($data, $printer){
    $doc = new Documentos();

    $connector = new WindowsPrintConnector($printer);
    $printer = new Printer($connector);
    $printer -> initialize();
    
    $printer -> setFont(Printer::FONT_B);
    
    $printer -> setTextSize(1, 2);
    $printer -> setLineSpacing(80);
    
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    
    $printer -> text("RESUMEN DE CORTE DE CAJA");
    
    
    $printer -> text("_______________________________________________________");
    $printer->feed();
    
    $printer -> text($doc->DosCol("CAJERO: ", 40, $data['cajero'], 10));
    $printer -> text($doc->DosCol("CAJA: ", 40, $data['caja'], 10));

       
    
    $printer -> text($doc->DosCol("TOTAL DE VENTA: ", 40, Helpers::Dinero($data['total_venta']), 10));
    


      
    $printer -> text("_______________________________________________________");
    $printer->feed();
    
    

    
    
    
    $printer -> text($doc->DosCol("GASTOS REGISTRADOS: ", 40, Helpers::Dinero($data['gastos']), 10));
    
    
    $printer -> text($doc->DosCol("REMESAS: ", 40, Helpers::Dinero($data['remesas']), 10));


    $printer -> text($doc->DosCol("ABONOS: ", 40, Helpers::Dinero($data['abonos']), 10));
    
    
    $printer -> text("__________________________________________________");
    $printer->feed();
    
    
    
    
    $printer -> text($doc->DosCol("DINERO EN APERTURA: ", 40, Helpers::Dinero($data['efectivo_inicial']), 10));
    
    $printer -> text($doc->DosCol("EFECTIVO INGRESADO: ", 40, Helpers::Dinero($data['efectivo_final']), 10));

    $printer -> text($doc->DosCol("EFECTIVO DEBIDO: ", 40, Helpers::Dinero($data['total_efectivo']), 10));

    
    
    $printer -> text($doc->DosCol("DIFERENCIA: ", 40, Helpers::Dinero($data['diferencia']), 10));
    
    $printer -> text("_______________________________________________________");
    $printer->feed();
    
    
    
    
    
    $printer->feed();
    $printer->cut();
    $printer->close();
      

}















}// class