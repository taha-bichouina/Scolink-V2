<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['student_id'])) {
    header("Location: student_dashboard.php"); // Rediriger vers le tableau de bord
    exit();
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // Connexion à la base de données scolink
        $pdo = new PDO('mysql:host=localhost;dbname=scolink;charset=utf8', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Vérifier si l'utilisateur existe
        $stmt = $pdo->prepare("SELECT id, user, password FROM eleves WHERE email = :email");

        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie : stocker les infos dans la session
            $_SESSION['student_id'] = $user['id'];
            $_SESSION['student_name'] = $user['user'];            
            $_SESSION['message'] = "Connexion réussie !";

            header("Location: student_dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Erreur de connexion : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Élève</title>

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

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            margin-top: 100px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-title {
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
            <div class="col-md-6 login-container">
                <h2 class="login-title">Connexion Élève</h2>

                <!-- Affichage des messages -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-danger">
                        <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Formulaire de connexion -->
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">E-mail :</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                </form>

                <div class="footer">
                    <p>Pas encore inscrit ? <a href="register.php">S'inscrire ici</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
