<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Kos</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(135deg, #6C63FF, #3A3AFF);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 350px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            padding: 30px 35px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: fadeIn 0.8s ease-in-out;
            text-align: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Icon Rumah Animasi */
        .home-icon {
            font-size: 60px;
            color: #fff;
            margin-bottom: 10px;
            animation: bounce 1.5s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .login-box h2 {
            color: #fff;
            margin-bottom: 25px;
        }

        label {
            color: #fff;
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            margin-bottom: 15px;
            outline: none;
        }

        input:focus {
            box-shadow: 0 0 8px rgba(255,255,255,0.9);
        }

        button {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            background: #fff;
            color: #3A3AFF;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ececec;
        }

        .error-msg {
            background: #ff3b3b;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            color: #fff;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="login-box">

    <!-- ICON RUMAH DI ATAS -->
    <i class="fa-solid fa-house home-icon"></i>

    <h2>Login Admin Kos</h2>

    <?php if(isset($_GET['error'])){ ?>
        <p class="error-msg"><?= $_GET['error']; ?></p>
    <?php } ?>

    <form action="auth/login_process.php" method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
