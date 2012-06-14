<?
// ====================== 
// Автор: stepweb.ru
// Дата: 09.23.2011 
// Клас для... 
// ====================== 


//http://www.fantasyland.ru/cgi/show_big_loc.php?i=CapitalMines/mine.jpg



class LigaClass {

	// Список урлов которые могут понадобится
	var $url = array(
		'login' => 'http://www.fantasyland.ru/login.php',
		'mine' => 'http://www.fantasyland.ru/cgi/no_combat.php',
		'work_stop' => 'http://www.fantasyland.ru/cgi/work_stop.php',
		'work_start' => 'http://www.fantasyland.ru/cgi/work_start.php'
	);

	// Cтатус авторизации
	var $StatusAutorise = false;

	// CURL Сессия
	var $CurlSession = null;

	// Cтатус авторизации
	var $WorkPages = null;



	// -----------------------------------------------------------------
	// Конструктор 
	function __construct() {
		$this->CurlSession = curl_init();
	}


	// -----------------------------------------------------------------
	// Устанавливает статус авторизации
	function SetStatusAutorise($status = true) {
		$this->StatusAutorise = $status;
	}


	// -----------------------------------------------------------------
	// Возвращает текущий статус авторизации
	function GetStatusAutorise() {
		return $this->StatusAutorise;
	}


	// -----------------------------------------------------------------
	// Инициализация CURL Cеанса
	// 
	function SetCurlSession () {
		$this->CurlSession = curl_init();
	}

	// -----------------------------------------------------------------
	// Возвращаем страницу установленную как рабочая
	function GetWorkPages() {
		return $this->WorkPages;
	}

	// -----------------------------------------------------------------
	// Устанавливаем страницу с которой работаем
	function SetWorkPages($pages) {
		$this->WorkPages = $pages;
	}


	// -----------------------------------------------------------------
	// Входящие данные: 
	//  $adrName - имя требуемого адреса
	// 
	// Результат:
	//   запрошенный адрес, либо false если такой адрес не задан
	function GetUrl($adrName) {
		if (isset($this->url[$adrName])) {
			return $this->url[$adrName];
		} else {
			return false;
		}
	}


	// -----------------------------------------------------------------
	// Данные:
	//   $url - имя требуемого адреса (string) 
	// 
	// Результат: string/boolean 
	//   код возвращаемый по указанному адресу либо false в случае если страница не доступна 
	// 
	// Пример: 
	//   $code = CheckUrl('stepweb.ru');
	// 
	function CheckUrl($url) {

		curl_setopt($this->CurlSession, CURLOPT_URL, $url);
		curl_setopt($this->CurlSession, CURLOPT_HEADER, 1); // читать заголовок
		curl_setopt($this->CurlSession, CURLOPT_NOBODY, 1); // читать ТОЛЬКО заголовок без тела
		curl_setopt($this->CurlSession, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->CurlSession, CURLOPT_FRESH_CONNECT, 1); // не использовать cache

		if (!curl_exec($this->CurlSession)) return false;

		$httpcode = curl_getinfo($this->CurlSession, CURLINFO_HTTP_CODE);
		return ($httpcode < 400);
	}


	// -----------------------------------------------------------------
	// Данные:
	//   $url - имя требуемого адреса (string) 
	// 
	// Результат: string
	//   Страница по указанному адресу 
	// 
	// Пример: 
	//   $code = LoadPage('stepweb.ru');
	// 
	function LoadPage($url) {
		curl_setopt($this->CurlSession, CURLOPT_URL, $url);
		curl_setopt($this->CurlSession, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->CurlSession, CURLOPT_FRESH_CONNECT, 1); // не использовать cache

		return curl_exec($this->CurlSession);
	}


	// -----------------------------------------------------------------
	// Авторизируемся на сайте 
	// 
	// Входящие данные: 
	//  $UrlLogin - имя адреса с формой авторизации
	// 
	// Результат:
	//  
	function Autorise($UrlLogin) {
		$ssl = false;
		$post = array(
			'login=AutoRun',
			'password=fv4eh6'
		);

		// установка URL и других необходимых параметров
		curl_setopt($this->CurlSession, CURLOPT_URL, $UrlLogin);
		curl_setopt($this->CurlSession, CURLOPT_POST, 1);
		curl_setopt($this->CurlSession, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->CurlSession, CURLOPT_FRESH_CONNECT, 1); 
		curl_setopt($this->CurlSession, CURLOPT_FOLLOWLOCATION, true);

		if ($ssl) { // если соединяемся с https
			curl_setopt($this->CurlSession, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->CurlSession, CURLOPT_SSL_VERIFYHOST, 0);
		}

		if (is_array($post)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
		}

		// загрузка страницы 
		return curl_exec($this->CurlSession);

	}



	// -----------------------------------------------------------------
	// Завершение сеанса и освобождение ресурсов
	// 
	function CloseCurlSession() {
		curl_close($this->CurlSession);
		$this->CurlSession = null;
	}


}





/*

curl_close -- Завершает сеанс CURL
curl_copy_handle --  Copy a cURL handle along with all of its preferences 

curl_errno -- Возвращает код последней ошибки
curl_error --  Возвращает строку с описанием последней ошибки 

curl_exec -- Выполняет запрос CURL
curl_getinfo -- Возвращает информацию о последней операции

curl_init -- Инициализирует сеанс CURL
curl_multi_add_handle -- Добавляет обычный cURL дескриптор к набору cURL дескрипторов
curl_multi_close -- Закрывает набор cURL дескрипторов
curl_multi_exec -- Выполняет операции с набором cURL дескрипторов

curl_multi_getcontent -- Возвращает результат операции, если был установлен параметр CURLOPT_RETURNTRANSFER
curl_multi_info_read -- Возвращает информацию о текущих операциях

curl_multi_init -- Создает набор cURL дескрипторов
curl_multi_remove_handle -- Удаляет cURL дескриптор из набора cURL дескрипторов
curl_multi_select -- Возвращает сокеты, созданные модулем cURL
curl_setopt -- Устанавливает параметр для сеанса CURL
curl_version -- Возвращает версию CURL



// $url     is string
// $post    is array
// $ssl     is boolean
// $headers is array
// $uagent  is string


*/



?>

