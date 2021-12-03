<?php //Ejemplo aprenderaprogramar.com, archivo escribir.php
header('Access-Control-Allow-Origin: *');
include_once 'common/Dinero.php';
include_once 'common/Fechas.php';
include_once 'common/Helpers.php';


$file = fopen("archivo.txt", "w");
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
}










?>