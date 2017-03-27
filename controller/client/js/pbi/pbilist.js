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
            url:route+'controller/server/pbi/pbilist.php',
            datatype: "json",
            colNames:['Id','Группа','Название','Комментарий','Логин','Пароль','IP','Пользователи','Действия'],
            colModel:[   		
                    {name:'id',index:'id', width:55},
                    {name:'groupname',index:'groupname', width:100,editable:true},
                    {name:'name',index:'name', width:100,editable:true},
		    {name:'comment',index:'comment', width:100,editable:true},
		    {name:'login',index:'login', width:100,editable:true},
		    {name:'pass',index:'pass', width:200,editable:true},
		    {name:'ip',index:'ip', width:200,editable:true},
		    {name:'forusers',index:'forusers', width:200,editable:true},
                    {name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
            ],
                grouping: true,
                groupingView: {
                    groupField: ["groupname"],
                    groupColumnShow: [true],
                    groupText: ["<b>{0}</b>"],
                    groupOrder: ["asc"],
                    groupSummary: [false],
                    groupCollapse: false
                    
                },	
	    onSelectRow: function(ids) {
		$("#pbiinfo").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
		$("#pbiinfo").load(route+'controller/server/pbi/pbiinfo.php&id='+ids);
	    },		
            autowidth: true,			
            rowNum:10,	   	
            pager: '#pager2',
            sortname: 'id',
            scroll:1,
            height: 200,
        viewrecords: true,
        sortorder: "asc",
        editurl:route+'controller/server/pbi/pbilist.php',
        caption:"Список станций PBI"  
    });   
    jQuery("#list2").jqGrid('navGrid','#pager2',{edit:true,add:true,del:true,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
};
$( document ).ready(function() {
    ViewConfigs();
});