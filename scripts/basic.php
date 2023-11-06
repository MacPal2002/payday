<?php
    include_once(__DIR__ . '/../config/config.php'); // Importuj plik konfiguracyjny tylko raz na poziomie głównego skryptu

    $response = array(
        'success' => false, // Flaga sukcesu - początkowo ustawiona na false
        'error' => array(null, null), // Tablica do przechowywania komunikatu błędu i kodu błędu
        'data' => null, // Dane wynikowe - początkowo brak danych
    );



    function connect() {
        global $dbHost, $dbUsername, $dbPassword, $dbName; // Odczytaj zmienne globalne

        try {
            $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            die("Error: Can't connect to the database: " . $e->getMessage());
        }
    }

    function query($queries, $useTransaction = false) {
        $db = connect();
        $response = $GLOBALS['response'];
        $transactionStarted = false;
        $allData = array();
        $queryResults = array();
    
        try {
            if ($useTransaction) {
                $db->beginTransaction();
                $transactionStarted = true;
            }

            if(count($queries) == 0 || $queries == NULL){
                $response['error'][0] = "Queries must not be empty";
                $response['success'] = false;

                return $response;
                
            }

            
            foreach ($queries as $query) {
                $sql = $query['sql'];
                $parameters = $query['parameters'];
    
                $stmt = $db->prepare($sql);
                foreach ($parameters as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
    
                if ($stmt->execute()) {
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $allData[] = $result;
                    $stmt->closeCursor();
                    $queryResults[] = true;
                } else {
                    $queryResults[] = false;
                }
            }
    
            if ($useTransaction) {
                if ($transactionStarted) {
                    if (in_array(false, $queryResults)) {

                        $db->rollBack();
                    } else {

                        $db->commit();
                    }
                }
            }
    
            $response['success'] = !in_array(false, $queryResults);
    
            // Jeśli przekazano pojedyncze zapytanie, zwróć wyniki bez dodatkowej tablicy
            $response['data'] = (count($queries) === 1) ? $allData[0] : $allData;
        } catch (PDOException $e) {
            $response['error'][0] = "PDOException: " . $e->getMessage();
            $response['error'][1] = $e->getCode();
    
            if ($transactionStarted) {
                $db->rollBack();
            }
    
            $response['success'] = false;
            $response['data'] = null;

        }   catch (TypeError $e) {
            $response['error'][0] = "TypeError: " . $e->getMessage();
            $response['success'] = false;
            $response['data'] = null;

        }   catch (ValueError $e) {
            $response['error'][0] = "ValueError: " . $e->getMessage();
            $response['success'] = false;
            $response['data'] = null;
        }
        return $response;
    }
    
    
    
    
    
    
    
    
    
    










?>