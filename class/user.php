<?php
class User
{
    // Connection
    private $conn;
    // Table
    private $db_table = "User";
    // Columns
    public $id;
    public $name;
    public $email;
    public $password;
    public $created;
    public $LOGIN = false;
    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }
    // GET ALL
    // public function getEmployees(){
    //     $sqlQuery = "SELECT id, name, email, age, designation, created FROM " . $this->db_table . "";
    //     $stmt = $this->conn->prepare($sqlQuery);
    //     $stmt->execute();
    //     return $stmt;
    // }
    // CREATE
    public function createUser()
    {
        $sqlQuery = "INSERT INTO
                        " . $this->db_table . "
                    SET
                        name = :name, 
                        email = :email, 
                        password = :password, 
                        created = :created";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind data
        $hash = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hash);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    //Logim
    public function login()
    {
        $sqlQuery = "SELECT
                    id, 
                    name, 
                    email, 
                    password, 
                    created
                  FROM
                    " . $this->db_table . "
                WHERE 
                   email = :email";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($dataRow)) {
            if (password_verify($this->password, $dataRow['password'])) {
                /* The password is correct. */
                $this->id = $dataRow['id'];
                return true;
            }
        } else {
            return false;
        }
    }
}
