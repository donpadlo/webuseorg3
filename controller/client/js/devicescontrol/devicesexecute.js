$("#devid").change(function() {// обрабатываем отправку формы
     devid=$("#devid :selected").val();
     $("#listcomm").load("controller/client/view/devicescontrol/devlist.php?devid="+devid);
});
