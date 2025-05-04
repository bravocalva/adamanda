<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false 
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }

    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $value = htmlspecialchars(strip_tags($value));
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        try {
            return $this->stmt->execute();
        } catch (PDOException $e) {
            error_log("Query Error: " . $e->getMessage());
            return false;
        }
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    public function paginated($sql, $page = 1, $limit = 10) {
        $sql .= " LIMIT :limit OFFSET :offset";
        $this->query($sql);
        $this->bind(':limit', $limit, PDO::PARAM_INT);
        $this->bind(':offset', ($page - 1) * $limit, PDO::PARAM_INT);
        return $this->resultSet();
    }

    public function closeCursor() {
        if ($this->stmt) {
            $this->stmt->closeCursor();
        }
    }

    public function executeProcedure() {
        $this->execute();
        $result = $this->stmt->fetch(); 
        while ($this->stmt->nextRowset()) {}
        $this->stmt->closeCursor();
        return $result;
    }

    public function multiResultSet() {
        $this->execute();
        $results = [];
    
        do {
            $result = $this->stmt->fetchAll();
            if ($result) {
                $results[] = $result;
            }
        } while ($this->stmt->nextRowset());
    
        $this->stmt->closeCursor();
        return $results;
    }
    
    
    public function close() {
        $this->dbh = null;
    }
}