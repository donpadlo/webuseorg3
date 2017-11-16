<?php
$userid = _GET("userid");
$sql = "select * from geouserhist where longitude<>'' or Nlongitude<>'' and userid=$userid order by id desc limit 1;";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать последние координаты пользователя!" . mysqli_error($sqlcn->idsqlconnection));
$cnt = 0;
while ($row = mysqli_fetch_array($result)) {
    $cnt ++;
    $starty = $row["longitude"];
    $startx = $row["latitude"];
    if ($startx == "") {
        $starty = $row["Nlongitude"];
        $startx = $row["Nlatitude"];
    }
    ;
}
;
if ($cnt == 0) {
    die("У пользователя не ведутся логи координат!");
}
;
?>
<div id="map"></div>
<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU"
	type="text/javascript"></script>
<script>
route = '<?php echo ($userewrite == 1) ? '/route/' : 'index.php?route=/'; ?>';

function LoadTrack(tuserid,tpG){
    //очищаем холст      
      myMap.geoObjects.each(function(ob) {
	myMap.geoObjects.remove(ob);  
      });    
     myCollection.removeAll();    
     function DrrawAll(tp){
		  for (i in obj_for_load) {		      
			var myPolyline = new ymaps.Polyline(obj_for_load[i]["coords"], {
					   hintContent : "hint",
					   interactiveZIndex:true,
					   balloonContent: "ballon"
				   }, {
					// Задаем опции геообъекта.
					// Цвет с прозрачностью.
					strokeColor: "#ff00ff",
					interactiveZIndex:true,
					// Ширину линии.
					strokeWidth: 2,
					// Максимально допустимое количество вершин в ломаной.
					editorMaxPoints: 50,  			       
				   }
			);
		    console.log("coors:",obj_for_load[i]["coords"]);
		    myCollection.add(myPolyline); 
		    myMap.geoObjects.add(myCollection);    
	
		    preset="islands#islands#darkGreenIcon";
		    presetcolor="#ffffff";    
		    myGeoObject = new ymaps.GeoObject({
			// Описание геометрии.
			geometry: {
			    type: "Point",
			    coordinates: obj_for_load[i]["coords"][1]
			},            
			// Свойства.
			properties: {
			    // Контент метки.
			    iconContent: "",
			    hintContent: obj_for_load[i]["dt"],
			    balloonContent:obj_for_load[i]["dt"]
			}
			}, {
			// Опции.
			// Иконка метки будет растягиваться под размер ее содержимого.
			preset: preset,
			// Метку можно перемещать.
			draggable: false,
		    }); 
		    myCollection.add(myGeoObject); //добавляем в коллекцию    
		    myMap.geoObjects.add(myCollection); // добавляем на холст		   
	    };	 
     };
     $.get(route+'controller/server/common/gettrack.php',  // сначала получем список
	    {userid: tuserid,type:tpG}, 
	    function(e) {  
		  obj_for_load=JSON.parse(e);   // загружаем JSON в массив     
		  DrrawAll(tpG);
		 }
     );       
};    
function init () {
    //обьект с коллекциями обьектов карт
    myCollection = new ymaps.GeoObjectCollection();
    //обьект коллекции - абоненты
    myCollectUsers= new ymaps.GeoObjectCollection();
    
    myMap = new ymaps.Map('map', {
        // При инициализации карты обязательно нужно указать
        // её центр и коэффициент масштабирования.
        center: [<?php echo "$startx, $starty" ?>], 
        zoom: 15,
        controls: ['smallMapDefaultSet']
    });
     mySaveButton = new ymaps.control.Button({
         data:    {content: '<i class=\"fa fa-check\" aria-hidden=\"true\"></i> Network'},
         options: {selectOnClick: false,size:'large'}                 
        });     
        mySaveButton.events.add('click', function () {                                                        
	    mySaveButton.data.set("content","<i class=\"fa fa-check\" aria-hidden=\"true\"></i> Network");
	    mySaveButton2.data.set("content","GPS");	    
            LoadTrack(<?php echo $userid;?>,1); 
        });                                
        myMap.controls.add(mySaveButton, {float: 'right'});   
      mySaveButton2 = new ymaps.control.Button({
         data:    {content: 'GPS'},
         options: {selectOnClick: false,size:'large'}                 
        });     
        mySaveButton2.events.add('click', function () {                                                        	    
	    mySaveButton2.data.set("content","<i class=\"fa fa-check\" aria-hidden=\"true\"></i> GPS");
	    mySaveButton.data.set("content","Network");
            LoadTrack(<?php echo $userid;?>,2); 
        });                                
        myMap.controls.add(mySaveButton2, {float: 'right'});   
	
    LoadTrack(<?php echo $userid;?>,1);     
};    
 ymaps.ready(init);
</script>