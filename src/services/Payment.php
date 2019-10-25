<?php

namespace Src\Services;
use Src\Model\FmModel;
require '../vendor/autoload.php'; 
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

define("AUTHORIZENET_LOG_FILE","phplog");

Class Payment{
/**
 * Save Payment Details
 *
 *
 *
 *
 *
 *
 * @param object $requestValue represents the
 *                         RequestBody
 * @param object $fmdb Database instance
 *
 *
 * @return Array           return array
 */
  function makePayment($value , $fmdb){
 /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName('6V4kKgEg9a');
    $merchantAuthentication->setTransactionKey('5972XUuPCQ7A9ybJ');
    
    // Set the transaction's refId
    $refId = 'ref' . time();

    // Create the payment data for a credit card
    $creditCard = new AnetAPI\CreditCardType();
    $creditCard->setCardNumber($value->cardNo);
    $creditCard->setExpirationDate($value->year."-".$value->month);
    $creditCard->setCardCode($value->cvv);

    // Add the payment data to a paymentType object
    $paymentOne = new AnetAPI\PaymentType();
    $paymentOne->setCreditCard($creditCard);

    // Create a TransactionRequestType object and add the previous objects to it
    $transactionRequestType = new AnetAPI\TransactionRequestType();
    $transactionRequestType->setTransactionType("authCaptureTransaction");
    $transactionRequestType->setAmount($value->amount);
    // $transactionRequestType->setOrder($order);
    $transactionRequestType->setPayment($paymentOne);
  

    // Assemble the complete transaction request
    $request = new AnetAPI\CreateTransactionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setTransactionRequest($transactionRequestType);

    // Create the controller and get the response
    $controller = new AnetController\CreateTransactionController($request);
    $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
    

    if ($response != null) {
        // Check to see if the API request was successfully received and acted upon
        if ($response->getMessages()->getResultCode() == "Ok") {
            // Since the API request was successful, look for a transaction response
            // and parse it to display the results of authorizing the card
            $tresponse = $response->getTransactionResponse();
        
            if ($tresponse != null && $tresponse->getMessages() != null) {
                    $newValue = array(
                            "PaymentType_xt"=>"CreditCard",
                            "PaymentAmount_xn"=>$value->amount,
                            "TransactionId_xt"=>$tresponse->getTransId(),
                            "CardHolderName_xt"=>$value->nameOnCard,
                            "CardNo_xt"=>$value->cardNo,
                            "PaymentStatus_xt"=>"Successful",
                            "__kf_ContactId"=>$value->contactId
                    );

                    $result = $this->savePaymentdetails($newValue ,$fmdb);
                    $message = array(
                        'status' => $result['data'],
                        'message'=> array(
                                    'TransactionStatus'=> 'Successful',
                                    'TransactionId' => $tresponse->getTransId(),
                                    'TransactionAmount'=> $value->amount
                                )
                         );
                return $message;
            } else {
                 if ($tresponse->getErrors() != null) {
                     $newValue = array(
                            "PaymentType_xt"=>"CreditCard",
                            "PaymentAmount_xn"=>$value->amount,
                            "TransactionId_xt"=> 0,
                            "CardHolderName_xt"=>$value->nameOnCard,
                            "CardNo_xt"=>$value->cardNo,
                            "PaymentStatus_xt"=>"Fail",
                            "__kf_ContactId"=>$value->contactId
                    );

                    $result = $this->savePaymentdetails($newValue ,$fmdb);
                return  array(
                        'status' =>'fail',
                        'dbstatus' => $result,
                        'code' => $tresponse->getErrors()[0]->getErrorCode(),
                        'message' => $tresponse->getErrors()[0]->getErrorText()
                    );
                }
               
            }
            // Or, print errors if the API request wasn't successful
        } else {
            return $result = array(
                'status' => 'error',
                'message' => $response->getTransactionResponse()
            );
        }
    }else {
        return $result = array('Message' => null , );
    }
}
/**
 * Save Payment Details
 *
 *
 *
 *
 *
 *
 * @param object $requestValue represents the
 *                         RequestBody
 * @param object $fmdb Database instance
 *
 *
 * @return Array           return array
 */
public function savePaymentdetails($requestValue,$fmdb){
    $fmModel = new FmModel();
    return $result = $fmModel->create('Payment', $requestValue, $fmdb);
}  





// Get Access token for paypal payment
 public function getAccessToken(){

    $url = 'https://api.sandbox.paypal.com/v1/oauth2/token';
    $clientId = 'AfV7mK_r9Uytg-jWS3nBnZyNrdVBVA8gN1-Gn8fq1m2NwNH8VzCiZrDOUHiEO493Ox8nqhyrakp8kvRR';
    $secret ='EB5u9SetBhTamXTuWQ8GD5NLvBe77cPE0TOYsSwyNuKtFCWk-AZd1eD-wJ8HL9c6LXInC9FpVTL-Ob1_';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSLVERSION , 6); //NEW ADDITION
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    $result = curl_exec($ch);
    curl_close($ch);  
    echo $token=json_decode($result)->access_token;
 }
 
// Function to authorized payment
 public function authorizedPayment(){
    $token = 'A21AAFMIhFanW6-qK9h91rFB__abiVZk8P1aYGpMDe1QY8VTvw2HmK69kLfzngcGg3pCJ_rc1ciogA213ieoF9ojukRgXW-bQ';
    // $data->transactions['0']->amount->total;
    $data = array (
  'intent' => 'sale',
  'payer' => 
  array (
    'payment_method' => 'paypal',
  ),
  'transactions' => 
  array (
    0 => 
    array (
      'amount' => 
      array (
        'currency' => 'INR',
        'total' => '93.00',
      ),
      'description' => 'This is the payment transaction description',
      'payment_options' => 
      array (
        'allowed_payment_method' => 'IMMEDIATE_PAY',
      ),
    ),
  ),
  'redirect_urls' => 
  array (
    'return_url' => 'https://example.com/return',
    'cancel_url' => 'https://example.com/cancel',
  ),
);
    $data['transactions'][0]['amount']['total'] = 91.01;
    $payload = json_encode($data);
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://api.sandbox.paypal.com/v1/payments/payment");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization : Bearer '.$token));
    // Submit the POST request
    $result = curl_exec($ch);
     
    // Close cURL session handle
    curl_close($ch);
    echo 'hello';
    print_r($result);
 }
}
?>