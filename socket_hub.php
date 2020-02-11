<?php
$method="GET";             // "POST" передача данных методом POST, "GET" методом GET
$serv_addr = 'localhost';  // ip адрес или доменное имя сервера, куда шлем данные
$serv_port = 80;           // номер порта
$serv_page = 'script.php'; // серверный скрипт принимаюший запрос
$timelimit = 2;            // время ожидания ответа в сек., по умолчанию - 30 сек.

/* передаваемые данные в формате: название переменной => значение */
$data = array(
  'request' => $json_str,  //! ЕСЛИ НУЖНО ОТПРАВИТЬ XML, ТО ЗАМЕНЯЕМ 
                           //! НА ДРУГУЮ ПЕРЕМЕННУЮ,Т.Е. НА $xml_str  
  'request2' => 'message 2'
);
/* генерируем строку с запросом */
$post_data_text = '';
  foreach ($data AS $key => $val)
    $post_data_text .= $key.'='.urlencode($val).'&';

/* убираем последний символ & из строки $post_data_text */
$post_data_text = substr($post_data_text, 0, -1);
/* прописываем заголовки, для передачи на сервер
    последний заголовок должен быть обязательно пустым,
    так как тело запросов отделяется от заголовков
    пустой строкой (символом перевода каретки "\r\n") */

// заголовок для метода POST 
$post_headers = array('POST /'.$serv_page.' HTTP/1.1',
						 'Host: '.$serv_addr,
						 'Content-type: application/x-www-form-urlencoded charset=utf-8',
						 'Content-length: '.strlen($post_data_text),
						 'Accept: */*',
						 'Connection: Close',
						 '');

// заголовок для метода GET 						 
$get_headers = array('GET /'.$serv_page.'?'.$post_data_text.' HTTP/1.1',
						 'Host: '.$serv_addr,
						 'Accept: */*',
						 'Connection: Close',
						 '');

if ($method=="POST") {
  $headers=$post_headers; 
} 
if ($method=="GET") {
  $headers=$get_headers;
}
/* сложим элементы массива в одну переменную $headers_txt
/* и добавим в конец каждой строки, знак "\r\n" - перевода каретки */
$headers_txt = '';
foreach ($headers AS $val) {
  $headers_txt .= $val.chr(13).chr(10);
}

// при POST запросе в конец заголовка добавляем наши данные
// для GET нет данной необходимости, т.к. данные уже в заголовке
if ($method=="POST") {
  $headers_txt = $headers_txt.$post_data_text.chr(13).chr(10).chr(13).chr(10);
}

// открытие сокета
$sp = fsockopen($serv_addr, $serv_port, $errno, $errstr, $timelimit);

// в случае ошибки, вернем ее		 
if (!$sp)
  exit('Error: '.$errstr.' #'.$errno);

// передача HTTP заголовка
  fwrite($sp, $headers_txt);

// если соединение, открытое fsockopen() не было закрыто сервером
// код while(!feof($sp)) { ... } приведет к зависанию скрипта
// в коде ниже - эта проблема решена
$server_answer = '';
$server_header= '';		

$start = microtime(true);
$header_flag = 1;
while(!feof($sp) && (microtime(true) - $start) < $timelimit) {
  if ($header_flag == 1) {
    $content = fgets($sp, 4096);
    if ($content === chr(13).chr(10)) 
      $header_flag = 0;
    else
      $server_header .= $content;
  }
  else {
      $server_answer .= fread($sp, 4096);
  }
}

 // закрываем дескриптор $sp
 fclose($sp);  	  

 /* для отладки, раскомментируйте строку ниже
    печать передаваемого HTTP запроса */	
 //echo $headers_txt;
?>
