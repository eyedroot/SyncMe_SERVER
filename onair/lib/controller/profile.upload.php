<?php

include_once 'app.php';

/**
 * 회원가입 라우터
 */
return function ($entityBody) 
{
  $entity = json_decode($entityBody, JSON_FORCE_OBJECT);
  $clone = $entity;
  $entity = toObject( $entity );

  print_r($_COOKIE);
  print_r($_SESSION);
  print_r(app()::session('email'));
  print_r($_POST);
  print_r($_FILES);
};