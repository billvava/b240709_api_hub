{__NOLAYOUT__}
<link href="__LIB__/jedate/skin/jedate.css" rel="stylesheet" type="text/css"/>
<script src="__LIB__/jedate/jedate.min.js" type="text/javascript"></script>
<script type="text/javascript">
    //日期时间
    jeDate({
        dateCell:"input[name=start_datetime],input[name=end_datetime],.jeDateTime",
        isinitVal:false,
        isTime:true,
        minDate:"1900-01-01 00:00:00",
    });
    //只显示年
    jeDate({
        format:"YYYY",
        dateCell:".jeYear",
        isinitVal:false,
        isTime:false,
        minDate:"1900-01-01 00:00:00",
    });
    //只显示年-月
    jeDate({
        format:"YYYY-MM",
        dateCell:".jeYearMouth",
        isinitVal:false,
        isTime:false,
        minDate:"1900-01-01 00:00:00",
    });
    //只显示年-月-日
    jeDate({
        format:"YYYY-MM-DD",
        dateCell:".jeYearMouthDay",
        isinitVal:false,
        isTime:false,
        minDate:"1900-01-01 00:00:00",
    });
    //只显示小时-分-秒
    jeDate({
        format:"hh:mm:ss",
        dateCell:".jeTime",
        isinitVal:false,
        isTime:true,
        minDate:"1900-01-01 00:00:00",
    });

</script>