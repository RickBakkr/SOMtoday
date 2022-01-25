<?php
namespace Curl;

class Curl {
	public bool $error = false;
	public string $errorMessage = "";
	public int $errorCode = 200;
	public object $response;

	protected $_useragent = 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
	protected $_url;
	protected $_followlocation = true;
	protected $_timeout = 0;
	protected $_maxRedirects = 10;
	protected $_cookieFileLocation = './cookie.txt';
	protected $_post;
	protected $_postFields;
	protected $_referer = "http://www.google.com";
	protected $_headers = array('Expect:', 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0');

	protected $_session;
	protected $_webpage;
	protected $_includeHeader = false;
	protected $_noBody = false;
	protected $_status;
	protected $_binaryTransfer = false;
	public $authentication = 0;
	public $auth_name= '';
	public $auth_pass= '';

	public function useAuth(bool $use): void{
		$this->authentication = 0;
		if($use == true) $this->authentication = 1;
	}

	public function setName(string $name): void{
		$this->auth_name = $name;
	}
	public function setPass(string $pass): void{
		$this->auth_pass = $pass;
	}

	public function __construct(string $url="https://google.com"){
		$this->_url = $url;
		$this->_cookieFileLocation = dirname(__FILE__).'/cookie.txt';
	}

	public function setReferer(string $referer): void{
		$this->_referer = $referer;
	}

	public function setCookieFileLocation(string $path): void {
		$this->_cookieFileLocation = $path;
	}

	public function setPost ($postFields): void {
		$this->_post = true;
		$postFields = (is_array($postFields)) ? http_build_query($postFields) : $postFields;
		$this->_postFields = $postFields;
	}

	public function setUserAgent(string $userAgent): void {
		$this->_useragent = $userAgent;
	}

	public function setHeaders(array $headers): void {
		$i_header = $headers;
		$headers = [];
		foreach ($i_header as $param => $value) {
			$headers[] = "$param: $value";
		}
		$headers[] = 'Expect:';
		$headers[] = "User-Agent: {$this->_useragent}";
		$this->_headers = $headers;
	}

	public function createCurl(string $url = 'nul') {
		if($url != 'nul') $this->_url = $url;

		$s = curl_init();

		curl_setopt($s,CURLOPT_URL,$this->_url);
		curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($s,CURLOPT_ENCODING,'');
		curl_setopt($s,CURLOPT_MAXREDIRS,$this->_maxRedirects);
		curl_setopt($s,CURLOPT_TIMEOUT,$this->_timeout);
		curl_setopt($s,CURLOPT_FOLLOWLOCATION,$this->_followlocation);
		curl_setopt($s,CURLOPT_COOKIEJAR,$this->_cookieFileLocation);
		curl_setopt($s,CURLOPT_COOKIEFILE,$this->_cookieFileLocation);
		curl_setopt($s,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
		curl_setopt($s,CURLOPT_HTTPHEADER,$this->_headers);

		if($this->authentication == 1) curl_setopt($s, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
		if($this->_post) {
			curl_setopt($s,CURLOPT_POST,true);
			curl_setopt($s,CURLOPT_POSTFIELDS,$this->_postFields);
			curl_setopt($s,CURLOPT_CUSTOMREQUEST,"POST");
		}
		if($this->_includeHeader) curl_setopt($s,CURLOPT_HEADER,true);
		if($this->_noBody) curl_setopt($s,CURLOPT_NOBODY,true);
		curl_setopt($s,CURLOPT_USERAGENT,$this->_useragent);
		curl_setopt($s,CURLOPT_REFERER,$this->_referer);

		$this->_webpage = curl_exec($s);
		$this->_status = curl_getinfo($s,CURLINFO_HTTP_CODE);

		$this->error = curl_errno($s);
		$this->errorMessage = curl_error($s);
		if ($this->error == 1){
			throw new Exception("cURL Error: {$this->_url}: {$this->_status}: {$this->errorMessage}");
		}

		curl_close($s);
	}

	public function getHttpStatus() {
		return $this->_status;
	}

	public function __tostring(){
		return $this->_webpage;
	}
}
?>
