<?php
class Functions
{
    var $tableAuth;
    var $squery;
    var $rows;
    var $result;
    var $data;
    var $rst;

    function __construct($authTable)
    {
        $this->tableAuth = $authTable;
    }

    /**------------------------------------------------------------------------------------
     * 모듈 사용여부 체크
     *-------------------------------------------------------------------------------------
     * $funcule : 모듈명
     */
    function checkModule($funclue)
    {
        return (in_array($funclue, $GLOBALS['cfg']['modules'])) ? true : false;
    }

    /**
     * 문서(게시물) 갯수 업데이트
     * @param string $type : 칼럼
     * @param int $count : 갯수
     * @param string $cate : 현재위치
     * @param string $cateNew : 변경위치
     */
    function checkCount($type, $count=1, $cate, $cateNew=null, $trashed='Y')
    {
        $set = ($count > 0) ? "+'".abs($count)."'" : "-'".abs($count)."'";
        switch($type)
        {
            case "articled":
                @mysql_query(" UPDATE `".$GLOBALS['cfg']['cate']['mode']."__` SET articled=articled".$set." WHERE '".$cate."' IN (cate,share) ");
                break;
            case "trashed":
                @mysql_query(" UPDATE `".$GLOBALS['cfg']['cate']['mode']."__` SET articleTrashed=articleTrashed".$set." WHERE '".$cate."' IN (cate,share) ");
                break;
            case "delete":
                $query = ($trashed == 'Y') ? "articled=articled".$set.", articleTrashed=articleTrashed".$set : "articled=articled".$set;
                @mysql_query(" UPDATE `".$GLOBALS['cfg']['cate']['mode']."__` SET ".$query." WHERE '".$cate."' IN (cate,share) ");
                break;
            case "move":
                @mysql_query(" UPDATE `".$GLOBALS['cfg']['cate']['mode']."__` SET articled=articled-'".abs($count)."' WHERE '".$cate."' IN (cate,share) ");
                @mysql_query(" UPDATE `".$GLOBALS['cfg']['cate']['mode']."__` SET articled=articled+'".abs($count)."' WHERE '".$cateNew."' IN (cate,share) ");
                break;
        }
        return true;
    }

    /**------------------------------------------------------------------------------------
     * 날짜입력 셀렉트 박스생성
     *-------------------------------------------------------------------------------------
     * $prefix : prefiex
     * $getDate : selected할 날짜
     * $dateOnly : 노출할 select Element
     */
    function insertDate($prefix, $getDate, $dateOnly="YMD")
    {
        $date = ($getDate) ? $getDate : time();
        $year = ($getDate) ? date('Y', $date) : null;
        $month= ($getDate) ? date('m', $date) : null;
        $day  = ($getDate) ? date('d', $date) : null;
        $hour = ($getDate) ? date('H', $date) : null;
        $min  = ($getDate) ? date('i', $date) : null;
        $sec  = ($getDate) ? date('s', $date) : null;
        if(preg_match('/Y/',$dateOnly))
        {
            $rst  = '<select id="'.$prefix.'year" name="'.$prefix.'year" class="bg_gray">';
            if(!$year) { $rst .= '<option selected="selected">----년</selected>'; }
            for($i=date('Y')+3;$i>=date('Y')-80;$i--)
            {
                $rst .= '<option value="'.$i.'"';
                if($year == $i) { $rst .= ' selected="selected" class="colorGreen"'; }
                $rst .= '>'.$i.'년</option>';
            }
            $rst .= '</select>&nbsp;';
        }
        if(preg_match('/M/',$dateOnly))
        {
            $rst .= '<select id="'.$prefix.'month" name="'.$prefix.'month" class="bg_gray">';
            if(!$month) { $rst .= '<option selected="selected">--월</selected>'; }
            for($i=1;$i<=12;$i++)
            {
                $rst .= '<option value="'.$i.'"';
                if($month == $i) { $rst .= ' selected="selected" class="colorGreen"'; }
                $rst .= '>'.str_pad($i, 2, "0", STR_PAD_LEFT).'월</option>';
            }
            $rst .= '</select>&nbsp;';
        }
        if(preg_match('/D/',$dateOnly))
        {
            $rst .= '<select id="'.$prefix.'day" name="'.$prefix.'day" class="bg_gray">';
            if(!$day) { $rst .= '<option selected="selected">--일</selected>'; }
            for($i=1;$i<=31;$i++)
            {
                $rst .= '<option value="'.$i.'"';
                if($day == $i) { $rst .= ' selected="selected" class="colorGreen"'; }
                $rst .= '>'.str_pad($i, 2, "0", STR_PAD_LEFT).'일</option>';
            }
            $rst .= '</select>&nbsp;';
        }
        if(preg_match('/H/',$dateOnly))
        {
            $rst .= '<select id="'.$prefix.'hour" name="'.$prefix.'hour" class="bg_gray">';
            if(!$hour) { $rst .= '<option selected="selected">--시</selected>'; }
            for($i=0;$i<=23;$i++)
            {
                $rst .= '<option value="'.$i.'"';
                if(!is_null($hour) && $hour == $i) { $rst .= ' selected="selected" class="colorGreen"'; }
                $rst .= '>'.str_pad($i, 2, "0", STR_PAD_LEFT).'시</option>';
            }
            $rst .= '</select>&nbsp;';
        }
        if(preg_match('/I/',$dateOnly))    //10분단위로 변경(엘리시아 요청)
        {
            $rst .= '<select id="'.$prefix.'min" name="'.$prefix.'min" class="bg_gray">';
            if(!$min) { $rst .= '<option selected="selected">--분</selected>'; }
            for($i=0;$i<6;$i++)
            {
                $rst .= '<option value="'.$i.'0"';
                if(!is_null($min) && $min == $i*10) { $rst .= ' selected="selected" class="colorGreen"'; }
                $rst .= '>'.str_pad($i*10, 2, "0", STR_PAD_LEFT).'분</option>';
            }
            $rst .= '</select>&nbsp;';
        }
        if(preg_match('/S/',$dateOnly))
        {
            $rst .= '<select id="'.$prefix.'sec" name="'.$prefix.'sec" class="bg_gray">';
            if(!$sec) { $rst .= '<option selected="selected">--초</selected>'; }
            for($i=0;$i<60;$i++)
            {
                $rst .= '<option value="'.$i.'"';
                if(!is_null($sec) && $sec == $i) { $rst .= ' selected="selected" class="colorGreen"'; }
                $rst .= '>'.str_pad($i, 2, "0", STR_PAD_LEFT).'초</option>';
            }
            $rst .= '</select>&nbsp;';
        }
        return $rst;
    }

