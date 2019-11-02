<?php

	//Recuperar Dados do Usuario
	function GetUser($link, $key = null){
		if(!IsLogged($link)){
			return false;
		}else{

			$usuario = UserLog();
			$result = DBread($link, 'users', "WHERE email = '$usuario' AND status = true LIMIT 1");

			if($key == null)
				return $result;
			else{
				if(isset($key))
				 	return $result[0][$key];
				else
					return false;
			}

		}
	}

	//Verifica Usuario Logado
	function StayLogged($link){
		$userKey = UserLog();
		$result  = DBread($link, 'users', "WHERE email = '$userKey' AND status = true");

		if($result)
			return true;
		else
			return false;
	}

	// Retorna a chave unica do usuário
	function GetKey($link, $usuario, $password){
		$dataKey = userVerify($link, $usuario, $password);
		return $dataKey[0]['email'];
	}


	//Verifica Usuario - onde se usa o md5
	function userVerify($link, $usuario, $password, $status = false){

		$result = DBread($link, 'users', "WHERE email = '$usuario' LIMIT 1");

		if($result == false){
			return 'erro1';
		}else{
			$result = DBread($link, 'users', "WHERE email = '$usuario' AND password = '".hash('sha256', $password.$result[0]['salt'])."' LIMIT 1");
			if ($result == false) {
				return 'erro2';
			}else{
				$verificaStatus = $result[0]['status'];
				if($verificaStatus == 0){
					return 'erro3';
				}else{
					return $result;
				}
			}
		}
	}

	//Deletar
	function DBDelete($link, $table, $where = null){
		$table 	= $table;
		$where  = ($where) ? " WHERE {$where}" : null;

		$query = "DELETE FROM {$table}{$where}";
		return DBexecute($link, $query);

	}
	//Alterar valor
	function DBUpDate($link, $table, array $data, $where = null, $insertId = false){
		foreach ($data as $key => $value) {
			$filtro[] = "{$key} = '{$value}'";
		}
		$filtro = implode(", ", $filtro);

		$table 	= $table;
		$campo 	= $data;
		$where  = ($where) ? " WHERE {$where}" : null;

		$query = "UPDATE {$table} SET {$filtro}{$where}";
		return DBexecute($link, $query, $insertId);
	}

	//Selecionar no banco de bados
	function DBread($link, $table, $params = null, $fields = '*'){
		$table 	= $table;
		$params = ($params) ? " {$params}" : null;
		$query 	= "SELECT {$fields} FROM {$table}{$params}";
		$result = DBexecute($link, $query);

		if (!mysqli_num_rows($result)) {
			return false;
		}else{
			while ($res = mysqli_fetch_assoc($result)) {
				$data[] = $res;
			}
			return $data;
		}

	}

	//gravar no banco
	function DBcreate($link, $table, array $data, $insertId = true){
		$table 	= $table;
		$data 	= $data;
		$campos = implode(", ", array_keys($data));
		$valors = "'".implode("', '", $data)."'";

		$query 	= "INSERT INTO {$table} ({$campos}) VALUES ({$valors})";

		return DBexecute($link, $query, $insertId);
	}

	//Execute query -> banco de dados
	function DBexecute($link, $query, $insertId = false){
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
		if($insertId){
			$result = mysqli_insert_id($link);
		}
		return $result;
	}

	function DBescape($link, $dados){
		if(!is_array($dados)){
			$dados = mysqli_escape_string($link, htmlspecialchars($dados, ENT_QUOTES));
		}else{
			$arr = $dados;

			foreach ($arr as $key => $value) {
				$key 		 = mysqli_escape_string($link, htmlspecialchars($key, ENT_QUOTES));
				$value 		 = mysqli_escape_string($link, htmlspecialchars($value, ENT_QUOTES));
				$dados[$key] = $value;
			}
		}
		return $dados;
	}


?>