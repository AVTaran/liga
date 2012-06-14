<?
include('./ligaclass.php');
$liga = new LigaClass();





$liga->Autorise($liga->url['login']);




if ($liga->GetStatusAutorise() === false ) {

	echo $liga->CheckUrl($liga->url['login']);


	/*
	if ($liga->CheckUrl($liga->url['login']) == 200) {
		
		//
		
	}
	*/

}

$liga->CloseCurlSession();





/*

function check_url($url) {
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_HEADER, 1); // читать заголовок
	curl_setopt($c, CURLOPT_NOBODY, 1); // читать ТОЛЬКО заголовок без тела
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); // не использовать cache
	if (!curl_exec($c)) return false;

	$httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE);
	return ($httpcode < 400);
}


function get_url($url) {
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); // не использовать cache

	return curl_exec($c);
}

$ret = get_url('http://stepweb.ru/');


echo '<pre>';
print_r($ret);
echo '</pre>';
*/


?>
