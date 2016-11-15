function showUsers()
{
    //показать всех пользователей
    $.post('controller/client/themes/bootstrap/smscenter/ajax.php',{'cat':'users','act':'show'},function(data)
	{
	    //console.log(data);
	    $('#content').html('');
	    $('#menu-users').addClass('active');
	    $('#menu-groups').removeClass('active');
	    $('#catmenu').html('<a class="btn" href="#" onclick="addUser();">Добавить</a>');
	    $('#catmenu').show();
	    $('#window').hide();
	    var obj = $.parseJSON(data);
	    var html = '<table width="100%" class="table"><tr><td style="width:25px;" class="clear"></td><td class="clear">ФИО</td><td class="clear" style="text-align:center">Телефон</td><td style="width:25px;" class="clear"></td>';
	    for (i=0;i<obj.c;i++)
	    {
		html = html + '<tr><td class="clear" align="center"><i class=\"fa fa-user\" aria-hidden=\"true\"></i></td><td class="clear"><a href="#" title="Редактировать" onclick="editUser('+obj.data[i].id+');">'+obj.data[i].name+'</a></td><td style="text-align:center">'+obj.data[i].phone+'</td><td class="clear" align="center"><a href="#" onclick="deleteUser('+obj.data[i].id+');"><i class=\"fa fa-trash \" aria-hidden=\"true\"></i></a></td></tr>';
	    }
	    html = html + '</table>';
	    $('#content').html(html);
	});
}

function addUser()
{
    //добавить пользователя
    $.post('controller/client/themes/bootstrap/smscenter/ajax.php',{'cat':'users','act':'getform'},function(data)
    {
	//Получаем форму нового пользователя
	$("#window").html(data);
	$("#window").show();
    });
}

function insertUser()
{
    // Добавляем пользователя
    var name = $('#name').val();
    var phone = $('#phone').val();
    var telegram = $('#telegram').val();
    $.post("controller/client/themes/bootstrap/smscenter/ajax.php",{'cat':'users','act':'insert','name':name,'phone':phone,'telegram':telegram}, function(data)
    {
	//console.log(data);
	var obj = $.parseJSON(data);
	if (obj.error==0)
	{
	    $('#error').html(obj.errormes);
	    alert('Пользователь '+name+' создан');
	    $('#window').html('');
	    showUsers();
	}
	else
	{
	    $('#error').html('Ошибка: '+obj.errormes);
	}
    });
}

function editUser(id)
{
    //добавить пользователя
    $('#window').hide();
    $.ajax({
	url:'controller/client/themes/bootstrap/smscenter/ajax.php',
	type:'POST',
	async: false,
	data:{'cat':'users','act':'getform'},
	success: function(data)
	{
	    //Получаем форму нового пользователя
	    $("#window").html(data);
	}
	});
    $.ajax({
	url:'controller/client/themes/bootstrap/smscenter/ajax.php',
	type:'POST',
	async: false,
	data:{'cat':'users','act':'getuser','id':id},
	success: function(data)
	{
	    var obj = $.parseJSON(data);
	    $('#name').val(obj.name);
	    $('#phone').val(obj.phone);
	    $('#telegram').val(obj.telegram);
	    $('#window button').attr('onclick','saveUserData('+obj.id+');');
	    $('#window button').html('Сохранить');
	}
	});
    $('#window').show();
}

function saveUserData(id)
{
    var name = $('#name').val();
    var phone = $('#phone').val();
    var telegram = $('#telegram').val();
    $.ajax({
	url:'controller/client/themes/bootstrap/smscenter/ajax.php',
	type:'POST',
	async: false,
	data:{'cat':'users','act':'saveedituser','id':id,'name':name,'phone':phone,'telegram':telegram},
	success: function(data){
	    var obj = $.parseJSON(data);
	    if (obj.error==0) { alert('Учетная запись отредактирована'); showUsers(); return; }
	    $('#error').html(obj.errormes);
	}
    });
}

function deleteUser(id)
{
 //alert(id);
$.ajax({
	url:'controller/client/themes/bootstrap/smscenter/ajax.php',
	type:'POST',
	async: false,
	data:{'cat':'users','act':'deluser','id':id},
	success: function(data){
	    //var obj = $.parseJSON(data);
	    //if (obj.error==0) { alert('Учетная запись удалена'); showUsers(); return; }
	    //$('#error').html(obj.errormes);
	}
    }); 
    showUsers();
}

function showGroups()
{
    //показать все группы
    $.post('controller/client/themes/bootstrap/smscenter/ajax.php',{'cat':'groups','act':'show'},function(data)
	{
	    //console.log(data);
	    $('#content').html('');
	    $('#menu-groups').addClass('active');
	    $('#menu-users').removeClass('active');
	    $('#catmenu').html('<a href="#" class="btn" onclick="addGroup();">Добавить</a>');
	    $('#catmenu').show();
	    $('#window').hide();
	    var obj = $.parseJSON(data);
	    var html = '<table class="table" width="100%"><tr><td style="width:25px;"></td><td>Название</td><td style="width:25px;"></td>';
	    for (i=0;i<obj.c;i++)
	    {
		html = html + '<tr><td align="center"><i class=\"fa fa-object-group\" aria-hidden=\"true\"></i></td><td><a href="#" title="Редактировать" onclick="editGroup('+obj.data[i].id+');">'+obj.data[i].name+'</a></td><td align="center"><a href="#" onclick="deleteGroup('+obj.data[i].id+');"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a></td></tr>';
	    }
	    html = html + '</table>';
	    $('#content').html(html);
	});
}

