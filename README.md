SlackSend
==========

From php to slack Easily send messages using slack's incoming webhooks

Description
============

You can create instances of php class, set texts, authors, thumbnail images etc using chain method, and send to specified slack channel with send method.

Preparation
===========

### 1. Download this class

Please put this php class file in a suitable directory

### 2. Require this class

Please require SlackSend class in the php file you want to notify slack

`require YourDirectory.'/SlackeSend.php';`

### 3. Set up incoming webhooks

Please set up incoming webhooks for Slack and get the webhook URL

Usage
===========

### START : Create an instance

    $slack = SlackSend::getSingleton('https://hooks.slack.com/services/YOURKEY/YOURKEY/YOURKEY');

### ・Notify simple text

    $slack
      ->set('pretext','Pretext')
      ->set('title','Title')
      ->set('text','Text')
      ->set('footer','footer title')
      ->sendMessage();

### Method

`->set(Key,Value)`

| Key           | Value                               | Description                      |
| :-----------: | :---------------------------------: | :------------------------------: |
| fallback      | `"Notification"`                    | Push notification message |
| username      | `"HappyBOT"`                        | Name of the notifier |
| color         | `"#CC6633"`                         | Color of the notification |
| pretext       | `"pretext"`                         | pretext of the notification |
| title         | `"title"`                           | title of the notification |
| title_link    | `"http://example.com/company.html"` | Please set the URL |
| text          | `"text"`                            | text of the notification |
| image_url     | `"http://example.com/img.png"`      | Please set the IMG URL |
| thumb_url     | `"http://example.com/img.png"`      | Please set the IMG URL |
| author_name   | `"alfredo"`                         | author name of the notification |
| author_icon   | `"http://example.com/alfredo.png"`  | Please set the IMG URL |
| author_link   | `"http://example.com/profile.html"` | Please set the URL |

`->setFields(title,text,boolean)`  
boolean === false Short text  
boolean === true  Long text  

Examples
===========

### ・Add title link

    $slack
      ->set('pretext','Pretext')
      ->set('title','Title')
      ->set('text','Text')
      ->set('title_link','https://example.com/')
      ->set('footer','footer title')
      ->sendMessage();

### ・Add author

    $slack
      ->set('pretext','Pretext')
      ->set('title','Title')
      ->set('text','Text')
      ->set('author_name','author_name')
      ->set('author_link','author_link')
      ->set('author_icon','author_icon')
      ->set('footer','footer title')
      ->sendMessage();

### ・Set name

    $slack
      ->set('username','bot')
      ->set('pretext','Pretext')
      ->set('title','Title')
      ->set('text','Text')
      ->set('footer','footer title')
      ->sendMessage();

### ・Set notification

    $slack
      ->set('fallback','notification')
      ->set('pretext','Pretext')
      ->set('title','Title')
      ->set('text','Text')
      ->set('footer','footer title')
      ->sendMessage();

### ・Set icon

    $slack
      ->set('pretext','Pretext')
      ->set('title','Title')
      ->set('text','Text')
      ->set('icon_emoji',':dolphin:')
      ->set('footer','footer title')
      ->set('footer_icon','https://YOUR_ICON_URL')
      ->sendMessage();

### ・Set image_url

    $slack
      ->set('pretext','Pretext')
      ->set('title','Title')
      ->set('text','Text')
      ->set('image_url','https://YOUR_IMAGE_URL')
      ->set('footer','footer title')
      ->sendMessage();

### ・Set thumb_url

    $slack
      ->set('pretext','Pretext')
      ->set('title','Title')
      ->set('text','Text')
      ->set('thumb_url','https://YOUR_THUMB_URL')
      ->set('footer','footer title')
      ->sendMessage();

### ・Set field

    $slack
      ->set('pretext','Pretext')
      ->set('title','Title')
      ->set('text','Text')
      ->setFields('titleA','longtext',false)
      ->setFields('titleB','shorttext1',true)
      ->setFields('titleC','shorttext2',true)
      ->set('footer','footer title')
      ->sendMessage();

### ・Multiple message

    $slack
      ->set('pretext','Pretext1')
      ->set('title','Title1')
      ->set('text','Text1')
      ->set('footer','footer title1')
      ->set('pretext','Pretext2')
      ->set('title','Title2')
      ->set('text','Text2')
      ->set('footer','footer title2')
      ->sendMessage();

License
===========

[MIT License](https://github.com/TakashiKakizoe1109/SlackSend/blob/master/LICENSE) © TakashiKakizoe
