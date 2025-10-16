<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - OCP Maintenance Manager</title>
     <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: #2c3e50;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #7f8c8d;
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            border-color: #43a047;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
            color: #7f8c8d;
        }
        
        .btn-login {
            width: 100%;
            padding: 15px;
            background-color: #43a047;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            background-color: #25392bff;
        }
        
        .separator {
            height: 1px;
            background-color: #ecf0f1;
            margin: 30px 0;
            position: relative;
        }
        
        .separator-text {
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 15px;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 16px;
        }
        
        .signup-link a {
            color: #43a047;
            text-decoration: none;
            font-weight: 600;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
        }
        
        .ocp-logo {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 24px;
            color: #2c3e50;
        }
        
        .ocp-logo span {
            color: #43a047;
        }
        
        @media (max-width: 500px) {
            .login-container {
                padding: 25px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }
    </style> -->
</head>
<body>
    <div class="login-container">
                <!-- <img src="../logo_ocp.png" alt="logo" style="height: 50px; margin-right: 10px;"> -->
        <div class="ocp-logo">OCP <span>Maintenance Manager</span></div>
        <!-- <div class="logo">
                <img src="../logo_ocp.png" alt="logo" style="height: 50px; margin-right: 10px;">
                <h1>Maintenance Manager </h1>
                 <i class="fas fa-cogs"></i> -->
            <!-- </div> --> 
        <div class="header">
            <h1>Connexion </h1>
            <p>Entrez vos identifiants pour accéder à votre compte</p>
        </div>
        
        <form action="login_process.php" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" placeholder="Votre nom d'utilisateur" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                    <span class="toggle-password" id="togglePassword">
                        <i class="far fa-eye"></i>
                    </span>
                </div>
            </div>
            
            <button type="submit" class="btn-login">Se connecter</button>
        </form>
        
        <div class="separator">
            <span class="separator-text">ou</span>
        </div>
        
        <div class="signup-link">
            Pas encore de compte ? <a href="register.php">Créer un compte</a>
        </div>
    </div>

    <script>
        // Fonctionnalité pour afficher/masquer le mot de passe
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>