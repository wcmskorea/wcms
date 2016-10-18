<?
// Class definition found at http://www.zend.com/zend/spotlight/creating-zip-files3.php
// Some alterations to the original posted code were made in order to get everything working properly
// See example usage at the bottom of this page
/*
$zipfile = new zipfile();

// add the subdirectory ... important!
$zipfile -> add_dir("test/");  //디렉토리 생성 (압축파일)

// add the binary data stored in the string 'filedata'

$filedata ="test/a.jpg";  //원본데이타 파일 (압축대상)
$zipfile -> add_file($filedata, "test/a.jpg");  //add_file(원본,압축대상)

// the next three lines force an immediate download of the zip file:
//header("Content-type: application/octet-stream");
//header("Content-disposition: attachment; filename=test.zip");
//echo $zipfile -> file();


// OR instead of doing that, you can write out the file to the loca disk like this:
$filename = "output.zip";
$fd = fopen ($filename, "wb");
$out = fwrite ($fd, $zipfile -> file());
fclose ($fd);

// then offer it to the user to download:
echo "<a href=\"output.zip\">Click here to download the new zip file.</a> ";


require 'classes/zipfile.class.php';
$zipfile = new zipfile();
$zipfile -> add_file("font/ariali.ttf","font/ariali.ttf");
$zipfile -> add_file("font/H2GPRM.TTF","font/H2GPRM.TTF");
$zipfile -> add_file("font/hangul.ttf","font/hangul.ttf");
$zipfile -> add_dirs("test/");
$fh = fopen('압축.zip', 'w');
fwrite($fh, $zipfile -> file());
fclose($fh);

*/
class zipFile {
    var $datasec = array();
    var $ctrl_dir = array();
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
    var $old_offset = 0;
    var $proc_count=0;
    var $makeDirCount;

    function add_dir($name) {
        $name = str_replace("\\", "/", $name);

        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x0a\x00";
        $fr .= "\x00\x00";
        $fr .= "\x00\x00";
        $fr .= "\x00\x00\x00\x00";

        $fr .= pack("V",0);
        $fr .= pack("V",0);
        $fr .= pack("V",0);
        $fr .= pack("v", strlen($name) );
        $fr .= pack("v", 0 );
        $fr .= $name;
        $fr .= pack("V", 0);
        $fr .= pack("V", 0);
        $fr .= pack("V", 0);

        $this -> datasec[] = $fr;
        $new_offset = strlen(implode("", $this->datasec));

        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .="\x00\x00";
        $cdrec .="\x0a\x00";
        $cdrec .="\x00\x00";
        $cdrec .="\x00\x00";
        $cdrec .="\x00\x00\x00\x00";
        $cdrec .= pack("V",0);
        $cdrec .= pack("V",0);
        $cdrec .= pack("V",0);
        $cdrec .= pack("v", strlen($name) );
        $cdrec .= pack("v", 0 );
        $cdrec .= pack("v", 0 );
        $cdrec .= pack("v", 0 );
        $cdrec .= pack("v", 0 );
        $ext = "\x00\x00\x10\x00";
        $ext = "\xff\xff\xff\xff";
        $cdrec .= pack("V", 16 );
        $cdrec .= pack("V", $this -> old_offset );
        $cdrec .= $name;

        $this -> ctrl_dir[] = $cdrec;
        $this -> old_offset = $new_offset;
        return;
    }

    function add_file($data, $name) {
        $fp = fopen($data,"r");
        $data = fread($fp,filesize($data));
        fclose($fp);
        $name = str_replace("\\", "/", $name);
        $unc_len = strlen($data);
        $crc = crc32($data);
        $zdata = gzcompress($data);
        $zdata = substr ($zdata, 2, -4);
        $c_len = strlen($zdata);
        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x14\x00";
        $fr .= "\x00\x00";
        $fr .= "\x08\x00";
        $fr .= "\x00\x00\x00\x00";
        $fr .= pack("V",$crc);
        $fr .= pack("V",$c_len);
        $fr .= pack("V",$unc_len);
        $fr .= pack("v", strlen($name) );
        $fr .= pack("v", 0 );
        $fr .= $name;
        $fr .= $zdata;
        $fr .= pack("V",$crc);
        $fr .= pack("V",$c_len);
        $fr .= pack("V",$unc_len);

        $this -> datasec[] = $fr;
        $new_offset = strlen(implode("", $this->datasec));

        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .="\x00\x00";
        $cdrec .="\x14\x00";
        $cdrec .="\x00\x00";
        $cdrec .="\x08\x00";
        $cdrec .="\x00\x00\x00\x00";
        $cdrec .= pack("V",$crc);
        $cdrec .= pack("V",$c_len);
        $cdrec .= pack("V",$unc_len);
        $cdrec .= pack("v", strlen($name) );
        $cdrec .= pack("v", 0 );
        $cdrec .= pack("v", 0 );
        $cdrec .= pack("v", 0 );
        $cdrec .= pack("v", 0 );
        $cdrec .= pack("V", 32 );
        $cdrec .= pack("V", $this -> old_offset );

        $this -> old_offset = $new_offset;

        $cdrec .= $name;
        $this -> ctrl_dir[] = $cdrec;
    }

    function add_files($files)
    {
        foreach($files as $file)
        {
            $file = str_replace("//", "/", $file);
            //echo $file."<br>";
            if (is_file($file)) //directory check
            {
                //$data = implode("",file($file));
                $this->add_file($file,$file);
            }
        }
    }

    function add_dirs($dirs){
        $files = $this->read_dir($dirs);
        $this->add_files($files);
    }

    function read_dir($dir) {
      $array = array();
      $d = dir($dir);
      while (false !== ($entry = $d->read())) {
          if($entry!='.' && $entry!='..') {
              $entry = $dir.'/'.$entry;
              if(is_dir($entry)) {
                  $array[] = $entry;
                  $array = array_merge($array, $this->read_dir($entry));
              } else {
                  $array[] = $entry;
              }
          }
      }
      $d->close();
      return $array;
    }

    function file() {
        $data = implode("", $this -> datasec);
        $ctrldir = implode("", $this -> ctrl_dir);

        return
        $data .
        $ctrldir .
        $this -> eof_ctrl_dir .
        pack("v", sizeof($this -> ctrl_dir)) .
        pack("v", sizeof($this -> ctrl_dir)) .
        pack("V", strlen($ctrldir)) .
        pack("V", strlen($data)) .
        "\x00\x00";
    }
}
?>
