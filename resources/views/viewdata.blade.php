<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Story editor</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Stylesheets -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        
    </head>
    <body>
        
        <div class="container">
            <div class="images-container">
                @foreach($imagesPath as $imagepath)
                    <div class="image-container">
                        <img src="{{ $imagepath }}"></img>
                        <form action="/changeimage" method="post" enctype="multipart/form-data">
                            @csrf
                            <span>Promijeni ovu sliku</span>
                            <input type="file" name="input">
                            <input type="hidden" value="{{ $imagepath }}" name="imagepath">
                            <button type="submit">Promijeni</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <div class="save-container">
                <form action="/saveshanges" method="post" enctype="multipart/form-data">
                    @csrf
                    <button type="submit">Spasi promjene</button>
                </form>

            </div>
        </div>

    </body>
</html>
