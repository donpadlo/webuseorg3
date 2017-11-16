<?php
include_once ("dbconfig.php");
include_once ("functions.php");

function getCalendarByRange($id)
{
    global $sqlcn;
    try {
        $sql = "select * from `jqcalendar` where `id` = " . $id;
        $handle = $sqlcn->ExecuteSQL($sql);
        // echo $sql;
        $row = @mysqli_fetch_array($handle);
    } catch (Exception $e) {}
    return $row;
}
if (isset($_GET["id"])) {
    $event = getCalendarByRange($_GET["id"]);
} else {
    $event = getCalendarByRange("");
}
;

?>
<!DOCTYPE html>
<html lang="ru-RU">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description"
	content="Учет ТМЦ в организации и другие плюшки">
<meta name="author" content="(c) 2011-2014 by Gribov Pavel">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>Заполнение события</title>

<meta name="generator" content="yarus" />
<link href="favicon.ico" type="image/ico" rel="icon" />
<link href="favicon.ico" type="image/ico" rel="shortcut icon" />
<link rel="stylesheet" type="text/css"
	href="/controller/client/themes/<?php echo "$cfg->theme"; ?>/css/bootstrap.min.css">

<link rel="stylesheet" type="text/css"
	href="/controller/client/themes/<?php echo "$cfg->theme"; ?>/css/bootstrap-responsive.min.css">         

    <?php
    echo "<link rel='stylesheet' type='text/css' href='/controller/client/themes/$cfg->theme/css/ui.jqgrid.css'>";
    echo "<link rel='stylesheet' type='text/css' href='/controller/client/themes/$cfg->theme/css/jquery-ui.min.css'>";
    echo "<script type='text/javascript' src='/js/jquery.min.js'></script>";
    echo "<script type='text/javascript' src='/js/jquery-migrate-1.2.1.js'></script>";
    echo "<script type='text/javascript' src='/controller/client/themes/$cfg->theme/js/jquery-ui.js'></script>";
    echo "<script type='text/javascript' src='/js/i18n/grid.locale-ru.js'></script>";
    echo "<script type='text/javascript' src='/js/jquery.jqGrid.min.js'></script>";
    echo "<script type='text/javascript' src='/js/jquery.form.js'></script>";
    echo "<script src='/js/chosen.jquery.js' type='text/javascript'></script>";
    ?>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>

<link rel="stylesheet"
	href="/controller/client/themes/<?php echo "$cfg->theme"; ?>/css/dp.css">
<link rel="stylesheet"
	href="/controller/client/themes/<?php echo "$cfg->theme"; ?>/css/calendar.css">
<link rel="stylesheet"
	href="/controller/client/themes/<?php echo "$cfg->theme"; ?>/css/main.css">
<link rel="stylesheet"
	href="/controller/client/themes/<?php echo "$cfg->theme"; ?>/css/dailog.css">
<link rel="stylesheet"
	href="/controller/client/themes/<?php echo "$cfg->theme"; ?>/css/alert.css">
<link rel="stylesheet"
	href="/controller/client/themes/<?php echo "$cfg->theme"; ?>/css/dropdown.css">
<link rel="stylesheet"
	href="/controller/client/themes/<?php echo "$cfg->theme"; ?>/css/colorselect.css">

<script type='text/javascript' src='/js/Common_cal.js'></script>
<script type='text/javascript' src='/js/jquery.alert.js'></script>
<script type='text/javascript' src='/js/wdCalendar_lang_US.js'></script>
<script type='text/javascript' src='/js/jquery.calendar.js'></script>
<script type='text/javascript' src='/js/jquery.ifrmdailog.js'></script>


<script src="/js/jquery.form.js" type="text/javascript"></script>
<script src="/js/jquery.validate.js" type="text/javascript"></script>
<script src="/js/jquery.dropdown.js" type="text/javascript"></script>
<script src="/js/jquery.colorselect.js" type="text/javascript"></script>

