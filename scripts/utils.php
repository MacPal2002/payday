<?php
    include_once(__DIR__ . './basic.php');

    $sqlArray =  array( 
        array(
            // Zapytanie SQL
            'sql' => null,
            // Parametry do zapytania SQL
            'parameters' => null
        ),
    );

    /**
     * Metody Autentykacji
     * 
     * Ta sekcja zawiera funkcje związane z uwierzytelnianiem i autoryzacją użytkowników.
     * Te funkcje umożliwiają logowanie, tworzenie i zarządzanie kontami użytkowników,
     * oraz sprawdzanie autentykacji w celu kontrolowania dostępu.
     */






    /**
     * Funkcja tworzenia konta
     *
     * @param string $username - Nazwa użytkownika.
     * @param string $password - Hasło użytkownika.
     * @param string $userRole - Rola użytkownika (domyślnie 'user').
     *
     */
    function createAccount($username, $password, $userRole='user') {
        $sqlArray = $GLOBALS['sqlArray'];

        // Zapytanie SQL do dodawania nowego użytkownika do bazy danych.
        $sqlArray[0]['sql'] = 'INSERT INTO users (username, password, userRole) VALUES (:username, :password, :userRole)';
        // Parametry do zapytania SQL, w tym zahaszowane hasło.
        $sqlArray[0]['parameters'] = [':username' => $username,  ':password' => password_hash($password, PASSWORD_BCRYPT), ':userRole' => $userRole];
        
        
        // Wykonanie zapytania i uzyskanie wyniku.
        $queryResult = query($sqlArray);

        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        if ($queryResult['success']) {
            // W przypadku sukcesu, zaktualizuj odpowiedź i ustaw flagę sukcesu.
            $response['success'] = true;
        } else {
            // W przypadku błędu, dostosuj komunikat błędu w zależności od rodzaju błędu.
            if ($queryResult['error'][1] == 23000) {
                $response['error'] = "A user with the given name already exists.";
            } else {
                $response['error'] = "An error occurred while executing the query.";
            }
        }

        // Zwróć odpowiedź.
        return $response;
    }

    
    

    /**
     * Funkcja logowania użytkownika
     *
     * @param string $username - Nazwa użytkownika.
     * @param string $password - Hasło użytkownika.
     *
     */
    function login($username, $password) {

        $sqlArray = $GLOBALS['sqlArray'];
        
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        // Zapytanie SQL do pobierania danych użytkownika na podstawie nazwy użytkownika.
        $sqlArray[0]['sql'] = 'SELECT id, username, password, userRole FROM users WHERE username = :username)';
        // Parametry do zapytania SQL, w tym nazwa użytkownika.
        $sqlArray[0]['parameters'] = [':username' => $username];
        
        

        // Wykonanie zapytania i uzyskanie wyniku.
        $queryResult = query($sqlArray);


        if ($queryResult['success'] && count($queryResult['data']) > 0) {
            // Jeśli zapytanie się powiodło i znaleziono użytkownika, sprawdź hasło.
            $user = $queryResult['data'][0];
            if (password_verify($password, $user['password'])) {
                // Jeśli hasło jest poprawne, ustaw flagę sukcesu i zwróć dane użytkownika.
                $response['success'] = true;
                $response['data'] = ['user' => $user];
            } else {
                // W przeciwnym razie, hasło jest nieprawidłowe.
                $response['error'] = "Incorrect password.";
            }
        } else if ($queryResult['success']) {
            // Jeśli zapytanie się powiodło, ale użytkownik nie został znaleziony.
            $response['error'] = "User not found.";
        } else {
            // Jeśli wystąpił błąd podczas wykonywania zapytania.
            $response['error'] = "Unable to login.";
        }

        // Zwróć odpowiedź.
        return $response;
    }

    /**
     * Funkcja usuwania użytkownika
     *
     * @param int $idUser - Identyfikator użytkownika do usunięcia.
     *
     */
    function deleteAccount($idUser) {

        $sqlArray = $GLOBALS['sqlArray'];
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        // Zapytanie SQL do usuwania użytkownika na podstawie identyfikatora użytkownika.
        $sqlArray[0]['sql'] = 'DELETE FROM users WHERE id = :idUser';
        // Parametry do zapytania SQL, zawierające identyfikator użytkownika.
        $sqlArray[0]['parameters'] = [':idUser' => $idUser];
        
        // Wykonanie zapytania i uzyskanie wyniku.
        $queryResult = query($sqlArray);

        
        if ($queryResult['success']) {
            // W przypadku sukcesu, ustaw flagę sukcesu.
            $response['success'] = true;
        } else {
            // W przypadku błędu, dostosuj komunikat błędu.
            $response['error'] = "An error occurred while deleting the user.";
        }

        // Zwróć odpowiedź.
        return $response;
    }

    /**
     * Funkcja zmiany hasła użytkownika
     *
     * @param int $idUser - Identyfikator użytkownika, któremu zmieniane jest hasło.
     * @param string $newPassword - Nowe hasło użytkownika.
     *
     */
    function changePassword($idUser, $newPassword) {
        // Zahaszowanie nowego hasła.
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $sqlArray = $GLOBALS['sqlArray'];
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

       // Zapytanie SQL do aktualizacji hasła użytkownika na podstawie identyfikatora użytkownika.
        $sqlArray[0]['sql'] = 'UPDATE users SET password = :newPassword WHERE id = :idUser';
        // Parametry do zapytania SQL, zawierające nowe zahaszowane hasło i identyfikator użytkownika.
        $sqlArray[0]['parameters'] = [ ':newPassword' => $hashedPassword, ':idUser' => $idUser];


        // Wykonanie zapytania i uzyskanie wyniku.
        $queryResult = query($sqlArray);


        if ($queryResult['success']) {
            // W przypadku sukcesu, ustaw flagę sukcesu.
            $response['success'] = true;
        } else {
            // W przypadku błędu, dostosuj komunikat błędu.
            $response['error'] = "Unable to change the user's password.";
        }

        // Zwróć odpowiedź.
        return $response;
    }

    
    


        /**
     * Metody Zarządzania Subskrypcjami
     * 
     * Ta sekcja zawiera funkcje do zarządzania subskrypcjami użytkowników, pozwalając im zmieniać
     * różne rodzaje subskrypcji. Te funkcje umożliwiają aktywowanie i dezaktywowanie subskrypcji.
     */







    /**
     * Funkcja aktywacji subskrypcji użytkownika
     *
     * @param int $idUser - Identyfikator użytkownika.
     * @param string $vodType - Rodzaj subskrypcji (np. 'basic', 'premium', itp.).
     * @param float $price - Cena subskrypcji.
     *
     */
    function activeUserSubs($idUser, $vodType, $price) {

        $sqlArray = $GLOBALS['sqlArray'];
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        // Zapytanie SQL do dodawania aktywnej subskrypcji użytkownika.
        $sqlArray[0]['sql'] = "INSERT INTO subscriptions (idUser, vodType, subscribeDate, status, price) VALUES (:idUser, :vodType, CURRENT_DATE(), 'active', :price)";
        // Parametry do zapytania SQL, w tym identyfikator użytkownika, rodzaj subskrypcji, data subskrypcji i cena.
        $sqlArray[0]['parameters'] = [ ':idUser' => $idUser, ':vodType' => $vodType, ':price' => $price];
        
        
        // Wykonanie zapytania i uzyskanie wyniku.
        $queryResult = query($sqlArray);


        if ($queryResult['success']) {
            // W przypadku sukcesu, ustaw flagę sukcesu.
            $response['success'] = true;
        } else {
            // W przypadku błędu, dostosuj komunikat błędu.
            $response['error'] = "Unable to subscribe the user.";
        }

        // Zwróć odpowiedź.
        return $response;
    }



    /**
     * Funkcja dezaktywacji subskrypcji
     *
     * @param int $idSubscription - Identyfikator subskrypcji do dezaktywacji.
     *
     */
    function deactivateUserSubs($idSubscription, $status='inactive') {
        // Zapytanie SQL do dezaktywacji subskrypcji na podstawie identyfikatora subskrypcji.
        if($status = 'inactive'){
            $sql = "UPDATE subscriptions SET status = 'inactive', cancelDate = CURRENT_DATE() WHERE id = :idSubscription";
        }   else if($status == 'suspend'){
            $sql = "UPDATE subscriptions SET status = 'suspend', cancelDate = CURRENT_DATE() WHERE id = :idSubscription";
        }

        $sqlArray = $GLOBALS['sqlArray'];
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        
        $sqlArray[0]['sql'] = $sql;
        $sqlArray[0]['parameters'] =  [':idSubscription' => $idSubscription];
        

        // Wykonanie zapytania i uzyskanie wyniku.
        $queryResult = query($sqlArray);

        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        if ($queryResult['success']) {
            // W przypadku sukcesu, ustaw flagę sukcesu.
            $response['success'] = true;
        } else {
            // W przypadku błędu, dostosuj komunikat błędu.
            $response['error'] = "Unable to deactivate the subscription.";
        }

        // Zwróć odpowiedź.
        return $response;
    }


    
    /**
     * Metody Pobierania/aktualizawania Danych
     * 
     * Ta sekcja zawiera funkcje do pobierania/aktualizowania różnych danych z bazy danych.
     * Te funkcje umożliwiają dostęp do subskrypcji użytkowników, historii płatności, historii doładowań,
     * i innych informacji.
     */





    /**
     * Funkcja pobierania stanu konta użytkownika
     *
     * @param int $idUser - Identyfikator użytkownika.
     *
     */
    function getAccountBalance($idUser) {

        $sqlArray = $GLOBALS['sqlArray'];
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        $parameters = [':idUser' => $idUser];
  
        $sqlArray[0]['sql'] = 'SELECT * FROM balances WHERE idUser = :idUser';
        $sqlArray[0]['parameters'] = $parameters;

        // Wykonanie zapytania i uzyskanie wyniku.
        $checkResult = query($sqlArray);
        
        if ($checkResult['success']) {
            $existingBalances = $checkResult['data'];
    
            if (count($existingBalances) == 0) {
                $sqlArray[1] = $sqlArray[0];
                // Jeśli wpis nie istnieje, dodaj nowy wpis z saldem początkowym równym 0
                $sqlArray[0]['sql'] = "INSERT INTO balances (idUser, balance) VALUES (:idUser, 0)";
                $sqlArray[1]['sql'] = 'SELECT * FROM balances WHERE idUser = :idUser';
                $queryResult = query($sqlArray);
                $createdBalances = $queryResult['data'][1];
    
                if ($queryResult['success']) {

                    $response['success'] = true;
                    $response['data'] = ['balance' => $createdBalances[0]['balance'], 'idBalance' => $createdBalances[0]['id'] ];
                    return $response;
                } else {

                    $response['error'] = "Error while adding a new balance entry.";
                    return $response;
                }
            } else{
                $response['success'] = true;
                $response['data'] = ['balance' => $existingBalances[0]['balance'], 'idBalance' => $existingBalances[0]['id'] ];
                return $response;
            }

        } else{
            $response['error'] = "Unable to retrieve account balance.";
            return $response;
        }
    }

    /**
     * Funkcja aktualizowania salda użytkownika w tabeli "balances".
     *
     * @param int $idUser - ID użytkownika.
     * @param string $operation - dozwolone wartości bill oraz topup.
     * @param int $variable - z zależności od parametru $operation jest to $idSubscription albo $amount.
     *
     */                                         
    //                                       bill ord topup
    function processBalanceUpdate($idUser, $operaction = NULL, $variable = NULL) {
        // pobiera z configu co ile odnawia się subskrypcja.
        $monthInterval = $GLOBALS['monthInterval'];   

        $sqlArray = $GLOBALS['sqlArray'];
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        // Pobranie/doładowanie  z konta
        $sqlArray[0]['sql'] = 'UPDATE balances SET balance = balance + :amount WHERE idUser = :idUser';
        $sqlArray[0]['parameters'] = [':amount' => NULL,  ':idUser' => $idUser];

        if ($operaction == 'bill' && $variable != NULL) {
            
            $idSubscription = $variable; 
            
            $userIdSubs = getAllUserSubs($idUser)['data']['subscriptions'];
            foreach ($userIdSubs as $userIdSub){
                if ($idSubscription == $userIdSub['id']) {

                    $sqlArray[0]['parameters'][':amount'] = -$userIdSub['price'];
                    $sqlArray[1] = $sqlArray[0];
                    // Aktualizacja daty odnowienia subskrypcji
                    $sqlArray[1]['sql'] = 'INSERT INTO bills (idSubscription, paymentDate, nextBillingDate) VALUES (:idSubscription, CURRENT_DATE(), DATE_ADD(CURRENT_DATE(), INTERVAL :monthInterval MONTH))';
                    $sqlArray[1]['parameters'] = [':idSubscription' => $idSubscription, ':monthInterval' => $monthInterval];
                    break;
                }
                      
            }

            if(!isset($sqlArray[1])){
                $response['error'] = 'idSubscription must be associated with specified user';
                unset($sqlArray[0]);

                return $response;
            }
            
                
        } elseif($operaction == 'topup' && $variable != NULL) {

                $amount = $variable;

                $idBalance = getAccountBalance($idUser)['data']['idBalance'];
                if($idBalance) {

                    $sqlArray[0]['parameters'][':amount'] = $amount;
                    $sqlArray[1] = $sqlArray[0];
                    // Wpisanie doładowania do historii doładowań
                    $sqlArray[1]['sql'] = 'INSERT INTO top_ups (idBalance, amount, topUpDate) SELECT :idBalance, :amount, CURRENT_DATE() FROM balances WHERE idUser = :idUser';
                    $sqlArray[1]['parameters'] = [':idBalance' => $idBalance, ':idUser' => $idUser, ':amount' => $amount];
                }   else{

                    $response['error'] = 'Unable to retrieve account balance';
                    unset($sqlArray[0]);

                    return $response;
                }

        } else{
            $response['error'] = 'Wrong arguments';
            unset($sqlArray[0]);

            return $response;
        }
        
        $queryResult = query($sqlArray, true);
    
        if ($queryResult['success']) {
            $response['success'] = true;
            return $response;
        } else {
            $response['error'] = "Error while processing balance update.";
            return $response;
        }
    }
    


    /**
     * Funkcja pobierania użytkowników
     *
     * @param string $userRole - Rola użytkownika ('all' lub konkretna rola).
     *
     */
    function getUsers($userRole) {

        $sqlArray = $GLOBALS['sqlArray'];
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        // Zapytanie SQL do pobierania użytkowników na podstawie roli (lub wszystkich użytkowników, jeśli 'all').
        $sql = "SELECT * FROM users";
        $parameters = null;

        if ($userRole != 'all') {
            $sql .= " WHERE userRole = :userRole";
            $parameters = [':userRole' => $userRole];
        }

        $sqlArray[0]['sql'] = $sql;
        $sqlArray[0]['parameters'] = $parameters;

        // Wykonanie zapytania i uzyskanie wyniku.
        $queryResult = query($sqlArray);

        
        if ($queryResult['success'] && count($queryResult['data']) > 0) {
            // Jeśli zapytanie się powiodło i znaleziono użytkowników, ustaw flagę sukcesu i zwróć listę użytkowników.
            $response['success'] = true;
            $response['data'] = ['users' => $queryResult['data']];
        } else {
            // W przypadku błędu, dostosuj komunikat błędu.
            $response['error'] = "Unable to retrieve users.";
        }

        // Zwróć odpowiedź.
        return $response;
    }

    /**
     * Funkcja pobierania wszystkich subskrypcji użytkownika
     *
     * @param int $idUser - Identyfikator użytkownika.
     *
     */
    function getAllUserSubs($idUser, $status='active') {

        $sqlArray = $GLOBALS['sqlArray'];
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        // Zapytanie SQL do pobierania wszystkich subskrypcji użytkownika na podstawie jego identyfikatora.
        $sql = "SELECT s.*, MAX(b.nextBillingDate) AS nextBillingDate
                FROM subscriptions s
                LEFT JOIN bills b ON s.id = b.idSubscription
                WHERE idUser = :idUser AND s.status = :status
                GROUP BY s.vodType";

        $sqlArray[0]['sql'] = $sql;
        $sqlArray[0]['parameters'] = [':idUser' => $idUser, ':status' => $status,];

        // Wykonanie zapytania i uzyskanie wyniku.
        $queryResult = query($sqlArray);

        if ($queryResult['success']) {
            // Jeśli zapytanie się powiodło, ustaw flagę sukcesu i zwróć listę subskrypcji użytkownika.
            $response['success'] = true;
            $response['data'] = ['subscriptions' => $queryResult['data']];
        } else {
            // W przypadku błędu, dostosuj komunikat błędu.
            $response['error'] = "Unable to retrieve user subscriptions.";
        }

        // Zwróć odpowiedź.
        return $response;
    }

    /**
     * Funkcja pobierania historii płatności użytkownika
     *
     * @param int $idUser - Identyfikator użytkownika.
     *
     */
    function getUserBillHistory($idUser) {

        $sqlArray = $GLOBALS['sqlArray'];
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        // Zapytanie SQL do pobierania historii płatności użytkownika na podstawie jego identyfikatora.
        $sql = "SELECT b.*, s.vodType
                FROM bills b
                INNER JOIN subscriptions s ON b.idSubscription = s.id
                WHERE s.idUser = :idUser";

        $sqlArray[0]['sql'] = $sql;
        $sqlArray[0]['parameters'] = [':idUser' => $idUser];

        // Wykonanie zapytania i uzyskanie wyniku.
        $queryResult = query($sqlArray);


        if ($queryResult['success']) {
            // Jeśli zapytanie się powiodło, ustaw flagę sukcesu i zwróć historię płatności użytkownika.
            $response['success'] = true;
            $response['data'] = ['billHistory' => $queryResult['data']];
        } else {
            // W przypadku błędu, dostosuj komunikat błędu.
            $response['error'] = "Unable to retrieve user bill history.";
        }

        // Zwróć odpowiedź.
        return $response;
    }

    /**
     * Funkcja pobierania historii doładowań użytkownika
     *
     * @param int $idUser - Identyfikator użytkownika.
     *
     */
    function getUserTopUpHistory($idUser) {

        $sqlArray = $GLOBALS['sqlArray'];
        // Inicjalizacja odpowiedzi.
        $response = $GLOBALS['response'];

        // Zapytanie SQL do pobierania historii doładowań użytkownika na podstawie jego identyfikatora.
        $sql = "SELECT t.*
                FROM top_ups t
                INNER JOIN balances b ON t.idUser = b.idUser
                WHERE t.idUser = :idUser";

        $sqlArray[0]['sql'] = $sql;
        $sqlArray[0]['parameters'] = [':idUser' => $idUser];

        // Wykonanie zapytania i uzyskanie wyniku.
        $queryResult = query($sqlArray);


        if ($queryResult['success']) {
            // Jeśli zapytanie się powiodło, ustaw flagę sukcesu i zwróć historię doładowań użytkownika.
            $response['success'] = true;
            $response['data'] = ['topupHistory' => $queryResult['data']];
        } else {
            // W przypadku błędu, dostosuj komunikat błędu.
            $response['error'] = "Unable to retrieve user top-up history.";
        }

        // Zwróć odpowiedź.
        return $response;
    }

    




    
?>