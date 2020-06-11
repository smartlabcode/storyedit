<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Story editor</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
    </head>
    <body>
        
        <div class="container">
            <div class="import-container">
                <form action="/handleinput" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="file" name="input" accept=".story"></input>
                    <button type="submit">Potvrdi</button>

                </form>
            </div>

        </div>

    </body>
</html>
