<?php
require_once('config.php');

/**
 * Class DataBaseInteraction
 */
Class DataBaseInteraction {

    /**
     * Holds the database name user chooses for the app in config.php
     * @var string
     */
    private $database = DB_NAME;
    /**
     *  Holds the table name user chooses for the app in config.php
     * @var string
     */
    private $table = DB_TABLE;

    /**
     * Create the database
     */
    public function createDb() {

        $con = mysqli_connect(DB_HOST,DB_USER,DB_PW);
        $sql = "CREATE DATABASE IF NOT EXISTS $this->database";

        if($con->select_db($this->database)) {
            echo "Database exists";
        } elseif(!$con->query($sql)){
            echo("Error description: " . mysqli_error($con));
        } else {
            echo "Database " . $this->database . " created";
        }
        $con->close();
    }

    /**
     * Creates the table for map locations
     */

    public function createTable() {

        if(!$this->databaseExists()){
            echo "Database " . $this->database . " does not exist. Please create the database";
            return;
        }

        $connection = $this->connect_to_db();

        $sql = "CREATE TABLE `$this->table` 
           (`id` INT NOT NULL AUTO_INCREMENT , 
            `name` VARCHAR(60) NOT NULL , 
            `address` VARCHAR(80) NOT NULL , 
            `lat` FLOAT(10,6) NOT NULL , 
            `lng` FLOAT(10,6) NOT NULL , 
            `type` VARCHAR(30) NOT NULL , PRIMARY KEY (`id`)) 
            ENGINE = InnoDB;";

        if(!$connection->query($sql)) {
            echo("Cannot create table: " . mysqli_error($connection));
        } else{
            echo "Table " . $this->table . " created";
        };

        $connection->close();

    }
    /**
     * Inserts test data to database
     */

    public function insertTestData() {

        if(!$this->databaseExists()){
            echo "Database " . $this->database . " does not exist. Please create the database";
            return;
        }

        if(!$this->tableExists()) {
            echo "Table " . $this->table . " does not exist. Please create the table";
            return;
        }
        if($this->select_data_from_db()->num_rows > 0) {
            echo $this->select_data_from_db()->num_rows . " rows already inserted";
            return;
        }

        $connection = $this->connect_to_db();

        $insertStatements = $this->getDataFromCsv();

        foreach ($insertStatements as $sql) {

            if(!$connection->query($sql)) {
                echo("Error description: " . mysqli_error($connection));
            }
        }
        echo $this->select_data_from_db()->num_rows . " rows inserted";


        $connection->close();

    }

    /**
     * Connect to the database
     * @return mysqli - returns an active connection
     */

    private function connect_to_db() {

        // Creates the connection using constants from config.php

        $con = mysqli_connect(DB_HOST,DB_USER,DB_PW,DB_NAME);

        // Check connection

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        return $con;
    }

    /**
     * Selects all location data from database
     * @return bool|mysqli_result
     */

    public function select_data_from_db() {

        $connection = $this->connect_to_db();

        $sql = "SELECT * from $this->table";

        $dataObject = $connection->query($sql);

        $connection->close();

        return $dataObject;
    }

    /**
     * Check if the database exists
     * @return bool
     */

    private function databaseExists() {

        $con = mysqli_connect(DB_HOST,DB_USER,DB_PW);

        if(!$con->select_db($this->database)){
            $con->close();
            return false;
        } else{
            $con->close();
            return true;
        }
    }

    /**
     * Checks if the table inserted to config.php exists in the database
     * @return bool
     */

    private function tableExists() {

        $connection = $this->connect_to_db();

        $sql = "SHOW TABLES LIKE '$this->table'";

        $query = mysqli_query($connection, $sql);

        $connection->close();

        if ($query->num_rows >= 1)
        {
            return true;
        } else {

            return false;
        }
    }

    /**
     * Gets data from CSV
     * @return array
     */

    private function getDataFromCsv() {

        $insertStatements =[];

        $file = fopen(dirname(__FILE__)."/test_data.csv","r");

        while(!feof($file))
        {
            $data = fgetcsv($file);
            array_push($insertStatements,$this->createSqlStatementForDataInsert($data)) ;
        }

        fclose($file);

        return $insertStatements;
    }

    /**
     * Converts CSV data to insert statement
     * @param $data
     * @return string
     */

    private function createSqlStatementForDataInsert($data) {

        $id = $data[0];
        $name = $data[1];
        $address = $data[2];
        $lat = $data[3];
        $lng = $data[4];
        $type = $data[5];

        $sql = "INSERT INTO $this->table (id, name, address, lat, lng, type) VALUES ($id, '$name', '$address', $lat, $lng, '$type')
                ";

        return $sql;
    }
}

