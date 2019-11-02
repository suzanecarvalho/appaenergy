<?php

function unique_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
	sort($temp_array);
    return $temp_array;
}

function paginatorGerente($link, $table, $sql,$maxPosts, $paginaAtual, $url){
		$resultsAll = DBread($link, $table, $sql);
		$totalPost = count($resultsAll);
		$paginas = ceil($totalPost/$maxPosts);

		if ($paginaAtual > $paginas || $totalPost <= $maxPosts) {
		}else{

			$search = (isset($_GET['search']) ? $_GET['search'] : '');
			$getSearch = (empty($search) ? '' : '&&search=');

			echo '<ul class="pagination justify-content-center">';

			//Página Anterior
			if ($paginaAtual >= 2) {
				$pagePrev = $paginaAtual - 1;
				echo '<li><a class="page-link" tabindex="-1" href="?page='.$pagePrev.$getSearch.$search.'">Anterior</a></li>';
			}

			if ($paginaAtual >= 2) {
				$pagePrev = $paginaAtual - 1;
				echo '<li> <a class="page-item"><a class="page-link" href="?page='.$pagePrev.$getSearch.$search.'">'.$pagePrev.'</a></li>';
			}

			echo '<li> <a class="page-item"><a style="background-color: #e9ecef;" class="page-link" href="#">'.$paginaAtual.'</a> </li>
						';
			if($paginaAtual != $paginas){
				$pageNext = $paginaAtual + 1;
				echo '<li> <aclass="page-item"><a class="page-link" href="?page='.$pageNext.$getSearch.$search.'">'.$pageNext.'</a></li>';
			}

			//PROXIMA PAGINA
			if($paginaAtual != $paginas){
				$pageNext = $paginaAtual + 1;
				echo '<li class="page-item"><a class="page-link" href="?page='.$pageNext.$getSearch.$search.'">Próximo</a></li>';
			}

			echo '</ul>';
		}
	}