<script type="text/javascript">
var i18n = $.extend({}, i18n || {}, {
    datepicker: {
        dateformat: {
            "fulldayvalue": "M/d/yyyy",
            "separator": "/",
            "year_index": 2,
            "month_index": 0,
            "day_index": 1,
            "sun": "Пон",
            "mon": "Вто",
            "tue": "Сре",
            "wed": "Чет",
            "thu": "Пят",
            "fri": "Суб",
            "sat": "Вос",
            "jan": "Янв",
            "feb": "Фев",
            "mar": "Мар",
            "apr": "Апр",
            "may": "Май",
            "jun": "Июн",
            "jul": "Июл",
            "aug": "Авг",
            "sep": "Сен",
            "oct": "Окт",
            "nov": "Ноя",
            "dec": "Дек",
            "postfix": ""
        },
        ok: " Ok ",
        cancel: "Отмена",
        today: "Сегодня",
        prev_month_title: "предыдущий месяц",
        next_month_title: "следующий месяц"
    }
});

        
        
        if (!DateAdd || typeof (DateDiff) != "function") {
            var DateAdd = function(interval, number, idate) {
                number = parseInt(number);
                var date;
                if (typeof (idate) == "string") {
                    date = idate.split(/\D/);
                    eval("var date = new Date(" + date.join(",") + ")");
                }
                if (typeof (idate) == "object") {
                    date = new Date(idate.toString());
                }
                switch (interval) {
                    case "y": date.setFullYear(date.getFullYear() + number); break;
                    case "m": date.setMonth(date.getMonth() + number); break;
                    case "d": date.setDate(date.getDate() + number); break;
                    case "w": date.setDate(date.getDate() + 7 * number); break;
                    case "h": date.setHours(date.getHours() + number); break;
                    case "n": date.setMinutes(date.getMinutes() + number); break;
                    case "s": date.setSeconds(date.getSeconds() + number); break;
                    case "l": date.setMilliseconds(date.getMilliseconds() + number); break;
                }
                return date;
            }
        }
        function getHM(date)
        {
             var hour =date.getHours();
             var minute= date.getMinutes();
             var ret= (hour>9?hour:"0"+hour)+":"+(minute>9?minute:"0"+minute) ;
             return ret;
        }
        $(document).ready(function() {
            //debugger;
            var DATA_FEED_URL = "/controller/server/calendar/datafeed.php";
            var arrT = [];
            var tt = "{0}:{1}";
            for (var i = 0; i < 24; i++) {
                arrT.push({ text: StrFormat(tt, [i >= 10 ? i : "0" + i, "00"]) }, { text: StrFormat(tt, [i >= 10 ? i : "0" + i, "30"]) });
            }
            $("#timezone").val(new Date().getTimezoneOffset()/60 * -1);
            $("#stparttime").dropdown({
                dropheight: 200,
                dropwidth:60,
                selectedchange: function() { },
                items: arrT
            });
            $("#etparttime").dropdown({
                dropheight: 200,
                dropwidth:60,
                selectedchange: function() { },
                items: arrT
            });
            var check = $("#IsAllDayEvent").click(function(e) {
                if (this.checked) {
                    $("#stparttime").val("00:00").hide();
                    $("#etparttime").val("00:00").hide();
                }
                else {
                    var d = new Date();
                    var p = 60 - d.getMinutes();
                    if (p > 30) p = p - 30;
                    d = DateAdd("n", p, d);
                    $("#stparttime").val(getHM(d)).show();
                    $("#etparttime").val(getHM(DateAdd("h", 1, d))).show();
                }
            });
            if (check[0].checked) {
                $("#stparttime").val("00:00").hide();
                $("#etparttime").val("00:00").hide();
            }
            $("#Savebtn").click(function() { $("#fmEdit").submit(); });
            $("#Closebtn").click(function() { CloseModelWindow(); });
            $("#Deletebtn").click(function() {
                 if (confirm("Вы уверены что хотите удалить эту задачу?")) {  
                    var param = [{ "name": "calendarId", value: 8}];                
                    $.post(DATA_FEED_URL + "?method=remove",
                        param,
                        function(data){
                              if (data.IsSuccess) {
                                    alert(data.Msg); 
                                    CloseModelWindow(null,true);                            
                                }
                                else {
                                    alert("Error occurs.\r\n" + data.Msg);
                                }
                        }
                    ,"json");
                }
            });
            
           $("#stpartdate,#etpartdate").datepicker({ picker: "<button class='calpick'></button>"});    
            var cv =$("#colorvalue").val() ;
            if(cv=="")
            {
                cv="-1";
            }
            $("#calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "colorvalue" });
            //to define parameters of ajaxform
            var options = {
                beforeSubmit: function() {
                    return true;
                },
                dataType: "json",
                success: function(data) {
                    alert(data.Msg);
                    if (data.IsSuccess) {
                        CloseModelWindow(null,true);  
                    }
                }
            };
            $.validator.addMethod("date", function(value, element) {                             
                var arrs = value.split(i18n.datepicker.dateformat.separator);
                var year = arrs[i18n.datepicker.dateformat.year_index];
                var month = arrs[i18n.datepicker.dateformat.month_index];
                var day = arrs[i18n.datepicker.dateformat.day_index];
                var standvalue = [year,month,day].join("-");
                return this.optional(element) || /^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3-9]|1[0-2])[\/\-\.](?:29|30))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3,5,7,8]|1[02])[\/\-\.]31)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:16|[2468][048]|[3579][26])00[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1-9]|1[0-2])[\/\-\.](?:0?[1-9]|1\d|2[0-8]))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?:\d{1,3})?)?$/.test(standvalue);
            }, "Ошибка формата даты");
            $.validator.addMethod("time", function(value, element) {
                return this.optional(element) || /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(value);
            }, "Ошибка формата времени");
            $.validator.addMethod("safe", function(value, element) {
                return this.optional(element) || /^[^$\<\>]+$/.test(value);
            }, "$<> not allowed");
            $("#fmEdit").validate({
                submitHandler: function(form) { $("#fmEdit").ajaxSubmit(options); },
                errorElement: "div",
                errorClass: "cusErrorPanel",
                errorPlacement: function(error, element) {
                    showerror(error, element);
                }
            });
            function showerror(error, target) {
                var pos = target.position();
                var height = target.height();
                var newpos = { left: pos.left, top: pos.top + height + 2 }
                var form = $("#fmEdit");             
                error.appendTo(form).css(newpos);
            }
        });
    </script>
