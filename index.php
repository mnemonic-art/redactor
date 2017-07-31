<?php
  error_reporting(E_ALL);
  header ("Content-Type:text/html; charset=UTF-8");
  
  require "ImperaviHandler.php";
  $handler = new ImperaviHandler();
  
  if($_POST && $handler->is_ajax()){
    $res = $handler->query("update `text` set `text`=? where `id`=?", [$_POST['text'], $_POST['art_id']], true);
    echo $res;
    exit;
  }
  
  if($_FILES && $handler->is_ajax()){
    $handler->uploadImage();
    exit;
  }
  
  $arrData = $handler->query("select * from `text` where `id`=1");

?>

<!DOCTYPE html>

<html lang="ru">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="" />
  <meta name="robots" content="index,follow" />
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
  <title>{{title}}</title>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/2.0.1/normalize.css"> 
  <link rel="stylesheet" href="imperavi/redactor.css" /> 
  <style type="text/css">
    ::selection {
      background: #3a9289;
      color: white;
      text-shadow: none;
    }
    ::-moz-selection{
      background: #3a9289;
      color: white;
      text-shadow: none;
    }
    .panel-body{
      width: 70%;
      margin: 33px auto;
    }
    textarea{
      width: 100%;
      margin: auto;
      height: 100%;
    }
  </style> 
</head>
<body>

  <div class="panel-body">

    <form enctype="multipart/form-data">
      <textarea name="text" class="wysiwyg">
        <?= $arrData[0]['text'] ?>
      </textarea>
      <input type="hidden" name="art_id" value="<?= $arrData[0]['id'] ?>">
      <input type="submit" value="Сохранить">
    </form>
    
  </div>

<script src="http://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.6/prefixfree.min.js"></script>
  <script src="imperavi/jquery-2.1.4.min.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript" src="imperavi/redactor.min.js"></script>
  <script type="text/javascript">
    
       
   $(function(){
     
     $("textarea").attr('spellcheck', 'false');
    
    // сохранение текста в таблице MySQL
    $("form").on("submit", function(e){
      
      preventdefault(e);
      var $this = $(this),
      data = $this.serialize();
      $.ajax({
        data: data,
        type: "POST",
        success: function(res){
          console.log(res);
          if(res > 0){
            $this.find("[type=submit]").css('background', 'green');
            setTimeout(function(){
              $this.find("[type=submit]").css('background', 'blue');
            }, 3333);
          }
        }
      });
      
    });
    
    // Редактор
    // настройки редактора
    var wysiwygSettings = {
        lang: 'ru',
        plugins: ['fontcolor', 'table', 'fullscreen', 'imagemanager', 'link', 'video', 'inlinestyle'],
        buttons: ['html', 'formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'image', 'table', 'link', 'alignment', 'horizontalrule', 'video'],
        formatting: ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5'],
        removeEmpty: ['strong', 'em', 'span', 'p'],
        toolbarFixed: false,
        //toolbarFixedTarget: '.fancybox-inner',
        imageUpload: 'index.php',
        imageManagerJson: 'images/images.json',
        imageFloatMargin: '10px',
        //imageUploadParam: $('#file_upl').data('name') || 'image', // Ключ, под которым будет массив данных загружаемого изображения

    };
    $('.wysiwyg').redactor(wysiwygSettings);
        
  });
  
  function preventdefault(e){
    e = e || window.event;
    if(e.preventDefault) e.preventDefault();
    else e.returnValue  = false;  
  }
    
  </script>
</body>
</html>