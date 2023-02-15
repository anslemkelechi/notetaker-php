<?php
class Note
{
    // Connection
    private $conn;
    // Table
    private $db_table = "Note";
    // Columns
    public $id;
    public $title;
    public $body;
    public $user_id;
    public $created;
    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }
    // CREATE
    public function createNote()
    {
        $sqlQuery = "INSERT INTO
                        " . $this->db_table . "
                    SET
                        title = :title, 
                        body = :body, 
                        user_id = :user_id, 
                        created = :created";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind data
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":body", $this->body);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    //READ single
    public function getSingleNote()
    {
        $sqlQuery = "SELECT
                    id, 
                    title, 
                    body, 
                    user_id,
                    created
                  FROM
                    " . $this->db_table . "
                WHERE 
                   user_id = :user_id";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam('user_id', $this->user_id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($dataRow)) {
            if ($_COOKIE['user_id'] == $dataRow['user_id']) {
                /* The password is correct. */
                $this->id = $dataRow['id'];
                $this->title = $dataRow['title'];
                $this->body = $dataRow['body'];
                $this->created = $dataRow['created'];
                return true;
            }
        } else {
            return false;
        }
    }
}
