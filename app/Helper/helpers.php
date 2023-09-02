<?php

use Illuminate\Database\Eloquent\Model;

if (!function_exists('apiError')) {
  function apiError($msg, $errors = null, $code = 422)
  {
    return apiRes(false, $msg, '', $errors, $code);
  }
}
if (!function_exists('apiRes')) {
  function apiRes($status, $message, $data, $error, $code)
  {
    $apiResp = [];
    $apiResp['status'] = $code  ? $code : 200;
    $apiResp['data'] = $data;
    $apiResp['msg'] = $message;
    $apiResp['errors'] = $error;
    return response($apiResp, $code ? $code : 200);
  }
}
if (!function_exists('apiOk')) {
  function apiOk($data, $list = false)
  {
    if (!$list) {
      return apiRes(true, '', $data, null, null);
    } else {
      $data = $data->toArray();
      $data['status'] = 'success';
      $data['msg'] = "";
      $data['errors'] = null;
      //return response($apiResp, $code ? $code : ($status ? 200 : 500));
      // Mobile library friendly with 200 response only: https://github.com/Alamofire/Alamofire
      return response($data, 200);
    }
  }
}
function updateFileByKey(Model $item, $key, $data)
{
  if (isset($data) && !empty($data)) {
    $pathToMedia = storage_path('app/' . $data);
    $mime = '';
    try {
      $mime = mime_content_type($pathToMedia);
    } catch (\Throwable $th) {
      return false;
    }
    if (strstr($mime, "image/")) {
      try {
        $item->addMedia($pathToMedia)->toMediaCollection($key);
      } catch (\Throwable $th) {
        return false;
      }
    }
  }
  return true;
}
function getFileByKey($item, $key)
{
  $file = '';
  try {
    $medias = $item->getMedia($key);
    foreach ($medias as $media) {
      $file = $media->getFullUrl();
    }
  } catch (\Throwable $th) {
    //throw $th;
  }
  return $file;
}
function deleteSingleFile($item, $type): void
{
  $item->clearMediaCollection($type);
}
