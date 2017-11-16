<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
if ($user->mode == 1) {
    $tsms = new Tcconfig();
    $tehno = $tsms->GetByParam("settehsmsagent");
    // echo "!$teh!";
    ?>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-12 col-md-12 col-sm-12">
			<table id="list2"></table>
			<div id="pager2"></div>
			<script type="text/javascript"
				src="controller/client/js/smscenter/smscenter.js"></script>
        <?php
    $sms = new SmsAgent();
    $sms->Login();
    $bal = $sms->getBalance();
    $agnt = $sms->agentname;
    ?>
            <h4>Текущий агент: <?php echo "$agnt";?> <h4>
					<h4>Баланс по агенту: <?php echo "$bal";?> <h4>

							<div class="panel panel-primary">
								<div class="panel-heading">Отсрочка отправки</div>
								<div class="panel-body">
									Приостановить отправку СМС на:
									<div id="time_to_div" name="time_to_div">
										<select name="time_to" id="time_to">
											<option value=0>Начать отправку</option>
											<option value=10>10 минут</option>
											<option value=30>30 минут</option>
											<option value=60>1 час</option>
											<option value=120>2 часа</option>
											<option value=180>3 часа</option>
											<option value=360>6 часов</option>
											<option value=720>12 часов</option>
										</select>
										<div id="time_to_cur_div" name="time_to_cur_div"></div>
										<button type="submit" class="form-control" id="setsendsms"
											name="setsendsms">Установить</button>
									</div>
								</div>
							</div>
							<div class="panel panel-primary">
								<div class="panel-heading">Технические SMS</div>
								<div class="panel-body">
									<div id="mess_set" name="mess_set"></div>
									<span class="help-block">Отправитель для технических СМС*:</span>
									<input name="kmonth" type="text" id="kmonth"
										value="<?php echo "$tehno";?>" class="span2"
										placeholder="Отправитель"><br>
									<button type="submit" class="form-control" id="settehsms"
										name="settehsms">Установить</button>
									* если в очереди на отправку SMS находится СМС с отправителем
									совпадающим с указанным, то отправка идет от агента у котого
									отправитель совпадает с указанным.
								</div>
							</div>
		
		</div>
	</div>
</div>
<?php
} else {
    ?>
<div class="alert alert-error">У вас нет доступа в данный раздел!</div>
<?php
    
}
