<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'LEEDCOURSE')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font pro -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;

            /* background global */
            background: 
                linear-gradient(rgba(255,255,255,0.4), rgba(255,255,255,0.4)),
                url('{{ asset("images/image.png") }}') no-repeat center center fixed;
            background-size: cover;
        }

        .page-container {
            min-height: 100vh;
            background-color: rgba(255,255,255,0.85); /* voile blanc pour lisibilité */
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="page-container">
        @yield('content')
    </div>

</body>
</html>

