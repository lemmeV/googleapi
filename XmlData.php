<?php

class XmlData
{
    private $databaseData;

    public function __construct()
    {
        $db_connection = new DataBaseInteraction();
        $this->databaseData = $db_connection;
    }

    public function getXml() {

        $result = $this->databaseData->select_data_from_db();

        if ($result->num_rows > 0) {

                // Start XML file, echo parent node
                echo '<markers>';

                // Iterate through the rows, printing XML nodes for each
                while ($row = $result->fetch_assoc()){
                    // Add to XML document node
                    echo '<marker ';
                    echo 'id="' . $row['id'] . '" ';
                    echo 'name="' . $this->parseToXML($row['name']) . '" ';
                    echo 'address="' . $this->parseToXML($row['address']) . '" ';
                    echo 'lat="' . $row['lat'] . '" ';
                    echo 'lng="' . $row['lng'] . '" ';
                    echo 'type="' . $row['type'] . '" ';
                    echo '/>';
                }

                // End XML file
                echo '</markers>';
        } else {
            echo "0 results";
        }
    }

    private function parseToXML($htmlStr)
    {
        $xmlStr=str_replace('<','&lt;',$htmlStr);
        $xmlStr=str_replace('>','&gt;',$xmlStr);
        $xmlStr=str_replace('"','&quot;',$xmlStr);
        $xmlStr=str_replace("'",'&#39;',$xmlStr);
        $xmlStr=str_replace("&",'&amp;',$xmlStr);

        return $xmlStr;
    }


}