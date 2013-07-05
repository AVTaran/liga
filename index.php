<?
//header('Content-type: text/html; charset=utf-8');
set_time_limit(120);

define('_DOMEN_', 'http://www.fantasyland.ru/');
define('_PATH_COOKIES_', dirname(__FILE__).'/cookies.txt');

include('./curlclass.php');


$cc = new cURL(TRUE, _PATH_COOKIES_); 

$AuthSt = ChekAuthSt();

if (
		!file_exists(_PATH_COOKIES_) 
	  OR 
		(time()-filemtime(_PATH_COOKIES_) > 900)
	  OR 
		!$AuthSt
	) {
	//echo "В последний раз файл $filename был изменен: " . date ("F d Y H:i:s.", filemtime($filename));
	$page = $cc->post(_DOMEN_.'login.php', 'login=AutoRun&password=fv4eh6'); 
	echo 'Авторизовались';
} else {
	//echo 'Нас здесь помнят :-)';
}
echo '<br>';


if ($_POST['submit']=='GO' ) {

	switch ($_POST['action']) {
		case 'work_start':
			echo 'Отправляем народ работать на work_start.php с value = '.$_POST['value'];
			echo '<br>';
			$work_start = $cc->post(_DOMEN_.'cgi/work_start.php', 'value='.$_POST['value']); 
		break;

		case 'train_start':
			echo _DOMEN_.'cgi/train_start.php?unit_id='.$_POST['unit_id'].'&code='.$_POST['value'].'';
			echo '<br>';
			$work_start = $cc->get(_DOMEN_.'cgi/train_start.php?unit_id=1401&code='.$_POST['value'].'');
		break;

		default:
	}

	unlink($_POST['submit']);
}




if ( $_POST['action']=='train_start' ) {
	$mine = $cc->post(_DOMEN_.'cgi/arena.php?rld=1');

} else {
	$mine = $cc->post(_DOMEN_.'cgi/no_combat.php');

}

echo '<textarea id="" rows="30" cols="150">'.$mine.'</textarea><br>';
if ( preg_match('#process_pb\( (\d+), (\d+)#', $mine, $time_work) ) {
	$tt = $time_work[1]-$time_work[2];
	echo 'Народ работает...';
	echo '<br>';
	echo '<span id="timer">Осталось подождать '.$tt.' секунд</span>';
	echo '<br>';

?>
<script type="text/javascript">
<!--
	var t=0;
	var ok=1;
	var m=0;
	var tm=0;

	var tt=<?=$tt?>;

	tm++;
	if(tm==1) {
		setInterval(
			function() {
				if (ok==0) { t++; } 
				if (t>=60) {
					m++;
					t%=60;
				}
				if (ok==0) {
					document.getElementById("timer").innerHTML="Осталось подождать "+(tt-t)+" секунд";
					if ((tt-t)<-10) {
						document.location.href='./?unit_id=<?=$_REQUEST["unit_id"]?>';
					}
				}
			}
			,1000
		);
	}
	ok=0;
// -->
</script>

<?

// (m-m%10)/10+""+m%10+":"+(t-t%10)/10+""+t%10


} else {
	echo 'можно запускать на новую ходку';
	echo '<br>';
	$mine = $cc->post(_DOMEN_.'cgi/work_stop.php'); 

	$img = $cc->post(_DOMEN_.'cgi/png.php'); 
	file_put_contents('1.png', $cc->loadbody);
	echo '<br>';

	$text = '';
	$antigate = '3b35de56fdedc17740766232bcdd7835'; // AutoRun
	$antigate = '9b2933faba86eaef071a8efeb05b6cab'; // stepweb
	//$text = recognize(dirname(__FILE__)."/1.png", $antigate, false, "antigate.com", 4, 20, 0, 0, 1, 4, 4); 

	echo $text;
	echo '<br>';

	echo '<form action="./" method="POST">';
		echo '<select name="action">';
			echo '<option value="train_start">Прокачка юнитов</option>';
			echo '<option value="work_start">Добыча в шахте</option>';
		echo '</select>';
		echo '<br>';

		echo '<label for="unit_id">Код юнита</label>';
		echo "<input type='text' class='text' name='unit_id' maxlength='4' size='4' value='".$_REQUEST['unit_id']."'>";
		echo '<br>';

		echo '<img src="1.png" width=\'90\' height=\'40\' border=1 bordercolor=white>';
		echo '<br>';
		echo "<input type='text' class='text' name='value' maxlength='4' size='4' value='".$text."'>";
		echo "<input name='submit' type='submit' class='button' value='GO'>";
	echo '</form>';
}




