/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

function DZopen() {
	$('#zabbix_mod_win').dialog('open');
}

// возвращает cookie с именем name, если есть, если нет, то undefined
function zab_getCookie(name) {
	var matches = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function createNotificationInstance(options) {
	if (options.notificationType == 'simple') {
		return window.webkitNotifications.createNotification('icon.png', 'Заголовок уведомления', 'Текст уведомления...');
	} else if (options.notificationType == 'html') {
		return window.webkitNotifications.createHTMLNotification('http://someurl.com');
	}
}

$(document).ready(function() {
	if (window.webkitNotifications) {
		console.log('Уведомления поддерживаются!');
	} else {
		console.log('Уведомления не поддерживаются в вашей версии браузера/операционной системы.');
	}
	$('<audio id="zabbix_sound"><source src="/media/notify.ogg" type="audio/ogg"><source src="/media/notify.mp3" type="audio/mpeg"><source src="/media/notify.wav" type="audio/wav"></audio>').appendTo('body');

	txt = '<div id="zabbix_mod_button" style="left:100%;margin-left:-30px;position:absolute;top:10px;">';
	txt = txt + '<img id="zab_img" onclick="DZopen();" src="controller/client/themes/bootstrap/img/zabbix.gif">';
	txt = txt + '</div>';
	$('body').append(txt);
	$('body').append('<div id="zabbix_mod_win" title="Окно сообщений Zabbix">События Zabbix загружаются.. Подождите несколько секунд!Настройка подписок <a href="?content_page=zabbix_mon">тут</a></div>');

	var timer = setInterval(function() {
		$.get('controller/server/zabbix/getcurdashboard.php').done(
				function(data) {
					cq = ''; // обнуляем потенциальные куки..
					obj_for_load = JSON.parse(data);
					ht = '<table class="table table-striped table-hover table-condensed">';
					ht = ht + '<thead><tr><th>Группа</th><th>Хост</th><th>Проблема</th><th>Время</th><th>Приоритет</th><th>Комментарий</th><tr></thead><tbody>';
					$('#zabbix_mod_win').html('');
					zx = 0;
					for (i in obj_for_load) {
						pd = 'success';
						switch (obj_for_load[i]['prinum']) {
							case '0':
								pd = 'success';
								break;
							case '1':
								pd = 'info';
								break;
							case '2':
								pd = 'warning';
								break;
							case '3':
							case '4':
							case '5':
								pd = 'error';
								break;
						}
						ht = ht + '<tr class=' + pd + '><td>' + obj_for_load[i]['group_name'] + '</td><td>' + obj_for_load[i]['hosterr'] + '</td><td>' + obj_for_load[i]['description'] + '</td><td>' + obj_for_load[i]['lastchange'] + '</td><td>' + obj_for_load[i]['priority'] + '</td><td>' + obj_for_load[i]['comment'] + '</td></tr>';
						cq = cq + obj_for_load[i]['triggerid'];
						zx++;
					}
					ht = ht + '</tbody></table></br>Настройка подписок <a href="?content_page=zabbix_mon">тут</a>';
					$('#zabbix_mod_win').html(ht);
					if (zx > 0) {
						$('#zab_img').css('border', 'red 3px solid');
					} else {
						$('#zab_img').css('border', 'green 1px solid');
						$('#zabbix_mod_win').html('На текущий момент проблем в работе сети не наблюдается..Настройка подписок <a href="?content_page=zabbix_mon">тут</a>');
					}
					zabbix_mod = zab_getCookie('zabbix_mod');
					if (zabbix_mod != cq) {
						DZopen();
						$('#zabbix_sound')[0].play();
					}
					document.cookie = 'zabbix_mod=' + cq;
				});
	}, 16000);

	$('#zabbix_mod_win').dialog({
		autoOpen: false,
		resizable: true,
		height: 440,
		width: 640,
		modal: true,
		buttons: {
			'Ok': function() {
				$(this).dialog('close');
			}
		}
	});
});
