<?php

/**
 * @brief Backend endpoint for working with client and his data
 */
class PeopleService
{
    private $pdo; // connection on database
    private $lastError;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->pdo = $this->connect_db();
        $this->lastError = NULL;
    }

    /**
     * Connect with database
     * @return Exception|PDO
     */
    function connect_db()
    {
        $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=db';
        $username = 'root';
        $password = 'admin';
        try {
            $pdo = new PDO($dsn, $username, $password, array());
        } catch (Exception $e) {
            return $e;
        }

        return $pdo;
    }

    function getErrorMessage()
    {
        if ($this->lastError === NULL)
            return '';
        else
            return $this->lastError[2]; //the message
    }

    /**
     * Get all peaople in table
     * @return mixed
     */
    function getPeople()
    {
        // Pokus o riesenie
        $stmt = $this->pdo->query('SELECT name FROM pacient LIMIT 100');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

//        $stmt = $this->pdo->query('SELECT name FROM pacient LIMIT 100');
//        return $stmt->fetch(PDO::FETCH_ASSOC)['name'];
        // return $this->pdo->prepare("SHOW COLUMNS FROM pacient;")->execute()->fetch();
    }

    /**
     * Get one person
     * @param $id int id of person
     * @return mixed
     */
    function getPerson($id)
    {
        $stmt = $this->pdo->prepare('SELECT id, name, email, phone, date, time, service, employee  FROM pacient WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Add person into table with data
     * @param $data
     * @return false
     */
    function addPerson($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO pacient (name, email, phone, date, time, service, employee) VALUES (:name, :email, :phone, :date, :time, :service, :employee)');
        if ($stmt->execute(['name' => $data[0], 'email' => $data[1], 'phone' => $data[2], 'date' => $data[3], 'time' => $data[4], 'service' => $data[5], 'employee' => $data[6]])) {
            $newid = $this->pdo->lastInsertId();
            $data['id'] = $newid;
            return $data;
        } else {
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    /**
     * Change atributes of person
     * @return bool True upon succes, otherwise false
     */
    function updatePerson($newdata, $id)
    {
        $stmt = $this->pdo->prepare("UPDATE pacient SET name = ?, email = ?, phone =?, employee = ? WHERE id LIKE '%$id%' ");
        $stmt->bindParam(1, $newdata[0], PDO::PARAM_STR);
        $stmt->bindParam(2, $newdata[1], PDO::PARAM_STR);
        $stmt->bindParam(3, $newdata[2], PDO::PARAM_INT);
        $stmt->bindParam(4, $newdata[3], PDO::PARAM_STR);
//        $stmt->bindParam("ssis", $newdata[0], $newdata[1], $newdata[2], $newdata[3]);
        if ($stmt->execute()) {
            return TRUE;
        } else {
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    /**
     * Update all stats of one person
     * @param $name string Name of person
     * @param $newdata array Array of new data
     * @return bool True if succesfull, False if error
     */
    function updatePersonAllStats($name, $newdata)
    {
        while (($id = $this->personGetId(array($name))) != false) {
            if (!$this->updatePerson($newdata, $id))
                return false;
            echo($id = $this->personGetId(array($name)));
            echo $id, " \n";
        }

        return TRUE;
    }

    /**
     * Delete person
     * @param $id
     * @return bool
     */
    function deletePerson($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM pacient WHERE id = ?');
        if ($stmt->execute([$id])) {
            return TRUE;
        } else {
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    /**
     * Find out id of client by data
     * @param $data Array name,date,time
     * @return mixed If entered only name, return first match
     * If enetered name and time coordinates, return id of match, otherwise false
     */
    function personGetId($data)
    {

        // Entered only name
        if (count($data) == 1) {
            $stmt = $this->pdo->prepare("SELECT id FROM pacient WHERE name LIKE '%$data[0]%'");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result == null)
                return false;

            $ultimateResult = $result[0]['id'];
            if ($ultimateResult == 0)
                return false;

            return intval(intval($ultimateResult));

            // Enetered name and time coordinates
        } elseif (count($data) == 3) {
            $stmt = $this->pdo->prepare("SELECT id FROM pacient WHERE name LIKE '%$data[0]%' AND date LIKE '%$data[1]%' AND time LIKE '%$data[2]%'");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result == null)
                return false;
            $ultimateResult = $result[0]['id'];
            if ($ultimateResult == 0)
                return false;

            return intval($ultimateResult);
        }
        return false;
    }
}