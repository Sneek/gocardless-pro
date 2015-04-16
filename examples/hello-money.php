<?php
/**
 * A slight change on the usual 'Hello World'.
 *
 * Here we are going to create a customer, add their
 * bank account, setup a mandate, and take our first payment!
 */

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GoCardless\Pro\Api;
use GoCardless\Pro\Models\Customer;
use GoCardless\Pro\Models\CustomerBankAccount;
use GoCardless\Pro\Models\Creditor;
use GoCardless\Pro\Models\Mandate;
use GoCardless\Pro\Models\Payment;

/**
 * When you sign up for GoCardless pro you will be given an API key by default.
 * Navigate to the organisation area and scroll to the API section. Grab those keys!
 *
 * @link https://manage-sandbox.gocardless.com/organisation
 * @link https://developer.gocardless.com/pro/#overview-backwards-compatibility
 */
$api = new Api(new Client, 'API_ID', 'API_KEY', '2014-11-03');

/** On with the show */

$customer = (new Customer())->setFullName('David', 'Cameron')
                            ->setAddress('10 Downing Street', 'London', 'SW1A 2AA', 'GB');
$customer = $api->createCustomer($customer);

$account = (new CustomerBankAccount())->withAccountDetails('Mr D Cameron', '55997711', '200000', 'GB', $customer);
$account = $api->createCustomerBankAccount($account);

// You also get a creditor ID automatically when you sign up.
$mandate = new Mandate($account, Creditor::withId('CR123'));
$mandate = $api->createMandate($mandate);

/**
 * Now lets take some money!
 */
$payment = (new Payment())->collect(5000, 'GBP')->on('2015-02-16')->using($mandate);
$payment = $api->createPayment($payment);

$payment->isPendingSubmission(); // true
