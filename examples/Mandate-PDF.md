## How to handle saving of Mandate PDFs
  
### Create a Manadate
  
```php
$mandate = new Mandate($account, Creditor::withId('CR123'));
$mandate = $api->createMandate($mandate);
```
  
### Now call the API method
  
```php
$pdf = $api->getMandatePdf($mandate);
file_put_contents(sprintf('/tmp/%s.pdf', $mandate->getId()), $pdf->getContents());
```
 >getMandatePdf accepts both an ID string (e.g. MD12345) or
 >a Mandate object as shown here.

`$pdf` will be an intance of `GuzzleHttp\Stream\StreamInterface`

[http://guzzle.readthedocs.org/en/latest/streams.html](http://guzzle.readthedocs.org/en/latest/streams.html)
