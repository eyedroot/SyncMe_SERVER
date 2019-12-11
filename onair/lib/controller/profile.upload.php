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

  try {
    $hf = handleFile( $_FILES );
  } catch (\ErrorException $e) {
    endpoint( "UPLOAD_FAILED_IN_EXCEPTION", $hf::CODE_ERROR );
  }
  
  if ( ! isset($_FILES) || ! app()->session('_id') ) {
    endpoint( "UPLOAD_FAILED", $hf::CODE_ERROR );
  }

  if ( $hf->integrity() ) {
    if ($result = $hf->upload($hf)) {
      print_r($result);
    }
  }

  eliminateHandler('handleFile');
  
};