    /**------------------------------------------------------------------------------------
     * Add-on 코드 컨버터
     *-------------------------------------------------------------------------------------
     * $html : DB의 저장된 HTM
     */
    function matchAddon($html)
    {
        $html = addslashes($html);
        @preg_match_all("#\{\{(.*?)\}\}#si", $html, $matchs);
        foreach($matchs[1] as $key=>$value)
        {
            $pos      = explode(".", $value);
            if(count($pos) > 1)
            {
                $replace  = '"; include "./';
                $replace .= $pos[0].'/'.$pos[1].'/'.$pos[2];
                $replace .= '.php"; echo "';
            }
            else
            {
                $replace    = '".$'.$value.'."';
            }
            $html = str_replace($matchs[0], $replace, $html);
        }
        return 'echo "'.$html.'";';
    }

    /**------------------------------------------------------------------------------------
     * Contents 이미지 컨버
     *-------------------------------------------------------------------------------------
     * $html : DB의 저장된 HTM
     */
    function matchImage($html)
    {
        $count = preg_match_all("#\<img(.*?)\>#si", $html, $matches);
        for ($i = 0; $i < $count; $i++)
        {
            $img = preg_match_all("#src=\"(.*?)\"#si", $matches[1][$i], $match);

            //게시판 이미지에 링크가 걸릴경우 링크가 안걸리는 부분처리 2011.12.06
            //img 태그 옵션에 data="link" 구문을 추가 하면 이미지 확대보기가 아닌 링크가 걸린다.
            $dataType = preg_match_all("#data=\"link\"#si", $matches[1][$i], $out);

            if(preg_match("/data/", $matches[1][$i]) && $dataType=="0")
            {
                foreach($match[1] as $key=>$value)
                {
                    $replace  = '<a href="'.$value.'" rel="facebox" title="확대보기">';
                    $replace .= $matches[0][$i];
                    $replace .= '</a>';
                }
                $html = str_replace($matches[0][$i], $replace, $html);
            }
        }
        return $html;
    }

    /**------------------------------------------------------------------------------------
     * PHP 코드 컨버터
     *-------------------------------------------------------------------------------------
     * $html : DB의 저장된 HTML
     */
    function matchPHP($html)
    {
        $start_html    = '<?php echo($';
        $end_html = ');?>';

        $matchCount = preg_match_all("#\{\{\:(.*?)\:\}\}#si", $html, $matches);
        for($i=0; $i<$matchCount; $i++)
        {
            $before_replace = $matches[1][$i];
            $after_replace = $matches[1][$i];
            $str_to_match = "{{:" . $before_replace . ":}}";

            $replacement = $start_html;
            $replacement .= $after_replace;
            $replacement .= $end_html;
            $html = str_replace($str_to_match, $replacement, $html);
        }
        $html = str_replace("{{:", $start_html, $html);
        $html = str_replace("}}:", $end_html, $html);
        return $html;
    }

    /**------------------------------------------------------------------------------------
     * Contents 코드 컨버터
     *-------------------------------------------------------------------------------------
     * $html : DB의 저장된 HTM
     */
    function matchCode($html)
    {
        $start_html    = '<div class="code">';
        $end_html = '</div>';

        $matchCount = preg_match_all("#\[code\](.*?)\[\/code\]#si", $html, $matches);
        for($i=0; $i<$matchCount; $i++)
        {
            $before_replace = $matches[1][$i];
            $after_replace = $matches[1][$i];
            $str_to_match = "[code]" . $before_replace . "[/code]";

            $replacement = $start_html;
            $replacement .= $after_replace;
            $replacement .= $end_html;
            $html = str_replace($str_to_match, $replacement, $html);
        }
        $html = str_replace("[code]", $start_html, $html);
        $html = str_replace("[/code]", $end_html, $html);
        return $html;
    }

    /**------------------------------------------------------------------------------------
     * 게시물의 마지막 Sequency
     *-------------------------------------------------------------------------------------
     */
    function getMaxNum($table, $colum)
    {
        $rst = @mysql_fetch_array(@mysql_query("SELECT MAX(".$colum.") AS ".$colum." FROM ".$table));
        return $rst[0] + 1;
    }

    # 카테고리 이름
    function getCateName($code)
    {
        $rst = @mysql_fetch_array(@mysql_query("SELECT name FROM `".$this->tableAuth."` WHERE cate='".$code."'"));
        return $rst[0];
    }

    # 댓글 총갯수
    function getCommentCount($table, $parent)
    {
        $rst = @mysql_fetch_array(@mysql_query(" SELECT COUNT(seq) FROM `".$table."` WHERE parent='".$parent."' "));
        if($rst[0] > 0) {
            return number_format($rst[0]);
        } else {
            return 0;
        }
    }

    # 게시물 총갯수
    function getTotalCount($table, $query=1)
    {
        $rst = @mysql_fetch_array(@mysql_query("SELECT SUM(articled) AS articled, SUM(trashed) AS trashed FROM `".$table."` WHERE ".$query.""));
        return $rst;
    }

    # 게시물 총갯수 - 실데이터
    function getArticledCount($table, $query=1)
    {
        $rst = @mysql_fetch_array(@mysql_query("SELECT COUNT(*) FROM `".$table."` WHERE ".$query.""));
        return $rst[0];
    }

    # 검색어 활성
    function searchWord($col, $text)
    {
        $replace = "<span style='background-color:yellow; color:red;'>" . $text . "</span>";
        return str_replace($text, $replace, $col);
    }

