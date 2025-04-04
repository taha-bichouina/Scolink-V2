<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['teacher_id']) || !isset($_SESSION['teacher_name'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: teacher_dashboard.php");
    exit();
}

// Si l'utilisateur est connecté, on récupère le nom du professeur
$teacher_name = $_SESSION['teacher_name'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Professeur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .dark-mode {
            background: linear-gradient(135deg, #121212, #1f1f1f);
            color: #e0e0e0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 0 30px;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 600;
            color: white;
        }

        .header button {
            background: none;
            border: none;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        .header button:hover {
            color: #ffc107;
        }

        .card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            height: 280px;
            background: #fff;
        }

        .dark-mode .card {
            background-color: #2c2c2c;
            color: #e0e0e0;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }

        .card-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .card-text {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #5a67d8;
            border-color: #5a67d8;
            font-size: 16px;
            padding: 12px 20px;
            border-radius: 8px;
            text-transform: uppercase;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4c51c7;
            border-color: #4c51c7;
        }

        .dark-mode .btn-primary {
            background-color: #3c4aad;
            border-color: #3c4aad;
        }

        .dark-mode .btn-primary:hover {
            background-color: #2c3e6b;
        }

        .toggle-dark-mode {
            cursor: pointer;
            background: none;
            border: none;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
        }

        .dark-mode .toggle-dark-mode {
            color: #e0e0e0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .col-md-4 {
            flex: 1 1 calc(33.33% - 15px);
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .col-md-4 {
                flex: 1 1 100%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Tableau de Bord - Professeur</h1>
            <button class="toggle-dark-mode" onclick="toggleDarkMode()">
                <i class="fas fa-moon"></i> Mode sombre
            </button>
        </div>

        <div class="row">
            <!-- Card: Welcome Message -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-chalkboard-teacher fa-4x"></i>
                        <h5 class="card-title">Bienvenue, <?php echo htmlspecialchars($teacher_name); ?> !</h5>
                        <p class="card-text">Gérez vos cours, activités et étudiants ici.</p>
                        <a href="show_students.php" class="btn btn-primary">Voir les étudiants</a>
                    </div>
                </div>
            </div>

            <!-- Card: Add Exercise -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-plus-circle fa-4x"></i>
                        <h5 class="card-title">Ajouter du contenu</h5>
                        <p class="card-text">Ajoutez des cours, des exercices ou des quiz pour vos étudiants.</p>
                        <a href="add_exercise.php" class="btn btn-primary">Ajouter un excercice</a>
                    </div>
                </div>
            </div>

            <!-- Card: Logout -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <i class="fas fa-sign-out-alt fa-4x"></i>
                        <h5 class="card-title">Déconnexion</h5>
                        <p class="card-text">Déconnectez-vous pour sécuriser votre session.</p>
                        <a href="logout.php" class="btn btn-primary">Déconnexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }
        });
    </script>
</body>

</html>
