<?php

include_once 'app.php';

return function ($entityBody) {
  $entity = toObject(
    json_decode($entityBody, JSON_FORCE_OBJECT)
  );

  if ( user()::isExists($entity->email) ) {
    jsonEnd( 
      "이미 가입되어 있는 이메일입니다",  
      user()::CODE_ALREADY_EXISTS_USER
    );
  }

  print_r($entity);
};