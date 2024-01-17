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
            INSERT INTO inserzione (Descrizione, IDUtente, Attivo)
            VALUES (?, ?, 1)
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
            SELECT DISTINCT(I.ID) AS UniqueID, I.Descrizione, O.Nome, O.Usura, O.Prezzo_unitario, I.TotCosto, I.Attivo
            FROM Inserzione I
            JOIN Oggetto O ON I.ID = O.IDInserzione
            GROUP BY I.ID
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderDetails(){
        $query = "
            SELECT * 
            FROM dettaglio_ordine
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

    public function getNumberOfOrderDetail() {
        $query = "
            SELECT COUNT(*) as nOrderDetail
            FROM dettaglio_ordine
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getInsertionObjects($IDInserzione) {
        $query = "
            SELECT O.ID, O.Nome, O.Usura, O.Prezzo_unitario
            FROM Oggetto O
            WHERE O.IDInserzione = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $IDInserzione);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function isInsertionActive($idInsertion){
        $query = "
            SELECT Attivo
            FROM Inserzione
            WHERE ID = ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idInsertion);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Attivo'] == 1; 
        }
    
        return false;
    }

    public function isAddressActive($idAddress){
        $query = "
            SELECT Attivo
            FROM indirizzo
            WHERE ID = ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idAddress);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Attivo'] == 1; 
        }
    
        return false;
    }

    public function getStateOfAddress($idAddress){
        $query = "
            SELECT Attivo
            FROM indirizzo
            WHERE ID = ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idAddress);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $row = $result->fetch_assoc();
        return $row;
    }

    public function activateAddress($idAddress){
        $query = "
            UPDATE indirizzo
            SET Attivo = 1
            WHERE ID = ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idAddress);
        $stmt->execute();
    
        return $stmt->affected_rows > 0;
    }

    public function disactivateOtherAddresses($idAddress, $idUtente) {
        $query = "
            UPDATE indirizzo
            SET Attivo = 0
            WHERE ID_Utente = ? AND ID <> ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idUtente, $idAddress);
        $stmt->execute();
        return $stmt->affected_rows > 0;
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
            SELECT O.Nome, I.TotCosto
            FROM Oggetto O
            JOIN Inserzione I ON I.ID = O.IDInserzione
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

    public function rimborso($insertionCost, $utenteID){
        $query = "
            UPDATE Portafoglio
            SET Saldo = Saldo + ?
            WHERE IDUtente = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('di', $insertionCost, $utenteID);
        $stmt->execute();
    }
    
    public function addResoInDettaglioOrdine($idInserzione, $idReso) {
        $query = "
            UPDATE dettaglio_ordine
            SET Cod_Reso = ? 
            WHERE ID_Inserzione = ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $idReso, $idInserzione);
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

    public function addReview($idUtenteRecensito, $idUtenteRecensore, $numStella) {
        $query = "
            INSERT INTO recensione (IDRecensito, IDRecensore, NumStelle)
            VALUES (?, ?, ?)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $idUtenteRecensito, $idUtenteRecensore, $numStella);
        $stmt->execute();
    }

    public function addReso() {
        $query = "
            INSERT INTO reso VALUES()
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
    }

    public function getInsertionsOnDetailOrder($idUtente){
        $query = "
            SELECT DISTINCT ID_Inserzione
            FROM dettaglio_ordine DT, ordine O
            WHERE DT.Cod_Ordine = O.Cod_Ordine AND O.IDUtente = ?
        ";

        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idUtente);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getInsertionDetailFromID($idInserzione){
        $query = "
            SELECT *
            FROM Inserzione
            WHERE ID = ?
        ";

        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idInserzione);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function isMoneyEnough($insertionCost, $utenteID) {
        $query = "
            SELECT P.Saldo
            FROM Portafoglio P
            WHERE P.IDUtente = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $utenteID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $saldo = $row['Saldo'];    
            return $saldo >= $insertionCost;
        } else {
            return false;
        }
    }

    public function removeMoney($insertionCost, $utenteID) {
        $query = "
            UPDATE Portafoglio
            SET Saldo = Saldo - ?
            WHERE IDUtente = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('di', $insertionCost, $utenteID);
        $stmt->execute();
    }

    public function disactiveInsertion($insertionID) {
        $query = "
            UPDATE Inserzione
            SET Attivo = 0
            WHERE ID = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $insertionID);
        $stmt->execute();
    }

    public function activateInsertion($insertionID) {
        $query = "
            UPDATE Inserzione
            SET Attivo = 1
            WHERE ID = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $insertionID);
        $stmt->execute();
    }

    public function isInsertion() {
        $query = "
            SELECT COUNT(I.Attivo) as nAttivi
            FROM Inserzione I
            WHERE I.Attivo = 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['nAttivi'];
        } else {
            return false;
        }
    }

    public function isAdm($admId) {
        $query = "
            SELECT A.Username
            FROM Amministratore A
            WHERE A.ID = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $admId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Username'];
        } else {
            return false;
        }
    }

    public function getUserById($idUser){
        $query = "
            SELECT *
            FROM Utente U
            WHERE ID = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idUser);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUsers() {
        $query = "
            SELECT U.ID, U.Username, U.Email, U.bloccato
            FROM Utente U
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function isUserToBlock($block) {
        $query = "
            SELECT COUNT(U.bloccato) as nBloccati
            FROM Utente U
            WHERE U.bloccato = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $block);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['nBloccati'];
        } else {
            return false;
        }
    }

    public function userToBlock($block) {
        $query = "
            SELECT U.ID, U.Username, U.Email
            FROM utente U
            WHERE U.bloccato = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $block);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function blockUSer($userID) {
        $query = "
            UPDATE Utente
            SET bloccato = 1
            WHERE ID = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
    }

    public function unlockUSer($userID) {
        $query = "
            UPDATE Utente
            SET bloccato = 0
            WHERE ID = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
    }

    public function getItemClassification() {
        $query = "
            SELECT C.NomeOggetto, C.Qta
            FROM Classifica_oggetto C
            ORDER BY C.Qta DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getItemCount() {
        $query = "
            SELECT COUNT(*) as nItem
            FROM Classifica_oggetto C
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getLastInsertId() {
        return $this->db->insert_id;
    }

    public function getSellerClassification() {
        $query = "
            SELECT R.IDRecensito, AVG(R.NumStelle) as valutazione
            FROM Recensione R
            GROUP BY R.IDRecensito
            ORDER BY valutazione DESC
            ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSellerCount() {
        $query = "
                SELECT COUNT(*) as nSeller
                FROM Recensione R
                GROUP BY R.IDRecensito
            ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSeller($idRecensione) {
        $query = "
                SELECT U.username
                FROM Utente U
                WHERE U.ID = ?
            ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idRecensione);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

}
?>