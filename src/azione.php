<?php
require_once './db/database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['address_id'])) {
    $addressId = $_GET['address_id'];
    $idUtente = $_SESSION['ID'];

    $dbH = new DatabaseHelper("localhost", "root", "", "edubay", 3307);

    // Disattiva gli altri indirizzi dell'utente
    $dbH->disactivateOtherAddresses($addressId, $idUtente);

    // Attiva o disattiva l'indirizzo specificato
    if (!$dbH->isAddressActive($addressId)) {
        $dbH->activateAddress($addressId);
    }

    echo json_encode($dbH->isAddressActive($addressId));
} else {
    // Gestisci eventuali errori
    echo "Errore nella richiesta";
}
?>