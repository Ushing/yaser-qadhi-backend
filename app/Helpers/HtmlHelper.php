<?php


function setMessage($type, $label = '')
{
    $label = ucfirst(strtolower($label));

    if(strtolower($type)=='create') {
        $msg = $label." has been created successfully";
    } elseif(strtolower($type)=='update') {
        $msg = $label." has been updated successfully";
    } elseif(strtolower($type)=='delete') {
        $msg = $label." has been deleted successfully";
    } elseif(strtolower($type)=='create.error') {
        $msg = "Error in saving ".$label;
    }elseif(strtolower($type)=='update.error') {
        $msg = "Error in updating ".$label;
    } else {
        $msg = '';
    }
    return $msg;
}

function getStatus($status){
    return $status == 1 ? 'Active' : 'Inactive';
}


function setStatus($status_id = '')
{
    if ($status_id == 0) {
        $status = '<span class="badge bg-danger m-1"">Inactive</span>';
    } else if($status_id == 1) {
        $status = '<span class="badge bg-primary m-1">Active</span>';
    } else {
        $status = '';
    }
    return $status;
}

function getCurrentStatus($status){
    return $status == 1 ? 'Active' : 'Inactive';
}


/**
 * Make select box
 *
 * @param array $data
 * @param int $value
 * @return string
 */

function messageStatus($status_id = 0)
{
    if ($status_id == 0) {
        $status = '<span class="badge badge-info">not yet seen</span>';
    } else if($status_id == 1) {
        $status = '<span class="badge badge-success">seen</span>';
    } else {
        $status = '';
    }

    return $status;
}

function checkEmpty($value){
    return isset($value) ? (!empty($value) ? $value : null) : null;
}

function checkNull($value){
    return isset($value) ? (!empty($value) ? $value : '--') : '--';
}

function stringCheck($string)
{
    $output =  str_replace( array('[',']','"','"') , ''  , $string);
    return $output;
}

function checkVideoFileType($value): bool
{
     if (\Illuminate\Support\Str::contains($value,['flv','mp4','m3u8','ts','3gp','mov','avi','wmv','mkv','webm'])){
         return true;
     }
     elseif (\Illuminate\Support\Str::contains($value,['FLV','MP4','M3U8','TS','3GP','MOV','AVI','WMV','MKV','WEBM'])){
         return true;
     }
     else{
         return false;
     }
}
