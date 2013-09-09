<?php 

	/**
	* Méthode de debugage
	* 
	* @param var  la variable à analyser
	* @param html bool affiche la sortie au format html ou modifie les balises html par leur équivalent unicode
	* @param return bool affiche ou non le résultat qui peut ainsi être récupéré dans une variable
	* @param sub integer affiche le résultat entre des balises pre
	* @param way string sens de lecture du tableau debug_backtrace
	* @return string le résultat
	* 
	* @author unknown, JeromeJ, Cyril MAGUIRE
	*/
	function explain($var = null, $html = false, $return = false, $sub = 0, $way = 'normal'){
		$debug = debug_backtrace();
		if ($var === null) {
			$var = array('_POST' => $_POST, '_GET' => $_GET, '_COOKIE' => $_COOKIE, '_SERVER' => $_SERVER);
			if (isset($_SESSION)) {
				$var['_SESSION'] = $_SESSION;
			}
			if (isset($_GLOBAL)) {
				$var['_GLOBAL'] = $_GLOBAL;
			}
		}

			// Recherche du nom de la variable passée en paramètre
			if ($way == 'normal') {
				$d = $debug;
			} else {
				$d = array_reverse($debug);
			}
			
			$file = fopen( $d[0]['file'], 'r' );
			$line = 0;
			$calledVar = '';
			while ( ( $row = fgets( $file ) ) !== false ) {
				if ( ++$line == $d[0]['line'] ) {
					if ($way == 'normal') {
						preg_match('/explain\((.*)\);$/', $row, $match);
						if (isset($match[1])) $calledVar = $match[1];
					} else {
						preg_match('/e\((.*)\);$/', $row, $match);
						if (isset($match[1])) $calledVar = $match[1];
					}
					break;
				}
			}
			fclose( $file );
		if($sub == 0) {
			$r = '<pre style="border: 1px solid #e3af43; background-color: #f8edd5; padding: 10px; overflow: auto;">';
			$r .= '<p>Appel du debug dans le fichier <br/>"<strong>'.$debug[0]['file'].'</strong>" ligne '.$debug[0]['line'].'</p>
			<h2 style="margin-top:-30px;">Traces&nbsp;<span id="expfolderclose" onclick="document.getElementById(\'id-debug-backtrace\').className=\'expshow\';document.getElementById(\'expfolderclose\').className=\'expclose\';document.getElementById(\'expfolderopen\').className=\'expshow\';" style="font-size:1px;cursor:pointer;">&#9654;</span>&nbsp;<span id="expfolderopen" onclick="document.getElementById(\'id-debug-backtrace\').className=\'expclose\';document.getElementById(\'expfolderopen\').className=\'expclose\';document.getElementById(\'expfolderclose\').className=\'expshow\';" class="expclose" style="font-size:1px;cursor:pointer;">&#9660;</span></h2>
			<ol style="margin-top:-30px" id="id-debug-backtrace" class="expclose">';
			foreach ($debug as $k => $v) {
				if ($k>0 && isset($v['file']) && isset($v['line']) ) {
					$r .= '<li><strong>'.$v['file'].'</strong> ligne '.$v['line'].'</li>';
				}
			}
			$r .= '</ol><br/><strong><span style="color:#8bb5eb;">'.$calledVar.'</span></strong> = ';
		}else{
			$r = '';
		}
		$type = htmlentities(gettype($var));
		switch ($type) {
			case 'NULL':$r .= '<em style="color: #0000a0; font-weight: bold;">NULL</em>';break;
			case 'boolean':if($var) $r .= '<span style="color: #327333; font-weight: bold;">TRUE</span>';
			else $r .= '<span style="color: #327333; font-weight: bold;">FALSE</span>';break;
			case 'integer':$r .= '<span style="color: red; font-weight: bold;">'.$var.'</span>';break;
			case 'double':$r .= '<span style="color: #e8008d; font-weight: bold;">'.$var.'</span>';break;
			case 'string':$r .= '<span style="color: #e84a00;">\''.($html ? $var:htmlentities($var)).'\'</span>';break;
			case 'array':$r .= 'Tableau('.count($var).')'."\r\n".str_repeat("\t", $sub).'{'."\r\n";
				foreach($var AS $k => $e) $r .= str_repeat("\t", $sub+1).'['.explain($k, $html, true, $sub+1).'] =&gt; '.($k === 'GLOBALS' ? '* RECURSION *':explain($e, $html, true, $sub+1, $var))."\r\n";
				$r .= str_repeat("\t", $sub).'}';
			break;
			case 'object':$r .= 'Objet «<strong>'.htmlentities(get_class($var)).'</strong>»'."\r\n".str_repeat("\t", $sub).'{'."\r\n";
				$prop = get_object_vars($var);
				foreach($prop AS $name => $val){
					if($name == 'privates_variables'){ # Hack (PS: il existe des biblio interne permettant d'étuexitr une classe)
						for($i = 0, $count = count($var->privates_variables); $i < $count; $i++) $r .= str_repeat("\t", $sub+1).'<strong>'.htmlentities($get = $var->privates_variables[$i]).'</strong> =&gt; '.explain($var->$get, $html, true, $sub+1)."\r\n";
						continue;
					}

					$r .= str_repeat("\t", $sub+1).'<strong>'.htmlentities($name).'</strong> =&gt; '.explain($val, $html, true, $sub+1)."\r\n";
				}
				$r .= str_repeat("\t", $sub).'}';break;
			default:$r .= 'Variable de type <strong>'.$type.'</strong>.';break;
		}
		if($sub == 0) $r .= '</pre>';
		if($return) return $r;
		else echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><style type="css"> .expshow{display:block;border:1px solid red;} .expclose{display:none;} pre {white-space: pre;white-space: pre-wrap;white-space: pre-line;white-space: -pre-wrap;white-space: -o-pre-wrap;white-space: -moz-pre-wrap;white-space: -hp-pre-wrap;word-wrap: break-word;}</style></head><body><div style="font-family: Helvetica, Arial, sans-serif;">'.$r.'</div></body></html>';
	}

	// Raccourcis debug
	function e($var=null, $html = false, $return = false, $sub = 0, $way = 'reverse') {
		explain($var, $html, $return, $sub, $way);
	}
	function ed($var=null, $html = false, $return = false, $sub = 0, $way = 'reverse') {
		explain($var, $html, $return, $sub, $way);
		exit();
	}
?>
