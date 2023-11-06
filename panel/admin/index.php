<?php
    session_start();
        // przekierowanie na stronę logowania jeśli użytkownik nie jest zalogowany, tylko dla adminów
    if(!($_SESSION[logged] == 'true' && $_SESSION['userRole'] == 'admin')) {
        header('Location: ../auth/index.php');
    }



?>