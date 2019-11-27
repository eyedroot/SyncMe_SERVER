<?php

include_once 'app.php';

return function ($entityBody) {
  print_r($_SERVER);
  print_r($entityBody);
};