<style type="text/css">
.calpick {
	width: 16px;
	height: 16px;
	border: none;
	cursor: pointer;
	background: url("sample-css/cal.gif") no-repeat center 2px;
	margin-left: -22px;
}
</style>
</head>
<body>
	<div>
		<div class="toolBotton">
			<a id="Savebtn" class="imgbtn" href="javascript:void(0);"> <span
				class="Save" title="Сохранить в календарь">Save(<u>S</u>)
			</span>
			</a>                           
        <?php if(isset($event)){ ?>
        <a id="Deletebtn" class="imgbtn" href="javascript:void(0);"> <span
				class="Delete" title="Отменить в календаре">Delete(<u>D</u>)
			</span>
			</a>             
        <?php } ?>            
        <a id="Closebtn" class="imgbtn" href="javascript:void(0);"> <span
				class="Close" title="Закрыть окно">Close </span></a> </a>
		</div>
		<div style="clear: both"></div>
		<div class="infocontainer">
			<form
				action="/controller/server/calendar/datafeed.php?method=adddetails<?php echo isset($event)?"&id=".$event["Id"]:""; ?>"
				class="fform" id="fmEdit" method="post">
				<label> <span> *Subject: </span>
					<div id="calendarcolor"></div> <input MaxLength="200"
					class="required safe" id="Subject" name="Subject"
					style="width: 85%;" type="text"
					value="<?php echo isset($event)?$event["Subject"]:"" ?>" /> <input
					id="colorvalue" name="colorvalue" type="hidden"
					value="<?php echo isset($event)?$event["Color"]:"" ?>" />
				</label> <label> <span>*Time: </span>
					<div>  
              <?php
            
if (isset($event)) {
                $sarr = explode(" ", php2JsTime(mySql2PhpTime($event["StartTime"])));
                $earr = explode(" ", php2JsTime(mySql2PhpTime($event["EndTime"])));
            }
            ?>                    
              <input MaxLength="10" class="required date"
							id="stpartdate" name="stpartdate"
							style="padding-left: 2px; width: 90px;" type="text"
							value="<?php echo isset($event)?$sarr[0]:""; ?>" /> <input
							MaxLength="5" class="required time" id="stparttime"
							name="stparttime" style="width: 40px;" type="text"
							value="<?php echo isset($event)?$sarr[1]:""; ?>" />To <input
							MaxLength="10" class="required date" id="etpartdate"
							name="etpartdate" style="padding-left: 2px; width: 90px;"
							type="text" value="<?php echo isset($event)?$earr[0]:""; ?>" /> <input
							MaxLength="50" class="required time" id="etparttime"
							name="etparttime" style="width: 40px;" type="text"
							value="<?php echo isset($event)?$earr[1]:""; ?>" /> <label
							class="checkp"> <input id="IsAllDayEvent" name="IsAllDayEvent"
							type="checkbox" value="1"
							<?php if(isset($event)&&$event["IsAllDayEvent"]!=0) {echo "checked";} ?> />
							All Day Event
						</label>
					</div>
				</label> <label> <span> Location: </span> <input MaxLength="200"
					id="Location" name="Location" style="width: 95%;" type="text"
					value="<?php echo isset($event)?$event["Location"]:""; ?>" />
				</label> <label> <span> Remark: </span> <textarea cols="20"
						id="Description" name="Description" rows="2"
						style="width: 95%; height: 70px">
<?php echo isset($event)?$event["Description"]:""; ?>
</textarea>
				</label> <input id="timezone" name="timezone" type="hidden" value="" />
			</form>
		</div>
	</div>
</body>
</html>