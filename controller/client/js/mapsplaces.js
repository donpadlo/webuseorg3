// Получаем список ТМЦ в выбранной организации, в выбранном помещении
function GetListTmc(placesid){
      //alert('S');
       url="controller/server/map/getlisttmc.php?placesid="+placesid+"&addnone=false";
       $("#sel_tmc").load(url);
};

// при выборе помещения
$("#splaces").click(function(){        
    GetListTmc($("#splaces :selected").val());
});

GetListTmc($("#splaces :selected").val());


//$("#map").html("");
