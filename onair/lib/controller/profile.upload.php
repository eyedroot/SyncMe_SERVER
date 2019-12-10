<?php

include_once 'app.php';

/**
 * 회원가입 라우터
 */
return function ($entityBody) 
{
  $entity = \toObject(
    json_decode($entityBody, JSON_FORCE_OBJECT)
  );
  
  if ( ! isset($_FILES) || ! app()->session('_id') ) {
    \endpoint( "UPLOAD_FAILED", handleFile()::CODE_ERROR );
  }

  
  
};