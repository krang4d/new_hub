//сформируем JSON
var JsonData = {
  "test1":"value1",
  "test2":{
	     "test2_in":"internal value test2"
  }
};

jQuery(function() {
    var cmd = $("#input_cmd");
    var msg = $("#textarea_cmd");
    var btn = $("#button_cmd");
    cmd.val("Go");
    btn.on('click', function () {
    //console.log($(this));
    //console.log("Send command: "+cmd.val());
        JsonData.test1=cmd.val();
        JsonData.test2.test2_in=msg.val();
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
});
