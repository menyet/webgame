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
                background: url("/public/images/bg.png") no-repeat center center fixed; 
                -webkit-background-size: cover;
                  -moz-background-size: cover;
                  -o-background-size: cover;
                  background-size: cover;
                margin: 0;
                padding: 0;
                width: 100%;
                font-weight: 100;
                font-family: 'Lato';
                color:#ffffff;
            }

            .container {
                height: 100%;
                width: 100%; 
                position: relative;
            }

            .content {
                position: absolute;
                
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                height: 30%;
                width: 50%;
                margin: auto;
            }
            
            
            .title {
                font-size: 96px;
                text-align: center;
            }
            
            .menu {
                float: right;
                margin: 20px;
            }
            
            .menu a {
                font-size: 24px;
                font-weight:bold;
                color: #ffffff;
                margin-left:5px;
                text-decoration:none;
            }
            
            .menu a:hover {
                text-decoration:underline;
                color: #cccccc;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="menu">
                <a href="{{ action('MainController@home') }}">Home</a>
                <a href="{{ action('MainController@home') }}">Sign in</a>
                <a href="{{ action('MainController@home') }}">Register</a>
                <a href="{{ action('MainController@home') }}">About</a>
            </div>
        
            <div class="content">
                @yield('content')
            </div>
        </div>
    </body>
</html>
