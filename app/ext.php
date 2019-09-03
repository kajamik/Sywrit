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
    return $this->uploadFile($img, $details);
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
          $this->uploadFile($img, array(
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
      } elseif( !$this->equals('/[a-z:]*\/\/[ww*.*]*|\/(.*)/', $src[1], \URL::to('/')) ) {
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
          $this->uploadFile($img, array(
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

function showCommit($query, $result)
{
    $commit_all = DB::table('group_article_commit')->where('article_id', $query->id)->get();

    foreach($commit_all as $commit) {
      $cor = \DB::table('group_article_correction')->where('commit_id', $commit->id)->orderBy('created_at', 'desc')->get();

      foreach($cor as $value) {
        $result = substr_replace($result, "<span class='add'><a href='$commit->article_id/commit/$commit->id'>". $value->new_word ."</a></span>", $value->start, strlen($value->replaced_word));
        //$result = substr_replace($result, "<span class='add'><a href='$commit->article_id/commit/$commit->id'>". $value->new_word ."</a></span>", $value->start, strlen($value->replaced_word));
      }
    }

    return $result;
}

function makeCorrection($query, $result)
{
    /*$commit_all = DB::table('group_article_commit')->where('article_id', $query->id)->get();

    foreach($commit_all as $commit) {
      $cor = \DB::table('group_article_correction')->where('commit_id', $commit->id)->orderBy('created_at', 'desc')->get();

      foreach($cor as $value) {
        $user = \DB::table('users')->where('id', $commit->user_id)->first();
        $result = substr_replace($result, "<span class='add'><a href='$commit->article_id/commit/$commit->id'>". $value->new_word ."</a></span>", $value->start, strlen($value->replaced_word));
        //$result = substr_replace($text_without_html, $value->start, strlen($value->replaced_word), "<span class='add'><a href='$commit->article_id/commit/$value->id'>". $value->new_word ."</a></span>", $result);
      }
    }

    return $result;*/
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
