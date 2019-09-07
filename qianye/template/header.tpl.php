<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.zh-CN.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery-validate/1.19.0/localization/messages_zh.min.js"></script>
    <script src="https://cdn.bootcss.com/select2/4.0.7/js/select2.min.js"></script>
    <script src="https://cdn.bootcss.com/select2/4.0.7/js/i18n/zh-CN.js"></script>
    <link href="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/select2/4.0.7/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./img/page.css" />
    <script>
        var html = '<div class="progress"><span>加载中...</span><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"></div></div>';

        function loadlist() {
            var form = $('#searchform');
            load(form.data('url'), form.serialize());
            return false;
        }

        function reload() {
            load($('#currentpage').val());
        }

        function load(url, data) {
            $('#modal').modal('show');
            $('#modal .modal-content').html(html);
            $('#listcontainer').load(url, data, function() {
                $('#modal').modal('hide');
            });
        }
    </script>
</head>

<body>