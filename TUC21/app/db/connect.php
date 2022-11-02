<?php

require('env.php');

try {
    $conn = new PDO(DATABASE['DB_DATABASE'] . ":host=" . DATABASE['DB_HOST'] . ";dbname=" . DATABASE['DB_NAME'] . ";port=" . DATABASE['DB_PORT'], DATABASE['DB_USERNAME'], DATABASE['DB_PASSWORD']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error:'.$e->getMessage();
}