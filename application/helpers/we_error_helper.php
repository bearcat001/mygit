<?php 
function we_single_error($message){
  return array('error'=>$message);
}

function we_double_error($message){
  return array(array('error'=>$message));
}