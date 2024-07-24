{__NOLAYOUT__}


<?php echo  W('js/jedate') ?>
<script src="__LIB__/select2/js/select2.full.min.js" type="text/javascript"></script>
<link href="__LIB__/select2/css/select2.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">

    function formatRepo(repo){return repo.text}
    function formatRepoSelection(repo){return repo.text}

    $("#user_id_select2").select2({
        language: 'zh-CN',
        ajax: {
            url: "{:url('user/Index/getuse')}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    username: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });
    $("#user_id_select").select2({
        language: 'zh-CN',
        ajax: {
            url: "{:url('user/Index/getuse')}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    username: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });
    $("#user_id_select3").select2({
        language: 'zh-CN',
        ajax: {
            url: "{:url('user/Index/getuse')}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    username: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });
    $("#user_id_select4").select2({
        language: 'zh-CN',
        ajax: {
            url: "{:url('user/Index/getuse')}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    username: params.term,
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

</script>
</body>
</html>