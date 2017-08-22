<?php
/**
 *
 * SlackSend
 *
 * @author  TakashiKakizoe
 * @version 1.4.0
 *
**/
class SlackSend
{
  private static $instances = array();
  private $slackUrl = 'https://hooks.slack.com/services/YOURKEY/YOURKEY/YOURKEY';
  private $options  = array();

  private $fallback   = 'Notification' ;
  private $username   = 'Bot' ;
  private $icon_emoji = ':slack:' ;
  private $channel    = '' ;

  private $color       = '#3AA3E3'   ;
  private $pretext     = 'pretext'   ;
  private $title       = 'title'     ;
  private $title_link  = 'titleLink' ;
  private $text        = 'text' ;
  private $ts          = '' ;
  private $field       = array() ;
  private $image_url   = '' ;
  private $thumb_url   = '' ;
  private $author_name = '' ;
  private $author_link = '' ;
  private $author_icon = '' ;
  private $footer      = 'footer' ;
  private $footer_icon = 'https://platform.slack-edge.com/img/default_application_icon.png' ;
  private $attachments = array() ;

  private function __construct($url=null)
  {
    $this->slackUrl = $url===null ? $this->slackUrl : $url ;
    $this->options = array(
      'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: application/json'
      )
    );
  }
  private function reset()
  {
    $this->fallback   = 'Notification' ;
    $this->username   = 'Bot' ;
    $this->icon_emoji = ':slack:' ;
    $this->channel    = '' ;

    $this->color       = '#3AA3E3'   ;
    $this->pretext     = 'pretext'   ;
    $this->title       = 'title'     ;
    $this->title_link  = 'titleLink' ;
    $this->text        = 'text' ;
    $this->ts          = '' ;
    $this->field       = array() ;
    $this->image_url   = '' ;
    $this->thumb_url   = '' ;
    $this->author_name = '' ;
    $this->author_link = '' ;
    $this->author_icon = '' ;
    $this->footer      = 'footer' ;
    $this->footer_icon = 'https://platform.slack-edge.com/img/default_application_icon.png' ;
    $this->attachments = array() ;
  }
  public function set($key='',$val='')
  {
    if($key==='' || $val==='' || !isset($this->{$key}) ){
      throw new Exception('Invalid argument');
    }
    if ( $key==='username' ) {
      $this->username   = $val ;
    } elseif ( $key==='icon_emoji' ) {
      $this->icon_emoji = $val ;
    } elseif ( $key==='channel' ) {
      $this->channel    = $val ;
    } else {
      $i = $this->getIndex();
      if ( $key==='ts' ) {
        if (strtotime(date('Y-m-d H:i:s',$val)) === $val){
          $val = $val ;
        } else {
          $val = strtotime($val) ;
        }
      }
      if( !isset($this->attachments[$i][$key]) ){
        $this->attachments[$i][$key] = $val ;
      }else{
        $this->attachments[$i+1][$key] = $val ;
      }
    }
    return $this ;
  }
  public function setFields($title,$text,$short=false){
    $i = $this->getIndex();
    $this->attachments[$i]['fields'][] = array(
      "title" => $title ,
      "value" => $text  ,
      "short" => $short
    );
    return $this ;
  }
  private function getIndex()
  {
    $index = count($this->attachments) - 1 ;
    $index = $index < 0 ? 0 : $index ;
    return $index ;
  }
  public function sendMessage()
  {
    $this->makeMessage();
    return $this->sendMessageMain() ;
  }
  public function getMessage($flg=true)
  {
    $this->makeMessage();
    $return = '' ;
    if ($flg) {
      $return = json_encode($this->message);
    } else {
      $return = $this->message;
    }
    $this->reset();
    return $return ;
  }
  private function makeMessage()
  {
    $this->message = array(
      'username' => $this->username ,
      'icon_emoji' => $this->icon_emoji ,
      'attachments' => array()
    );
    if(!empty($this->channel)){
      $this->message['channel'] = $this->channel ;
      $this->channel = '' ;
    }
    if(!empty($this->attachments)){
      foreach ($this->attachments as $key => $attachment) {
        // params
        $fallback    = isset($attachment['fallback']) ? $attachment['fallback'] :$this->fallback ;
        $color       = isset($attachment['color']) ? $attachment['color'] :$this->color ;
        $text        = isset($attachment['text']) ? $attachment['text'] :$this->text ;

        $setArray = array(
          'fallback'=> $fallback,
          'color'=> $color,
          'text'=> $text
        );
        if ( isset($attachment['ts']) ) {
          $setArray['ts']     = $attachment['ts'] ;
        }
        if ( isset($attachment['title']) ) {
          $setArray['title']     = $attachment['title'] ;
        }
        if ( isset($attachment['title_link']) ) {
          $setArray['title_link']     = $attachment['title_link'] ;
        }
        if ( isset($attachment['pretext']) ) {
          $setArray['pretext']     = $attachment['pretext'] ;
        }
        if ( isset($attachment['footer']) && isset($attachment['footer_icon']) ) {
          $setArray['footer']      = $attachment['footer'] ;
          $setArray['footer_icon'] = $attachment['footer_icon'] ;
        }
        if ( isset($attachment['image_url']) ) {
          $setArray['image_url'] = $attachment['image_url'] ;
        }
        if ( isset($attachment['thumb_url']) ) {
          $setArray['thumb_url'] = $attachment['thumb_url'] ;
        }
        if ( isset($attachment['author_name']) ) {
          $setArray['author_name'] = $attachment['author_name'] ;
          if ( isset($attachment['author_link']) ) {
            $setArray['author_link'] = $attachment['author_link'] ;
          }
          if ( isset($attachment['author_icon']) ) {
            $setArray['author_icon'] = $attachment['author_icon'] ;
          }
        }
        if ( isset($attachment['fields']) ) {
          $setArray['fields'] = $attachment['fields'];
        }
        $this->message['attachments'][] = $setArray ;

      }

    } else {
      $fallback    = isset($attachment['fallback']) ? $attachment['fallback'] :$this->fallback ;
      $color       = isset($attachment['color']) ? $attachment['color'] :$this->color ;
      $text        = isset($attachment['text']) ? $attachment['text'] :$this->text ;
      $setArray = array(
        'fallback'=> $fallback,
        'color'=> $color,
        'text'=> $text
      );
      $this->message['attachments'][] = $setArray ;
    }
  }
  private function setMessage($message=null)
  {
    $message = $message===null ? $this->message : $message ;
    if($message===null || !is_array($message)){
      return false ;
    }
    $this->options['http']['content'] = json_encode($message);
  }
  public function sendMessageMain($message=null)
  {
    $this->setMessage($message);
    $response = file_get_contents($this->slackUrl, false, stream_context_create($this->options));
    $this->reset();
    return $response === 'ok';
  }
  public static function getSingleton($url=null)
  {
    $set = $url === null ? 0 : md5($url) ;
    if (!self::$instances[$set]){
      self::$instances[$set] = new self($url);
    }
    return self::$instances[$set];
  }
  public function __toString()
  {
    $msg = '**
     * SlackSend
     *';
    return nl2br($msg);
  }
  public function __call($name, $argments)
  {
  }
  public static function __callStatic( $name, $argments )
  {
  }
  public function __invoke($val)
  {
    var_dump($val);
  }
  public function __destruct()
  {
  }
  final function __clone()
  {
    throw new \Exception('Clone is not allowed against' . get_class($this));
  }
}
?>
