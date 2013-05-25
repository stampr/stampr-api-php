stampr-api-php
==============

## Examples

### Quick-Send a New Postal Mailing

```php
require 'Stampr.php';

$endpoint = 'https://testing.dev.stam.pr/api';
$stampr = new Stampr([email], [password], $endpoint);

$to  = 'John Smith' . "\n";
$to .= '123 Fake St.' . "\n";
$to .= 'Someplaceville, CA 90210';

$from  = 'Homer Simpson' . "\n";
$from .= '742 Evergreen Tr.' . "\n";
$from .= 'Springfield, IL 12345';

$mailing = $stampr->mail($top, $from, '<html>Hello World!</html>');
print 'Mailing #' . $mailing->getId() . ' was created';
```

### Advanced Implementation

The advanced implementation is based loosely on the Kohana ORM -- http://kohanaframework.org/3.0/guide/orm/using

```php
require 'Stampr.php';

$endpoint = 'https://testing.dev.stam.pr/api';
$stampr = new Stampr([email], [password], $endpoint);

// Create empty object
$batch = $stampr->factory('Batch');

// Load existing object
$batch = $stampr->factory('Batch', 1234);
// or
$batch = $stampr->factory('Batch');
$batch->find(1234);

// Save to API
$batch = $stampr->factory('Batch')
            ->set('config_id', 1111)
            ->create();
print $batch->getId();

// Update
$batch = $stampr->factory('Batch', 1234)
            ->set('status', 'hold')
            ->update();

// Delete
$batch = $stampr->factory('Batch', 1234)
            ->delete();

// Search/List many 
function findBatches($daysOld, $displayPage = 0)
{
  $status = null; // null means any status
  $start= StamprUtils::Date( time() - (86400 * $daysOld) ); 
  $end = StamprUtils::Date( time() );
  $page = $displayPage;

  $batches = $stampr->batches($status, $start, $end, $page);

  foreach ($batches as $batch)
  {
    print $batch->getId() . "\n";
  }
}

findBatches(20, 0);
findBatches(20, 1);
findBatches(20, 2);
```
