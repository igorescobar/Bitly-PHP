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
 * PHP Library to use the RESTfull API of Bit.ly
 * 
 * @author Igor Escobar (blog@igorescobar.com)
 * 
 * ----- ATENCAO -----
 * 
 * If possible, always work with the json return. 
 * The PHP has natively the functions to care of this type of data.
 * If you want use a XML return, the library was return the entire response data in the XML format,
 * then you have to use a XML Parser that you wanna use.
 * 
 * -------------------
 * 
 * @uses  
 *  		http://wiki.github.com/igorescobar/Bitly-PHP/
 *
 */

class Bitly {
	
	/**
	 * Api version that you want use.
	 *
	 * @var string
	 */
	
	public $version = '2.0.1';
	
	/**
	 * Bit.ly Login
	 *
	 * @var string
	 */
	
	public $login = 'login_bitly';
	
	/**
	 * Api-key to acess the bit.ky API.
	 *
	 * @var string
	 */
	
	public $api_key = 'api_key';
	
	/**
	 * API out-put format .
	 *
	 * @uses JSON
	 * @uses XML
	 * @var string
	 */
	
	public $format = 'json';
	
	/**
	 * Callback function. It's optional. If you wanna use, fill the function name that you
	 * want call.
	 *
	 * @var string
	 */
	
	public $callback;
	
	/**
	 * Url that you want to use on the bit.ly API.
	 *
	 * @var string
	 */
	
	public $url;
	
	/**
	 * To set when the API was already invoked.
	 *
	 * @var boolean
	 */
	
	protected $active = false;
	
	/**
	 * Just in case the Bit.ly API fail. 
	 *
	 * @var boolean
	 */
	
	protected $fail = false;
	
	/**
	 * Action that the library was execute
	 *
	 * @var string
	 */
	
	protected $action = null;
	
	public function __construct ( $login = null, $api_key = null ) {
		
		// Force the lower-case format
		$this->format = strtolower( $this->format );
		
		/**
		 * If you prefer you also can use the Bitly Library like this: 
		 * $bit = new Bitly('<your_login>', '<your_api_key>');
		 */
		
		$this->login 	= ( !is_null ( $login ) ) ? $login : $this->login;
		$this->api_key 	= ( !is_null ( $login ) ) ? $api_key : $this->api_key;
		
		
	}
	
	/**
	 * Convert data from bit.ly API
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
			else
				$this->fail = false;		
		
		} 
		
	}
	
	/**
	 * Function responsible to read what the class have to do on the bit.ly API
	 * and make that simple.
	 *
	 * @param $action - action to perform on bit.ly
	 * @return void
	 * @author Igor Escobar
	 */
	
	private function action ( $action ) {
		
		$this->action = $action;
		$this->active = false;
		
		/**
		 * Create the packet that was sent to the Bit.ly API
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
		
	 	// Make a requisition to the Bit.ly API		
		$this->return = $this->get_file_contents ( 'http://api.bit.ly/' . $this->action . '?' . $params );
		
		// Take care of the response
		$this->get();
		
	}
	
	/**
	 * Execute the Bit.ly shorten method
	 *
	 * @author Igor Escobar
	 */
	
	public function shorten ( $url = null ) {

		/**
		 * Just i case if you wanna invoke this method like this: 
		 * $bitly->shorten ( '<long_url>' );
		 */
		
		$this->url = ( !is_null( $url ) ) ? $url : $this->url;
		
		// Inform the action type that you need execute
		$this->action('shorten');
		
		/**
		 * Shortcut if you wanna read the shortened url directly when you
		 * print the method. 
		 */
		
		return $this->getData()->shortUrl;
				
	}
	
	/**
	 * Execute the expand method directly on Bit.ly
	 *
	 * @author Igor Escobar
	 */
	
	public function expand ( $url = null ) {
		
		/**
		 * Just i case if you wanna invoke this method like this: 
		 * $bitly->shorten ( '<short_url>' );
		 */
		
		$this->url = ( !is_null( $url ) ) ? $url : $this->url;
		
		// Inform the action type that you need execute
		$this->action('expand');
		
		/**
		 * Shortcut if you wanna read the shortened url directly when you
		 * print the method. 
		 */
		
		return $this->getData()->longUrl;
		
	}
	
	/**
	 * Executa o Info do Bit.ly
	 *
	 * @author Igor Escobar
	 */
	
	public function info ( $url = null ) {
		
		/**
		 * Just i case if you wanna invoke this method like this: 
		 * $bitly->shorten ( '<short_url>' );
		 */
		
		$this->url = ( !is_null( $url ) ) ? $url : $this->url;
		
		// Inform the action type that you need execute
		$this->action('info');
		
		/**
		 * Shortcut if you wanna read the shortened url directly when you
		 * print the method. 
		 */
		
		return $this->getData();
		
		
	}
	
	/**
	 * Executa o Stats do Bit.ly
	 *
	 * @author Igor Escobar
	 */
	
	public function stats ( $url = null ) {
		
		/**
		 * Just i case if you wanna invoke this method like this: 
		 * $bitly->shorten ( '<short_url>' );
		 */
		
		$this->url = ( !is_null( $url ) ) ? $url : $this->url;
		
		// Inform the action type that you need execute
		$this->action('stats');
		
		/**
		 * Shortcut if you wanna read the shortened url directly when you
		 * print the method. 
		 */
		
		return $this->getData();
		
		
	}
	
	/**
	 * Use this function if you wanna read any parameter from the response bit.ly result.
	 *
	 * @return void
	 * @author Igor Escobar
	 */
	
	public function getData() {
		
		// If the RESTful API was invoked, then i will proceed.
		if ( $this->active != false )
			return false;
			
		 if ( $this->format == 'json' ) {

        	if ( $this->fail != true ) {
				    
				/**
				 * In some cases the bit.ly return a diferent array. Then i have
				 * to develop a exit to do this method work correctly on all function
				 * cases. The solution is get always the first parameter from the
				 * 'result' parameter from bit.ly.
				 */
				
				$ar_object_vars = get_object_vars ( $this->return->results );
				$ar_object_keys = array_keys ( $ar_object_vars );
				$node = $ar_object_keys[0];
				
				 // Stats have return the response on a diferent node.
				
				if ( $this->action != 'stats' )
		 			return 	$this->return->results->$node;
				else
					return $this->return->results;
		
			} else {
				
				// Debug is activated
				$this->debug();
			}
         
		 /**
		  * If you need a XML return i always will return the original reponse from bit.ly. 
		  * Then will have to use a XML Parser that you prefer.
		  */
		
		 } elseif ( $formato == 'xml' ) {
         
		 	return $this->return;
         
		 }
		
	}
	
	/**
	 * This functions is responsible to make the requisition on the Bit.ly server.
	 * By benchmarking propouses the class always will use the cURL to make all requisitions.
	 * 
	 * Case you don't have the cURL extensions on your server, then i will use the file_get_contents (slow) to make it for you. 
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
	 * If anything gone wrong, call this functions to discover what the fuck is going on.
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