<?php  
// Выключение протоколирования ошибок
//error_reporting(0);

// имя файла, в который производиться запись POST или GET запроса
$filename = "request.log"; 
// имя поля в POST или GET запросе
$name_var='request';

// JSON передаваемый в ответ на запрос
$request_data = array('test1' => 'value1', 'test2' => array('test2_in' => 'internal value test2'));
$json_str = json_encode($request_data);


// проверка существования файла 
if (file_exists($filename)) { 
  // если файл существует - открываем его 
  $file = fopen($filename, "a+"); 
} else { 
  // если файл не существует - создадим его 
  $file = fopen($filename, "x+"); 
} 
// данные из поля $name_var в POST или GET запросе
$json_text = $_POST[$name_var]; 
$_text = json_decode($json_text); 
//$text = $_GET[$name_var]; 
//(раскомментируйте нужную строку)

// записываем строку в файл 
fwrite($file, $json_text."\n"); 
// закрываем файл 
fclose($file); 

// ответ скрипта на запрос
//echo "The request was accepted"; //+" "+text;
echo $json_text;
?>
