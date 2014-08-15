<?php 	
		/*
		Desenvolvido Pela Web Ideas Soluções Interativas
		Responsável pelo código: Saulo Carius
		Email: saulo@ideasweb.com.br
		Tel: (24) 4104 0664 | 8822 6696
		www.ideasweb.com.br
		*/

		session_start();
		ob_start();

		//verificando se o site está ativo
		include_once('intranet/conexao.php');
		$qr_a = "SELECT ativar FROM ativar";
		$rs_a = mysql_query($qr_a, $conexao);
		$ln_a = mysql_fetch_array($rs_a);
		$ativar = $ln_a['ativar'];
			
		if($ativar == 1){
			header('Location: manutencao.php');
			ob_flush();
			break;
		}
		
		//base
		if($_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
			$base  = 'http://localhost/WI/';
		}else{
			$base  = 'http://webideas.com.br/';
		}
		
		//função para paginação com querystring
        $atual     = (isset($_GET['pg'])) ? $_GET['pg'] : 'inicio';
        $pasta     = 'paginas';
   
		if (substr_count($atual, '/') > 0) {
			$atual     = explode('/', $atual);
			if($atual[1] == '2009' && $atual[2] == '04'){
				header( 'HTTP/1.1 301 Moved Permanently' );
				header( 'Location: http://ideasweb.com.br');
			}
			if($atual[1] == "Neumann\'s-Memorias-Fotograficas"){
				header( 'HTTP/1.1 301 Moved Permanently' );
				header( 'Location: http://ideasweb.com.br/portifolio/neumanns-memorias-fotograficas');
			}
			if($atual[1] == "Led-"){
				header( 'HTTP/1.1 301 Moved Permanently' );
				header( 'Location: http://ideasweb.com.br/portifolio/led-sol-solucoes-energeticas');
			}
			if($atual[1] == "Dunga-Constantino----Artes-em-aerografia"){
				header( 'HTTP/1.1 301 Moved Permanently' );
				header( 'Location: http://ideasweb.com.br/portifolio/dunga-constantino-artes-em-aerografia');
			}
			if($atual[1] == "branding-print" || $atual[1] == 'Branding-Print'){
				header( 'HTTP/1.1 301 Moved Permanently' );
				header( 'Location: '.$base.$atual[0].'/design-e-impressos');
			}
			$ar = array('Mamore-Imoveis','Kapa-Editorial','Malaga-import','Agencia-Multimidia','CoWorking-Petropolis','Armati-Oculos','Tutty-Free','Grafica-Americana','Virginia-Farias','Imobiliaria-Itaipava','Rick-Designer','Lalitta-Modas','Photo-Petropolis','Guia-Panoramico','Mad-Stamp','PSA-Telecom','Cava-de-Vinhos','Atelier-Raquel-Duran','Celso-Couto-Imoveis','Papo-de-Musico','Estilo-do-DVD','Inove-Consultoria-Corporativa','Mad-Stamp-Racing','Mad-Stamp-Racing','Mad-Stamp-Racing','Mad-Stamp-Racing','Mad-Stamp-Racing','Web-Print-Digital','Igreja-Ceifa-Itaipava','Pousadinha-de-Itaipava','Gaiatri-Produtos-Integrais','Instituto-Connections-de-educacao','Petropolis-Jazz-e-Blues','Promove-Arte-e-Eventos','Condominio-Vale-do-Cafe','Pousada-Mirante-de-Itaipava','Pousada-Rancho-da-Ferradura','Golden-Contabil-e-imoveis','Nova-Itaipava-Moveis-e-decoracoes','Mais-Informatica','Led-Sol-Solucoes-Energeticas');
			if(in_array($atual[1],$ar)){
					header( 'HTTP/1.1 301 Moved Permanently' );
					header( 'Location: '.$base.strtolower($atual[0].'/'.$atual[1]));
			}
			$pagina    = file_exists("$pasta/".strtolower($atual[0]).'.php')  ? strtolower($atual[0]) : 'erro';
			$categoria = isset($atual['1']) && !empty($atual['1']) ? $atual['1'] : '';
			
			//verificando se existe algun slug pré definido
			$query = sprintf("SELECT Slug FROM textos_slug WHERE Slug = '%s' AND Pagina = '%s'", $categoria, $pagina);
			$resultado = mysql_query($query, $conexao) or die('Erro ao consultar o Slug: '.mysql_query());
			$qt = mysql_num_rows($resultado);
			if($qt >= 1){
				$Slug = 'true';
			}else{
				$Slug = 'false';
			}
		}   
		else {
			if($atual == 'home'){
				header( 'HTTP/1.1 301 Moved Permanently' );
				header( 'Location: '.$base );
			}
			$ar_pg = array('Inicio','Quem-Somos','Solucoes','Clientes','Portifolio','Contato');
			if(in_array($atual,$ar_pg)){
				header( 'HTTP/1.1 301 Moved Permanently' );
				header( 'Location: '.$base.strtolower($atual) );
			}
			$pagina = (file_exists("$pasta/".strtolower($atual).'.php')) ? strtolower($atual) : 'erro';
			$slug = 'false';
			$categoria = '';
		}
		
	
		//função para buscar os dados da página e SEO
		require_once('intranet/conexao.php');
		$sql_seo = "SELECT * FROM seo WHERE id = 'multimidia'";
		$qry_seo = mysql_query($sql_seo, $conexao);
		$ln_seo  = mysql_fetch_assoc($qry_seo);
		
		if($pagina == 'quem-somos'){
			$Titulo = 'Quem Somos | '.$ln_seo['titulo'];
			$Descricao_pg = 'Conheça a Web Ideas, empresa de comunicação interativa que busca simplicidade em seus projetos!';
			$KeyWords = 'agencia site, agencia web, empresa site, empresa de comunicação, empresa de sistema';
		}
		elseif($pagina == 'solucoes'){
			
			if(isset($categoria) && !empty($categoria)){
				$qr_seo_port = mysql_query(sprintf("SELECT Keysw, Nome, Descricao FROM textos_slug WHERE Slug = '%s'",$categoria),$conexao);
				$ln_seo_port = mysql_fetch_assoc($qr_seo_port);
				
				$Titulo = $ln_seo_port['Nome'].' - Web Ideas';
				$Descricao_pg = $ln_seo_port['Descricao'];
				$KeyWords = $ln_seo_port['Keysw'];
			}
			else{
				$Titulo = 'Soluções | '.$ln_seo['titulo'];
				$Descricao_pg = 'Oferecemos soluções em design e internet totalmente integradas, que vão desde a criação da sua marca até o desenvolvimento do seu site / sistema com ferramentas exclusivas para otimizar o seu negócio';
				$KeyWords = 'criação de sites, sistema de pousadas, sistema de gestão, design de logo, designer gráfico';
			}
		}
		elseif($pagina == 'clientes'){
			$Titulo = 'Clientes | '.$ln_seo['titulo'];
			$Descricao_pg = 'Conheça os nossos clientes e projetos realizados exclusivamente! trabalhamos com empresas de vários setores, mercados e portes, isso demostra nossa versatilidade em produzir.';
			$KeyWords = 'clientes web design, clientes da agência, clientes sites';
		}
		elseif($pagina == 'portifolio'){
			//montando os filtros
			if(isset($categoria) && !empty($categoria)){
				//verificando se é uma categoria
				$qr = sprintf("SELECT Id_Categoria, Nome, KeyWord, Descricao FROM categoria WHERE Slug = '%s'", $categoria);
				$rs = mysql_query($qr, $conexao) or die('Erro ao buscar as categorias: '.mysql_error());
				if(mysql_num_rows($rs) > 0){
					$ln = mysql_fetch_array($rs);
					$Titulo = $ln['Nome'].' - Portifólio - Web Ideas';
					$Descricao_pg = $ln['Descricao'];
					$KeyWords = $ln['KeyWord'];
				}
				
				//se não encontrar a categoria tenta buscar em clientes
				else{
					$qr = sprintf("SELECT Id_Cliente, Nome, KeyWord, Descricao FROM cliente WHERE Slug = '%s'", $categoria);
					$rs = mysql_query($qr, $conexao) or die('Erro ao buscar as categorias: '.mysql_error());
					if(mysql_num_rows($rs) > 0){
						$ln = mysql_fetch_array($rs);
						
						$Titulo = $ln['Nome'].' - Portifólio - Web Ideas';
						$Descricao_pg = $ln['Descricao'];
						$KeyWords = $ln['KeyWord'];
					}
				}
			}else{
				$Titulo = 'Portifólio - Web Ideas';
				$Descricao_pg = 'Confira nosso portifólio com os trabalhos realizados pela nossa equipe!';
				$KeyWords = 'clientes web design, clientes da agência, clientes sites, web sites, folders, logotipos, logo, identidade visual, cartão de visitas';
			}
		}
		elseif($pagina == 'contato'){
			$Titulo = 'Contato | '.$ln_seo['titulo'];
			$Descricao_pg = 'Entre em contato conosco! envie suas idéias e tire suas dúvidas! estamos aguardando você!';
			$KeyWords = 'contato web ideas, web design, contato empresa de site, empresa de site, agência de site, agência web';
		}
		else{
			$Titulo = $ln_seo['titulo'];
			$Descricao_pg = $ln_seo['descricao'];
			$KeyWords = $ln_seo['keywords'];
		}
		
		
		
		//função para retirar acentos
		function retira_acentos($texto){
  			$array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" );
  			$array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c", "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" );
  			return str_replace( $array1, $array2, $texto );
		}
		
		//descobrindo o navegador usado
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		 
		  if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
			$browser_version=$matched[1];
			$browser = 'IE';
		  } elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
			$browser_version=$matched[1];
			$browser = 'Opera';
		  } elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
			$browser_version=$matched[1];
			$browser = 'Firefox';
		  } elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
			$browser_version=$matched[1];
			$browser = 'Chrome';
		  } elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
			$browser_version=$matched[1];
			$browser = 'Safari';
		  } else {
			// browser not recognized!
			$browser_version = 0;
			$browser= 'other';
		  }
		  
		  //caso o navegador do cliente seja ie 7 ou inferior redireciona para mensagem de erro
		  if($browser == 'IE' && $browser_version == '5.0'){
			  header('Location: NaoSuportado.php');
			  ob_flush();
			  break;
		  }if($browser == 'IE' && $browser_version == '6.0'){
			  header('Location: NaoSuportado.php');
			  ob_flush();
			  break;
		  }if($browser == 'IE' && $browser_version == '7.0'){
			  header('Location: NaoSuportado.php');
			  ob_flush();
			  break;
		  }
		  
		   //funcao para enviar email
			 function sendmail($nome,$msg, $assunto, $para){
				$data = date("d/m/Y h:i");
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";//iso-8859-1
				
				// Additional headers
				//$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
				$headers .= 'From: '.$nome.' <contato@webideas.com.br>' . "\r\n";
				//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
				//$headers .= 'Bcc: ligadosnacompra@gmail.com' . "\r\n";
				
				mail($para, $assunto, $msg, $headers);
			}
?>