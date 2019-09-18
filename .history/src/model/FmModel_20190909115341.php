<?php
/**
 * Filemaker Model
 *
 * CURD operations
 * Created date : 17/08/2019
 *
 * PHP version 7
 *
 * @author  Original Author <swarupb@mindfiresolutions.com>
 * @version <GIT: https://github.com/swarup-b/Employement>
 */
namespace Src\Model;

/**
 * FmUser Model
 *
 * Contain and two method
 * (create , updateRecord ,deleteRecord,findFmRecord)
 */

class FmModel
{
/**
 * Create Record
 *
 *
 *
 *
 * @param String $layout_name  Layout Name Received
 *
 * @param object $values represents the
 *                         RequestBody
 * @param object $fmdb Database instance
 *
 *
 * @return Array           return array
 */
    public function create($layout_name, $values, $fmdb)
    {/**
     * Used to contain new record object
     *
     * @var Object
     */
        $fmquery = $fmdb->newAddCommand($layout_name);
        while (list($key, $val) = each($values)) {
            $fmquery->setField($key, $val);
        }
        /**
         * Used to contain result of executed query
         *
         * @var Object
         */
        $result = $fmquery->execute();
        if ($fmdb::isError($result)) {
            return ["error" => "Some error occured"];
        } else {
            return ["data" => "Successful"];
        }
    }

/**
 * Update Contact Details
 *
 *
 *
 *
 * @param String $layout_name  Layout Name Received
 *
 * @param object $values represents the
 *                         RequestBody
 * @param object $fmdb Database instance
 * @param Integer $recordID record Id
 *
 * @return Array           return array
 */
    public function updateRecord($layoutName, $values, $fmdb, $recordID)
    {
        /**
         * Used to contain  RecordObject
         *
         * @var Object
         */
        $rec = $fmdb->getRecordById($layoutName, $recordID);
        while (list($key, $val) = each($values)) {
            $rec->setField($key, $val);
            $result = $rec->commit();
        }

        if ($fmdb::isError($result)) {
            return ["error" => "Some error occured"];
        } else {
            return ["data" => "Updated Successfully"];
        }
    }

/**
 * Delete Records
 *
 *
 *
 *
 * @param String $layout_name  Layout Name Received
 *
 *
 *@param object $fmdb Database instance
 * @param Integer $recordID record Id
 * @return Array           return array
 */

    public function deleteRecord($layoutName, $fmdb, $recordID)
    {
        $rec = $fmdb->getRecordById($layoutName, $recordID);
        $result = $rec->delete();
        return ["message" => "Successfully Deleted"];
    }

/**
 * Update Contact Details
 *
 *
 *
 *
 * @param String $layout_name  Layout Name Received
 *
 * @param object $values represents the
 *                         RequestBody
 * @param object $fmdb Database instance
 *
 * @return Array           return array
 */
    public function findFmRecord($layout_name, $fieldName, $fmdb)
    {
        /**
         * Used to contain Field Name Present
         *
         * @var Integer
         */
        $count = count($fieldName); //Getting total no of field
        $fmquery = $fmdb->newFindCommand($layout_name);
        if ($count === 1) {
            $field = each($fieldName);
            $fmquery->addFindCriterion($field['key'], '==' . $field['value']);
        } else {
            $fmquery->setLogicalOperator('FILEMAKER_FIND_AND');

            //Add find criteria from key-value pair from
            foreach ($fieldName as $key => $value) {
                $fmquery->addFindCriterion($key, '==' . $value);
            }

        }

        $result = $fmquery->execute();
        
        //Return if record not present there
        if ($fmdb::isError($result)) {
            return "NOT_FOUND";

        }
        /**
         * Used to contain Record
         *
         * @var Object
         */
        $recs = $result->getRecords();

        $count = 0;
        foreach ($recs as $rec) {
            $field = $rec->getFields();
            $res['recordId'] = $rec->getRecordID();
            foreach ($field as $field_name) {
                if($field_name == 'dob'){
                    $date =  $rec->getField($field_name);
                    $newDate = date("Y-m-d", strtotime($date));
                    $res[$field_name] = $newDate;
                }else{
                $res[$field_name] = $rec->getField($field_name);
                }
            }
            $response[$count] = $res;
            $count++;
        }
        return $response;

    }
}
