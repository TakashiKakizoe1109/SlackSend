<?php
/**
 *
 * SlackSend
 *
 * @Author  TakashiKakizoe
 * @Version 1.1.0
 *
 * ex.
 * $slack = SlackSend::getSingleton('https://hooks.slack.com/services/YOURKEY/YOURKEY/YOURKEY');
 * $slack
 * ->set('pretext','Pretext')
 * ->set('title','Title')
 * ->set('text','Text')
 * ->set('footer','footer title')
 * ->set('title_link','https://example.com/')
 * ->sendMessage();
 *
**/
class SlackSend
{
  private static $instance ;
  private $slackUrl = 'https://hooks.slack.com/services/YOURKEY/YOURKEY/YOURKEY';
  private $options  = array();

  private $fallback   = 'fallback' ;
  private $username   = 'Bot' ;
  private $icon_emoji = ':slack:' ;

  private $color       = '#3AA3E3'  ;
  private $pretext     = 'pretext'  ;
  private $title       = 'title'    ;
  private $title_link  = 'titleLink';
  private $text        = 'text' ;
  private $field       = array() ;
  private $image_url   = '' ;
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
  public function set($key='',$val='')
  {
    if($key==='' || $val==='' || !isset($this->{$key}) ){
      throw new Exception('Invalid argument');
    }
    if ( $key==='username' ) {
      $this->username   = $val ;
    } elseif ( $key==='icon_emoji' ) {
      $this->icon_emoji = $val ;
    } else {
      $flg = false ;
      foreach ($this->attachments as $i => $attachment) {
        if(!isset($attachment[$key])){
          $this->attachments[$i][$key] = $val ;
          $flg = true ;
        }
      }
      if(!$flg){
        $this->attachments[][$key] = $val ;
      }
    }
    return $this ;
  }
  public function setFields($title,$text,$short=false){
    $i = count($this->attachments) - 1 ;
    $i = $i < 0 ? 0 : $i ;
    $this->attachments[$i]['fields'][] = array(
      "title"=>$title,
      "value"=>$text,
      "short"=>$short
    );
    return $this ;
  }
  public function sendMessage()
  {
    $this->message = array(
      'username' => $this->username ,
      'icon_emoji' => $this->icon_emoji ,
      'attachments' => array()
    );
    foreach ($this->attachments as $key => $attachment) {
      // params
      $fallback    = isset($attachment['fallback']) ? $attachment['fallback'] :$this->fallback ;
      $color       = isset($attachment['color']) ? $attachment['color'] :$this->color ;
      $pretext     = isset($attachment['pretext']) ? $attachment['pretext'] :$this->pretext ;
      $title       = isset($attachment['title']) ? $attachment['title'] :$this->title ;
      $title_link  = isset($attachment['title_link']) ? $attachment['title_link'] :$this->title_link ;
      $text        = isset($attachment['text']) ? $attachment['text'] :$this->text ;
      $footer      = isset($attachment['footer']) ? $attachment['footer'] :$this->footer ;
      $footer_icon = isset($attachment['footer_icon']) ? $attachment['footer_icon'] :$this->footer_icon ;

      $setArray = array(
        'fallback'=> $fallback,
        'color'=> $color,
        'pretext'=> $pretext,
        'title'=> $title,
        'title_link'=> $title_link,
        'text'=> $text,
        'footer'=> $footer,
        "footer_icon"=> $footer_icon,
        "ts"=> time()
      );
      if ( isset($attachment['image_url']) ) {
        $setArray['image_url'] = $attachment['image_url'] ;
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
    return $this->sendMessageMain() ;
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
    return $response === 'ok';
  }
  public static function getSingleton($url=null)
  {
    if (!self::$instance){
      self::$instance = new self($url);
    }
    return self::$instance;
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
