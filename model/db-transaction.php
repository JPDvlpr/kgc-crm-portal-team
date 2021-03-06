<?php

require_once("db-object.php");

/**
 * Class DBTransaction extends DBObject
 */
class DBTransaction extends DBObject
{
    /* @param $id
     * @param $amount
     * @param $transDate
     * @param $checkNum
     * @param $depositById
     * @param $bankDepositDate
     * @param $transStatus
     * @param $sourceType
     * @param $sourceId
     * @param $transType
     * @param $contactId
     * @param $dateCreated
     * @param $createdBy
     * @param $dateModified
     * @param $modifiedBy
     * @param $transactionItems
     */
    function addTransaction($id, $amount, $transDate, $checkNum, $depositById,
                            $bankDepositDate, $transStatus, $transDesc, $sourceType, $sourceId,
                            $transType, $contactId, $dateCreated, $createdBy,
                            $dateModified, $modifiedBy, $transactionItems,
                            $table = 'transaction')
    {//}, $dateOfTrans, $payMethod, $discount, $payAmount){
        global $dbh;
        $dbh = Parent::connect();

        //Define Query
        $sql = "INSERT INTO " . $table;
//        $sql = $sql . "(dateOfTrans, payMethod, discount, paymentAmount)";
//        $sql = $sql . "VALUES (:dateOfTrans, :payMethod, :discount, :payAmount)";
        $sql = $sql . " (trans_id, amount, trans_date, ";
        if ($checkNum != 0) {
            $sql = $sql . "check_num, ";
        }
        if ($sourceType != null || trim($sourceType) != "") {
            $sql = $sql . "source_type, ";
        }
        $sql = $sql . "trans_status, trans_desc, trans_type, contact_id,
            date_created, created_by, date_modified, modified_by) ";
        $sql = $sql . "VALUES (:id, :amount, :transDate, ";
        if ($checkNum != 0) {
            $sql = $sql . ":checkNum, ";
        }
        if ($sourceType != null || trim($sourceType) != "") {
            $sql = $sql . ":sourceType, ";
        }
        $sql = $sql . ":transStatus, :transDesc, :transType, :contactId,
            :dateCreated, :createdBy, :dateModified, :modifiedBy)";

        //Prepare Statement
        $statement = $dbh->prepare($sql);

        //Bind Parameter
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':amount', $amount, PDO::PARAM_STR);
        $statement->bindParam(':transDate', $transDate, PDO::PARAM_STR);
        if ($checkNum != 0) {
            $statement->bindParam(':checkNum', $checkNum, PDO::PARAM_STR);
        }
        if ($sourceType != null || trim($sourceType) != "") {
            $statement->bindParam(':sourceType', $sourceType, PDO::PARAM_STR);
        }
        $statement->bindParam(':transStatus', $transStatus, PDO::PARAM_STR);
        $statement->bindParam(':transDesc', $transDesc, PDO::PARAM_STR);
        $statement->bindParam(':transType', $transType, PDO::PARAM_STR);
        $statement->bindParam(':contactId', $contactId, PDO::PARAM_STR);
        $statement->bindParam(':dateCreated', $dateCreated, PDO::PARAM_STR);
        $statement->bindParam(':createdBy', $createdBy, PDO::PARAM_STR);
        $statement->bindParam(':dateModified', $dateModified, PDO::PARAM_STR);
        $statement->bindParam(':modifiedBy', $modifiedBy, PDO::PARAM_STR);

        //Execute Statement
        $statement->execute();

        //return id to use when adding line_items to database
        return $dbh->lastInsertId();
    }

    /**
     * @param transaction $table
     * @return array
     */
    function getTransactions($table = 'transaction')
    {
        global $dbh;
        $dbh = Parent::connect();

        //Define Query
        $sql = "SELECT * FROM " . $table;

        //prepare statement
        $statement = $dbh->prepare($sql);

        //execute query
        $statement->exexute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        Parent::disconnect();

        return $results;
    }

    /**
     * @param null $filters
     * @return array
     */
    public static function getFilteredTransactions($filters = null)
    {
        global $dbh;
        $dbh = Parent::connect();

        //Define Query
        $sql = 'SELECT * FROM transaction_information ';
        if (sizeof($filters) > 0) {
            $first = true;
            foreach ($filters as $filter => $filterValue) {
                if ($filterValue != "" && $filterValue != "all") {
                    if ($first) $sql .= 'WHERE ';
                    if (!$first) $sql .= ' AND ';
                    if ($filter == 'start_date') {
                        $sql .= 'trans_date' . " >= '" . $filterValue . "' ";
                    } elseif ($filter == 'end_date') {
                        $sql .= 'trans_date' . " <= '" . $filterValue . "' ";
                    } elseif ($filter == 'item_name' && $filterValue == 'item') {
                        $sql .= '(quantity * item_price)' . " = 0 ";
                    } elseif ($filter == 'item_name' && $filterValue == 'cash') {
                        $sql .= '(quantity * item_price)' . " > 0 ";
                    } else {
                        $sql .= $filter . " = '" . $filterValue . "' ";
                    }
                    $first = false;
                }
            }
        }
        //prepare statement
        $statement = $dbh->prepare($sql);
        
        //execute query
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        Parent::disconnect();

        return $results;
    }
}