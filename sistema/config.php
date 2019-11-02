<?php
	$bd = parse_ini_file(__DIR__ . "/configuracao.ini", true);
	$bd = $bd['BANCO_DE_DADOS'];
	
	//LOCAL
	//BANCO DE DADOS
	define('HOSTNAME', $bd['servidor']);
	define('USERNAME', $bd['usuario']);
	define('PASSWORD', $bd['senha']);
	define('DATABASE', $bd['banco']);
	define('PORT', $bd['porta']);
	define('PREFIX', '');
	define('CHARSET', 'utf8');

	define('URLBASE', 'http://localhost/sua_pasta/');

	//URLS
	define('URL_BASE', 'http://localhost/sua_pasta/');
	define('URL_PAINEL', URL_BASE.'painel/');

	//DIRS
	define('DIR_BASE', $_SERVER['DOCUMENT_ROOT'].'/');
	define('DIR_SYSTEM', DIR_BASE.'sistema/');
	define('DIR_IMG', DIR_BASE.'images/');

	//DIRS PARA PDF
	define('DIR_PDF', DIR_BASE.'painel/pdfs/');
	define('DIR_VIDEOS', DIR_BASE.'painel/videos/');
	define('DIR_IMAGES', DIR_BASE.'painel/images/');

	//FILES
	define('FLIE_CONFIG', DIR_SYSTEM.'config.php');
	define('FLIE_HELPERS', DIR_SYSTEM.'helpers.php');
	define('FLIE_DATABASE', DIR_SYSTEM.'database.php');
	define('FILE_CONNECTION', DIR_SYSTEM.'connection.php');


?>