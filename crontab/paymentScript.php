<?php
    include_once('../scripts/utils.php');

    // crontab
    // 0 1 * * * /usr/bin/php paymentScript.php >> logs.log 2>&1


    $today = date("Y-m-d");
    echo "---------------------------------------\n";
    echo 'Rozpoczynanie aktualizacji płatności na dzień: ' . $today . '\n';

    // Pobieranie wszystkich użytkowników
    $allUsers = getUsers('all');

    if ($allUsers['success']) {
        $users = $allUsers['data']['users'];

        foreach ($users as $user) {
            $idUser = $user['id'];
            $username = $user['username'];

            echo "Przetwarzanie użytkownika: $username (ID: $idUser)\n";

            // Pobieranie subskrypcji danego użytkownika
            $userSubs = getAllUserSubs($idUser);

            if ($userSubs['success']) {
                $subscriptions = $userSubs['data']['subscriptions'];

                if (count($subscriptions) > 0) {
                    echo "Subskrypcje użytkownika:\n";
                    foreach ($subscriptions as $subscription) {
                        $idSubscription = $subscription['id'];
                        $vodType = $subscription['vodType'];
                        $price = $subscription['price'];
                        $nextBillingDate = $subscription['nextBillingDate'];

                        // Sprawdzenie czy data następnej płatności minęła
                        if ($today >= $nextBillingDate) {
                            // Pobieranie stanu konta użytkownika
                            $userBalance = getAccountBalance($idUser);
                            

                            if ($userBalance['success'] && $userBalance['data']['balance'] >= $price) {
                                echo "Powodzenie. Pobrano $price zł od użytkownika $username za subskrypcję typu: $vodType\n";

                                // Aktualizacja stanu konta użytkownika po pobraniu płatności
                                processBalanceUpdate($idUser, 'bill', $idSubscription);

                            } else if($userBalance['success'] && $userBalance['data']['balance'] < $price){
                                if ($userBalance['data']['balance'] - $price > $allowedDebt){
                                    echo "Warunek! Dozwolony debet. Brak wystarczających środków na koncie użytkownika $username do opłacenia subskrypcji typu: $vodType\n";
                                    // Tu trzeba zrobić obsługę powiadomień email.

                                    processBalanceUpdate($idUser, 'bill', $idSubscription);

                                } else{
                                    deactivateUserSubs($id, 'suspend');
                                    echo "Niepowodzenie. Brak wystarczających środków na koncie użytkownika $username do opłacenia subskrypcji typu: $vodType\n";
                                }
                            }
                        } else {
                            echo "Data następnej płatności dla subskrypcji typu: $vodType jeszcze nie nadeszła.\n";
                        }
                    }
                } else {
                    echo "Użytkownik nie ma aktywnych subskrypcji.\n";
                }
            } else {
                echo "Błąd podczas pobierania subskrypcji użytkownika: " . $userSubs['error'] . "\n";
            }

            echo "---------------------------------------\n";
        }
    } else {
        echo "Błąd podczas pobierania użytkowników: " . $allUsers['error'] . "\n";
    }

    echo "Koniec aktualizacji płatności na dzień: \n " . $today;
    echo "---------------------------------------\n";


        
?>
