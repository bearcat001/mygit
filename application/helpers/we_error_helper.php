<?php 
function we_single_error($message){
  return array('error'=>$message);
}

function we_double_error($message){
  return array(array('error'=>$message));
}

function is_we_error($error){
    if($error instanceof WE_Error)
        return true;
    else
        return false;
}

function we_escape(&$tmp,$exchg=""){
    return $tmp?$tmp:$exchg;
}