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
        $stmt = $this->pdo->query('SELECT name FROM pacient');
        return $stmt->fetch(PDO::FETCH_ASSOC)['name'];
//        return $this->pdo->prepare("SHOW COLUMNS FROM pacient;")->execute()->fetch();
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
        $stmt = $this->pdo->prepare('INSERT INTO users (name, surname) VALUES (:name, :surname)');
        if ($stmt->execute($data))
        {
            $newid = $this->pdo->lastInsertId();
            $data['id'] = $newid;
            return $data;
        }
        else
        {
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    /**
     * Change atributes of person
     * @return bool
     */
    function updatePerson($id,$name,$email,$phone)
    {
        $stmt = $this->pdo->prepare('UPDATE pacient SET NAME = :name SET email = :email SET phone = :phone WHERE id = :id');
        $data = array(
            "name" => $name,
            "email" => $email,
            "phone" => $phone
        );
        if ($stmt->execute($data))
        {
            return TRUE;
        }
        else
        {
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

    /**
     * Delete person
     * @param $id
     * @return bool
     */
    function deletePerson($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');
        if ($stmt->execute([$id]))
        {
            return TRUE;
        }
        else
        {
            $this->lastError = $stmt->errorInfo();
            return FALSE;
        }
    }

}