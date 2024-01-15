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

    public function addAddress($numCivico, $via, $cap, $attivo, $idUtente) {
        $query = "
            INSERT INTO indirizzo (NumCivico, Via, CAP, Attivo, ID_Utente)
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('isiii', $numCivico, $via, $cap, $attivo, $idUtente);
        $stmt->execute();
    }

    public function countAddressOfUser($idUtente){
        $query = "
            SELECT COUNT(*) as count FROM indirizzo
            WHERE ID_Utente = ?
        ";

        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i',$idUtente);
        $stmt->execute();
        // Associare una variabile al risultato
        $stmt->bind_result($count);
        
        // Recuperare il risultato
        $stmt->fetch();
        
        // Chiudere l'istruzione preparata
        $stmt->close();
        
        // Restituire il risultato
        return $count;
    }

    public function getAddressOfUser($userID){
        $query = "
            SELECT *
            FROM indirizzo
            WHERE ID_Utente=?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        
        $addresses = array();

        while ($row = $result->fetch_assoc()) {
            $addresses[] = $row;
        }

        return $addresses;
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

    public function addTotalInInsertion($idInserzione) {
        $query = "
            UPDATE inserzione
            SET TotCosto = (
                SELECT SUM(Prezzo_unitario)
                FROM oggetto
                WHERE IDInserzione = ?
            )
            WHERE ID = ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idInserzione, $idInserzione);
        $stmt->execute();
    }

    public function getInsertions() {
        $query = "
            SELECT I.Descrizione, O.Nome, O.Usura, O.Prezzo_unitario, I.TotCosto, MIN(I.ID) AS UniqueID
            FROM Inserzione I
            JOIN Oggetto O ON I.ID = O.IDInserzione
            GROUP BY I.Descrizione, O.Nome, O.Usura, O.Prezzo_unitario, I.TotCosto;        
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getNumberOfInsertions() {
        $query = "
            SELECT COUNT(*) as nInsertion
            FROM Inserzione I
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getInsertionObjects($IDInserzione) {
        $query = "
            SELECT O.Nome, O.Usura, O.Prezzo_unitario
            FROM Oggetto O
            WHERE O.IDInserzione = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $IDInserzione);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addOrder($utenteID) {
        $query = "
            INSERT INTO ordine (IDUtente)
            VALUES (?)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $utenteID);
        $stmt->execute();
    }

    public function getOrderCode($utenteId) {
        $query = "
            SELECT O.Cod_Ordine
            FROM Ordine O
            WHERE O.IDUtente = ?
            ORDER BY O.Cod_Ordine DESC 
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $utenteId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getInsertionItem($insertionId) {
        $query = "
            SELECT O.Nome
            FROM Oggetto O
            WHERE O.IDInserzione = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $insertionId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addOrderDetail($orderID, $insertionID, $object) {
        $query = "
            INSERT INTO Dettaglio_ordine (Cod_Ordine, ID_Inserzione, NomeOggetto)
            VALUES (?, ?, ?)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iis', $orderID, $insertionID, $object);
        $stmt->execute();
    }

    public function isObjectRank($nameObject) {
        $query = "
            SELECT C.Qta
            FROM Classifica_oggetto C
            WHERE C.NomeOggetto = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $nameObject);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Qta'];
        }
        return false;
    }

    public function updateObjectRank($nameObject) {
        $query = "
            UPDATE classifica_oggetto
            SET Qta = Qta + 1 
            WHERE NomeOggetto = ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $nameObject);
        $stmt->execute();
    }

    public function addObjectRank($nameObject) {
        $query = "
            INSERT INTO classifica_oggetto (NomeOggetto, Qta)
            VALUES (?, 1)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $nameObject);
        $stmt->execute();
    }
}
?>