<?php
session_start();

// Connexion à la base de données
$host = "localhost"; // À modifier si nécessaire
$dbname = "scolink";
$username = "root"; // Ton utilisateur MySQL
$password = ""; // Ton mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Vérifier si tous les champs sont remplis
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format d'email invalide.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email est déjà utilisé
        $stmt = $pdo->prepare("SELECT id FROM eleves WHERE email = :email");
        $stmt->execute(["email" => $email]);
        
        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Hasher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insérer l'utilisateur dans la base de données
            $stmt = $pdo->prepare("INSERT INTO eleves (user, email, password) VALUES (:name, :email, :password)");
            $stmt->execute([
                "name" => $name,
                "email" => $email,
                "password" => $hashed_password
            ]);

            $success = "Inscription réussie ! Vous pouvez vous connecter.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Élève</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            font-family: 'Montserrat', sans-serif;
            color: white;
            min-height: 100vh;
            background-size: cover;
            background-attachment: fixed;
            margin: 0;
        }
        .register-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            margin-top: 100px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .register-title {
            text-align: center;
            color: #333;
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 30px;
        }
        .form-group label {
            font-weight: 600;
            color: #333;
        }
        .form-control {
            border-radius: 8px;
            border: 2px solid #ddd;
        }
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.5);
        }
        .btn-primary {
            background-color: #6a11cb;
            border-color: #6a11cb;
            font-weight: 600;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }
        .footer a {
            color: #2575fc;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 register-container">
                <h2 class="register-title">Inscription Élève</h2>

                <!-- Affichage des messages -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php elseif (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <!-- Formulaire d'inscription -->
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Entrez votre nom" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail :</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmer le mot de passe :</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmez votre mot de passe" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
                </form>

                <div class="footer">
                    <p>Déjà inscrit ? <a href="login.php">Se connecter ici</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
