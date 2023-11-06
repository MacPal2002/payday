<?php
    session_start();
    // przekierowanie na stronę logowania jeśli użytkownik nie jest zalogowany
    if(!($_SESSION[logged] == 'true' && ($_SESSION['userRole'] == 'user' || $_SESSION['userRole'] == 'admin'))) {
        header('Location: ../auth/index.php');
    }



?>