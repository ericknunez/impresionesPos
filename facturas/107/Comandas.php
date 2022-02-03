 <?php

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;



class Comandas {


public function ImprimirComanda($data){
    // $data['panel'] = 0; // maneja el tipo de panel a imprimir

    if ($data['panel'] == 1) {
        $printer = "IMPRESORA";
        $panel = "COCINA";
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
    


}






public function ComandaBorrada($data, $printer, $panel){
  $doc = new Documentos();
    



}










}// class