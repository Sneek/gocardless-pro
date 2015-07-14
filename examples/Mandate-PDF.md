## How to handle saving of Mandate PDFs

### From an existing Mandate

#### Create a Manadate

```php
$mandate = new Mandate($account, Creditor::withId('CR123'));
$mandate = $api->createMandate($mandate);
```

#### Now call the API method

```php
$mandatePdf = new MandatePdf($mandate);
$mandatePdf = $api->getMandatePdf($mandatePdf);

file_put_contents(sprintf('/tmp/%s.pdf', $mandate->getId()), file_get_contents($mandatePdf->getUrl()));
```

#### From customer information

```php
$mandatePdf = new MandatePdf();

$mandatePdf
    ->setAccountNumber('124345678')
    ->setBranchCode('112233')
    ->setCountryCode('GB');
$mandatePdf = $api->getMandatePdf($mandatePdf);

file_put_contents(sprintf('/tmp/%s.pdf', $mandate->getId()), file_get_contents($mandatePdf->getUrl()));
```
