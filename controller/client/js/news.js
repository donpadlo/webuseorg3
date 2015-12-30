// загружаем в таблицу #list2 список новостей
jQuery("#list2").jqGrid({
   	url:'controller/server/news/news.php',
	datatype: "json",
   	colNames:['Id','Дата','Заголовок','Закреплено','Действия'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false},
   		{name:'dt',index:'dt', width:60,editable:false},
   		{name:'title',index:'title', width:200,editable:true},
		{name:'stiker',index:'stiker', width:200,editable:true,formatter: 'checkbox',edittype: 'checkbox', editoptions: {value: '1:0'}},
		{name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
   	],
	autowidth: true,		
   	pager: '#pager2',
   	sortname: 'dt',
	height: 480,
	rowNum: 30,
    viewrecords: true,
    sortorder: "desc",
    editurl:"controller/server/news/news.php",
    caption:"Новости"
});
// загружаем навигационную панель
jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});

// добавляем в таблицу кнопку "Добавить"
jQuery("#list2").jqGrid('navButtonAdd','#pager2',{caption:"Добавить",                              
	onClickButton:function(){
                     //  $("#pg_add_edit" ).dialog( "destroy" );
                       $("#pg_add_edit").dialog({autoOpen: false,height: 600,width: 800,modal:false,title: "Добавление новости" });
                       $("#pg_add_edit" ).dialog( "open" );                                   
                       $("#pg_add_edit").load("controller/client/view/news/news.php?step=add");            
		}								
});
// добавляем в таблицу кнопку "Отредактировать"
jQuery("#list2").jqGrid('navButtonAdd','#pager2',{caption:"Отредактировать",                              
	onClickButton:function(){
              var gsr = jQuery("#list2").jqGrid('getGridParam','selrow');
		if(gsr){                     
                       //$("#pg_add_edit" ).dialog( "destroy" );
                       $("#pg_add_edit").dialog({autoOpen: false,height: 600,width: 800,modal:false,title: "Редактирование новости" });
                       $("#pg_add_edit" ).dialog( "open" );                                   
                       $("#pg_add_edit").load("controller/client/view/news/news.php?step=edit&id="+gsr);
                       } else {
			//alert("Сначала выберите строку!");
			$().toastmessage('showWarningToast', 'Сначала выберите строку!');
		}							
	}
});        