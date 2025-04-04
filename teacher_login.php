<?php
session_start();

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données (ajuste ces paramètres selon ton setup)
    $host = 'localhost';
    $dbname = 'scolink';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les informations du formulaire
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Préparer la requête pour vérifier l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM professeurs WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe et que le mot de passe est correct
        if ($teacher && password_verify($password, $teacher['password'])) {
            // L'authentification est réussie, créer une session pour l'utilisateur
            $_SESSION['teacher_id'] = $teacher['id'];
            $_SESSION['teacher_name'] = $teacher['name'];
            $_SESSION['teacher_email'] = $teacher['email'];

            // Rediriger vers le tableau de bord du professeur
            header('Location: teacher_dashboard.php');
            exit();
        } else {
            // Afficher un message d'erreur si les informations sont incorrectes
            $error_message = 'Email ou mot de passe incorrect.';
        }
    } catch (PDOException $e) {
        $error_message = 'Erreur de connexion à la base de données : ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Professeur</title>

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
                <h2 class="login-title">Connexion Professeur</h2>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Formulaire de connexion -->
                <form method="POST">
                    <div class="form-group">
                        <label for="email">Adresse e-mail :</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                </form>

                <div class="footer">
                    <p>Pas encore inscrit ? <a href="teacher_register.php">S'inscrire ici</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
