<?php
require_once __DIR__ . '/../constants/endpoints.php';

class FmModel
{

    public function create($layout_name, $values, $fmdb)
    {
        $fmquery = $fmdb->newAddCommand($layout_name);
        while (list($key, $val) = each($values)) {
            $fmquery->setField($key, $val);
        }
        $result = $fmquery->execute();
        if ($fmdb::isError($result)) {
            return ["error" => "Some error occured"];
        } else {
            return ["data" => "Successful"];
        }
    }


    public function updateRecord($layout_name, $values, $fmdb){

    }



    public function findFmRecord($layout_name, $fieldName, $fmdb)
    {
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

        $recs = $result->getRecords();

        $count = 0;
        foreach ($recs as $rec) {
            $field = $rec->getFields();
            $res['recordId'] = $rec->getRecordID();
            foreach ($field as $field_name) {
                $res[$field_name] = $rec->getField($field_name);
            }
            $response[$count] = $res;
            $count++;
        }
        return $response;

    }
}