    # 태그삭제
    function deleteTags($text)
    {
        $src = array("/\n/i","/<html.*<body[^>]*>/i","/<\/body.*<\/html>.*/i",
             "/<\/*(layer|body|html|head|meta|input|select|option|form)[^>]*>/i",
             "/<(style|script|title).*<\/(style|script|title)>/i",
             "/<\?xml(.*)\/>/i",
             "/<\/*(script|style|title|xmp)>/i","/<(\\?|%)/i","/(\\?|%)>/i",
             "/#\^--ENTER--\^#/i");
        $tar = array("#^--ENTER--^#","","","","","","","&lt;\\1","\\1&gt;","\n");
        $this->TEXT = preg_replace($src,$tar,$text);
        return $this->TEXT;
    }

    //new이미지 띄우
    //게시물날짜,제한일,이미지경로
    function iconNew($date, $limit, $icon)
    {
        $toDay        = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
        $dateResult = $toDay - $date;
        if($dateResult > $limit)
        {
            return null;
        }
        else
        {
            return $icon;
        }
    }

    //글자자르기
    //
    function cutStr ($str, $len=12, $tail=null)
    { //문자열 자르기 함수
        //UTF-8 전용
        preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);
        $m    = $match[0];
        $slen = strlen($str);        // length of source string
        $tlen = strlen($tail);    // length of tail string
        $mlen = count($m);            // length of matched characters

        if ($slen <= $len) return $str;
        if (!$checkmb && $mlen <= $len) return $str;

        $ret  = array();
        $count = 0;

