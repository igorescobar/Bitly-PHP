<?php
/**
 * Classe PHP para utilização da API RESTful do Bit.ly
 * 
 * @author Igor Escobar (blog@igorescobar.com)
 * 
 * ----- ATENCAO -----
 * 
 * Se possível, sempre trabalhar com o formato de retorno em JSON. 
 * O PHP possuí nativamente as funções para tratamento destes dados.
 * Caso você precise do retorno em XML esta library vai retornar o
 * arquivo inteiro em XML e você irá precisar tratá-lo com um XML Parser
 * de sua escolha.  
 *
 * @functions getShortUrl
 * @functions getUserhash
 * @functions debug
 * 
 * @uses  
 * 
 * 		Se você preencheu fixo na API o login e a API-Key, você pode usar assim:
 * 
 * 		$bitly 		= new Bitly();
 *		$bitly->url = 'http://www.google.com/';
 *		$bitly->getShortUrl(); #saída: http://bit.ly/b6R4Uf
 *		$bitly->getUserhash(); #saída: b6R4Uf
 * 		
 * @uses 
 * 
 * 		Se você não preencheu ou prefere informar os dados de Login e API-Key, use assim:
 * 
 * 		$bitly 		= new Bitly();'
 *		$bitly->login	= 'seu_login_na_api_bitly';
 *		$bitly->api_key	= 'sua_api_key';
 *		$bitly->url 	= 'http://www.google.com/';
 * 		$bitly->getShortUrl(); #saída: http://bit.ly/b6R4Uf
 *		$bitly->getUserhash(); #saída: b6R4Uf
 *
 */

class Bitly {
	
	/**
	 * Versão da API que você quer utilizar.
	 *
	 * @var string
	 */
	
	public $version = '2.0.1';
	
	/**
	 * Login que foi cadastrado para acesso na API do Bit.ly
	 *
	 * @var string
	 */
	
	public $login = 'login_bitly';
	
	/**
	 * Chave gerada para acesso à API do Bit.ly
	 *
	 * @var string
	 */
	
	public $api_key = 'api_key';
	
	/**
	 * Formato de saída da API.
	 *
	 * @uses JSON
	 * @uses XML
	 * @var string
	 */
	
	public $format = 'json';
	
	/**
	 * Função de callback. É opcional. Caso queira utilizar, basta preencher o nome da função que quer chamar.
	 *
	 * @var string
	 */
	
	public $callback;
	
	/**
	 * Url que você quer trabalhar utilizando a API do Bit.ly
	 *
	 * @var string
	 */
	
	public $url;
	
	/**
	 * Variável de controle para ser quando a API já foi invocada
	 *
	 * @var boolean
	 */
	
	 protected $active = false;
	

	/**
	 * Função responsável por encurtar a url diretamente no Bit.Ly
	 *
	 * @return void
	 * @author Igor Escobar
	 */
	
	public function shorter() {
		
		/**
		 * Cria a query que será enviada para a API do Bit.ly
		 */
		
		$params = http_build_query ( array(
			'version' 	=> $this->version,
			'login'		=> $this->login,
			'apiKey'	=> $this->api_key,
			'longUrl'   => $this->url,			
			'format'	=> $this->format,
			'callback'  => $this->callback
		) );
		
		/**
		 * Faz a requisição na API do Bit.ly
		 */
		
		$this->return = file_get_contents ( 'http://api.bit.ly/shorten?' . $params );
		$this->active = true;
	}
	
	/**
	 * Faz a requisição para encurtar as URLs e retorna no proprio objeto o retorno.
	 *
	 * @return void
	 * @author Igor Escobar
	 */
	
	public function get() {
		
		// Faz a requisição no servidor do bit.ly
		 if( $this->active == false ) 
			$this->shorter();
         
		 $formato = strtolower ( $this->format );
         
		 if( $formato == 'json' ) {
         
		 	$retorno = json_decode ( $this->return );
		 	$node 	 = $this->url;
         
		 	return 		$retorno->results->$node;
         
		 /**
		  * Se você precisa que o retorno seja em XML irei retornar o XML puro para que você trate
		  * com um XML-Parser de sua preferencia. 
		  */
		
		 } elseif ( $formato == 'xml' ) {
         
		 	return $this->return;
         
		 }
		
	}
	
	/**
	 * Retorna a url encurtada.
	 *
	 * @return String
	 * @author Igor Escobar
	 */
	
	public function getShortUrl() {
		
		$formato = strtolower ( $this->format );
		
		if( $formato == 'json' )
			return $this->get()->shortUrl;
		
		return $this->get();
		
	}
	
	/**
	 * Retorna o Hash do Usuário do link gerado.
	 *
	 * @return void
	 * @author Igor Escobar
	 */
	
	public function getUserHash() {
		
		$formato = strtolower ( $this->format );
		
		if( $formato == 'json' )
			return $this->get()->userHash;
		
		return $this->get();
		
	}
	
	/**
	 * Se algo estiver dando errado, chame esta função para descobrir
	 * o que a API está retornando.
	 *
	 * @return void
	 * @author Igor Escobar
	 */
	
	public function debug() {
		
		echo "<pre>"; 
		print_r( $this->return ); 
		echo "</pre>";
		
	}
	
}

$bitly 		= new Bitly();
$bitly->url 	= 'http://www.google.com/';
echo $bitly->getShortUrl();
?>