//PAGINACAO PARA FORUM
	function paginatorForum($link, $table, $sql,$maxPosts, $paginaAtual, $url){
		$resultsAll = DBread($link, $table, $sql);
		$totalPost = count($resultsAll);
		$paginas = ceil($totalPost/$maxPosts);

		if ($paginaAtual > $paginas || $totalPost <= $maxPosts) {
		}else{

			$search = (isset($_GET['search']) ? $_GET['search'] : '');
			$getSearch = (empty($search) ? '' : '&&search=');

			echo '<div class="paginator" style="">
				<div class="paginator-btn">';

			echo '<ul class="ul-nav" style="text-align: left;">
						<li><a href="?page=1'.$getSearch.$search.'"> << </a></li>';
			//Página Anterior
			if ($paginaAtual >= 2) {
				$pagePrev = $paginaAtual - 1;
				echo '<li style="margin-left: 5px;"><a href="?page='.$pagePrev.$getSearch.$search.'">Anterior</a></li>';
			}

			echo '</ul>';

			echo '<ul class="conta-pag">';

			if ($paginaAtual >= 2) {
				$pagePrev = $paginaAtual - 1;
				echo '<li> <a href="?page='.$pagePrev.$getSearch.$search.'">'.$pagePrev.'</a></li>';
			}

			echo '<li class="pag-atual"> <a href="#">'.$paginaAtual.'</a> </li>
						';
			if($paginaAtual != $paginas){
				$pageNext = $paginaAtual + 1;
				echo '<li> <a href="?page='.$pageNext.$getSearch.$search.'">'.$pageNext.'</a></li>';
			}

			echo '</ul>';

			echo '<ul class="ul-nav" style="text-align: right;">';

			//PROXIMA PAGINA
			if($paginaAtual != $paginas){
				$pageNext = $paginaAtual + 1;
				echo '<li style="margin-right: 5px;"><a href="?page='.$pageNext.$getSearch.$search.'">Próximo</a></li>';
			}

			echo '<li><a href="?page='.$paginas.$getSearch.$search.'"> >> </a></li></ul>
					<div class="clear"></div></div></div>';
		}
	}

	function actions($link, $table, $way, $caminho, $foto, $padrao){
	      if (isset($_GET['action']) && $_GET['action'] != '' && isset($_GET['id']) && $_GET['id'] != '') {
		    $action = DBescape($link, $_GET['action']);
		    $id     = DBescape($link, $_GET['id']);

		    switch ($action) {
		        case 0:
		            $up['status'] = 0;
		            if (DBUpDate($link, $table, $up, "id = '$id'")) {
		                load($way);
		            }
		        break;
		        case 1:
		            $up['status'] = 1;
		            if (DBUpDate($link, $table, $up, "id = '$id'")) {
		                load($way);
		            }
		        break;
		        case 2:
		        	if ($caminho != '') {
		        		$nome = DBread($link, $table, "WHERE Id = '".$id."'", $foto);
		        	}
		        	if (DBDelete($link, $table, "Id = '".$id."'")) {
				        if ($caminho != '') {
				        	if (file_exists($caminho.$nome[0][$foto])) {
				        		if ($nome[0][$foto] != $padrao) {
					        		if (unlink($caminho.$nome[0][$foto])) {
					        		}
				        		}
					    	}
				        }
				        load($way);
				    }
		        break;
		    }

		}
	}

	function getPlataforma($link, $user, $url){
		if (isset($_GET['plataforma']) && $_GET['plataforma'] != '') {
			$form['id_plataforma']  = DBescape($link, $_GET['plataforma']);
			if (DBUpDate($link, 'users', $form, "Id = '".$user['Id']."' ")) {
				load($url);
			}
		}
	}

	function getMib($input){
		$con = $input/(1024*1024);

		$con = round($con, 2);
		$exi = explode('.', $con);
		$exi[1] = ($exi[1] == 1) ? $exi[1].'0' : $exi[1];

		return $exi[0].','.$exi[1];
	}

	function getFullHour($input) {
	    $seconds = intval($input); //Converte para inteiro
	    $negative = $seconds < 0; //Verifica se é um valor negativo
	    if ($negative) {
	        $seconds = -$seconds; //Converte o negativo para positivo para poder fazer os calculos
	    }
	    $hours = floor($seconds / 3600);
	    $mins = floor(($seconds - ($hours * 3600)) / 60);
	    $secs = floor($seconds % 60);
	    $sign = $negative ? '-' : ''; //Adiciona o sinal de negativo se necessário
	    return $sign . sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
	}

	function alerta($text){
		echo '<script>alert("'.$text.'");</script>';
	}

	function alertaLoad($text, $url){
		echo '<script>alert("'.$text.'");
	        window.location="'.$url.'";</script>';
	}

	function load($url){
		echo '<script>
	        window.location="'.$url.'";</script>';
	}

	function alertaQuest($text, $url1, $url2){
		echo '<script>
			if(confirm("'.$text.'")){
	        	 window.location="'.$url1.'";
	        }else{
	        	window.location="'.$url2.'";
	        }</script>';
	}

	//SOMENTE PARA O FORUM
	function alertaConfirm($text, $url, $get){

		echo '<script>
			if(confirm("'.$text.'")){
	        	 window.location="'.$url.'?forum='.$get.'&&confirm=sim";
	        }else{
	        	window.location="'.$url.'?forum='.$get.'";
	        }</script>';

	}

	function removerEspacos($str){
		$str = str_replace('\n', "", $str);
		$str = str_replace(" ", "", $str);
		$str = strip_tags($str);
		return $str;
	}

	function fitImagem($caminho, $tamanho){
		echo 'style="background: url('.$caminho.') center center/'.$tamanho.'px no-repeat; background-size: cover;"';
	}

	function printCheckbox($post, $valor){
		if ($post !== false) {
			for ($i=0; $i < count($post); $i++) {
				if ($post[$i] == $valor) {
					return 'checked';
				}
			}
		}
	}

	function printSelect($post, $valor){
		if ($post !== false) {
			if ($post == $valor) {
				return 'selected';
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
	function printRadio($post, $valor){

		if ($post !== false) {
			if ($post == $valor) {
				return 'checked';
			}else{
				return '';
			}
		}else{
			return '';
		}
	}


	function printPost($post, $ind){
		if ($post == null) {
			$post = '';
		}
		if ($ind == 'page') {
			$post = printPostHTML($post);
			return $post;
		}else if($ind == 'campo'){
			$post = printPostTextarea($post);
			return $post;
		}else{
			$post = printPostHTML($post);
			return $post;
		}
	}
	function printPostHTML($post){
		$post = str_replace('\r\n', "<br>", $post);
		$post = str_replace('\\', "", $post);
		return $post;
	}
	function printPostTextarea($post){
		$post = str_replace('\r\n', "&#10;", $post);
		$post = str_replace('\\', "", $post);
		return $post;
	}

	//Pegar a primeira, segunda... n palavra de uma string
	function GetName($nome, $n){
		if ($n === null || $n === false) {
			$n = 1;
		}
		$nome 	= explode(" ", $nome);
		$n 		= $n-1;
		return $nome[$n];
	}
	function GetCampo($key = null){
		if($key == null)
			return $_POST;
		else
			return (isset($_POST[$key])) ? str_replace("'", "", $_POST[$key]) : false;
	}


	//CONVER EM MAIÚSCULA
	 function nomeM($nome){
          $nome = str_replace("-", "1", $nome);
          $nome = strtoupper($nome);
          $nome = str_replace("ç", "Ç", $nome); $nome = str_replace("â", "Â", $nome);
          $nome = str_replace("ã", "Ã", $nome); $nome = str_replace("á", "Á", $nome);
          $nome = str_replace("ê", "Ê", $nome); $nome = str_replace("é", "É", $nome);
          $nome = str_replace("í", "Í", $nome); $nome = str_replace("ó", "Ó", $nome);
          $nome = str_replace("ô", "Ô", $nome); $nome = str_replace("ú", "Ú", $nome);
          $nome = str_replace("1", "-", $nome);
          return $nome;

    }

	//Converte para Slug
	function Slug($name){
		$name = str_replace(" - ", " ", $name);
		$name = str_replace(" ", "-", $name);
		$name = str_replace("à", "a", $name); $name = str_replace("á", "a", $name); $name = str_replace("ã", "a", $name); $name = str_replace("â", "a", $name);
		$name = str_replace("À", "a", $name); $name = str_replace("Á", "a", $name); $name = str_replace("Ã", "a", $name); $name = str_replace("Â", "a", $name);

		$name = str_replace("è", "e", $name); $name = str_replace("é", "e", $name); $name = str_replace("ê", "e", $name);
		$name = str_replace("È", "e", $name); $name = str_replace("É", "e", $name); $name = str_replace("Ê", "e", $name);

		$name = str_replace("ì", "i", $name); $name = str_replace("í", "i", $name); $name = str_replace("î", "i", $name);
		$name = str_replace("Ì", "i", $name); $name = str_replace("Í", "i", $name); $name = str_replace("Î", "i", $name);

		$name = str_replace("ò", "o", $name); $name = str_replace("ó", "o", $name); $name = str_replace("õ", "o", $name); $name = str_replace("ô", "o", $name);
		$name = str_replace("Ò", "o", $name); $name = str_replace("Ó", "o", $name); $name = str_replace("Õ", "o", $name); $name = str_replace("Ô", "o", $name);

		$name = str_replace("ù", "u", $name); $name = str_replace("ú", "u", $name); $name = str_replace("û", "u", $name);
		$name = str_replace("Ù", "u", $name); $name = str_replace("Ú", "u", $name); $name = str_replace("Û", "u", $name);
		$name = str_replace("ç", "c", $name); $name = str_replace("Ç", "c", $name); $name = str_replace(".", "", $name); $name = str_replace(";", "", $name);
		$name = str_replace("[", "", $name);  $name = str_replace("]", "", $name); $name = str_replace("[]", "", $name); $name = str_replace("|", "", $name);
		$name = str_replace("/", "", $name);  $name = str_replace("''", "", $name); $name = str_replace(":", "-", $name);
		$name = str_replace('"', "", $name);  $name = str_replace('""', "", $name);  $name = str_replace(",", "", $name);  $name = str_replace("#", "", $name);
		$name = str_replace("?", "", $name);
		$name = strtolower($name);
		return $name;
	}

	//VERIFICA SE EXTISTE O COOKIE CONECTADO
	function VerifyConectado($link){

		if (isset($_COOKIE['conectado']) && $_COOKIE['conectado'] == '1'  && isset($_COOKIE['email']) && $_COOKIE['email'] != '') {
			$user 		= $_COOKIE['email'];
			$senha		= $_COOKIE['senha'];
			$conectado 	= $_COOKIE['conectado'];

			if(userVerify($link, $user, $senha) == 'erro2'){
				$msg = "Esta conta está Desativada";
			}if(userVerify($link, $user, $senha) == 'erro1'){
				$msg = "Email ou senha estão incorretos";
			}else{
				CreateSession($link, $user, $senha);
			}
		}
	}

	//Valida Login
	function ValidaLogin($link){
		if(isset($_POST['send'])){

			$msg 		= null;
			$user 		= GetPost('email');
			$senha		= GetPost('password');
			$conectado 	= GetPost('conectado');


			if(empty($user)){
				$msg = "Informe seu Nome de Usuário";

			}else if(empty($senha)){
				$msg = "Informe sua Senha!";

			}else{
				if(userVerify($link, $user, $senha) == 'erro3'){
					$msg = "Esta conta está desativada.";
				}else if(userVerify($link, $user, $senha) == 'erro2'){
					$msg = "Email ou senha estão incorretos.";
				}else if(userVerify($link, $user, $senha) == 'erro1'){
					$msg = "Email ou senha estão incorretos.";
				}else{
					if ($conectado == 'on' || $conectado == true || $conectado == '1') {
						setcookie('conectado', true, time() + 3600 * 24 * 30 * 12, '/');
						setcookie('email', $user, time() + 3600 * 24 * 30 * 12, '/');
						setcookie('senha', $senha, time() + 3600 * 24 * 30 * 12, '/');
					}else{
						setcookie('conectado', '', time() - 3600 * 24 * 30 * 12, '/');
						setcookie('email', '', time() - 3600 * 24 * 30 * 12, '/');
						setcookie('senha', '', time() - 3600 * 24 * 30 * 12, '/');
					}

					CreateSession($link, $user, $senha);
				}

			}
			if ($msg != null) {
				alerta($msg);
			}
			// echo ($msg != null) ? '<div class="msg" style="color: red; font-weight: 700; border: 1px solid #ccc; display: block;">'.$msg.'</div>' : null;
		}
	}


	/* ======================================== */
	//PROTECAO
	//Controla Acesso Publico
	function AcessPublic($link){
  		if(IsLogged($link)){
  			if (isset($_GET['link'])) {
  				if (isset($_GET['curso'])) {
  					if (isset($_GET['turma'])) {
  						$_SESSION['turma'] = $_GET['turma'];
  					}
  					$_SESSION['login'] = $_GET['curso'];
  					echo Redirect(URL_PAINEL.$_GET['link']);
  				}else{
  					echo Redirect(URL_PAINEL.$_GET['link']);
  				}
  			}else{
				Redirect(URL_PAINEL);
  			}
		}
	}

	//Controla Acesso Privado
	function AcessPrivate($link){
		if(!IsLogged($link)){
			Redirect(URLBASE);
		}
	}

	/* ======================================== */

	/* ======================================== */
	//SESSÃO

	//Executa Logout
	function DoLogout($link){
		if(isset($_GET['logout'])){
			//MATA OS COOKIES QUE SALVAM SUA SESSÃO
			if (isset($_COOKIE['conectado']) && $_COOKIE['conectado'] == '1') {
				setcookie('conectado', '', time() - 3600 * 24 * 30 * 12, '/');
				setcookie('email', '', time() - 3600 * 24 * 30 * 12, '/');
				setcookie('senha', '', time() - 3600 * 24 * 30 * 12, '/');
			}
			DestroySession($link);
		}

	}

	//Destroi Sessao
	function DestroySession($link){
		unset($_SESSION['user']);
		unset($_SESSION['login']);
		AcessPrivate($link);
	}

	//Cria Sessao
	function CreateSession($link, $user, $password){
		$key = GetKey($link, $user, $password);
		UserLog($key);
		AcessPublic($link);
	}

	//Seta ou Recupera USER LOG
	function UserLog($value = null){
		if($value === null)
			return $_SESSION['user'];
		else
			$_SESSION['user'] = $value;

	}

	//Verifica Login
	function IsLogged($link){
		if(!isset($_SESSION['user']) || empty($_SESSION['user']))
			return false;
		else{
			if(StayLogged($link))
				return true;
			else
				DestroySession($link);
		}
	}

	/* ======================================== */

	//Gera key
	function KeyGeneration(){
		return sha1(rand().time());
	}

	//recuperar POST[]
	function GetPost($key = null){
		if($key == null)
			return $_POST;
		else
			return (isset($_POST[$key])) ? trim($_POST[$key]) : false;
	}

	//redirecinar
	function Redirect($url){
		header("Location: ".$url);
		die();
	}

//Limita Texto
	function texto($texto, $maximo = 200){
		$texto = strip_tags($texto);
		$conta = strlen($texto);

		if($conta <= $maximo){
			return $texto;
		}else{
			$limita = substr($texto, 0, $maximo);
			$espaco	= strrpos($limita, " ");
			$limita = substr($texto, 0, $espaco);
			return $limita.'...';
		}
	}

//Paginador
	//Paginador Categoria
	// function paginatorCategoria($sql,$categoria,$maxPosts, $paginaAtual){
	// 	//PAGINATOR

	// 	$resultsAll = DBread($sql, "WHERE status = true AND categoriaSlug = '$categoria'");

	// 	//Contagem
	// 	$totalPost	= count($resultsAll);

	// 	//Paginas
	// 	$paginas	= ceil($totalPost / $maxPosts);

	// 	if($paginaAtual > $paginas || $totalPost <= $maxPosts){
	// 	}else{
	// 		if(isset($_GET['url'])){
	// 			$GetUrl = $_GET['url'];
	// 		}else{
	// 			$GetUrl = '';
	// 		}
	// 		echo '<div class="pagenav clearfix">';

	// 			//Pagina Inicial
	// 			//
	// 			echo '<a href="'.URLBASE.''.$GetUrl.'?page=1" class="number">Primeira Página</a>';

	// 			//Pagina Alterior
	// 			if($paginaAtual >= 2){
	// 				$pagePrev = $paginaAtual - 1;
	// 				echo '<a href="'.URLBASE.''.$GetUrl.'?page='.$pagePrev.'" class="number">'.$pagePrev.'</a>';
	// 			}

	// 			//Pagina Atual
	// 			echo '<span>'.$paginaAtual.'</span>';

	// 			//Proxima Pagina
	// 			if($paginaAtual != $paginas){
	// 				$pageNext = $paginaAtual + 1;
	// 				echo '<a href="'.URLBASE.''.$GetUrl.'?page='.$pageNext.'" class="number">'.$pageNext.'</a>';
	// 			}

	// 			//Ultima Inicial
	// 			echo '<a href="'.URLBASE.''.$GetUrl.'?page='.$paginas.'" class="number">Ultima Página</a>';

	// 		echo '</div>';
	// 	}
	// }
?>