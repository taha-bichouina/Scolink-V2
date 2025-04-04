<?php
// Démarrage de la session
session_start();

// Connexion à la base de données
$host = "localhost";
$dbname = "scolink";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Gestion de l'inscription du professeur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $subject = htmlspecialchars($_POST['subject']);
    
    // Validation des champs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($subject)) {
        $error = "Tous les champs doivent être remplis.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM professeurs WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            $error = "Un compte avec cet email existe déjà.";
        } else {
            // Crypter le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insérer les données dans la base de données
            $stmt = $pdo->prepare("INSERT INTO professeurs (user, email, password, subject) VALUES (:user, :email, :password, :subject)");
            $stmt->execute([
                'user' => $name,
                'email' => $email,
                'password' => $hashed_password,
                'subject' => $subject
            ]);
            
            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Professeur</title>
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
            box-shadow: none;
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

        .btn-primary:hover {
            background-color: #5a09b2;
            border-color: #5a09b2;
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

        .footer a:hover {
            text-decoration: underline;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 register-container">
                <h2 class="register-title">Inscription Professeur</h2>

                <!-- Affichage des messages d'erreur ou de succès -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <p><?php echo $error; ?></p>
                    </div>
                <?php elseif (isset($success)): ?>
                    <div class="alert alert-success">
                        <p><?php echo $success; ?></p>
                    </div>
                <?php endif; ?>

                <!-- Formulaire d'inscription -->
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Nom complet :</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Entrez votre nom" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse e-mail :</label>
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
                    <div class="form-group">
                        <label for="subject">Matière :</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Entrez la matière que vous enseignez" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
                </form>

                <div class="footer">
                    <p>Déjà inscrit ? <a href="teacher_login.php">Se connecter ici</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
