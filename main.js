//сформируем JSON
var JsonData = {
  "test1":"value1",
  "test2":{
	     "test2_in":"internal value test2"
  }
};

$(document).ready(
    $(function() {
        $('button').on('click', function () {
            //console.log($(this));
            $.ajax({
                type: "POST", //метод запроса, можно POST можно GET (если опустить, то по умолчанию GET)
                url: "catcher.php",
                data: {request:$.toJSON(JsonData)}, //отправим данные, если нужно
             })
            .done(function(data) {                      //функция выполняется при удачном заверщение
                console.log(data); //$.parseJSON(data).test1);        //выведем в консоль содержимое test1
	            //console.log($.parseJSON(data).test1); //выведем в консоль содержимое test2_in
            })
        });
    }));
