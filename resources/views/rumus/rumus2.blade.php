<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Contoh penggunaan dalam view -->
    @php
    $topThreeStores = collect($recommendedStores)->sortByDesc(function($score) {
        return $score;
    })->take(3);
    @endphp

@foreach ($topThreeStores as $storeId => $score)
    @php
        $store = DB::table('users')->where('id', $storeId)->first();
    @endphp

    <!-- Tampilkan informasi toko -->
    <p>Store ID: {{ $store->name }}, Score: {{ $score }}</p>
@endforeach


</body>
</html>