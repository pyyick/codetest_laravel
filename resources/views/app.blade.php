<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Home</title>
        <meta name="csrf-token" content="{{csrf_token()}}"/>
        <script>
            window.Laravel = {
                "csrfToken": "{{csrf_token()}}"
            }
        </script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <div class="container">
                <Welcome></Welcome>
                <div class="row">
                    <router-view></router-view>
                </div>
            </div>
        </div>
        <script src="/js/app.js"></script>
    </body>
</html>
