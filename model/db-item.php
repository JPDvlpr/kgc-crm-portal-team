<?php

require_once("db-object.php");

/**
 * Class DBObject uses the connection function to connect to the database.
 *
 * @author Just ok team
 * @version 1.0
 * @copyright  2018
 */
class DBItem extends DBObject
{

    /**
     * Function to view the sku table in the database
     * @param name -
     * @param description -
     * @param price -
     * @param unit -
     * @param category -
     * @param item_id -
     * @return int
     *
     * This should be changed to getItems since it gets all. getItem would get only one item.
     */
    function getItem($table = 'item', $numReturn = 0, $category = 'category')
    {
        //gives access to the variable in index
        global $dbh;
        $dbh = Parent::connect();

        //1. Define the query
        $sql = "SELECT * FROM " . $table;

        if ($numReturn != 0) {
            $sql = $sql . " LIMIT :number";
        }

        //2. Prepare the statement
        $statement = $dbh->prepare($sql);

        //3. Bind parameters
        if ($numReturn != 0) {
            $statement->bindParam(':number', $numReturn, PDO::PARAM_INT);
        }

        //4.Execute statement
        $statement->execute();

        //5. Return the results
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        //print_r($result);

        Parent::disconnect();

        if (empty($result)) {
            return -1;
        }

        return $result;


    }

    function addLineItem($transItemId, $transId, $itemDesc, $amount, $itemId, $dateCreated, $createdBy, $dateModified, $modifiedBy, $table = 'transaction_item')
    {
        global $dbh;
        $dbh = Parent::connect();

        $table = "transaction_item";

        //Define Query
        $sql = "INSERT INTO " . $table;
        $sql = $sql . "(trans_item_id, trans_id, item_desc, amount, 
            item_id, date_created, created_by, date_modified, modified_by)";
        $sql = $sql . "VALUES (:transItemId, :transId, :itemDesc, :amount, 
            :itemId, :dateCreated, :createdBy, :dateModified, :modifiedBy) ";

        //Prepare Statement
        $statement = $dbh->prepare($sql);

        //Bind Parameter
        $statement->bindParam(':transItemId', $transItemId, PDO::PARAM_STR);
        $statement->bindParam(':transId', $transId, PDO::PARAM_STR);
        $statement->bindParam(':itemDesc', $itemDesc, PDO::PARAM_STR);
        $statement->bindParam(':amount', $amount, PDO::PARAM_STR);
        $statement->bindParam(':itemId', $itemId, PDO::PARAM_STR);
        $statement->bindParam(':dateCreated', $dateCreated, PDO::PARAM_STR);
        $statement->bindParam(':createdBy', $createdBy, PDO::PARAM_STR);
        $statement->bindParam(':dateModified', $dateModified, PDO::PARAM_STR);
        $statement->bindParam(':modifiedBy', $modifiedBy, PDO::PARAM_STR);

        //Execute Statement
        $statement->execute();
    }
}