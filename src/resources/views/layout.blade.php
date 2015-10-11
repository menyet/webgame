<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                clear:both;
                background-color: #6495ed;
                height:500px;
                width:1000px; 
                position: relative;
            }

            .content {
                
                position: absolute;
                top: 50%;
                left: 50%;
                height: 30%;
                width: 50%;
                margin: -15% 0 0 -25%;
            }
            
            
            .title {
                font-size: 96px;
            }
            
            .menu {
                afloat: right;
            }
        </style>
    </head>
    <body>
        <div class="menu">
            <a href="{{ action('MainController@home') }}">Home</a>
            <a href="{{ action('MainController@home') }}">Home</a>
            <a href="{{ action('MainController@home') }}">Home</a>
        </div>
        
        <div class="container">
            <div class="content">
                @yield('content')
            </div>
        </div>
    </body>
</html>
