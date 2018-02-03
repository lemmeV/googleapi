<?php
require_once('config.php');

/**
 * Class DataBaseInteraction
 */
Class DataBaseInteraction {

    /**
     * Method to connect to the database
     * @return mysqli - return an active connection
     */

    function connect_to_db() {

        $con = mysqli_connect(DB_HOST,DB_USER,DB_PW,DB_NAME);

        // Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        return $con;

    }

    function select_data_from_db() {

        $connection = $this->connect_to_db();

        $sql = "SELECT * from locations";

        $dataObject = $connection->query($sql);

        if ($dataObject->num_rows > 0) {

            while($row = $dataObject->fetch_assoc()) {
                echo "id: " . $row["id"]. " - Name: " . $row["name"]. " " . $row["address"]. $row["lat"]. $row["lng"]. $row["type"]."<br>";
            }
        } else {

            echo "0 results";
        }
        $connection->close();

    }



}

