

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "recensement";

$conn = new mysqli($host, $user, $pass, $db);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Kullanıcı ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prenom = $_POST['prenom'];
    $nom= $_POST['nom'];
    $email = $_POST['email'];
    $numero = $_POST['numero'];
    $filiere = $_POST['filiere'];
    $numero_tel = $_POST['numero_tel'];
    $numero_pas = $_POST['numero_pas'];
    $numero_kim = $_POST['numero_kim'];
    $adresse = $_POST['adresse'];
    $etat_f = $_POST['etat_f'];
            $sql = "INSERT INTO recensement (prenom, nom, email,numero, filiere, numero_tel, numero_pas, numero_kim,adresse,etat_f  )
            VALUES ('$prenom', '$nom', '$email','$numero', '$filiere', '$numero_tel', '$numero_pas','$numero_kim','$adresse ','$etat_f')";
    if ($conn->query($sql) === TRUE) {
        echo "votre connexion a la base est reussie!!!";
    } else {
        echo "Hata: " . $conn->error;
    }
}



