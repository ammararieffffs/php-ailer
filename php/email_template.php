<?php

function emailTemplateOTP()
{
    $html = <<<EOT
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet"
        />
        <style>
            body {
                background: #f5f1d5;
            }
        h1 {
            font-size: 70px;
            font-family: "Roboto", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
        .flex {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .roboto-bold {
            font-family: "Roboto", sans-serif;
            font-weight: 700;
            font-style: normal;
        }
        </style>
    </head>
    <body>
        <div class="flex">
        <h1>Your maintenance with us only have <span class="roboto-bold">ONE</span> month remaining</h1>
        </div>
    </body>
    </html>

    EOT;
    return $html;
}