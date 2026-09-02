<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pascoal CRM</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body>
    <div id="app" data-page="{{ $page ?? 'dashboard' }}" data-user='@json($user ?? null)' data-stats='@json($stats ?? [])'></div>
</body>
</html>
