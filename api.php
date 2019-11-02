<?php 

include 'sistema/system.php';

header('Content-Type: application/json');

$link = DBconnec();

//$table = DBread($link, 'consumo');
if (!isset($_POST['q']) && $_POST['q'] == '') {
	
	echo json_encode(array('error' => true, 'message' => 'Nada encontrado'));
	return false;
}


$q = $_POST['q'];

if ($q == 'producao_acumulada') {
	$hora = $_POST['hora'];
	$casa = $_POST['casa'];
	$dia  = date('d')%2 == '0' ? 1 : 2;

	$prod = DBread($link, 'consumo', "WHERE casa = '".$casa."' AND dia = '".$dia."' AND hora  BETWEEN '07:00:00' AND '".$hora."'");

	$soma = 0;
	if ($prod) {
		for ($i=0; $i < count($prod); $i++) { 
			$soma+=$prod[$i]['valor'];
		}
	}

	echo json_encode(array('prod' => number_format($soma, 2, '.', '')));

}

DBclose($link);