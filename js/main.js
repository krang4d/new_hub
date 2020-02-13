//сформируем JSON
var JsonData = {
  "command":"value1",
  "message":"value2"
};

jQuery(function() {
    var body= $("body");
    var cmd = $("#selectmenu_cmd").selectmenu();
    var txt = $("#textarea_cmd");
    var btn = $("#button_cmd").button();
    var msg = $("#textarea_status");
    var stat= $("#input_status");

    btn.on('click', function () {
    //console.log($(this));
    //console.log("Send command: "+cmd.val());
        JsonData.message=txt.val();
        JsonData.command=cmd.val();
        const now_m = new Date().format("yyyy-MM-dd HH:mm:ss fff");
        msg.val(msg.val()+now_m+": Запрос "+$.toJSON(JsonData)+"\n");
        $.ajax({
            type: "POST", //метод запроса, можно POST можно GET (если опустить, то по умолчанию GET)
            url: "catcher.php",
            data: {request:$.toJSON(JsonData)}, //отправим данные, если нужно
         })
        .done(function(data) {                      //функция выполняется при удачном заверщение
            const now_m = new Date().format("yyyy-MM-dd HH:mm:ss fff");
            //console.log($.parseJSON(data).test1);        //выведем в консоль содержимое test1
            msg.val(msg.val()+now_m+": Ответ "+data+"\n");
        })
    });
});
