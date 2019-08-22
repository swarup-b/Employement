<?php
/**
 * User Profile Controller
 *
 * User profile view and update
 * Created date : 17/08/2019
 *
 * PHP version 7
 *
 * @author  Original Author <swarupb@mindfiresolutions.com>
 * @version <GIT: https://github.com/swarup-b/Employement>
 */
namespace App\api\services;

use \Firebase\JWT\JWT;

function decodeToken()
{
    try {
        $headers = apache_request_headers();
        $token = $headers['Authorization'];
        $decoded = JWT::decode($token, "supersecretkeymylogindemo", array('HS256'));
        return $decoded->id;
    } catch (Exception $e) { // Also tried JwtException
        echo "";
    }
}

function getRecordId($layoutName, $fmdb, $id)
{
    try {
        $findCommand = $fmdb->newFindCommand($layoutName);
        $findCommand->addFindCriterion('id', $id);
        $result = $findCommand->execute();
        $recs = $result->getRecords();
        foreach ($recs as $rec) {
            $recordID = $rec->getRecordID();
        }

        return $recordID;
    } catch (Exception $e) {
        echo "";
    }

}
