/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */


function GetGrid(){
    jQuery("#list2").jqGrid({
            url:'controller/server/smscenter/getsmslist.php?orgid='+defaultorgid,
            datatype: "json",
            colNames:['Id','Номер телефона','Текст сообщения','Статус','Дата','Действия'],
            colModel:[   		
                    {name:'id',index:'id', width:55, hidden:true},
                    {name:'mobile',index:'mobile', width:100,editable:true},
                    {name:'smstxt',index:'smstxt', width:200,editable:true},
                    {name:'status',index:'status', width:100,editable:true},
		    {name:'dt',index:'dt', width:100,editable:true},
                    {name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
            ],
            autowidth: true,			
            rowNum:10,	   	
            pager: '#pager2',
            sortname: 'id',
            scroll:1,
            height: 140,
        viewrecords: true,
        sortorder: "asc",
        editurl:"controller/server/smscenter/getsmslist.php?orgid="+defaultorgid,
        caption:"Список для отправки СМС"  
    });

    var addOptions={
        top: 0, left: 0, width: 500
    };
    jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:true,del:false,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );

    jQuery("#list2").jqGrid('navButtonAdd',"#pager2",{caption:"<img src='controller/client/themes/"+theme+"/ico/icon_currency.gif'> Загрузить список",                              
            title: "Загрузить список телефонов и текстов СМС",
	    buttonicon: 'none',
            onClickButton:function(){
              $( "#dialog-load" ).dialog("open" );                             
            } 
    });

    jQuery("#list2").jqGrid('navButtonAdd',"#pager2",{caption:"<img src='controller/client/themes/"+theme+"/ico/icon_currency.gif'> Очистить",                              
            title: "Очистить список",
	    buttonicon: 'none',
            onClickButton:function(){
                $.get('controller/server/smscenter/dellist.php', function( data ) {
                  jQuery("#list2").jqGrid().trigger('reloadGrid');                     
                });
            } 
    });
    jQuery("#list2").jqGrid('navButtonAdd',"#pager2",{caption:"<img src='controller/client/themes/"+theme+"/ico/comment.png'> СМС ",                              
         title: "Отправить СМС",
	 buttonicon: 'none',
	onClickButton:function(){
         $( "#dialog-confirm" ).dialog("open" );                                  
	} 
});        

};



$( document ).ready(function() {
    GetGrid();
    $( "#dialog-load" ).dialog({
          autoOpen: false,        
          resizable: false,
          height:400,
          width: 640,
          modal: true,
          buttons: {
            "Ok": function() {
                $("#message_send").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
                $.post('controller/server/smscenter/uploadlist.php',
                    {
                        pdata: $("#smstext").val()
                    },
                    function(data,status){
                            if (data!="") alert(data);
                            $("#dialog-load").dialog( "close" );
                            jQuery("#list2").jqGrid().trigger('reloadGrid');                    
                            $("#message_send").html("");        
                    }
                );                
            }
          }
        });    
    $( "#dialog-confirm" ).dialog({
      autoOpen: false,        
      resizable: false,
      height:240,
      width: 350,
      modal: true,
      buttons: {
        "Лучше не нада!": function() {
          $( this ).dialog( "close" );},          
        "Да": function() {
          $( this ).dialog( "close" );
          var timer = setInterval(function() {
            //  отображаю прогрессбар
                $('#list2').jqGrid('GridUnload');
                $("#list2").load('controller/server/smscenter/smsgroupsendprogress.php?orgid='+defaultorgid+"&blibase="+$("#blibase").val()+"&grp="+$("#grp").val());                                
            }, 2000);
                // запускаю рассылку СМС
                $.get("controller/server/smscenter/smsglistsend.php?orgid="+defaultorgid, {blibase:$("#blibase").val()} , function(data){                
                    clearInterval(timer);
                    jQuery("#list2").jqGrid().trigger('reloadGrid');                               
                });            
        }        
      }
    });        
});
