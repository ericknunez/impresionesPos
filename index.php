<?php
header('Access-Control-Allow-Origin: *');
include_once 'common/Dinero.php';
include_once 'common/Fechas.php';
include_once 'common/Helpers.php';


$file = fopen("archivos/". Helpers::TimeId() . Helpers::HashId() .".json", "w");
fwrite($file, json_encode($_POST));
fclose($file);



// echo json_encode($_POST);

		/*
Los tipos de impresion se distribuiran asi:
1. Pre Cuenta
2. Comanda (produsctos guardados)
3. Factura (Ya cancelada, independiente si es factura, ticket, ccf. etc)
4. Comanda (Productos Eliminados)
*/



if($_POST["identidad"] != NULL){
	require_once ('src/autoload.php'); 
	include_once 'facturas/'.$_POST["identidad"].'/Impresiones.php'; // maneja el documento a imprimir
	include_once 'facturas/'.$_POST["identidad"].'/Documentos.php'; // configuraciones de impresion
		$fac = new Impresiones();

		
	if($_POST["tipo_impresion"] == 1){ /// solo la pre cuenta
	include_once 'facturas/'.$_POST["identidad"].'/Precuenta.php'; // documento de precuenta
		$fac->PreCuenta($_POST); 
	}

	if($_POST["tipo_impresion"] == 2){ /// comanda a cualquier panel
	include_once 'facturas/'.$_POST["identidad"].'/Comandas.php'; // documento
		$fac->Comanda($_POST); 
	}

	if($_POST["tipo_impresion"] == 3){ /// factura, ticket, ccf, etc
	include_once 'facturas/'.$_POST["identidad"].'/Facturas.php'; // documento de precuenta
		$fac->Factura($_POST); 
	}

	if($_POST["tipo_impresion"] == 4){ // / comanda borrda a cualquier panel
	include_once 'facturas/'.$_POST["identidad"].'/Comandas.php'; // documento
		$fac->ComandaBorrada($_POST); 
	}

    if($_POST["tipo_impresion"] == 5){ /// solo abre la caja
	include_once 'facturas/'.$_POST["identidad"].'/Facturas.php'; // documento de precuenta
		$fac->AbrirCaja($_POST); 
	}

	// cortes
	if($_POST["tipo_impresion"] == 10){ /// Corte de Caja
		include_once 'facturas/'.$_POST["identidad"].'/Cortes.php'; // documento de precuenta
			$fac->Corte($_POST); 
	}
	if($_POST["tipo_impresion"] == 11){ /// Corte Z
		include_once 'facturas/'.$_POST["identidad"].'/Cortes.php'; // documento de precuenta
			$fac->CorteZ($_POST); 
	}
	if($_POST["tipo_impresion"] == 12){ /// Reporte Diario
		include_once 'facturas/'.$_POST["identidad"].'/Cortes.php'; // documento de precuenta
			$fac->ReporteDiario($_POST); 
	}
}





// busca todos los archivos en el directorio
$archivos = glob("archivos/*.json");  
  foreach($archivos as $data){ 

  	$archivo = str_replace("archivos/", "", $data);
  	$nombre = str_replace(".json", "", $archivo);

	if ($nombre < (Helpers::TimeId() - 3600) and file_exists($data)) {
		@unlink($data); 
	} 
} // termina busqueda de archivos en la carpeta





?>