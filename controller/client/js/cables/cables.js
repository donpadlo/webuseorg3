/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
addOptions={
    top: 100, left: 100, width: 500
};   
function ViewFibre(module_id){
    jQuery("#list4").jqGrid({
            url:'controller/server/cables/cable_fibres.php?orgid='+defaultorgid+"&module_id="+module_id,
            datatype: "json",
            colNames:['Id','Номер','Цвет 1','Цвет 2','Действия'],
            colModel:[   		
                    {name:'id',index:'id', width:55},
                    {name:'number',index:'number', width:200,editable:true},
                    {name:'color1',index:'color1', width:200,editable:true,edittype:"select",editoptions:{
                        editrules: { required: true },
                        dataUrl: 'controller/server/cables/color_cables_1.php'
                        }
                    },
                    {name:'color2',index:'color2', width:200,editable:true,edittype:"select",editoptions:{
                        editrules: { required: true },
                        dataUrl: 'controller/server/cables/color_cables_1.php'
                        }
                    },                    
                    {name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
            ],
            autowidth: true,			
            rowNum:10,	   	
            pager: '#pager4',
            sortname: 'id',
            scroll:1,
            height: 140,
        viewrecords: true,
        sortorder: "asc",
            onSelectRow: function(ids) {                             
                           //         ViewModules(ids);
            },           
        editurl:'controller/server/cables/cable_fibres.php?orgid='+defaultorgid+"&module_id="+module_id,
        caption:"Волокна"  
    });   
    jQuery("#list4").jqGrid('navGrid','#pager4',{edit:true,add:true,del:true,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );        
};
function ViewModules(cable_id){
    $('#list4').jqGrid('GridUnload');      
    $('#list3').jqGrid('GridUnload');      
    jQuery("#list3").jqGrid({
            url:'controller/server/cables/cable_modules.php?orgid='+defaultorgid+"&cable_id="+cable_id,
            datatype: "json",
            colNames:['Id','Номер','Цвет1','Цвет2','Действия'],
            colModel:[   		
                    {name:'id',index:'id', width:55},
                    {name:'number',index:'number', width:200,editable:true},
                    {name:'color',index:'color', width:200,editable:true,edittype:"select",editoptions:{
                        editrules: { required: true },
                        dataUrl: 'controller/server/cables/color_cables_1.php'
                        }
                    },
                    {name:'color1',index:'color1', width:200,editable:true,edittype:"select",editoptions:{
                        editrules: { required: true },
                        dataUrl: 'controller/server/cables/color_cables_1.php'
                        }
                    },                    
                    {name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
            ],
            autowidth: true,			
            rowNum:10,	   	
            pager: '#pager3',
            sortname: 'id',
            scroll:1,
            height: 140,
        viewrecords: true,
        sortorder: "asc",
            onSelectRow: function(ids) {                             
		jQuery("#list4").jqGrid('setGridParam',{url:'controller/server/cables/cable_fibres.php?orgid='+defaultorgid+"&module_id="+ids});
		jQuery("#list4").jqGrid('setGridParam',{editurl:'controller/server/cables/cable_fibres.php?orgid='+defaultorgid+"&module_id="+ids}).trigger('reloadGrid');;                
                ViewFibre(ids);
            },           
        editurl:'controller/server/cables/cable_modules.php?orgid='+defaultorgid+"&cable_id="+cable_id,
        caption:"Модули"  
    });   
    jQuery("#list3").jqGrid('navGrid','#pager3',{edit:true,add:true,del:true,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );        
    jQuery("#list3").jqGrid('navButtonAdd','#pager3',{caption:"<img src='controller/client/themes/"+theme+"/ico/vcard.png'>",
            title: "Размножить модуль",
            onClickButton:function(){
                  var mid = jQuery("#list3").jqGrid('getGridParam','selrow');
                    if(mid){
                            $.get("controller/server/cables/cable_modules_copy.php", {module_id: mid},
                             function(data){
                                    jQuery("#list3").jqGrid().trigger('reloadGrid');                    
                            });                                 

                           } else {
                            alert("Сначала выберите модуль для копирования!")
                    }							
            } 	
    });
    
};
function ViewCables(){    
    jQuery("#list2").jqGrid({
            url:'controller/server/cables/name_mark.php?orgid='+defaultorgid,
            datatype: "json",
            colNames:['Id','Имя','Маркировка','Действия'],
            colModel:[   		
                    {name:'id',index:'id', width:55},
                    {name:'name',index:'name', width:200,editable:true},
                    {name:'mark',index:'mark', width:200,editable:true},
                    {name:'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true}}
            ],
            autowidth: true,			
            rowNum:10,	   	
            pager: '#pager2',
            sortname: 'id',
            scroll:1,
            height: 140,
        viewrecords: true,
        sortorder: "asc",
            onSelectRow: function(ids) {                                             
				jQuery("#list3").jqGrid('setGridParam',{url:'controller/server/cables/cable_modules.php?orgid='+defaultorgid+"&cable_id="+ids});
				jQuery("#list3").jqGrid('setGridParam',{editurl:'controller/server/cables/cable_modules.php?orgid='+defaultorgid+"&cable_id="+ids}).trigger('reloadGrid');;
                                ViewModules(ids);
            },           
        editurl:'controller/server/cables/name_mark.php?orgid='+defaultorgid,
        caption:"Типы и марки оптических кабелей"  
    });   
    jQuery("#list2").jqGrid('navGrid','#pager2',{edit:true,add:true,del:true,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
};
$( document ).ready(function() {
    ViewCables();
});