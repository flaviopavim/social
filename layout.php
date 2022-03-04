<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Chat">
        <meta name="keywords" content="Chat">
        <meta name="author" content="WhiteHats">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chat</title>
    </head>
    <body>

        <style>
            body {
                margin: 0;
                padding: 0;
            }
            #top, #left, $center, #right {
                display: block;
            }
            #top {
                width: 100%;
                height: 60px;
                background-color: #ccc;
            }
            #left, #center, #right {
                float: left;
                height: calc(100% - 60px);
                overflow-x: hidden;
                overflow-y: auto;
            }
            #left, #right {
                width: 20%;
                background-color: #444;
            }
            #center {
                width: 60%;
                background-color: #666;
            }
        </style>

        <div id="top">
            <div style="float: left; width: 80%;">
                <img id="top-img">
                <div id="top-title"></div>
                <div id="top-subtitle"></div>
            </div>
            <div class="right">
                <i class="glyphicon glyphicon-lock" onclick="$('#msg').val('/k ').focus()"></i>
                <!--<i class="glyphicon glyphicon-remove"></i>-->
            </div>
        </div>
        <div id="left">
            
        </div>
        <div id="center">
            
        </div>
        <div id="right">
            
        </div>

        <!-- Libs -->
        <script src="js/jquery.js"></script>
        

    </body>
</html>