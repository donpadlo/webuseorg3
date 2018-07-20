<div class="container-fluid">
	<div class="row">
		<div class="col-xs-4 col-md-4 col-sm-4">
		    <table id="list2"></table>
		    <div id="pager2"></div>				    
		</div>
		<div class="col-xs-8 col-md-8 col-sm-8">
		    <table id="list3"></table>
		    <div id="pager3"></div>				    
		</div>
	</div>
</div>
<!-- Формируем чек -->
<div id="dialog-online_check" title="Сформировать электронный чек">
	<input class="col-xs-12 col-md-12 col-sm-12" name="number_ls" type="text" id="number_ls" placeholder="Лицевой счет абонента">
	<input class="col-xs-12 col-md-12 col-sm-12" name="tovar_title" type="text" id="tovar_title" value="Телематические услуги" placeholder="Телематические услуги">
	<input class="col-xs-12 col-md-12 col-sm-12" name="tovar_summ" type="text" id="tovar_summ" placeholder="Сумма (100 приход, -100 возврат)">
	<input class="col-xs-12 col-md-12 col-sm-12" name="eorphone" type="text" id="eorphone" value="online_checks@tviinet.ru" placeholder="email для чека">
</div>
<script type="text/javascript" src="controller/client/js/online_kkm_qwery.js"></script>