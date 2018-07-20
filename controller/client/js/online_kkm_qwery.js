function GetGrid(){
    jQuery("#list2").jqGrid({
	    url:route+'controller/server/config_online.php',
	    datatype: "json",
	    colNames:['Id','Название','ИНН','Действия'],
	    colModel:[   		
		    {name:'id',index:'id', width:25},
		    {name:'kname',index:'kname', width:50,editable:true},
		    {name:'inn',index:'inn', width:50,editable:true},
		    {name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
	    ],
		    onSelectRow: function(ids) {
		      SubgridView(ids);
		    },
	    autowidth: true,			
	    rowNum:50,	   	
	    pager: '#pager2',
	    sortname: 'id',
	    scroll:1,
	    height: 400,
	viewrecords: true,
	sortorder: "asc",
	editurl:route+"controller/server/config_online.php&orgid="+defaultorgid,
	caption:"Онлайн кассы Атол"  
    });
    jQuery("#list2").jqGrid('navGrid','#pager2',{edit:true,add:false,del:false,search:false},{},{top: 0, left: 0, width: 500},{},{multipleSearch:false},{closeOnEscape:true} );
};
function SubgridView(ids){
    $("#tovar_title").val("Телематические услуги");
    $("#eorphone").val("online_checks@tviinet.ru");
    $.jgrid.gridUnload("#list3");  
    jQuery("#list3").jqGrid({
	    url:route+'controller/server/kkm_qwery.php&kkm='+ids,
	    datatype: "json",
	    colNames:['Id','Индекс','Дата',"Сумма документа","Товар","Статус","Номер документа",'ФП','Номер Чека','Действия'],
	    colModel:[   		
		    {name:'id',index:'id', width:25},
		    {name:'numcheck',index:'numcheck', width:50,editable:false},
		    {name:'docdate',index:'docdate', width:50,editable:false},
		    {name:'summdoc',index:'summdoc', width:50,editable:false},
		    {name:'goodsjson',index:'goodsjson', width:50,editable:false},
		    {name:'status',index:'status', width:50,editable:false},
		    {name:'dognum',index:'dognum', width:50,editable:false},
		    {name:'fiscalSign',index:'fiscalSign', width:50,editable:false},
		    {name:'documentNumber',index:'documentNumber', width:50,editable:false},
		    {name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
	    ],
	    autowidth: true,			
	    rowNum:50,	   	
	    pager: '#pager3',
	    sortname: 'docdate',
	    scroll:1,
	    height: 400,
	viewrecords: true,
	sortorder: "desc",
	editurl:route+'controller/server/kkm_qwery.php&kkm='+ids,
	caption:"Очередь чеков"  
    });
    jQuery("#list3").jqGrid('navGrid','#pager3',{edit:false,add:false,del:false,search:false},{},{top: 0, left: 0, width: 500},{},{multipleSearch:false},{closeOnEscape:true} );
    jQuery("#list3").jqGrid('navButtonAdd',"#pager3",{caption:'<i class=\"fa fa-bank fa-2x\"></i> Добавить электронный чек ',                              
        title: "Добавить чек в очередь",
        buttonicon: "none",
        position:"last",
	onClickButton:function(){
	    $( "#dialog-online_check" ).dialog("open");
	} 
    });   
};

$("#dialog-online_check" ).dialog({
  autoOpen: false,        
  resizable: false,
  height:300,
  width: 400,
  modal: true,
  buttons: {
    "Ok": function() {	
	if ($("#number_ls").val()!=""){
	    if ($("#tovar_title").val()!=""){			    
		if ($("#tovar_summ").val()!=""){		    
		    $.post(route+'controller/server/online_kkm_insert_check.php',{
			    kkm:jQuery("#list2").jqGrid('getGridParam','selrow'),
			    number_ls:$("#number_ls").val(),
			    tovar_title:$("#tovar_title").val(),
			    tovar_summ:$("#tovar_summ").val(),
			    eorphone:$("#eorphone").val()
		    }, function(data){
			    $().toastmessage('showWarningToast', data);
		    }); 		    
		    $( this ).dialog( "close" );
		} else {
		$().toastmessage('showWarningToast', 'Не введена сумма услуги!');
		};
	    } else {
	    $().toastmessage('showWarningToast', 'Не введено название услуги!');
	    };
	} else {
	  $().toastmessage('showWarningToast', 'Не введен лицевой счет абонента!');
	};
    }        
  }
});       
GetGrid();