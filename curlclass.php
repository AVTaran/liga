<?

#################################################################
$script_url = trim($_GET['url']); 
//$url_domain = 'http://vkonliner.ru/';
$url_domain = 'http://www.fantasyland.ru/';
#################################################################


class cURL { 
	var $headers; 
	var $user_agent; 
	var $compression; 
	var $cookie_file; 
	var $proxy; 

	var $loadheader;
	var $loadbody;

	function cURL ($cookies=TRUE, $cookie='cookies.txt', $compression='gzip', $proxy='') { 
		$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg'; 
		$this->headers[] = 'Connection: Keep-Alive'; 
		$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8'; 
		$this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)'; 
		$this->compression=$compression; 
		$this->proxy=$proxy; 
		$this->cookies=$cookies; 
		if ($this->cookies == TRUE) {
			$this->cookie($cookie); 
		}
	}


	function cookie($cookie_file) { 
		if (file_exists($cookie_file)) { 
			$this->cookie_file=$cookie_file; 
		} else { 
			fopen($cookie_file,'w') or $this->error('Кукисы не могут быть открыты. Убедитесь в правах доступа'); 
			$this->cookie_file=$cookie_file; 
			fclose($this->$cookie_file); 
		}
	}


	function get($url) { 
		$process = curl_init($url); 
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers); 
		curl_setopt($process, CURLOPT_HEADER, 0); 
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent); 
		if ($this->cookies == TRUE) {
			curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file); 
			curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file); 
		}
		curl_setopt($process,CURLOPT_ENCODING , $this->compression); 
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		if ($this->proxy) {
			curl_setopt($process, CURLOPT_PROXY, $this->proxy); 
		}
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
		$return = curl_exec($process); 

		$this->loadheader = substr($return, 0, curl_getinfo($process, CURLINFO_HEADER_SIZE));
		$this->loadbody = substr($return, curl_getinfo($process, CURLINFO_HEADER_SIZE));

		curl_close($process); 
		return $return; 
	}


	function post ($url, $data) { 
		$process = curl_init($url); 
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers); 
		curl_setopt($process, CURLOPT_HEADER, 1); 
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent); 
		if ($this->cookies == TRUE) {
			curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file); 
			curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file); 
		}
		curl_setopt($process, CURLOPT_ENCODING , $this->compression); 
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		if ($this->proxy) {
			curl_setopt($process, CURLOPT_PROXY, $this->proxy); 
		}
		curl_setopt($process, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($process, CURLOPT_POST, 1); 
		$return = curl_exec($process); 

		$this->loadheader = substr($return, 0, curl_getinfo($process, CURLINFO_HEADER_SIZE));
		$this->loadbody = substr($return, curl_getinfo($process, CURLINFO_HEADER_SIZE));

		curl_close($process); 
		return $return; 
	} 


	function error($error) { 
		echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>"; 
		die; 
	} 
}


/*
$cc = new cURL(); 

$page = $cc->post('http://www.cron-job.org/cgi-bin/cronweb','action=login&mail=(здесь ваша почта)&pass=(здесь ваш пароль)'); 


function get_cid ($post_page) {
	if (preg_match_all('/content="0;URL=(.*?)\\"/s', $post_page, $result_html)) {
		foreach($result_html[0] as $element) {
			$element = preg_replace("/\"/","",$element);
			$element = strstr( $element,"sid");
		}
	}

	return $element;
}

$sid = get_cid ($page);
$twomin='&i=00&i=02&i=04&i=06&i=08&i=10&i=12&i=14&i=16&i=18&i=20&i=22&i=24&i=26&i=28&i=30&i=32&i=34&i=36&i=38&i=40&i=42&i=44&i=46&i=48&i=50&i=52&i=54&i=56&i=58';
$link='action=add_cron&'.$sid.'&url='.$cron_job_url.'&aktiv=1&d=01&d=02&d=03&d=04&d=05&d=06&d=07&d=08&d=09&d=10&d=11&d=12&d=13&d=14&d=15&d=16&d=17&d=18&d=19&d=20&d=21&d=22&d=23&d=24&d=25&d=26&d=27&d=28&d=29&d=30&d=31&m=01&m=02&m=03&m=04&m=05&m=06&m=07&m=08&m=09&m=10&m=11&m=12'.$timer;

$post = $cc->post("http://www.cron-job.org/cgi-bin/cronweb","$link") or die('Error!!!'); 

if ($post) {
	echo "Add Succefull";
}
*/

?>
