<?php
    include_once('../scripts/utils.php');

    session_start();

    // Sprawdź, czy użytkownik jest zalogowany
    if (isset($_SESSION["logged"])) {
        header("Location: ../panel/" . $_SESSION['userRole'] . "/"); // Przekieruj do panelu użytkownika, jeśli użytkownik  jest zalogowany
        exit();
    }

    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $result = login($username, $password);

        if($result['success']) {
            $_SESSION['logged'] = true;
            $_SESSION['idUser'] = $result['data']['user']['id'];
            $_SESSION['userRole'] = $result['data']['user']['userRole'];
            header("Location: ../panel/" . $_SESSION['userRole'] . "/"); // Przekieruj jeśli pomyślnie zalogowano
        } else{
            $errorMessage = $result['error'];
        }
    }

    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="..style/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../style/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../style/favicon/favicon-16x16.png">
    <link rel="manifest" href="../style/favicon/site.webmanifest">
    <title>PayDay</title>
    <!-- Dodaj link do Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Opcjonalny plik CSS dla niestandardowych stylów -->
    <link rel="stylesheet" href="../style/style.css">
</head>
<body class="d-flex flex-column">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom fs-3" href="../index.php">Pay Day</a>
        </div>
    </nav>
    <main style="flex: 1;" class="container my-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Log in
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST">
                            <?php
                                if (isset($errorMessage)) {
                                    echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
                                }
                            ?>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Dodaj link do Bootstrap 5 JavaScript (Popper.js i Bootstrap JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
