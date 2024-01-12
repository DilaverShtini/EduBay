<?php
class DatabaseHelper{
    public $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    public function addInsertion($descrizione, $idUtente) {
        $query = "
            INSERT INTO inserzione (Descrizione, IDUtente)
            VALUES (?, ?)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $descrizione, $idUtente);
        $stmt->execute();
    }

    public function insertWallet($userID, $saldo){
        $query = "
            INSERT INTO portafoglio (IDUtente, Saldo)
            VALUES (?, ?)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('id', $userID, $saldo);
        $stmt->execute();
    }

    public function getWalletOfUser($userID){
        $query = "
            SELECT Saldo
            FROM portafoglio
            WHERE IDUtente=?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getLastInsertionId() {
        $query = "
            SELECT ID
            FROM Inserzione
            ORDER BY ID DESC
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //LAST_INDEX_ID()
    public function addObject($nomeOggetto, $prezzoOggetto, $livelloUsura) {
        $query = "
            INSERT INTO oggetto (Nome, Prezzo_Unitario, Usura, IDInserzione)
            VALUES (?, ?, ?, (SELECT ID
                              FROM Inserzione
                              ORDER BY ID DESC
                              LIMIT 1))
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sds', $nomeOggetto, $prezzoOggetto, $livelloUsura);
        $stmt->execute();
    }
    

}
?>