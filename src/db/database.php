<?php
class DatabaseHelper{
    public $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    public function addInsertion($descrizione) {
        $query = "
            INSERT INTO inserzione (Descrizione)
            VALUES (?)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $descrizione);
        $stmt->execute();
    }

    public function getLastInsertionId() {
        $query = "
            SELECT TOP 1 ID
            FROM Inserzione
            ORDER BY ID DESC
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
            VALUES (?, ?, ?, (SELECT TOP 1 ID
                              FROM Inserzione
                              ORDER BY ID DESC))
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sfs', $nomeOggetto, $prezzoOggetto, $livelloUsura);
        $stmt->execute();
    }
    

}
?>