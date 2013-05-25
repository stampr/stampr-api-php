<?php
require dirname(__FILE__).'/../vendor/autoload.php';
require dirname(__FILE__).'/../src/Stampr/Stampr.php';

$stampr = new Stampr('dummy.user@example.com', 'hello', 'http://localhost:3100/api');

// $day = 86400;
// $results = $stampr->batches(null, StamprUtils::Date(time() - (1000*$day)), StamprUtils::Date(time()));
// foreach($results as $result)
// {
//   print $result->getId() . "\n";
// }

// $batch = reset($results);
// foreach($batch->mailings() as $mailing)
// {
//   print $mailing->getId() . "\n";
// }

// $model = $stampr->factory('Mailing', 1387);
  // ->find(1870);

// var_dump(array(
//     'id' => $model->getId(),
//     'size' => $model->getSize(),
//     'turnaround' => $model->getTurnaround(),
//     'style' => $model->getStyle(),
//     'output' => $model->getOutput(),
//     'returnenvelope' => $model->getReturnEnvelope(),
//   ));

// var_dump(array(
//     'id' => $model->getId(),
//     'batch_id' => $model->getBatchId(),
//     'address' => $model->getAddress(),
//     'returnaddress' => $model->getReturnAddress(),
//     'format' => $model->getFormat(),
//   ));

// $response = $stampr->get('test/ping')->send()->json();
// var_dump($response);



// $mailing = $stampr->mail('cory', 'somebody else', 'this is the message body!');
// var_dump($mailing);
// print $mailing->getId() . "\n";
