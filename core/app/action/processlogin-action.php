<?php
// Iniciamos sesión si no está iniciada
if(!isset($_SESSION)) {
    session_start();
}

// Solo procesamos si NO hay una sesión activa de usuario
if(!isset($_SESSION["user_id"])) {
    
    // Validamos que lleguen los datos por POST
    $user = isset($_POST['username']) ? $_POST['username'] : '';
    $pass_raw = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Cifrado compatible con tu versión anterior
    $pass = sha1(md5($pass_raw));

    $base = new Database();
    $con = $base->connect();

    // Verificamos si hubo error de conexión
    if ($con->connect_error) {
        die("❌ Error de conexión: " . $con->connect_error);
    }

    // Consulta SQL con comillas simples (estándar de PHP 8 / MySQL 8)
    $sql = "SELECT * FROM user WHERE (email='$user' OR username='$user') AND password='$pass' AND status=1";
    
    $query = $con->query($sql);

    // Si la consulta falla, nos dirá exactamente por qué
    if (!$query) {
        die("❌ Error de SQL: " . $con->error . "<br><strong>Consulta ejecutada:</strong> " . $sql);
    }

    $found = false;
    $userid = null;

    // Recorremos los resultados
    while($r = $query->fetch_array()){
        $found = true ;
        $userid = $r['id'];
    }

    if($found == true) {
        $_SESSION['user_id'] = $userid;
        echo "Cargando ... $user";
        echo "<script>window.location='index.php?view=home';</script>";
    } else {
        // Si no se encuentra el usuario, vuelve al login
        echo "<script>alert('Datos incorrectos o usuario inactivo'); window.location='index.php?view=login';</script>";
    }

} else {
    // Si ya tiene sesión, lo mandamos al inicio
    echo "<script>window.location='index.php?view=home';</script>";
}
?>