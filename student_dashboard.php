<?php
session_start();

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

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

// Récupérer les informations de l'étudiant
$student_id = $_SESSION['student_id'];

$stmt = $pdo->prepare("SELECT user FROM eleves WHERE id = :id");
$stmt->execute(["id" => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'étudiant existe
if (!$student) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$student_name = htmlspecialchars($student['user']); // Sécuriser l'affichage du nom
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Étudiant</title>
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
            justify-content: center;
            align-items: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #42a5f5;
            border-color: #42a5f5;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            width: 100%;
            text-transform: uppercase;
            color: #ffffff;
            margin-top: 20px;
            text-align: center;
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: #1e88e5;
            border-color: #1e88e5;
        }

        .btn-primary i {
            margin-right: 8px;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tableau de Bord - Étudiant</h1>
        <h2>Bienvenue, <?php echo $student_name; ?> !</h2>
        <div class="btn-group">
            <a href="mes_cours.php" class="btn btn-primary"><i class="fas fa-book"></i> Mes Cours</a>
            <a href="examens.php" class="btn btn-primary"><i class="fas fa-clipboard"></i> Examens</a>
            <a href="profil.php" class="btn btn-primary"><i class="fas fa-user"></i> Mon Profil</a>
        </div>
        <a href="logout.php" class="btn btn-primary mt-4"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
</body>

</html>
