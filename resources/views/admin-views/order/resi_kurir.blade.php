<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resi Kurir</title>
    <style>
        .row{
            display: flex;
            justify-content: center;
        }
        .row img{
            height: auto;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="row">
        <img src="/storage/resi/{{ $data->resi_kurir }}" alt="">
    </div>
</body>
</html>
