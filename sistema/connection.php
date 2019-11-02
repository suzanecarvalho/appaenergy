<?php
	//Abre conexcao com msqli
	function DBconnec(){
		$link = @mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE,PORT) or die(mysqli_connect_error());
		mysqli_set_charset($link, CHARSET) or die(mysqli_error($link));

		return $link;
	}

	//Fecha conexcao com myqli
	function DBclose($link){
		mysqli_close($link) or die(mysqli_error($link));
	}

	//PARAR USAR

	// $link = DBconnec();

	// DBclose($link);

?>