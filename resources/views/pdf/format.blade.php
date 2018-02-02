<!doctype html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf8mb4"/>
    <title>Receipt</title>
    <style type="text/css">
        @font-face {
            font-family: ipag;
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/ipag.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: ipam;
            font-style: bold;
            font-weight: bold;
            src: url('{{ storage_path('fonts/ipag.ttf') }}') format('truetype');
        }
        body {
            font-family: ipam !important;
        }
        ul {
            list-style:none;
            text-align:right;
        }
         
        li{
            padding:0 10px;
        }
    </style>
</head>
<body>
    <h1 align="center">領収書</h1>
    
    <h3 align="right">
        <span style="border-bottom: solid 1px black;">
            {{ $user }} 様
        </span>
    </h3>
    
    <div class="header" style="border: solid 3px black;">
        <h3 align="center"><strong>内容確認</strong></h3>
    </div>
    <div class="content_purchase" style="border: solid 3px black;">
        <h4 align="left"><strong>注文詳細:</strong></h4>
        
        @for ($i = 1; $i < count($content); $i++)
            <h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $content[$i] }}</h5>
        @endfor
    </div>
</body>
</html>