        for ($i=0; $i < $len; $i++) {
        $count += ($checkmb && strlen($m[$i]) > 1)?2:1;
        if ($count + $tlen > $len) break;
        $ret[] = $m[$i];
        }
        return join('', $ret).$tail;
//        if (!$str || strlen($str)<=$len) return $str;
//        $str  = preg_replace("/(([\x80-\xff].)*)[\x80-\xff]?$/", "\\1", @substr($str, 0, $len));
//        $str .= ($tail) ? $tail :  NULL;
//        return $str;
    }

    # 자동 URL
    function autoLink($str)
    {
        // URL 치환
        $homepage_pattern = "/([^\"\'\=\>])(mms|http|HTTP|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"\']+)/";
        $str = preg_replace($homepage_pattern,"\\1<a href=\\2://\\3 target=_blank>\\2://\\3</a>", " ".$str);

        // 메일 치
        $email_pattern = "/([ \n]+)([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)/";
        $str = preg_replace($email_pattern,"\\1<a href=mailto:\\2@\\3>\\2@\\3</a>", " ".$str);

        return $str;
    }

    /**------------------------------------------------------------------------------------
     * 디렉토리 열기
     |--------------------------------------------------------------------------------------
     * $dir        : 디렉토리 위치(절대경로)
     * $is            : 디렉토리인지 여부
     */
    function dirOpen($dir, $is=null)
    {
        $fileArray = array();
        if(is_dir($dir))
        {
            # 디렉토리를 열고..
            if($handle = @opendir($dir))
            {
                # 디렉토리를 읽어서...
                while(false != ($file = @readdir($handle)))
                {
                    if($file != "." & $file != "..")
                    {
                        # 배열에 저장하자
                        if($is == "dir")
                        {
                            if(is_dir($dir."/".$file)) @array_push($fileArray, $file);
                        }
                        else
                        {
                            if(is_dir($dir."/".$file)) @array_unshift($fileArray, $file); else @array_push($fileArray, $file);
                        }
                    }
                }
            }
            # 디렉토리를 닫자
            closedir($handle);
        }
        return $fileArray;
    }

    /**------------------------------------------------------------------------------------
     * 디렉토리 복사
     |--------------------------------------------------------------------------------------
     * $source        : 원본 디렉토리
     * $dest            : 대상 디렉토리
     * $overwrite: 덮어씌울건지 여부
     */
    function dirCopy($source, $dest, $overwrite = false)
    {
        if($handle = @opendir($source))
        {
            while(false !== ($file = @readdir($handle)))
            {
                if($file != '.' && $file != '..')
                {
                    $path = $source.'/'.$file;
                    if(is_file($path))
                    {
                        if(!is_file($dest.'/'.$file) || $overwrite)
                        if(!@copy($path, $dest.'/'.$file)) $this->err("File (".$dest.'/'.$file.") could not be copied, likely a permissions problem");
                    }
                    else if(is_dir($path))
                    {
                        if(!is_dir($dest.'/'.$file))
                        @mkdir($dest.'/'.$file);
                        $this->dirCopy($path, $dest.'/'.$file, $overwrite);
                    }
                }
            }
            closedir($handle);
        }
        return true;
    }

    /**------------------------------------------------------------------------------------
     * 디렉토리 삭제
     |--------------------------------------------------------------------------------------
     * $source        : 대상 디렉토리
     */
    function dirDel($source)
    {
        if($handle = @opendir($source))
        {
            while(false !== ($file = @readdir($handle)))
            {
                if($file != '.' && $file != '..')
                {
                    $path = $source.'/'.$file;
                    if(is_file($path))
                    {
                        @unlink($path);
                    }
                    else if(is_dir($path))
                    {
                        $this->dirDel($path);
                        @rmdir($path);
                    }
                }
            }
            closedir($handle);
        }
        @rmdir($source);
        return true;
    }

    function getDirectorySize($path)
    {
      $totalsize = 0;
//      $totalcount = 0;
//      $dircount = 0;
      if ($handle = opendir ($path))
      {
        while (false !== ($file = readdir($handle)))
        {
          $nextpath = $path . '/' . $file;
          if ($file != '.' && $file != '..' && !is_link ($nextpath))
          {
            if (is_dir ($nextpath))
            {
//              $dircount++;
              $result = $this->getDirectorySize($nextpath);
              $totalsize += $result;
//              $totalcount += $result['count'];
//              $dircount += $result['dircount'];
            }
            else if (is_file ($nextpath))
            {
              $totalsize += filesize ($nextpath);
//              $totalcount++;
            }
          }
        }
      }
      closedir ($handle);
      //$total['size'] = $totalsize;
      //$total['count'] = $totalcount;
      //$total['dircount'] = $dircount;
      return $totalsize;
    }


    /**------------------------------------------------------------------------------------
     * 배열값 확인
     |--------------------------------------------------------------------------------------
     * $arr        : 배열값
     */
    function showArray($arr)
    {
        print_r("<pre>");
        print_r($arr);
        print_r("</pre>");
    }

    /**------------------------------------------------------------------------------------
     * 유효성 검사
     |--------------------------------------------------------------------------------------
     * $val : POST된 데이터값
     * $name : POST된 데이터명
     */
    function vaildCheck($val, $title, $type=null, $must="N", $target=null)
    {
        if($must != 'N' && $val !='')
        {
            switch($type)
            {
                # 짧은 내용 체크 ------------------------------------------------
                case 'short':
                    $msg = "최소 5자이상 입력해주세요.";
                    $rst = (strlen($val) < 5) ? false : true;
                    break;
                    # 배열 체크 ------------------------------------------------
                case 'array':
                    $msg = "선택항목을 다시 체크해주세요";
                    $rst = (!is_array($val)) ? false : true;
                    break;
                    # 동일항목 체크 ------------------------------------------------
                case 'match':
                    $msg = "두 항목의 값이 일치하지 않습니다.";
                    $rst = (trim(strtolower($val[0])) !== trim(strtolower($val[1]))) ? false : true;
                    break;
                    # 이메일주소 체크 ------------------------------------------
                case 'email':
                    $msg = "이메일 형식에 맞지 않습니다.";
                    $rst = (!preg_match("/^[^@ ]+@[a-zA-Z0-9\-\.]+\.+[a-zA-Z0-9\-\.]/", $val)) ? false : true;
                    break;
                    # URL 체크 -------------------------------------------------
                case 'url':
                    $msg = "URL 형식에 맞지 않습니다.";
                    $rst = (!preg_match("/^[\/\:\.가-힣a-zA-Z0-9_-]+\.[a-zA-Z]+$/", $val)) ? false : true;
                    break;
                    # 영문전용 체크 --------------------------------------------
                case 'engonly':
                    $msg = "영문만 입력하셔야 합니다.";
                    $rst = (!preg_match("/^[a-zA-Z]+$/", $val)) ? false : true;
                    break;
                    # 한글전용 체크 --------------------------------------------
                case 'korean':
                    $msg = "한글만 입력하셔야 합니다.";
                    //$rst = (!preg_match("/^[\xA1-\xFE][\xA1-\xFE]+$/", $val)) ? false : true;
                    $rst = (!preg_match("/^[가-힣]+$/", $val)) ? false : true;
                    break;
                    # 숫자전용 체크 --------------------------------------------
                case 'num':
                    $msg = "숫자만 입력하셔야 합니다.";
                    $rst = (!preg_match("/^[0-9]+$/", $val)) ? false : true;
                    break;
                    # 숫자전용(소숫점) 체크 ------------------------------------
                case 'decnum':
                    $msg = "숫자만(소숫점 포함) 입력하셔야 합니다.";
                    $rst = (!preg_match("/^[-0-9\.]+$/", $val)) ? false : true;
                    break;
                    # 금액전용 체크 ------------------------------------
                case 'money':
                    $msg = "숫자만(콤마 포함) 입력하셔야 합니다.";
                    $rst = (!preg_match("/^[-0-9\,]+$/", $val)) ? false : true;
                    break;
                    # 주민번호 체크 --------------------------------------------
                case 'idcode':
                    $msg = "주민등록번호 형식에 맞지 않습니다.";
                    $rst = true;
                    if(!preg_match("/^([0-9]{6})-?([0-9]{7})$/", $val))
                    {
                        $rst = false;
                    } else
                    {
                        $val = str_replace("-","",trim($val));
                        if ( 13 != strlen($val) ) { $rst = false; }  // 13자리체크

                        $sex = substr($val,6,1);
                        if ( "1" > $sex && "8" < $sex ) { $rst = false; } // 성별이 1~8체크
                        if      ("1" == $sex || "2" == $sex || "5" == $sex || "6" == $sex ) { $year = "19".substr($val,0,2); }
                        else if ("3" == $sex || "4" == $sex || "7" == $sex || "8" == $sex ) { $year = "20".substr($val,0,2); }
                        $month = substr($val,2,2);
                        $day   = substr($val,4,2);
                        if ( !checkdate($month,$day,$year) ) { $rst = false; }

                        $now = mktime();
                        //$old = mktime(0,0,1,$month,$day,$year);
                        $old = strtotime($year."-".$month."-".$day." 01:00:00");
                        if ($now < $old) { $rst = false; }  // 시스템날짜이후체크

                        for($loop=0;$loop < 12;$loop++)
                        {
                            if ($chk < 2 || $chk > 9) { $chk = 2; }
                            $hap += substr($val,$loop,1) * $chk;
                            ++$chk;
                        }
                        if (11 - ($hap%11) != substr($val,12,1)) { $rst = false; } // 체크비트
                    }
                    break;
                    # 사업자번호 체크 ------------------------------------------
                case 'bizno':
                    $chk = '137137135';
                    $msg = "사업자등록번호 형식에 맞지 않습니다.";
                    $rst = true;
                    if(!preg_match("/([0-9]{3})-?([0-9]{2})-?([0-9]{5})/", $val)) {
                        $rst = false;
                    }

                    $val = str_replace("-","",trim($val));
                    if ( 10 != strlen($val) ) { $rst = false; } // 길이체크(10)

                    for ( $loop=0; $loop<9; $loop++ )
                    {
                        $sum = $sum + (substr($val,$loop,1)*substr($chk,$loop,1));
                    }
                    $sum = $sum + ((substr($val,8,1)*5)/10);
                    $rst = $sum%10;
                    if (0 == $rst) { $result = 0;         }
                    else           { $result = 10 - $rst; }
                    $saub = substr($val,9,1);
                    if ( $result <> $saub ) { $rst = false; }
                    break;
                    # 전화번호 체크 --------------------------------------------
                case 'phone':
                    $msg = "전화번호 형식에 맞지 않습니다.";
                    $rst = (!preg_match("/^(0[2-8][0-5]?|01[01346-9])-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/", $val) &&
                    !preg_match("/^(1544|1566|1577|1588|1644|1688)-?([0-9]{4})$/", $val)) ? false : true;
                    break;
                    # 집전화 체크 ----------------------------------------------
                case 'homephone':
                    $msg = "전화번호 형식에 맞지 않습니다.";
                    $rst = (!preg_match("/^(0[2-8][0-5]?)-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/", $val) &&
                    !preg_match("/^(1544|1566|1577|1588|1644|1688)-?([0-9]{4})$/", $val)) ? false : true;
                    break;
                    # 휴대폰 체크 ----------------------------------------------
                case 'mobile':
                    $msg = "전화번호 형식에 맞지 않습니다.";
                    $rst = (!preg_match("/^(01[01346-9])-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/", $val)) ? false : true;
                    break;
                    # 아이디 체크 ----------------------------------------------
                case 'userid':
                    $msg = "4자이상 12자 미만으로 입력하십시오. [영문], [숫자], [ _ ] 문자만 사용할 수 있습니다.";
                    $rst = (!preg_match("/^[a-zA-Z0-9\_]{4,12}$/", $val)) ? false : true;
                    break;
                    # 닉네임 체크 ----------------------------------------------
                case 'nick':
                    $msg = "2자이상 10자 미만으로 입력하십시요. [한글] [영문] [숫자] 문자만 사용할 수 있습니다.";
                    $rst = (!preg_match("/^[가-힣a-zA-Z0-9]{4,30}$/", $val)) ? false : true;
                    //$rst = (!preg_match("/^[\xA1-\xFEa-zA-Z0-9][\xA1-\xFEa-zA-Z0-9]{3,15}$/", $val)) ? false : true;
                    break;
                    # 파일명 체크 ----------------------------------------------
                case 'filename':
                    $msg = "1자이상 100자 미만으로 입력하십시요. [한글] [영문] [숫자] [ _ ] [ - ] 문자만 사용할 수 있습니다.";
                    $rst = (!preg_match("/^[\.가-힝a-zA-Z0-9_-]{1,100}$/u", $val)) ? false : true;
                    break;
                    # 날짜 체크 ------------------------------------------------
                case 'date':
                    $msg = "년-월-일(2008-01-01) 형식으로 입력해주십시요.";
                    $rst = (!preg_match("/^[12][0-9]{3}\-[01]?[0-9]\-[0-3]?[0-9]$/", $val)) ? false : true;
                    if($val) {
                        $date = explode("-", $val);
                        $rst = (!checkdate($date[1],$date[2],$date[0])) ? false : true;
                    }
                    break;
                    # 년월 체크 ------------------------------------------------
                case 'month':
                    $msg = "년-월(2008-01) 형식으로 입력해주십시요.";
                    $rst = (!preg_match("/^[12][0-9]{3}\-[01]?[0-9]$/", $val)) ? false : true;
                    $date = explode("-", $val);
                    $rst = (!checkdate($date[1],1,$date[0])) ? false : true;
                    break;
                    # 기본 공백 체크
                default:
                    $msg = "필수 입력항목입니다.";
                    $rst = (preg_match("(^[[:space:]]+)", $val) || $val == "") ? false : true;
                    break;
            }
            $rst = ($must == 'Y' && !$val) ? true : $rst;
            //print($must."-".$val."<br />");
            if($rst === false)
            {
                //$this->Err($title."은(는) ".$msg, "parent.$('input[name=".$target."]').select();parent.$('textarea[name=".$target."]').select()");
                //if(!$target)
                //{
                switch($target){
                    case "back" : default :
                        $this->err($title."은(는) ".$msg, 'back');
                    break;
                    case "pass":
                        $this->err($title."은(는) ".$msg);
                    break;
                    case "dialog":
                        $this->ajaxMsg($title."은(는) ".$msg, '', 20);
                    break;
                }
            }
            else
            {
                return $rst;
            }
        }
        else
        {
            return true;
        }
    }

    /**------------------------------------------------------------------------------------
     * 로그생성
     */
    function setLog($file, $subject, $force=false)
    {
        if(__LOGS__ === false && $force === false) { return false; }
        $dir = __HOME__.'data';
        if(!is_dir($dir))                             { mkdir($dir, 0707); }
        if(!is_dir($dir."/".date("Y")))               { mkdir($dir."/".date("Y"), 0707); }
        if(!is_dir($dir."/".date("Y")."/".date("m"))) { mkdir($dir."/".date("Y")."/".date("m"), 0707); }
        $this->logDir = $dir."/".date("Y")."/".date("m");
        $fp = fopen($this->logDir.'/log_system.txt', 'a');
        fwrite($fp, "[".date("Y/m/d H:i:s")."][".$_SERVER['REMOTE_ADDR']."][".$_SESSION['uid']."][".$file."]".$subject."\n");
        fclose($fp);
        return true;
    }

    /*
     * 리퍼러 체크
     */
    function checkRefer($method=null)
    {
        if(!preg_match("/".$_SERVER['HTTP_HOST']."/", $_SERVER['HTTP_REFERER'])) { $this->err404(); }
        if($method && $_SERVER['REQUEST_METHOD'] != $method ) { $this->err404(); }
    }

    //메세지
    function alt($a)
    {
        echo('<script type="text/javascript">
          //<![CDATA[
            window.alert("'.$a.'");
        //]]>
        </script>');
    }

    function ajaxMsg($msg, $scr=null, $margin=0)
    {
        echo('<div style="padding:10px 0; margin-top:'.$margin.'px; text-align:center;"><strong>'.$msg.'</strong>');
        if($scr)
        {
            echo('<br /><br /><span class="btnPack black medium"><a href="javascript:;" onclick="'.$scr.'">확인</a></span></div>');
        }
        else
        {
            echo('<br /><br /><span class="small_gray">(1초후 자동으로 창이 닫힙니다)</span></div>
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function(){setTimeout("$.dialogRemove();",2000);});
            //]]>
            </script>');
        }
        exit(0);
    }

    function ajaxAlt($msg, $target="parent.")
    {
        echo('<!DOCTYPE html>
        <html>
        <head>
          <title>메세지</title>
          <meta http-equiv="content-type" content="application/xhtml+xml; charset='.$GLOBALS['cfg']['charset'].'" />
          <meta http-equiv="content-style-type" content="text/css" />
          <meta http-equiv="pragma" content="no-cache" />
          <style type="text/css">
            @import url("'.$GLOBALS['cfg']['droot'].'common/default.css");
          </style>
          <script type="text/javascript" src="'.$GLOBALS['cfg']['droot'].'common/js/jquery.js"></script>
          <script type="text/javascript">
          //<![CDATA[
          $(document).ready(function()
          {
            '.$target.'$.message("'.$GLOBALS['cfg']['droot'].'error.php", "&msg='.$msg.'");
          });
          //]]>
        </script>
        </head>
        </html>');
        exit(0);
    }

// 메세지 없이 페이지 이동
    function movePage($scr){
        echo('<script type="text/javascript">');
        if(preg_match('/location/',$scr) || preg_match('/\$\./',$scr) || preg_match('/\$\(/',$scr) || preg_match('/window/',$scr))
        {
            echo(urldecode($scr)).PHP_EOL;
        }
        else
        {
            echo('document.location.replace("'.urldecode($scr).'");').PHP_EOL;
        }
        echo('</script>');
    }


// 확인 메세지 출력후 페이지 이동
    function confirmMove($msg, $scr){
        echo('<script type="text/javascript">');
        echo('if ( confirm("'.$msg.'")) {');
        echo('document.location.replace("'.urldecode($scr).'");').PHP_EOL;
        echo('}else{');
        echo('history.back();').PHP_EOL;
        echo('}');
        echo('</script>');
    }


//    경고 페이지
    function err($msg, $scr=null)
    {
        echo('<!DOCTYPE html>
        <html>
        <head>
            <title>알립니다!</title>
            <meta http-equiv="content-type" content="application/xhtml+xml; charset='.$GLOBALS['cfg']['charset'].'" />
            <meta http-equiv="content-style-type" content="text/css" />
            <meta http-equiv="pragma" content="no-cache" />
            <link rel="stylesheet" href="'.$GLOBALS['cfg']['droot'].'common/css/default.css" type="text/css" media="all" />
            <script type="text/javascript" src="'.$GLOBALS['cfg']['droot'].'common/js/jquery.js"></script>
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function()
            {
                window.alert("'.$msg.'");
            ');
            if($scr && $scr != "back")
            {
                if(preg_match('/document/',$scr) || preg_match('/location/',$scr) || preg_match('/\$\./',$scr) || preg_match('/\$\(/',$scr) || preg_match('/window/',$scr))
                {
                    echo(urldecode($scr)).PHP_EOL;
                }
                else
                {
                    echo('document.location.replace("'.urldecode($scr).'");').PHP_EOL;
                }
            }
            else if($scr == "back")
            {
                echo('history.back();').PHP_EOL;
            }
            else if($scr == "close")
            {
                echo('self.close();').PHP_EOL;
            }
            else {
                echo null;
            }
            echo('});
            //]]>
            </script>
        </head>
        <body><noscript><div class="msgBox"><p>'.$msg.'</p><div class="msgBtn"><br />');
        if($scr && !preg_match('/location/',$scr) && !preg_match('/\$\./',$scr) && !preg_match('/\$\(/',$scr) && !preg_match('/window/',$scr))
        {
            echo('<a href="'.$scr.'" class="btn"><span>확인</span></a>');
        }
        else
        {
            echo('<span>[브라우저의 "뒤로가기"를 누르시거나 "Back Space키"를 누르세요]</span>');
        }
        echo('</div></div></noscript></body></html>');
        exit(0);
    }

//    경고 페이지
    function errCfm($msg, $scr="top")
    {
        if(preg_match('/parent/', $scr))
        {
            echo('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
            <head>
                <title>알립니다!</title>
                <meta http-equiv="content-type" content="application/xhtml+xml; charset='.$GLOBALS['cfg']['charset'].'" />
                <meta http-equiv="content-style-type" content="text/css" />
                <meta http-equiv="pragma" content="no-cache" />
                <link rel="stylesheet" href="'.$GLOBALS['cfg']['droot'].'common/css/default.css" type="text/css" media="all" />
                <script type="text/javascript" src="'.$GLOBALS['cfg']['droot'].'common/js/jquery.js"></script>
                <script type="text/javascript" src="'.$GLOBALS['cfg']['droot'].'common/js/ajax.js"></script>
                <script type="text/javascript">
                //<![CDATA[
                $(document).ready(function()
                {
                    if(confirm("'.$msg.'\n-------------------------------------------------\n페이지를 [새로고침] 하시겠습니까?") == true)
                    {
                        parent.location.reload();
                    }
                    else
                    {
                        '.$scr.'
                    }
                });
                //]]>
                </script>
            </head>
            <body class="back_gray"><noscript></noscript></body></html>');
        }
        else
        {
            echo('<script type="text/javascript">
            //<![CDATA[
            $(document).ready(function()
            {
                if(confirm("'.$msg.'\n-------------------------------------------------\n페이지를 [새로고침] 하시겠습니까?") == true)
                {
                    location.reload();
                }
                else
                {
                    '.$scr.'
                }
            });
            //]]>
            </script>');
        }
        exit(0);
    }

    function err404()
    {
        header("HTTP/1.0 404 Not found");
        die();
    }

    //양력<->음력 데이터
    function sunlunar_data() { 
        return 
        "1212122322121-1212121221220-1121121222120-2112132122122-2112112121220-2121211212120-2212321121212-2122121121210-2122121212120-1232122121212-1212121221220-1121123221222-1121121212220-1212112121220-2121231212121-2221211212120-1221212121210-2123221212121-2121212212120-1211212232212-1211212122210-2121121212220-1212132112212-2212112112210-2212211212120-1221412121212-1212122121210-2112212122120-1231212122212-1211212122210-2121123122122-2121121122120-2212112112120-2212231212112-2122121212120-1212122121210-2132122122121-2112121222120-1211212322122-1211211221220-2121121121220-2122132112122-1221212121120-2121221212110-2122321221212-1121212212210-2112121221220-1231211221222-1211211212220-1221123121221-2221121121210-2221212112120-1221241212112-1212212212120-1121212212210-2114121212221-2112112122210-2211211412212-2211211212120-2212121121210-2212214112121-2122122121120-1212122122120-1121412122122-1121121222120-2112112122120-2231211212122-2121211212120-2212121321212-2122121121210-2122121212120-1212142121212-1211221221220-1121121221220-2114112121222-1212112121220-2121211232122-1221211212120-1221212121210-2121223212121-2121212212120-1211212212210-2121321212221-2121121212220-1212112112210-2223211211221-2212211212120-1221212321212-1212122121210-2112212122120-1211232122212-1211212122210-2121121122210-2212312112212-2212112112120-2212121232112-2122121212110-2212122121210-2112124122121-2112121221220-1211211221220-2121321122122-2121121121220-2122112112322-1221212112120-1221221212110-2122123221212-1121212212210-2112121221220-1211231212222-1211211212220-1221121121220-1223212112121-2221212112120-1221221232112-1212212122120-1121212212210-2112132212221-2112112122210-2211211212210-2221321121212-2212121121210-2212212112120-1232212122112-1212122122120-1121212322122-1121121222120-2112112122120-2211231212122-2121211212120-2122121121210-2124212112121-2122121212120-1212121223212-1211212221220-1121121221220-2112132121222-1212112121220-2121211212120-2122321121212-1221212121210-2121221212120-1232121221212-1211212212210-2121123212221-2121121212220-1212112112220-1221231211221-2212211211220-1212212121210-2123212212121-2112122122120-1211212322212-1211212122210-2121121122120-2212114112122-2212112112120-2212121211210-2212232121211-2122122121210-2112122122120-1231212122212-1211211221220-2121121321222-2121121121220-2122112112120-2122141211212-1221221212110-2121221221210-2114121221221"; 
    } 

    //음력->양력 변환
    function getLunarToSola($yyyymmdd)
    {
        $getYEAR = substr($yyyymmdd,0,4); 
        $getMONTH = substr($yyyymmdd,4,2); 
        $getDAY = substr($yyyymmdd,6,2); 

        $arrayDATASTR = $this->sunlunar_data(); 
        $arrayDATA = explode("-",$arrayDATASTR); 

        $arrayLDAYSTR="31-0-31-30-31-30-31-31-30-31-30-31"; 
        $arrayLDAY = explode("-",$arrayLDAYSTR); 

        $arrayYUKSTR="갑-을-병-정-무-기-경-신-임-계"; 
        $arrayYUK = explode("-",$arrayYUKSTR); 

        $arrayGAPSTR="자-축-인-묘-진-사-오-미-신-유-술-해"; 
        $arrayGAP = explode("-",$arrayGAPSTR); 

        $arrayDDISTR="쥐-소-호랑이-토끼-용-뱀-말-양-원숭이-닭-개-돼지"; 
        $arrayDDI = explode("-",$arrayDDISTR); 

        $arrayWEEKSTR="일-월-화-수-목-금-토";
        $arrayWEEK = explode("-",$arrayWEEKSTR); 

        if ($getYEAR <= 1881 || $getYEAR >= 2050) { //년수가 해당일자를 넘는 경우 
            $YunMonthFlag = 0; 
            return false; //년도 범위가 벗어남.. 
        } 
        if ($getMONTH > 12) { // 달수가 13이 넘는 경우 
            $YunMonthFlag = 0; 
            return false; //달수 범위가 벗어남.. 
        } 

        $m1 = $getYEAR - 1881; 

        if (substr($arrayDATA[$m1],12,1) == 0) { // 윤달이 없는 해임 
        $YunMonthFlag = 0; 
        } else { 
            if (substr($arrayDATA[$m1],$getMONTH, 1) > 2) { 
                $YunMonthFlag = 1; 
            } else { 
                $YunMonthFlag = 0; 
            } 
        } 
        
        $m1 = -1; 
        $td = 0; 

        if ($getYEAR > 1881 && $getYEAR < 2050) { 
            $m1 = $getYEAR - 1882; 
            for ($i=0;$i<=$m1;$i++) { 
                for ($j=0;$j<=12;$j++) { 
                    $td = $td + (substr($arrayDATA[$i],$j,1)); 
                } 

                if (substr($arrayDATA[$i],12,1) == 0) { 
                    $td = $td + 336; 
                } else { 
                    $td = $td + 362; 
                } 
            } 
        } else { 
            $gf_lun2sol = 0; 
        } 

        $m1++; 
        $n2 = $getMONTH - 1; 
        $m2 = -1; 

        while(1) { 
            $m2++; 
            if (substr($arrayDATA[$m1], $m2, 1) > 2) { 
                $td = $td + 26 + (substr($arrayDATA[$m1], $m2, 1)); 
                $n2++; 
            } else { 
                if ($m2 == $n2) { 
                    if ($gf_yun) { 
                        $td = $td + 28 + (substr($arrayDATA[$m1], $m2, 1)); 
                    }
                break; 

                } else { 
                    $td = $td + 28 + (substr($arrayDATA[$m1], $m2, 1)); 
                } 
            } 
        } 

        $td = $td + $getDAY + 29; 
        $m1 = 1880; 

        while(1) { 
            $m1++; 
            if ($m1 % 400 == 0 || $m1 % 100 != 0 && $m1 % 4 == 0) { 
                $leap = 1; 
            } else { 
                $leap = 0; 
            } 

            if ($leap == 1) { 
                $m2 = 366; 
            } else { 
                $m2 = 365; 
            } 

            if ($td < $m2) break; 

            $td = $td - $m2; 
        } 

        $syear = $m1; 
        $arrayLDAY[1] = $m2 - 337; 

        $m1 = 0; 

        while(1) { 
            $m1++; 
            if ($td <= $arrayLDAY[$m1-1]) { 
                break; 
            } 
            $td = $td - $arrayLDAY[$m1-1]; 
        } 
        $smonth = $m1; 
        $sday = $td; 
        $y = $syear - 1; 
        $td = intval($y*365) + intval($y/4) - intval($y/100) + intval($y/400); 

        if ($syear % 400 == 0 || $syear % 100 != 0 && $syear % 4 == 0) { 
            $leap = 1; 
        } else { 
            $leap = 0; 
        } 

        if ($leap == 1) { 
            $arrayLDAY[1] = 29; 
        } else { 
            $arrayLDAY[1] = 28; 
        } 

        for ($i=0;$i<=$smonth-2;$i++) { 
            $td = $td + $arrayLDAY[$i]; 
        } 

        $td = $td + $sday; 
        $w = $td % 7; 

        $sweek = $arrayWEEK[$w]; 
        $gf_lun2sol = 1; 

        if($smonth<10) $smonth="0".$smonth; 
        if($sday<10) $sday="0".$sday; 

        return $syear.$smonth.$sday;
    }

    //양->음 변환
    function getSolaToLunar($yyyymmdd) {
        $getYEAR = substr($yyyymmdd,0,4); 
        $getMONTH = substr($yyyymmdd,4,2); 
        $getDAY = substr($yyyymmdd,6,2); 

        $arrayDATASTR = $this->sunlunar_data();
        $arrayDATA = explode("-",$arrayDATASTR); 

        $arrayLDAYSTR="31-0-31-30-31-30-31-31-30-31-30-31"; 
        $arrayLDAY = explode("-",$arrayLDAYSTR); 

        $dt = $arrayDATA; 

        for ($i=0;$i<=168;$i++) { 
            $dt[$i] = 0; 
            for ($j=0;$j<12;$j++) { 
                switch (substr($arrayDATA[$i],$j,1)) { 
                    case 1: 
                    $dt[$i] += 29; 
                    break; 
                    case 3: 
                    $dt[$i] += 29; 
                    break; 
                    case 2: 
                    $dt[$i] += 30; 
                    break; 
                    case 4: 
                    $dt[$i] += 30; 
                    break; 
                } 
            } 

            switch (substr($arrayDATA[$i],12,1)) { 
                case 0: 
                break; 
                case 1: 
                $dt[$i] += 29; 
                break; 
                case 3: 
                $dt[$i] += 29; 
                break; 
                case 2: 
                $dt[$i] += 30; 
                break; 
                case 4: 
                $dt[$i] += 30; 
                break; 
            } 
        } 

        $td1 = 1880 * 365 + (int)(1880/4) - (int)(1880/100) + (int)(1880/400) + 30; 
        $k11 = $getYEAR - 1; 

        $td2 = $k11 * 365 + (int)($k11/4) - (int)($k11/100) + (int)($k11/400); 

        if ($getYEAR % 400 == 0 || $getYEAR % 100 != 0 && $getYEAR % 4 == 0) { 
            $arrayLDAY[1] = 29; 
        } else { 
            $arrayLDAY[1] = 28; 
        } 

        if ($getMONTH > 13) { 
            $gf_sol2lun = 0; 
        } 

        if ($getDAY > $arrayLDAY[$getMONTH-1]) { 
            $gf_sol2lun = 0; 
        } 

        for ($i=0;$i<=$getMONTH-2;$i++) { 
            $td2 += $arrayLDAY[$i]; 
        } 

        $td2 += $getDAY; 
        $td = $td2 - $td1 + 1; 
        $td0 = $dt[0]; 

        for ($i=0;$i<=168;$i++) { 
            if ($td <= $td0) { 
                break; 
            } 
            $td0 += $dt[$i+1]; 
        } 

        $ryear = $i + 1881; 
        $td0 -= $dt[$i]; 
        $td -= $td0; 

        if (substr($arrayDATA[$i], 12, 1) == 0) { 
            $jcount = 11; 
        } else { 
            $jcount = 12; 
        } 
        $m2 = 0; 

        for ($j=0;$j<=$jcount;$j++) { // 달수 check, 윤달 > 2
            if (substr($arrayDATA[$i],$j,1) <= 2) { 
                $m2++; 
                $m1 = substr($arrayDATA[$i],$j,1) + 28; 
                $gf_yun = 0; 
            } else { 
                $m1 = substr($arrayDATA[$i],$j,1) + 26; 
                $gf_yun = 1; 
            } 
            if ($td <= $m1) { 
                break; 
            } 
            $td = $td - $m1; 
        } 

        $k1=($ryear+6) % 10; 
        $syuk = $arrayYUK[$k1]; 
        $k2=($ryear+8) % 12; 
        $sgap = $arrayGAP[$k2]; 
        $sddi = $arrayDDI[$k2]; 

        $gf_sol2lun = 1; 

        if($m2<10) $m2="0".$m2; 
        if($td<10) $td="0".$td; 

        $Ary[year]=$ryear;
        $Ary[month]=$m2;
        $Ary[day]=$td;
        $Ary[time]=mktime(0,0,0,$Ary[month],$Ary[day],$Ary[year]);

        return $ryear.$m2.$td;
    }

//암호화 함수
    function getEncrypt($sStr) {
        //암호화 변수 초기화
        $nlvSize = @mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        //암호화
        $sCipher = @mcrypt_encrypt(MCRYPT_RIJNDAEL_128, CIPHER_KEY, $sStr, MCRYPT_MODE_CBC, IV);
        return bin2hex($sCipher); //문자열 형태로 저장하기 위해 16진수 형태로 변환, 이 값을 쿠키에 저장합니다.
    }

}
?>
