<?php

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
        $stmt = $this->pdo->query('SELECT jmeno FROM pacient LIMIT 100');
        return $stmt->fetch(PDO::FETCH_ASSOC)['jmeno'];
//        return $this->pdo->prepare("SHOW COLUMNS FROM pacient;")->execute()->fetch();
    }

    /**
     * Get one person
     * @param $id of person
     * @return mixed
     */
    function getPerson($id)
    {
        $stmt = $this->pdo->prepare('SELECT id, name, surname FROM users WHERE id = ?');
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
    function updatePerson()
    {
        $stmt = $this->pdo->prepare('UPDATE pacient SET email = :email WHERE id = :id');
        $data = array(
            "email" => "new@email.com",
            "name" => "Petr Pacientový"
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