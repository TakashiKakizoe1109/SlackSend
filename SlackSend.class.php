<?php
/**
 *
 * SlackSend
 *
 * @Author  TakashiKakizoe
 * @Version 1.0.2
 *
 * ex.
 * $slack = SlackSend::getSingleton('https://hooks.slack.com/services/YOURKEY/YOURKEY/YOURKEY');
 * $slack
 * ->set('pretext','Pretext')
 * ->set('title','Title')
 * ->set('text','Text')
 * ->set('footer','footer title')
 * ->set('title_link','https://example.com/')
 * ->makeMessage()->sendMessage();
 *
**/
class SlackSend
{
  private static $instance ;

  private $slackUrl = 'https://hooks.slack.com/services/YOURKEY/YOURKEY/YOURKEY';
  private $options  = array();

  private $fallback   = 'fallback' ;
  private $username   = 'Bot' ;
  private $color      = '#3AA3E3'  ;
  private $pretext    = 'pretext'  ;
  private $title      = 'title'    ;
  private $title_link = 'titleLink';
  private $text       = 'text' ;
  private $footer     = 'footer' ;
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
    if ( $key!=='username' ) {
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
    } else {
      $this->username = $val ;
    }
    return $this ;
  }

  public function makeMessage()
  {
    $this->message = array(
      'username' => $this->username ,
      'attachments' => array()
    );
    foreach ($this->attachments as $key => $attachment) {
      $fallback    = isset($attachment['fallback']) ? $attachment['fallback'] :$this->fallback ;
      $color       = isset($attachment['color']) ? $attachment['color'] :$this->color ;
      $pretext     = isset($attachment['pretext']) ? $attachment['pretext'] :$this->pretext ;
      $title       = isset($attachment['title']) ? $attachment['title'] :$this->title ;
      $title_link  = isset($attachment['title_link']) ? $attachment['title_link'] :$this->title_link ;
      $text        = isset($attachment['text']) ? $attachment['text'] :$this->text ;
      $footer      = isset($attachment['footer']) ? $attachment['footer'] :$this->footer ;
      $footer_icon = isset($attachment['footer_icon']) ? $attachment['footer_icon'] :$this->footer_icon ;

      $this->message['attachments'][] =
      array(
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
    }
    return $this ;
  }

  private function setMessage($message=null)
  {
    $message = $message===null ? $this->message : $message ;
    if($message===null || !is_array($message)){
      return false ;
    }
    $this->options['http']['content'] = json_encode($message);
  }

  public function sendMessage($message=null)
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
