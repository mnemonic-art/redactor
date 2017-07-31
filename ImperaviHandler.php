<?php

class ImperaviHandler {
  
  function __construct() {
   try{
     
      $this->imageDir = "images/";
      
      // читаем xml файл в обьект::
      $xml = simplexml_load_file("config/db_conf.xml");
      $host = $xml->host[0];
      $dbname = $xml->dbname[0];
      $user = $xml->user[0];
      $password = $xml->password[0];
      
      $this->db = new \PDO('mysql: host='.$host.'; dbname='. $dbname, $user, $password);
      
      $this->db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
      $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      $this->db->exec("SET NAMES 'utf8mb4'"); 
      $this->db->exec("SET CHARACTER SET 'utf8mb4'");
      $this->db->exec("SET SESSION collation_connection = 'utf8mb4_general_ci'");
    
   }catch(\PDOException $err) { 
      echo 'Ошибка при соединении с БД ' . $err->getMessage(). '<br> 
            в файле '.$err->getFile().", строка ".$err->getLine() . "<br><br>Стэк вызовов: " . preg_replace('/#\d+/', '<br>$0', $err->getTraceAsString()); 
      exit;  
    }
  }
  
  
   /**
   * Запись данных файла в JSON-файл (для редактора)
   * @param string $path
   * @return bool
   */
  function write_image_json($path = '') {
    $dir = $this->imageDir;
    $arr = json_decode(file_get_contents($dir . 'images.json'), 1);
    if($arr){
      $duplicate = false;
      foreach ($arr as $item) {
        if ($item['image'] == $path) {
          $duplicate = true;
          break;
        }
        if ($duplicate) {
          return false;
        }
      }
    }
    $arr[] = [
      'thumb' => $path,
      'image' => $path,
      'title' => ''
    ];
    file_put_contents($dir . 'images.json', stripcslashes(json_encode($arr)));
    return false;
  }


  function query($query, array $values = array(
), $param=false){
    try{
      $stmt = $this->db->prepare($query);
      $values_len = count($values);
      
      for ($i = 0; $i < $values_len; $i++) {
        $value = trim($values[$i]);
        if (preg_match('/^\d+$/', $value)) {
          $stmt->bindValue($i + 1, $value, \PDO::PARAM_INT);
        }
        else {
          $stmt->bindValue($i + 1, $value, \PDO::PARAM_STR);
        }
      }
      $stmt->execute($values);
      if(!$param){
        return $stmt->fetchAll();
      }else{
        return $stmt->rowCount(); 
      }
    } catch (\PDOException $err) {
      echo 'Ошибка при операции с БД ' . $err->getMessage(). '<br> 
              в файле '.$err->getFile().", строка ".$err->getLine() . "<br><br>Стэк вызовов: " . preg_replace('/#\d+/', '<br>$0', $err->getTraceAsString()); 
        exit;  
    }
  }
  
  
  function is_ajax(){

		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
		   !empty($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
		   strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest"){

			return true;
		}
  }

  
  function get_mimeType($filename){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filename);
    finfo_close($finfo);
    return $mime;
  }
  
  
  function uploadImage(){
    if(is_uploaded_file($_FILES['file']['tmp_name'])){
      $tmp = $_FILES['file']['tmp_name'];
      $size = $_FILES['file']['size'];
      $type = $this->get_mimeType($tmp);
      
      $supportMimeTypes = array(
        "image/jpg",
        "image/jpeg",
        "image/pjpeg",
        "image/png",
        "image/gif",
        "image/x-ms-bmp"
      );
      
      if(!in_array($type, $supportMimeTypes) || $size > 10 * 1024 * 1024){
        exit('2');
      }
      
      $namefile = $_FILES['file']['name'];
      //$namefile = mb_convert_encoding($namefile, "UTF-8");
      $namefile = preg_replace('/[^\wа-яё.]/iu', '_', $namefile);
      $namefile = $this->translit($namefile);
      $path = $this->imageDir . $namefile;
      
      if(move_uploaded_file($_FILES['file']['tmp_name'], $path)){
        // displaying file
        $array = array(
            'filelink' => $path
        );
        $this->write_image_json($path);
        exit(stripslashes(json_encode($array)));
      }else{
        exit('2');
      }
    }else{
      exit('2');
    }
  }
  
  
  function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
  }
  
  
  function uploadImage2(){
    $dir = 'images/';

    $_FILES['file']['type'] = strtolower($_FILES['file']['type']);

    if ($_FILES['file']['type'] == 'image/png'
    || $_FILES['file']['type'] == 'image/jpg'
    || $_FILES['file']['type'] == 'image/gif'
    || $_FILES['file']['type'] == 'image/jpeg'
    || $_FILES['file']['type'] == 'image/pjpeg')
    {
        // setting file's mysterious name
        $filename = $_FILES['file']['name'];
        $file = $dir.$filename;

        // copying
        move_uploaded_file($_FILES['file']['tmp_name'], $file);

        // displaying file
        $array = array(
            'filelink' => 'images/'.$filename //,
            //'id' => 
        );

        echo stripslashes(json_encode($array));

    }
  }
  
}
  
  
  
