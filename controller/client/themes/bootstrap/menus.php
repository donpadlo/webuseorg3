<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф
?>
<script>
	$(function() {
		$('#menu').mmenu({
			extensions: ['effect-zoom-menu', 'effect-zoom-panels', 'pageshadow', 'iconbar'],
			header: true,
			searchfield: false,
			counters: true,
			dragOpen: true,
			navbar: {title: 'Меню', panelTitle: 'Меню'},
			onClick: {
				setSelected: true,
				close: false
			}
		});
	});
</script>            
<nav id="menu">	                        
<?php
function PutMenu($par) {
	global $gmenu, $cfg, $content_page;
	echo '<ul>';
	$list = $gmenu->GetList($par);
	foreach ($list as $key => $pmenu) {
		$nm = $pmenu['name'];
		$path = $pmenu['path'];
		$uid = $pmenu['uid'];
		$url = ($path == '') ? 'javascript:void(0)' : "index.php?content_page=$path";
		$sel = ($content_page == $path) ? ' class="Selected"' : '';
		echo "<li$sel>";
		if ($path=="") {echo $nm;} else {echo "<a href=\"$url\">$nm</a>";};
		if (count($gmenu->GetList($uid)) > 0) {
			PutMenu($uid);
		}
		echo '</li>';
	}
	echo '</ul>';
}

PutMenu('main');
unset($mm);
?>
</nav>