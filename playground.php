<?php

use GoCardless\Pro\Models\Customer;
use GoCardless\Pro\Models\CustomerBankAccount;

$customer = new Customer;
$customer->setForename('John')
         ->setSurname('Doe')
         ->setEmail('john.doe@gmail.com')
         ->setStreet('10 Downing Street')
         ->setCity('London')
         ->setPostalCode('SW1A 2AA')
         ->setCountryCode('GB');

$customer->setFullName('John', 'Doe')
         ->setAddress('10 Downing Street', 'London', 'SW1A 2AA', 'GB');

$customer = $api->createCustomer($customer);

// ------------------------------------------------------------//
$account = new CustomerBankAccount;
$account->setOwner($customer)
        ->setDetails('55779911', '200000');

$account = $api->createBankAccount($account);



// $api->customer->create($details);
// $api->customer->list($limit);
// $api->customer->update($customer);

// vs

// $api->createCustomer($details);
// $api->listCustomers($limit);
// $api->updateCustomer($customer);
