<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Reschedule System</title>
    <style>  
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            overflow: auto;
            background: linear-gradient(315deg, rgba(101,0,94,1) 3%, rgba(60,132,206,1) 38%, rgba(48,238,226,1) 68%, rgba(255,25,25,1) 98%);
            animation: gradient 15s ease infinite;
            background-size: 400% 400%;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            text-align: center;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 0%;
            }
            50% {
                background-position: 100% 100%;
            }
            100% {
                background-position: 0% 0%;
            }
        }

        .wave {
            background: rgb(255 255 255 / 25%);
            border-radius: 1000% 1000% 0 0;
            position: fixed;
            width: 200%;
            height: 12em;
            animation: wave 10s -3s linear infinite;
            transform: translate3d(0, 0, 0);
            opacity: 0.8;
            bottom: 0;
            left: 0;
            z-index: -1;
        }

        .wave:nth-of-type(2) {
            bottom: -1.25em;
            animation: wave 18s linear reverse infinite;
            opacity: 0.8;
        }

        .wave:nth-of-type(3) {
            bottom: -2.5em;
            animation: wave 20s -1s reverse infinite;
            opacity: 0.9;
        }

        @keyframes wave {
            2% {
                transform: translateX(1);
            }

            25% {
                transform: translateX(-25%);
            }

            50% {
                transform: translateX(-50%);
            }

            75% {
                transform: translateX(-25%);
            }

            100% {
                transform: translateX(1);
            }
        }

        /* Center container */
        .container {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        .container h1 {
            color: white;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .btn-get-started {
            display: inline-block;
            padding: 12px 30px;
            font-size: 18px;
            color: white;
            background-color: #333;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-get-started:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

    <!-- Background waves -->
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>

    <!-- Centered Container with content -->
    <div class="container">
        <h1>Welcome to the Lab Reschedule System</h1>
        <a href="login.php" class="btn-get-started">Get Started</a>
    </div>

</body>
</html>

