var addOptions={
    top: 0, left: 0, width: 500,
};
jQuery("#list_post_users").jqGrid({
                        url:'controller/server/post/listpostusers.php?orgid='+defaultorgid,
                        datatype: "json",
                        colNames:['','Id','Должность','Пользователь','Действие'],
                        colModel:[
                            {name:'active',index:'active', width:20, search: false,frozen : true},
                            {name:'id',index:'id', width:25},
                            {name:'post',index:'post', width:100,sortable:false,editable: true},
                            {name:'userlogin',index:'userlogin', editable: true, width:100,edittype:"select",sortable:false,
                                editoptions:{
                                    editrules: { required: true },
                                    dataUrl: 'controller/server/post/listallusers.php?orgid='+defaultorgid
                                    }
                                },
                             {name:'myac',  width:80, fixed:true, sortable:false, resize:false, formatter:'actions',formatoptions:{keys:true},search: false}                                                                
   	],
        	autowidth: true,		
                height: 300,
                rowNum:100,	
                rowList:[10,20,30],
                pager: '#pager_post_users',
                sortname: 'id',
                scroll:1,
                viewrecords: true,
                sortorder: "asc",
                editurl:'controller/server/post/listpostusers.php?orgid='+defaultorgid,
                caption:"Должности огранизации"
                });        
                //alert(status);
jQuery("#list_post_users").jqGrid('navGrid','#pager_post_users',{edit:false,add:true,del:true,search:false},{},addOptions,{},{multipleSearch:false},{closeOnEscape:true} );
