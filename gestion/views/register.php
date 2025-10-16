<?php 
session_start();
// Rediriger si l'utilisateur est déjà connecté
if(isset($_SESSION['username'])) { 
    header("Location: ../index.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte - OCP Smart Maintenance</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-message {
            color: #e74c3c;
            background-color: #fadbd8;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            display: none;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="ocp-logo">OCP <span>Maintenance Manager</span></div>
    
    <div class="header">
        <h2>Créer un compte</h2>
        <p>Remplissez le formulaire ci-dessous pour vous inscrire</p>
    </div>

    <!-- Affichage des messages d'erreur -->
    <?php if(isset($_SESSION['error'])): ?>
    <div class="error-message" id="errorMessage" style="display: block;">
        <?php 
        echo $_SESSION['error']; 
        unset($_SESSION['error']);
        ?>
    </div>
    <?php endif; ?>

    <form action="register_process.php" method="post" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom" required 
                   value="<?php echo isset($_SESSION['form_data']['nom']) ? htmlspecialchars($_SESSION['form_data']['nom']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required
                   value="<?php echo isset($_SESSION['form_data']['prenom']) ? htmlspecialchars($_SESSION['form_data']['prenom']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="fonction">Fonction</label>
            <input type="text" id="fonction" name="fonction" placeholder="Votre fonction" required
                   value="<?php echo isset($_SESSION['form_data']['fonction']) ? htmlspecialchars($_SESSION['form_data']['fonction']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" placeholder="Choisir un nom d'utilisateur" required
                   value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Choisir un mot de passe (min. 6 caractères)" required>
                <span class="toggle-password" id="togglePassword">
                    <i class="far fa-eye"></i>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <div class="password-container">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer votre mot de passe" required>
                <span class="toggle-password" id="toggleConfirmPassword">
                    <i class="far fa-eye"></i>
                </span>
            </div>
        </div>

        <button type="submit" class="btn-login">Créer le compte</button>
    </form>

    <div class="separator">
        <span class="separator-text">ou</span>
    </div>

    <div class="signup-link">
        Déjà un compte ? <a href="login.php">Se connecter</a>
    </div>
</div>

<script>
    // Afficher / masquer le mot de passe
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

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('confirm_password');
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

    // Validation du formulaire côté client
    function validateForm() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const errorDiv = document.getElementById('errorMessage');
        
        if (password.length < 6) {
            errorDiv.textContent = 'Le mot de passe doit contenir au moins 6 caractères';
            errorDiv.style.display = 'block';
            return false;
        }
        
        if (password !== confirmPassword) {
            errorDiv.textContent = 'Les mots de passe ne correspondent pas';
            errorDiv.style.display = 'block';
            return false;
        }
        
        return true;
    }
    
    // Masquer le message d'erreur après 5 secondes
    setTimeout(function() {
        const errorDiv = document.getElementById('errorMessage');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }, 5000);
</script>

<?php
// Nettoyer les données de formulaire sauvegardées après affichage
if(isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
?>
</body>
</html>