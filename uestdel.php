<?php
// uest.php — pour insertion + suppression (on peut aussi les séparer si tu préfères)

// **CONFIGURATION**
$host = "localhost";
$user = "root";
$pass = "";
$db   = "recensement";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

// S’assurer qu’on utilise le bon charset
$conn->set_charset("utf8mb4");

// Fonction de nettoyage simple
function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// =====================
// INSERTION
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kaydet'])) {
    // Nettoyage & validation
    $prenom     = clean_input($_POST['prenom'] ?? '');
    $nom        = clean_input($_POST['nom'] ?? '');
    $email      = clean_input($_POST['email'] ?? '');
    $numero     = clean_input($_POST['numero'] ?? '');
    $filiere    = clean_input($_POST['filiere'] ?? '');
    $numero_tel = clean_input($_POST['numero_tel'] ?? '');
    $numero_pas = clean_input($_POST['numero_pas'] ?? '');
    $numero_kim = clean_input($_POST['numero_kim'] ?? '');
    $adresse    = clean_input($_POST['adresse'] ?? '');
    $etat_f     = clean_input($_POST['etat_f'] ?? '');

    // Vérifications simples
    $errors = [];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }
    if (!is_numeric($numero_tel)) {
        $errors[] = "Numéro de téléphone doit être numérique.";
    }
    if (!is_numeric($numero_kim)) {
        $errors[] = "Kimlik doit être numérique.";
    }
    // Autres vérifications selon besoin...

    if (count($errors) === 0) {
        // Préparer la requête
        $stmt = $conn->prepare("
            INSERT INTO recensement
                (prenom, nom, email, numero, filiere, numero_tel, numero_pas, numero_kim, adresse, etat_f)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            die("Erreur de préparation: " . $conn->error);
        }
        // Lier les paramètres : types selon les champs : s = string, i = integer
        // Ici, j’imagine que numero, numero_tel, numero_kim sont des entiers, les autres des chaînes.
        $stmt->bind_param(
            "sssisissss",
            $prenom,
            $nom,
            $email,
            $numero,
            $filiere,
            $numero_tel,
            $numero_pas,
            $numero_kim,
            $adresse,
            $etat_f
        );
        if ($stmt->execute()) {
            echo "Ajout réussi !";
            // Tu peux faire une redirection : header("Location: success.php");
        } else {
            echo "Erreur lors de l’insertion : " . $stmt->error;
        }
        $stmt->close();
    } else {
        foreach ($errors as $e) {
            echo "<p style='color:red;'>$e</p>";
        }
    }
}

// =====================
// SUPPRESSION
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $email_delete = clean_input($_POST['email'] ?? '');
    if (!filter_var($email_delete, FILTER_VALIDATE_EMAIL)) {
        echo "Email de suppression invalide.";
    } else {
        $stmt = $conn->prepare("DELETE FROM recensement WHERE email = ?");
        if (!$stmt) {
            die("Erreur de préparation DELETE: " . $conn->error);
        }
        $stmt->bind_param("s", $email_delete);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Utilisateur supprimé avec succès.";
                // Redirection possible
            } else {
                echo "Aucun utilisateur trouvé avec cet email.";
            }
        } else {
            echo "Erreur lors de la suppression : " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>
