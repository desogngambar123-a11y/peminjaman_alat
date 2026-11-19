<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Alat</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #FFFFFF;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        .bg-maroon-dark { background-color: #4A0E17; }
        .bg-maroon-card { background-color: #5C121E; }
        .bg-maroon-hover:hover { background-color: #6E1624; }
        .border-maroon { border-color: #7A1C2B; }
        .text-gold { color: #E5A93C; }
        .bg-gold { background-color: #E5A93C; }
        .bg-gold-hover:hover { background-color: #C99028; }
        .badge-terlambat { background-color: #E53E3E; color: white; }
        .badge-dipinjam { background-color: #DD6B20; color: white; }
        .badge-dikembalikan { background-color: #38A169; color: white; }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>