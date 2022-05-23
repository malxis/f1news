<?php
class model
{
    public $id;
    public $table;
    public $conf = "default";
    public $db;

    function __construct()
    {
        // We call the database configuration
        $conf = conf::$databases[$this->conf];

        try {
            // Change connection to show debug messages
            // $this->db = new PDO('mysql:host=' . $conf["host"] . ';dbname=' . $conf["database"] . ';charset=utf8', $conf["login"], $conf["password"], array(
            //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            // ));
            $this->db = new PDO('mysql:host=' . $conf["host"] . ';dbname=' . $conf["database"] . ';charset=utf8', $conf["login"], $conf["password"]);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage() . "<br/>";
            die();
        }
    }

    /**
     * Insert or update a row in the database (insert if id is unknown, else it update)
     *
     * @param array $data
     * @return void
     */
    function save($data)
    {
        // foreach ($data as $key => $value) {
        //     $data[$key] = strip_tags($data[$key], Conf::$allowedTags);
        // }

        // ID is unknown -> INSERT data
        if (empty($data["id"])) {

            unset($data["id"]);

            $query = "INSERT INTO " . $this->table . " (";
            $values = "";
            foreach ($data as $key => $value) {
                $query .= $key . ",";
                $values .= ":" . $key . ",";
            }
            // Remove the last comma
            $query = substr($query, 0, -1);
            $values = substr($values, 0, -1);

            $query .= ") VALUES (" . $values . ");";
            //echo $query;

            $sth = $this->db->prepare($query);
            $this->bindValues($data, $sth);

            if ($sth->execute()) {
                $this->id = $this->db->lastInsertId();
                return true;
            } else {
                return false;
            }
        }

        // ID is known -> UPDATE data
        else {
            $this->id = $data["id"];
            unset($data["id"]);

            $query = "UPDATE " . $this->table . " SET ";

            foreach ($data as $key => $value) {
                $query .= " " . $key . " = :" . $key . ",";
            }
            // Remove the last comma
            $query = substr($query, 0, -1);

            $query .= " WHERE id = :id";
            //echo $query;

            // Reset the id for binding param
            $data["id"] = $this->id;

            $sth = $this->db->prepare($query);
            $this->bindValues($data, $sth);

            return $sth->execute();
        }
    }

    /**
     * Retrieve data in the actual table
     *
     * @param array $params Array of the params of the query : parameters available : fields (ex: "id, name"), join (ex : "INNER JOIN othertable ot ON ot.idFK = actualtable.id"), where (ex : "name = :name"), order (ex: "name DESC"), limit (ex: "LIMIT 0, 25"), values (array of the values that will be bind to the values in where ex : array("id" => 1, "name" => $firstName))
     * @return array
     */
    function find($params = null)
    {
        // Initialise the parameters with the default values
        $fields = " * ";
        $join = "";
        $where = " 1=1 ";
        $order = " " . $this->table . ".id ";
        $limit = "";
        $values = array();

        // Retrieve the params if they are set
        if (isset($params)) {
            foreach ($params as $key => $value) {
                ${$key} = $value;
            }
        }

        $query = "SELECT " . $fields . " FROM " . $this->table . " " . $join . " WHERE " . $where . " ORDER BY " . $order . " " . $limit;
        // echo $query . "<br><br>";

        $sth = $this->db->prepare($query);
        if (!empty($values))
            $this->bindValues($values, $sth);

        if ($sth->execute()) {
            $data = $sth->fetchAll(PDO::FETCH_OBJ);

            return $data;
        } else {
            echo "<br>Erreur SQL";
        }
    }

    /**
     * Bind the values in a query
     *
     * @param array $values values to bind
     * @param object $statement dbh statement with the prepared query
     * @return void
     */
    function bindValues($values, $statement)
    {
        // Get column meta of the final query
        $select_columns = "";
        foreach ($values as $key => $value) {
            $select_columns .= $key . ",";
        }

        // Remove the last comma
        $select_columns = substr($select_columns, 0, -1);
        $select_columns = "SELECT " . $select_columns . " FROM " . $this->table . " LIMIT 0, 1";

        $stmt = $this->db->prepare($select_columns);

        if ($stmt->execute()) {
            $i = 0;
            foreach ($values as $key => $value) {
                $statement->bindValue(":$key", $value, $stmt->getColumnMeta($i)["pdo_type"]);
                $i++;
            }
        } else {
            foreach ($values as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
        }
    }

    /**
     * Retrieve the first row of data in the actual table according to the parameters
     *
     * @param array $params Array of the params of the query : parameters available : fields (ex: "id, name"), join (ex : "INNER JOIN othertable ot ON ot.idFK = actualtable.id"), where (ex : "name = :name"), order (ex: "name DESC"), limit (ex: "LIMIT 0, 25"), values (array of the values that will be bind to the values in where ex : array("id" => 1, "name" => $firstName))
     * @return object
     */
    function findFirst($data)
    {
        return current($this->find($data));
    }

    /**
     * Delete the row identified by $id in the actuel table
     *
     * @param int $id
     * @return bool
     */
    function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        $sth = $this->db->prepare($query);
        $sth->bindValue(":id", $id, PDO::PARAM_INT);

        return $sth->execute();
    }

    /**
     * Save an image in the database at the specified $field
     *
     * @param int $id id of the row in the table of the model where the photo will be saved
     * @param string $field field where the photo will be stored
     * @param string $filename
     * @return bool
     */
    function saveImage($id, $field, $filename)
    {
        $query = "INSERT INTO photos (filename) VALUES (:filename);";
        $sth = $this->db->prepare($query);

        if ($sth->execute(array("filename" => $filename))) {
            return $this->save(array("id" => $id, $field => $this->db->lastInsertId()));
        } else
            return false;
    }

    /**
     * Return the image stored in $field in the actual table for the row identified by $id
     *
     * @param int $id id of the row where you want the photo
     * @param string $field name of the field that contain the photo to retrieve
     * @return bool
     */
    function getImage($id, $field)
    {
        return $this->findFirst(array(
            "fields" => "p.id as id, filename",
            "join" => "INNER JOIN photos p ON p.id = $this->table.$field",
            "where" => " $this->table.id = :id ",
            "values" => array("id" => $id)
        ));
    }

    /**
     * Delete the image identified by $idPhoto in the photos table
     *
     * @param int $idPhoto
     * @return bool
     */
    function deleteImage($idPhoto)
    {
        $query = "DELETE FROM photos WHERE id = :id;";
        $sth = $this->db->prepare($query);

        return $sth->execute(array(":id" => $idPhoto));
    }

    /**
     * Return the total number of row in a table
     *
     * @return int
     */
    public function getCount()
    {
        return $this->findFirst(array(
            "fields" => "COUNT(*) AS count"
        ))->count;
    }
}
