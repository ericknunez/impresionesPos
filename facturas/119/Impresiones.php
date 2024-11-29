 <?php  

class Impresiones {


public function Factura($data){
    $doc = new Facturas();
    $doc->ImprimirFactura($data);
}

// Solo peara la precuenta del cliente en termico
public function PreCuenta($data){
    $doc = new Precuenta();
    $printer = "TICKET";
    $doc->PrecuentaPrint($data, $printer);
}

// comandas para cocina a diferente panel
public function Comanda($data){
    $doc = new Comandas();
    $doc->ImprimirComanda($data);
}

// comandas borradas para la cocina de diferente panel
public function ComandaBorrada($data){
    $doc = new Comandas();
    $doc->ImprimirComanda($data);
}

// solo abrir caja segun el cajero
public function AbrirCaja($data){
    $doc = new Facturas();
    $doc->AbreCaja($data);
}


// Corte de Cja
public function Corte($data){
    $doc = new CorteDeCaja();
    $printer = "TICKET";
    $doc->CortePrint($data, $printer);
}

public function CorteZ($data){
    $doc = new CorteDeCaja();
    $printer = "TICKET";
    $doc->CorteZ($data, $printer);
}

// Reporte
public function ReporteDiario($data){
    $doc = new CorteDeCaja();
    $printer = "TICKET";
    $doc->ReporteDiario($data, $printer);
}



}// class