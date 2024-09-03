<?php

function emailTemplateOTP($data)
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
            font-size: 30px;
            font-family: "Roboto", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
        .roboto-bold {
            font-family: "Roboto", sans-serif;
            font-weight: 700;
            font-style: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid black;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        </style>
    </head>
    <body>
        <h1>Maintenance List</h1>
        <div class="flex">
        <thead>
            <tr>
                <th>Website</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Days Remaining</th>
            </tr>
        </thead>
        <tbody>
        (data goes here ehe)
        </tbody>
        </div>
        <br><br>
        <span>This email was sent at: (TimeStamp)</span>
    </body>
    </html>

    EOT;
    $html = str_replace('(data goes here ehe)', $data, $html);
    $timestamp = date('Y-m-d H:i:s');
    $html = str_replace('(TimeStamp)', $timestamp, $html);

    return $html;
}