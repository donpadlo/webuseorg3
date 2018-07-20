var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
function PrintableView(){
  var newWin3=window.open('','Печатная форма','');
    newWin3.focus();
    newWin3.document.write('<html>'); 
    newWin3.document.write("<script>printable=true;\x3C/script>"); 
    newWin3.document.write($("#idheader").html());		    
    newWin3.document.write('<body>');     
    report=$("#report").html();
    if (report!=""){
	newWin3.document.write(report);  
    };
    newWin3.print();
    newWin3.document.write('</body></html>');  
    newWin3.document.close();
    
  
};
function UpdateChosen(){
    for (var selector in config) {
	$(selector).chosen({ width: '100%' });
	$(selector).chosen(config[selector]);
    };        
};  
function AddToNavBarQuick(title){
    $.post(route+"controller/server/common/save_quick.php",{
		title:title,
		url:window.location.href,	
		ico:current_page_ico
	    }, function(data){
		$().toastmessage('showWarningToast', data);
		QuickMenuRedraw();
    });       
};

function GetCookieJS(name) {	
	var matches = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'));
	return matches ? decodeURIComponent(matches[1]) : undefined;
    };
function SaveCookiesJS(key,result,days){
	var exdate=new Date();
        exdate.setDate(exdate.getDate() + days);        
        document.cookie=key+"="+result+"; path=/; expires="+exdate.toUTCString();	
	$.cookie(key,result);
    };
function QuickMenuRedraw(){    
    if (printable==false){	
	agent=window.navigator.userAgent;
	//agent="NOC-agent";
	if (agent=="NOC-agent") {
	    $.get(route+'controller/server/common/quickmenuredraw.php&mode=menu', function( data ) {
		 $("#menu").find(".mm-listview" ).append("<li><a title='Быстрые ссылки' href=\"javascript:void(0)\"><i class=\"fa fa-bolt\" aria-hidden=\"true\"></i> Быстрые ссылки</a><ul>"+data+"</ul></li>");
	    });    	    
	} else {
	    $.get(route+'controller/server/common/quickmenuredraw.php', function( data ) {
		$("#quick_div" ).html(data);     
	    });
	};
  };
};    
function SetBreadcrumb(url){
      $("#breadcrumb").html();  
};
function GetAjaxPage(url){
      mmenuapi.close();
      $("#ajaxpage").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
      $.get(ajax+url, function( data ) {
          $("#ajaxpage" ).html(data);                            
      });        
      SetBreadcrumb(url);
};
$(function() {  
    if (typeof printable!=="undefined"){

    } else {printable=false;};
    //рисуем "быстрое меню"
    QuickMenuRedraw();
    //
    $.jgrid.extend({
	setColWidth: function (iCol, newWidth, adjustGridWidth) {
	    return this.each(function () {
		var $self = $(this), grid = this.grid, p = this.p, colName, colModel = p.colModel, i, nCol;
		if (typeof iCol === "string") {
		    colName = iCol;
		    for (i = 0, nCol = colModel.length; i < nCol; i++) {
			if (colModel[i].name === colName) {
			    iCol = i;
			    break;
			}
		    }
		    if (i >= nCol) {
			return; 
		    }
		} else if (typeof iCol !== "number") {
		    return;
		}
		grid.resizing = { idx: iCol };
		grid.headers[iCol].newWidth = newWidth;
		grid.newWidth = p.tblwidth + newWidth - grid.headers[iCol].width;
		grid.dragEnd(); 
		if (adjustGridWidth !== false) {
		    $self.jqGrid("setGridWidth", grid.newWidth, false); 
		}
	    });
	}
    });  
    $.jgrid.extend({
	saveCommonParam: function(stname){	    
	    //информация о колонках
	     colarray=$(this).jqGrid('getGridParam','colModel');
	     localStorage.setItem(stname, JSON.stringify(colarray));
	     atherparam = {};
	     atherparam["height"]=$(this).jqGrid("getGridParam","height");
	     localStorage.setItem(stname+"_ather", JSON.stringify(atherparam));
	     //console.log(JSON.stringify(colarray));	     	     
	},
	loadCommonParam: function(stname){	    	
	    if (localStorage[stname]!=undefined) {
		colarray=localStorage[stname];		
		if (localStorage[stname+"_ather"]!=undefined) {
		    atherparam=JSON.parse(localStorage[stname+"_ather"]);
		    if (atherparam!=""){
		       $(this).jqGrid("setGridHeight",atherparam["height"]); 
		       console.log("--высота таблицы",atherparam["height"]);
		    };
		};
		if (colarray!=""){
		    obj_for_load=JSON.parse(colarray);   // загружаем JSON в массив     
		    for (i in obj_for_load) {
			//console.log("name:",obj_for_load[i].name);
			//console.log("width:",obj_for_load[i].width);			
			//console.log("hidden:",obj_for_load[i].hidden);			
			if  (obj_for_load[i].hidden==true){
			   $(this).hideCol(obj_for_load[i].name);
			} else {
			   $(this).showCol(obj_for_load[i].name);
			   if (obj_for_load[i].fixed==true){
				$(this).setColWidth(obj_for_load[i].name, obj_for_load[i].width);
			   };
			}			
		    };    
		}
	    } else {
		console.log("в локальном хранилище не найден ключ "+stname);
	    };
	}
    });
});

$(window).scroll(function(){
    if ($(this).scrollTop() > 100) {
	$('.scrollup').fadeIn();
    } else {
	$('.scrollup').fadeOut();
    }
});

$('.scrollup').click(function(){
    $("html, body").animate({ scrollTop: 0 }, 600);
    return false;
});  