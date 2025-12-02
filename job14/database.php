<?php

function db_connect()
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $pdo = new PDO("mysql:host=localhost:3306;dbname=draft-shop", "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les exceptions en cas d'erreur SQL
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retourne les résultats sous forme de tableau associatif
            ]);
            // $pdo = PDO::connect("mysql:host=localhost:3306;dbname=draft-shop", "root", "");
        } catch (PDOException $e) {
            exit('Erreur de connexion BDD');
        }
    }

    return $pdo;
}

function db_select($query, $params = [])
{
    $pdo = db_connect();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function db_select_one($query, $params = [])
{
    $pdo = db_connect();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetch();
}

function db_execute($query, $params = [])
{
    $pdo = db_connect();
    $stmt = $pdo->prepare($query);
    return $stmt->execute($params);
}

function get_last_inserted()
{
    $pdo = db_connect();
    $stmt = $pdo->lastInsertId();
    return $stmt;
}

/**
 * Makes a transation for multiple exec purposes.
 * 
 * @param array $quert_param_arr MUST be built as : 
 * $array = [["query" => $query, "param" => $param], repeat...]
 */
function db_transaction($query_param_arr)
{
    $pdo = db_connect();
    try {
        $pdo->beginTransaction();

        foreach ($query_param_arr as $qp) {
            db_execute($qp["query"], $qp["params"]);
            sleep(1);
        }
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Issue during transaction... ", $e->getMessage();
    }
}
