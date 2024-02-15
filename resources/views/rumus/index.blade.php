<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Product Review</h1>
    @foreach($productreview as $data)
    <p>
        {{ $data->average }}
    </p>    
    @endforeach
</body>
</html>