function ChekAuthSt() {
	$page = '';
	$cc = new cURL(TRUE, _PATH_COOKIES_); 
	$page = $cc->post(_DOMEN_.'cgi/show_info.php'); 
	$auth = strpos($page, 'AutoRun');
	return $auth;
}



function ModifiPath ($page) {
	$page = str_replace('/cgi/', _DOMEN_.'cgi/', $page);
	$page = str_replace('/ch/', _DOMEN_.'ch/', $page);
	$page = str_replace('www.fantasyland.ruhttp://www.fantasyland.ru/', 'http://www.fantasyland.ru/', $page);
	return $page;
}




function GetUrlFromPages ($page) {
	$arUrl = array();
	if (preg_match_all('#(cgi/.+?)["|\']#', $page, $ListUrl)) {
		foreach ($ListUrl[1] AS $key => $val) {
			$arUrl[] = _DOMEN_.$val;
		}
	}
	if (preg_match_all('#(ch/.+?)["|\']#', $page, $ListUrl)) {
		foreach ($ListUrl[1] AS $key => $val) {
			$arUrl[] = _DOMEN_.$val;
		}
	}
	return $arUrl;
}





/*
$filename - file path to captcha
$apikey   - account's API key
$rtimeout - delay between captcha status checks
$mtimeout - captcha recognition timeout

$is_verbose - false(commenting OFF),  true(commenting ON)

additional custom parameters for each captcha:
$is_phrase - 0 OR 1 - captcha has 2 or more words
$is_regsense - 0 OR 1 - captcha is case sensetive
$is_numeric -  0 OR 1 - captcha has digits only
$min_len    -  0 is no limit, an integer sets minimum text length
$max_len    -  0 is no limit, an integer sets maximum text length
$is_russian -  0 OR 1 - with flag = 1 captcha will be given to a Russian-speaking worker

usage examples:
$text=recognize("/path/to/file/captcha.jpg","YOUR_KEY_HERE",true, "antigate.com");

$text=recognize("/path/to/file/captcha.jpg","YOUR_KEY_HERE",false, "antigate.com");  

$text=recognize("/path/to/file/captcha.jpg","YOUR_KEY_HERE",false, "antigate.com",1,0,0,5);  

*/



function recognize(
		$filename,
		$apikey,
		$is_verbose = true,
		$domain="antigate.com",
		$rtimeout = 5,
		$mtimeout = 120,
		$is_phrase = 0,
		$is_regsense = 0,
		$is_numeric = 0,
		$min_len = 0,
		$max_len = 0,
		$is_russian = 0
	) {

	if (!file_exists($filename))
	{
		if ($is_verbose) echo "file $filename not found\n";
		return false;
	}
	$postdata = array(
		'method'    => 'post', 
		'key'       => $apikey, 
		'file'      => '@'.$filename, 
		'phrase'	=> $is_phrase,
		'regsense'	=> $is_regsense,
		'numeric'	=> $is_numeric,
		'min_len'	=> $min_len,
		'max_len'	=> $max_len,
		
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,             "http://$domain/in.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,     1);
	curl_setopt($ch, CURLOPT_TIMEOUT,             60);
	curl_setopt($ch, CURLOPT_POST,                 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,         $postdata);
	$result = curl_exec($ch);
	if (curl_errno($ch)) 
	{
		if ($is_verbose) echo "CURL returned error: ".curl_error($ch)."\n";
		return false;
	}
	curl_close($ch);
	if (strpos($result, "ERROR")!==false)
	{
		if ($is_verbose) echo "server returned error: $result\n";
		return false;
	}
	else
	{
		$ex = explode("|", $result);
		$captcha_id = $ex[1];
		if ($is_verbose) echo "captcha sent, got captcha ID $captcha_id\n";
		$waittime = 0;
		if ($is_verbose) echo "waiting for $rtimeout seconds\n";
		sleep($rtimeout);
		while(true)
		{
			$result = file_get_contents("http://$domain/res.php?key=".$apikey.'&action=get&id='.$captcha_id);
			if (strpos($result, 'ERROR')!==false)
			{
				if ($is_verbose) echo "server returned error: $result\n";
				return false;
			}
			if ($result=="CAPCHA_NOT_READY")
			{
				if ($is_verbose) echo "captcha is not ready yet\n";
				$waittime += $rtimeout;
				if ($waittime>$mtimeout) 
				{
					if ($is_verbose) echo "timelimit ($mtimeout) hit\n";
					break;
				}
				if ($is_verbose) echo "waiting for $rtimeout seconds\n";
				sleep($rtimeout);
			}
			else
			{
				$ex = explode('|', $result);
				if (trim($ex[0])=='OK') return trim($ex[1]);
			}
		}
		
		return false;
	}
}




?>