function addGroup()
{
    //добавить группу
    $.post('controller/client/themes/bootstrap/smscenter/ajax.php',{'cat':'groups','act':'getform'},function(data)
    {
	//Получаем форму новой группы
	$("#window").html(data);
	$("#window").show();
    });
}

function insertGroup()
{
    // Добавляем группу
    var name = $('#name').val();
    $.post("controller/client/themes/bootstrap/smscenter/ajax.php",{'cat':'groups','act':'insert','name':name}, function(data)
    {
	//console.log(data);
	var obj = $.parseJSON(data);
	if (obj.error==0)
	{
	    $('#error').html(obj.errormes);
	    alert('Группа '+name+' создана');
	    $('#window').html('');
	    showGroups();
	}
	else
	{
	    $('#error').html('Ошибка: '+obj.errormes);
	}
    });
}

function editGroup(id)
{
    //редактировать группу
    $('#window').hide();
    $.post('controller/client/themes/bootstrap/smscenter/ajax.php',{'cat':'groups','act':'geteditform'},function(data)
    {
	//Получаем форму группы
	$('#content').hide();
	$('#content').html('');
        var html = '<input type="hidden" id="groupid" value="'+id+'"><table class="table"><tr><td width="50%" align="center">Пользователи:<br /><select id="users" size="20" style="width:250px">';
	html = html +'</select></td><td align="center">В группе:<br /><select id="ingroup" size="20" style="width:250px"';
	html = html +'</select></td></tr>';
	html = html +'</table><center><button onclick="inGroup();">>> В группу</button><button onclick="fromGroup();"><< Из группы</button></center>';
	$('#window').html(data);
	$('#content').html(html);
	$.ajax({
	    url:'controller/client/themes/bootstrap/smscenter/ajax.php',
	    type:'POST',
	    data:{'cat':'users','act':'show'},
	    async:false,
	    success:function(data1)
	    {
	    var obj1 = $.parseJSON(data1);
	    for (i=0;i<obj1.c;i++)
	    {
		$('#users').append($( '<option value="'+obj1.data[i].id+'">'+obj1.data[i].name+'</option>'));
	    }
	    //console.log(data1);
	    }
	    });
	$.ajax({
	    url:'controller/client/themes/bootstrap/smscenter/ajax.php',
	    type:'POST',
	    data:{'cat':'groups','act':'getmembers','id':id},
	    async:false,
	    success:function(data2)
	    {
	    var obj2 = $.parseJSON(data2);
	    for (i=0;i<obj2.c;i++)
	    {
		$('#ingroup').append($( '<option value="'+obj2.data[i].id+'">'+obj2.data[i].name+'</option>'));
	    }
	    //console.log(data2);
	    //убираем из левой колонки всех кто есть в правой
	    $('#ingroup option').each(function(){
	    mid = $(this).val();
	    //console.log(id);
	    $('#users option').each(function(){
		if ($(this).val()==mid) {
		    $(this).remove();
		    //console.log(id);
		    }
	    });
	    });
	    }
	    });
	$.ajax({
	    url:'controller/client/themes/bootstrap/smscenter/ajax.php',
	    type:'POST',
	    data:{'cat':'groups','act':'getgroupname','id':id},
	    async:false,
	    success:function(data3)
	    {
	    var obj3 = $.parseJSON(data3);
	    console.log(data3);
	    $('#name').val(obj3.name);
	    }
	    });
	$('#users option:first').attr('selected','selected');
	$('#ingroup option:first').attr('selected','selected');
	$('#window').show();
	//$('#content').css('height','560');
	//$('#content').css('overflow-y','auto');
	$('#content').show();
	//$('#wrapper').css('height','100%');
	
    });
}

function deleteGroup(id)
{
$.ajax({
	url:'controller/client/themes/bootstrap/smscenter/ajax.php',
	type:'POST',
	async: false,
	data:{'cat':'groups','act':'delgroup','id':id},
	success: function(data){
	    //var obj = $.parseJSON(data);
	    //if (obj.error==0) { alert('Учетная запись удалена'); showUsers(); return; }
	    //$('#error').html(obj.errormes);
	}
    }); 
    showGroups();
}

function inGroup()
{
    var id = $('#users option:selected').val();
    var text = $('#users option:selected').html();
    $('#ingroup').append($('<option value="'+id+'">'+text+'</option>'));
    $('#users option:selected').remove();
}

function fromGroup()
{
    var id = $('#ingroup option:selected').val();
    var text = $('#ingroup option:selected').html();
    $('#users').append($('<option value="'+id+'">'+text+'</option>'));
    $('#ingroup option:selected').remove();
}

function saveEditGroup()
{
    var ingr=[];
    var i=0;
    var name = $("#name").val();
    $('#ingroup option').each(function(){
	ingr[i] = $(this).val();
	i++;
    });
    var id = $("#groupid").val();
    $.post('controller/client/themes/bootstrap/smscenter/ajax.php',{'cat':'groups','act':'saveeditgroup','id':id,'name':name,'users[]':ingr}, function(data)
    {
	//console.log(data);
	alert("Группа отредактирована");
	showGroups();
	$("#window").hide();
    });
}

function sleep(ms)
{
    ms+=new Date().getTime();
    while (new Date() < ms){}
}