<?php

/*
  @return: image url
*/
function uploadFile($img, $details)
{
  if(File::isDirectory($details['path'])) {
    $image = Image::make($img)->fit($details['width'], $details['height'])->save( $details['path'].$details['name'] );
  } else {
    File::makeDirectory($details['path'], 0777, true);
    return uploadFile($img, $details);
  }
  return $details['name'];
}

/*
  @description: delete file if exist
*/
function deleteFile($filePath)
{
    if(File::exists($filePath)){
      File::delete($filePath);
    }
}

function convertImages($source, $file)
{
    $img = preg_match_all('/[<]img src=[^>]+/', $source, $output);
    foreach($output[0] as $value) {
      $src = preg_split('/src="*|"/', $value);
      if( preg_match('/data:[a-z]+\/[a-zA-Z]+;base64,(.*)/', $src[1]) ) {
        $info = explode('/', preg_split('/data:|;base64,(.*)/', $src[1])[1]);
        $type = $info[0];
        $mimetype = $info[1];
        $base64[0][] = $src[1];
        if($type == "image" && in_array($mimetype, ['png','jpeg','jpg'])) {
          /***************************************************************/
          $img = file_get_contents($src[1]);
          $name = $file['name'].'.'.explode('/', getimagesizefromstring($img)['mime'])[1];
          uploadFile($img, array(
            'name' => $name,
            'path' => $file['path'],
            'width' => getimagesizefromstring($img)[0],
            'height' => getimagesizefromstring($img)[1]
          ));
          /***************************************************************/
          $base64[1][] = asset('sf/ct/'. $name);
        } else {
          $base64[1][] = '';
        }
      } elseif( !equals('/[a-z:]*\/\/[ww*.*]*|\/(.*)/', $src[1], \URL::to('/')) ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $src[1]);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $img = curl_exec($ch);
        $encode = base64_encode($img);
        $base64[0][] = $encode;
        $mimetype = explode('/', curl_getinfo($ch, CURLINFO_CONTENT_TYPE))[1];
        if(in_array($mimetype, ['png','jpeg','jpg'])) {
          $name = $file['name'].'.'.$mimetype;
          uploadFile($img, array(
            'name' => $name,
            'path' => $file['path'],
            'width' => getimagesizefromstring($img)[0],
            'height' => getimagesizefromstring($img)[1]
          ));
          curl_close($ch);
          $base64[1][] = asset('sf/ct/'. $name);
        } else {
          // formato non accettabile
          // ignora l'immagine
          $source = str_replace($value.'>', '', $source);
        }
      }
    }
    if(isset($base64)) {
      return str_replace($base64[0], $base64[1], $source);
    } else {
      return $source;
    }
}

function equals($pattern, $first, $second)
{
    $first = preg_split($pattern, $first)[1];
    $second = preg_split($pattern, $second)[1];
    if ($first === $second) {
      return true;
    } else {
      return false;
    }
}

/*
  return array commited
*/
function getDiff($a, $b)
{
    $arr3 = array();
    $k = $q = 0;

    for($i = 0; $i < count($a); $i++) {
      $a[$i] = strip_tags($a[$i]);
      $b[$i] = strip_tags($b[$i]);
      if($a[$i] == $b[$i]) {
        array_push($arr3, ['str' => $a[$i]]);
      } else {
        $prec = explode(' ', $a[$i]);
        $cur = explode(' ', $b[$i]);
        $prec = array_pad($prec, max(count($prec), count($cur)), null);
        $cur = array_pad($cur, max(count($prec), count($cur)), null);
        foreach($cur as $index => $value) {
          /*if($value != $prec[$index]) {
            if(!isset($dif)) {
              $diff = 1;
            }
            if(!empty($prec[$index])) {
              array_splice($prec, $index, 1, '<medium class="bg-commit-strong-less">'. $prec[$index] . '</medium>');
            }
            array_splice($cur, $index, 1, '<medium class="bg-commit-strong-plus">'. $value. '</medium>');
          }*/
        }
        if(isset($diff)) {
          $a[$i] = implode(' ', $prec);
          $b[$i] = implode(' ', $cur);
        }
        array_push($arr3, ['tag' => 'remove', 'str' => $a[$i]]);
        array_push($arr3, ['tag' => 'add', 'str' => $b[$i]]);
      }
      $k++;
    }

    for(; $k < count($b); $k++) {
      array_push($arr3, ['tag' => 'add', 'str' => $b[$k]]);
    }

    return $arr3;
}
