<?php
/**
 * 
 * 
 * The MIT License
 *
 * Copyright (c) 2010 Igor Escobar, Bitly-PHP
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * -----------------------------------------------------------------------------
 * 
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
 * -------------------
 * 
 * @uses  
 *  		http://wiki.github.com/igorescobar/Bitly-PHP/
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
	 * Variável de controle para ser usada quando a API já foi invocada
	 *
	 * @var boolean
	 */
	
	protected $active = false;
	
	/**
	 * Variável de controle de ser usada quando o acesso a API falhar. 
	 *
	 * @var boolean
	 */
	
	protected $fail = false;
	
	/**
	 * Ação que a biblioteca vai executar
	 *
	 * @var string
	 */
	
	protected $action = null;
	
	public function __construct () {
		
		// Forçar o formato sempre em minusculo
		$this->format = strtolower( $this->format );
		
	}
	/**
	 * Função responsável por encurtar a url diretamente no Bit.Ly
	 *
	 * @return void
	 * @author Igor Escobar
	 */
	
	private function action () {
		
		$this->active = false;
		/**
		 * Cria a query que será enviada para a API do Bit.ly
		 */
		
		$params = http_build_query ( array(
			'version'	=> $this->version,
			'login'		=> $this->login,
			'apiKey'	=> $this->api_key,
			'longUrl'	=> $this->url,
			'shortUrl'	=> $this->url,		
			'format'	=> $this->format,
			'callback'	=> $this->callback
		) );
		
		/**
		 * Faz a requisição na API do Bit.ly
		 */
		
		$this->return = $this->get_file_contents ( 'http://api.bit.ly/' . $this->action . '?' . $params );
		
	}
	
	/**
	 * Executa o Shorten do Bit.ly
	 *
	 * @author Igor Escobar
	 */
	
	public function shorten () {
		
		$this->action = 'shorten';
		
		$this->action();
				
	}
	
	/**
	 * Executa o Expand do Bit.ly
	 *
	 * @author Igor Escobar
	 */
	
	public function expand () {
		
		$this->action = 'expand';
		
		$this->action();
		
	}
	
	/**
	 * Executa o Info do Bit.ly
	 *
	 * @author Igor Escobar
	 */
	
	public function info () {
		
		$this->action = 'info';
		
		$this->action();
		
	}
	
	/**
	 * Executa o Stats do Bit.ly
	 *
	 * @author Igor Escobar
	 */
	
	public function stats () {
		
		$this->action = 'stats';
		
		$this->action();
		
	}
	
	/**
	 * Converte os dados da requisição na API
	 *
	 * @return void
	 * @author Igor Escobar
	 */
	
	public function get (){
				
		 if( $this->format == 'json' ) {
			
			if ( !is_object ( $this->return ) ) 
				$this->return = json_decode( $this->return );
			
			if($this->return->statusCode == 'ERROR')
				$this->fail = true;
		
		} 
		
	}
	
	/**
	 * Funcao utilizada para receber qualquer parametro que você queira acessar do retorno da API
	 *
	 * @return void
	 * @author Igor Escobar
	 */
	
	public function getData() {
		
		// Se o metodo da RESTful API foi invocado, então posso prosseguir
		if ( $this->active != false )
			return false;
			
		// Recebe os dados da requisição
		$this->get ();
			
		 if ( $this->format == 'json' ) {

        	if ( $this->fail != true ) {
				    
				/**
				 * Em determinadas ocasioes o bit.ly retorna o array com uma estrutura
				 * diferente. Para isso eu tive que programar uma solução que pega
				 * sempre o primeiro parametro do objeto como o node de partida.
				 */
				
				$ar_object_vars = get_object_vars ( $this->return->results );
				$ar_object_keys = array_keys ( $ar_object_vars );
				$node = $ar_object_keys[0];
				
				/**
				 * Quando utilizamos o Stats do Bitly o retorno possui uma estrutura
				 * diferente de todos os outros metodos. 
				 */
				
				if ( $this->action != 'stats' )
		 			return 	$this->return->results->$node;
				else
					return $this->return->results;
		
			} else {
				
				// Ativa o debug
				$this->debug();
			}
         
		 /**
		  * Se você precisa que o retorno seja em XML irei retornar o XML puro para que você trate
		  * com um XML-Parser de sua preferencia. 
		  */
		
		 } elseif ( $formato == 'xml' ) {
         
		 	return $this->return;
         
		 }
		
	}
	
	/**
	 * Esta função é a responsável por fazer a requisição no servidor.
	 * Por questões de desempenho, sempre vamos utilizar o CURL para fazer
	 * as requisições.
	 * 
	 * Caso você não tenha o CURL instalado, vamos utilizar a função nativa:
	 * file_get_contents().
	 *
	 * @param string $url
	 * @return stream-response
	 * @author Igor Escobar
	 */
	
	private function get_file_contents ( $url ) {
	
		if ( function_exists( 'curl_init' ) ) {

			$curl = curl_init ();
			curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $curl, CURLOPT_URL, $url );
			$contents = curl_exec ( $curl );
			curl_close ( $curl );

			if ( $contents ) 
				return $contents;
			else 
				return false;
				
		} else {
			return file_get_contents ( $url );
		}
		
	}
	
	/**
	 * Se algo estiver dando errado, chame esta função para descobrir
	 * o que a API está retornando.
	 *
	 * @return void
	 * @author Igor Escobar
	 */
	
	public function debug () {
		
		echo "<pre>"; 
		print_r( $this->return ); 
		echo "</pre>";
		
	}
	
}
?>