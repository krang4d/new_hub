<?php  
// Выключение протоколирования ошибок
//error_reporting(0);

// имя файла, в который производиться запись POST или GET запроса
$filename = "request.log"; 
// имя поля в POST или GET запросе
$cmd_var='request';

// JSON передаваемый в ответ на запрос
$response_data = array('test1' => 'value1', 'test2' => array('test2_in' => 'internal value test2'));
$json_str = json_encode($response_data);


// проверка существования файла 
if (file_exists($filename)) { 
  // если файл существует - открываем его 
  $file = fopen($filename, "a+"); 
} else { 
  // если файл не существует - создадим его 
  $file = fopen($filename, "x+"); 
} 
// данные из поля $cmd_var в POST или GET запросе
$json_text = $_POST[$cmd_var]; 
$_text = json_decode($json_text); 
//$text = $_GET[$cmd_var]; 
//(раскомментируйте нужную строку)

// записываем строку в файл 
fwrite($file, $json_text."\n"); 
// закрываем файл 
fclose($file); 

// ответ скрипта на запрос
//echo "The request was accepted"; //+" "+text;
echo $json_text; 
