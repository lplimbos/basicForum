<!DOCTYPE html>
<html>
    <head>
        <style>
            div.relative {
                display: block;
                position: relative;
                width: 400px;
                height: 200px;
                border: 3px solid #73AD21;
            } 

            div.absolute {
                display: inline-block;
                position: relative;
                width: 150px;
                height: 100px;
                border: 3px solid #73AD21;
            }
        </style>
    </head>
    <body>

        <h2>position: absolute;</h2>

        <p>An element with position: absolute; is positioned relative to the nearest positioned ancestor (instead of positioned relative to the viewport, like fixed):</p>

        <div class="relative">
            <div class="absolute">This div element has position: absolute;</div>
            <div class="absolute" style="float: right;">This div element has position: absolute;</div>
        </div>

    </body>
</html>
