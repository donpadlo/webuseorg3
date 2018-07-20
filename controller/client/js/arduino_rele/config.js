/* 
 * (с) 2011-2017 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

addOptions={
    top: 100, left: 100, width: 500
};   

function ViewConfigs(){    
    jQuery("#list2").jqGrid({
            url:route+'controller/server/arduino_rele/config.php',
            datatype: "json",
            colNames:['Id','IP','Пользователи','Комментарий','Ноги','Действия'],
            colModel:[   		
                    {name:'id',index:'id', width:55},
                    {name:'ip',index:'ip', width:100,editable:true},
                    {name:'roles',index:'comment', width:100,editable:true},
		    {name:'comment',index:'comment', width:100,editable:true},
		    {name:'foot',index:'foot', width:200,editable:true},
                    {name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
            ],
            autowidth: true,			
            rowNum:1000,	   	
            pager: '#pager2',
            sortname: 'id',
            scroll:1,
            height: 400,
        viewrecords: true,
        sortorder: "asc",
        editurl:route+'controller/server/arduino_rele/config.php',
        caption:"Список реле Arduino"  
    });   
    jQuery("#list2").jqGrid('navGrid','#pager2',{edit:true,add:true,del:true,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
};
$( document ).ready(function() {
    ViewConfigs();
});