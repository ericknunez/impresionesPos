<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Request-Method: POST');
header('content-type: "application/json"');

include_once 'common/Dinero.php';
include_once 'common/Fechas.php';
include_once 'common/Helpers.php';


$json = file_get_contents('php://input');

$data = json_decode($json, true);

$file = fopen("archivos/". Helpers::TimeId() . Helpers::HashId() .".json", "w");
fwrite($file, json_encode($data));
fclose($file);


if($data["identidad"] != NULL){
	require_once ('src/autoload.php'); 
	include_once 'facturas/'.$data["identidad"].'/Impresiones.php'; // maneja el documento a imprimir
	include_once 'facturas/'.$data["identidad"].'/Documentos.php'; // configuraciones de impresion
		$fac = new Impresiones();

		
	if($data["tipo_impresion"] == 1){ /// solo la pre cuenta
	include_once 'facturas/'.$data["identidad"].'/Precuenta.php'; // documento de precuenta
		$fac->PreCuenta($data); 
	}

	if($data["tipo_impresion"] == 2){ /// comanda a cualquier panel
	include_once 'facturas/'.$data["identidad"].'/Comandas.php'; // documento
		$fac->Comanda($data); 
	}

	if($data["tipo_impresion"] == 3){ /// factura, ticket, ccf, etc
	include_once 'facturas/'.$data["identidad"].'/Facturas.php'; // documento de precuenta
		$fac->Factura($data); 
	}

	if($data["tipo_impresion"] == 4){ // / comanda borrda a cualquier panel
	include_once 'facturas/'.$data["identidad"].'/Comandas.php'; // documento
		$fac->ComandaBorrada($data); 
	}

    if($data["tipo_impresion"] == 5){ /// solo abre la caja
	include_once 'facturas/'.$data["identidad"].'/Facturas.php'; // documento de precuenta
		$fac->AbrirCaja($data); 
	}

	if($data["tipo_impresion"] == 6){ /// Gastos
		include_once 'facturas/'.$data["identidad"].'/Gastos.php'; 
			$fac->Gastos($data); 
		}

	// cortes
	if($data["tipo_impresion"] == 10){ /// Corte de Caja
		include_once 'facturas/'.$data["identidad"].'/Cortes.php'; // documento de precuenta
			$fac->Corte($data); 
	}
	if($data["tipo_impresion"] == 11){ /// Corte Z
		include_once 'facturas/'.$data["identidad"].'/Cortes.php'; // documento de precuenta
			$fac->CorteZ($data); 
	}
	if($data["tipo_impresion"] == 12){ /// Reporte Diario
		include_once 'facturas/'.$data["identidad"].'/Cortes.php'; // documento de precuenta
			$fac->ReporteDiario($data); 
	}
}





$archivos = glob("archivos/*.json");  
  foreach($archivos as $data){ 

  	$archivo = str_replace("archivos/", "", $data);
  	$nombre = str_replace(".json", "", $archivo);

	if ($nombre < (Helpers::TimeId() - 3600) and file_exists($data)) {
		@unlink($data); 
	} 
} 





?>