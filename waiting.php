<?php
// Define the path to the JSON file
$jsonFilePath = 'M_PESAConfirmationResponse.json';

// Check if 'merchantRequestID' is present in the URL
if (isset($_GET['merchantRequestID'])) {
    $merchantRequestID = htmlspecialchars($_GET['merchantRequestID']);
    $transactionStatus = "Transaction status not found.";
    $found = false;

    // Attempt to find the transaction up to 8 times (every 30 seconds for 4 minutes)
    for ($attempt = 0; $attempt < 8; $attempt++) {
        // Load the JSON file content
        $jsonData = file_get_contents($jsonFilePath);

        // Decode the JSON data into an associative array
        $callbackData = json_decode($jsonData, true);

        // Loop through the callback data to find the matching MerchantRequestID
        foreach ($callbackData as $transaction) {
            if ($transaction['Body']['stkCallback']['MerchantRequestID'] === $merchantRequestID) {
                // Found the matching MerchantRequestID
                $resultCode = $transaction['Body']['stkCallback']['ResultCode'];
                $resultDesc = $transaction['Body']['stkCallback']['ResultDesc'];

                if ($resultCode === 0) {
                    // Extract additional details for a successful transaction
                    $amount = $transaction['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
                    $mpesaReceiptNumber = $transaction['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
                    $transactionDate = $transaction['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'];
                    $phoneNumber = $transaction['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];

                    // Format the transaction date
                    $transactionDateFormatted = DateTime::createFromFormat('YmdHis', $transactionDate)->format('Y-m-d H:i:s');

                    // Construct the detailed transaction status
                    $transactionStatus = "Transaction Successful: $resultDesc. Amount: KES $amount, M-PESA Receipt Number: $mpesaReceiptNumber, Date: $transactionDateFormatted, Phone Number: $phoneNumber.";
                } else {
                    $transactionStatus = "Transaction Failed: $resultDesc";
                }

                $found = true;
                break 2; // Exit both the loop and the for loop after finding the matching ID
            }
        }

        // If not found, wait for 30 seconds before the next attempt
        if (!$found) {
            sleep(30);
        }
    }

    // If the transaction is not found after 8 attempts, consider it failed
    if (!$found) {
        $transactionStatus = "Transaction failed: Could not find the transaction status after 4 minutes.";
    }

    // Echo the transaction status
    echo "Merchant Request ID: " . $merchantRequestID . "<br>";
    echo "Transaction Status: " . $transactionStatus;

} else {
    echo "Merchant Request ID is not available.";
}
?>
