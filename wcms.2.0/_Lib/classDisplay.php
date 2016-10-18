<?php
class Display
{
    var $mode = 'normal';
    var $n;
    var $squery;
    var $rst;
    var $result;
    var $displayPos;
    var $lang;
    var $dpCount;
    var $tabCount;
    var $cate;
    var $cateName;
    var $menuCate;
    var $sort;
    var $padTop;
    var $padRight;
    var $padBottom;
    var $padLeft;
    var $titleView;
    var $width;
    var $height;
    var $funcule;
    var $listing;
    var $setup;
    var $file;
    var $prefix;
    var $droot;

    function __construct()
    {
        $this->droot            = str_replace("mobile/", null, $GLOBALS['cfg']['droot']);
        $this->cateParent       = (defined('__PARENT__')) ? __PARENT__ : 0;
        $this->title            = $GLOBALS['cfg']['site']['siteTitle'];
        $this->description      = $GLOBALS['cfg']['site']['description'];
        $this->keyword          = $GLOBALS['cfg']['site']['keywords'];
        $this->dpCount          = 0;
        $this->caching          = false;
        $this->prototype        = array();
    }

    function __destruct()
    {
        unset($this->skin, $this->result);
    }

    /**
     * 페이지 로드 : 헤더
     */
    function loadHeader()
    {
        echo('<!DOCTYPE html>').PHP_EOL;
        echo('<html lang="ko">').PHP_EOL;
        echo('<head>').PHP_EOL;
        echo('<title>'.$this->title.'</title>').PHP_EOL;
        echo('<meta http-equiv="content-type" content="text/html; charset='.$GLOBALS['cfg']['charset'].'" />').PHP_EOL;
        echo('<meta http-equiv="content-style-type" content="text/css" />').PHP_EOL;
        echo('<meta http-equiv="imagetoolbar" content="no" />').PHP_EOL;
        echo('<meta name="description" content="'.$this->description.'" />').PHP_EOL;
        echo('<meta name="keywords" content="'.$this->keyword.'" />').PHP_EOL;
        echo('<meta name="robots" content="ALL" />').PHP_EOL;
        echo('<meta name="Generator" content="wcms'.$GLOBALS['cfg']['version'].'" />').PHP_EOL;
        echo('<meta name="publisher" content="(주)10억홈피" />').PHP_EOL;
        if($GLOBALS['cfg']['skin'] == 'mobile' || preg_match('/m\./i', $_SERVER['HTTP_HOST']))
        {
            echo('<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" />').PHP_EOL;
            echo('<meta name="format-detection" content="telephone=no" />').PHP_EOL;
        }
        if(is_file(__HOME__.'/image/icon/favicon.ico'))  //파비콘 파일이 있을때
        {
            echo('<link rel="shortcut icon" href="'.$this->droot.'user/'.$GLOBALS['cfg']['skin'].'/image/icon/favicon.ico" type="image/x-ico" />').PHP_EOL;
        }
        echo('<link rel="stylesheet" href="'.$this->droot.'common/css/default.css" type="text/css" charset="'.$GLOBALS['cfg']['charset'].'" media="all" />').PHP_EOL;
        if($this->mode == 'setup')
        {
            echo('<link rel="stylesheet" href="'.$this->droot.'common/css/style.css" type="text/css" charset="'.$GLOBALS['cfg']['charset'].'" media="all" />').PHP_EOL;
        } else {
            echo('<link rel="stylesheet" href="'.$this->droot.'user/'.$GLOBALS['cfg']['skin'].'/cache/stylesheet.css" type="text/css" charset="'.$GLOBALS['cfg']['charset'].'" media="all" />').PHP_EOL;
        }
        echo('<link rel="stylesheet" type="text/css" href="'.$this->droot.'common/css/print.css" media="print" />');
        //추가 CSS 출력
        echo('<style type="text/css">').PHP_EOL;
        echo('#wrap {width:'.$GLOBALS['cfg']['site']['size']);
        echo ($GLOBALS['cfg']['site']['size'] > 100) ? "px" : "%";
        echo('; margin:');
        if($GLOBALS['cfg']['site']['align'] == 'center') { echo('auto'); } else { echo('0'); }
        echo(';}').PHP_EOL;
        echo('#siteMap {width:'.intval($GLOBALS['cfg']['site']['size']-4).'px; margin:');
        if($GLOBALS['cfg']['site']['align'] == 'center') { echo('auto'); } else { echo('0'); }
        echo(';}').PHP_EOL;

        //다국어 지원
        if($GLOBALS['cfg']['site']['navUnb'] == 'Y') { echo('#header .gnb .direct { padding-right:65px; }'); }
        echo('</style>').PHP_EOL;
        echo('<script type="text/javascript" src="'.$this->droot.'common/js/common.js"></script>').PHP_EOL;
        echo('<script type="text/javascript" src="'.$this->droot.'common/js/jquery.js"></script>').PHP_EOL;
        echo('<script type="text/javascript" src="'.$this->droot.'common/js/jquery.ui.js"></script>').PHP_EOL;
        echo('<script type="text/javascript" src="'.$this->droot.'common/js/jquery.easing.1.3.js"></script>').PHP_EOL;
        echo('<script type="text/javascript" src="'.$this->droot.'common/js/jquery.validate.js"></script>').PHP_EOL;
        echo('<script type="text/javascript" src="'.$this->droot.'common/js/jquery.facebox.js"></script>').PHP_EOL;
        echo('<script type="text/javascript" src="'.$this->droot.'common/js/jquery.scrollList.js"></script>').PHP_EOL;
        //echo('<script type="text/javascript" src="'.$this->droot.'common/js/jquery.masonry.js"></script>').PHP_EOL;
        //echo('<script type="text/javascript" src="'..'common/js/jquery.translate.js"></script>').PHP_EOL;
        echo('<script type="text/javascript" src="'.$this->droot.'common/js/ajax.js"></script>').PHP_EOL;
        if($GLOBALS['cfg']['site']['ssl']) { echo('<script type="text/javascript" src="'.$this->droot.'common/js/comodo.js"></script>').PHP_EOL; }
        //마우스 오른쪽 버튼 차단시(2012-11-27)
        if($GLOBALS['cfg']['site']['mouseDrag'] == '1' && $_SESSION['ulevel'] != '1') { echo('<script type="text/javascript" src="'.$this->droot.'common/js/mouseDrag.js"></script>').PHP_EOL; }
        //추가 Header 출력
        if($this->mode == 'normal') { echo($this->addHeader()).PHP_EOL; }
        echo('</head>').PHP_EOL;
    }

    /**
     * 페이지 로드 : 바디
     */
    function loadBody()
    {
        switch($this->mode)
        {
            case "notice" :
                echo('<body class="back_gray">').PHP_EOL;
            break;
            case "content" :
                echo('<body style="background:url();">').PHP_EOL;
            break;
            case "print" :
                echo('<body style="background:url();">').PHP_EOL;
            break;
            default :
                echo('<body>').PHP_EOL;
            break;
        }
        echo('<h1>'.$GLOBALS['cfg']['site']['siteName'].' - '.$this->title.'</h1>').PHP_EOL;
        echo('<div id="messageBox"><img src="'.$GLOBALS['cfg']['droot'].'common/image/icon/delete.gif" /></div>').PHP_EOL;
        //스킨 셀렉터
        if($_SESSION['ulevel'] && $_SESSION['ulevel'] <= $GLOBALS['cfg']['operator'] && $this->mode == 'normal') { echo ($this->skinSelector()).PHP_EOL; }
    }

    /**
     * 페이지 로드 : 풋
     */
    function loadFooter()
    {
        if($this->mode != "recent" && $this->mode != "intro")
        {
            //페이스북 API 사용
            if($GLOBALS['cfg']['site']['facebook'])
            {
                echo('<div id="fb-root"></div><script>(function(d, s, id) {    var js, fjs = d.getElementsByTagName(s)[0];    if (d.getElementById(id)) { return; }    js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/ko_KR/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs);    }(document, "script", "facebook-jssdk"));</script>').PHP_EOL;
            }
            //트위터 API 사용
            if($GLOBALS['cfg']['site']['twitter'])
            {
                echo('<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>').PHP_EOL;
            }
            //기본 스크립트
            echo('<script type="text/javascript">').PHP_EOL;
            echo('//<![CDATA[').PHP_EOL;
            echo('$(document).ready(function(){').PHP_EOL;
                //구글자동변역
                //echo('$("#content").translate("ja");').PHP_EOL;
                echo('$.changeLang();').PHP_EOL;
                echo('$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");$(this).select();}).blur(function(){$(this).toggleClass("input_active"); });').PHP_EOL;
                //이미지 Viewer
                echo('$("a[rel*=facebox]").facebox();').PHP_EOL;
                //컨텍스트메뉴 차단 스크립트
                if($GLOBALS['cfg']['site']['contextmenu'] == '1' && $_SESSION['ulevel'] != '1') { 
                    echo('$(function() {
                    $(this).bind("contextmenu",function(e) {
                     e.preventDefault();
                    });
                 });').PHP_EOL; 
                }
            echo('});').PHP_EOL;
            //스킨 셀렉터 스크립트
            if($_SESSION['ulevel'] && $_SESSION['ulevel'] <= $GLOBALS['cfg']['operator'] && $this->mode == 'normal')
            {
                echo('$("#setLayout").click(function(){
                $("#skinSelector").slideDown("fast", function(){$.insert(this, "'.$this->droot.'addon/system/manage/layout.php", "&skin='.$GLOBALS['cfg']['skin'].'&uri='.urlencode($_SERVER['REQUEST_URI']).'", 30);});});').PHP_EOL;
            }
            echo('$(document).keypress(function(e){if(e.which == 27) $.dialogRemove();});').PHP_EOL;
            echo('$("body").css("zoom", "100%");').PHP_EOL;
            echo('addFavor = function(){window.external.AddFavorite("http://'.$GLOBALS['cfg']['site']['domain'].'","'.$GLOBALS['cfg']['site']['siteName'].'-'.$GLOBALS['cfg']['site']['siteTitle'].'");}').PHP_EOL;
            echo('//]]>').PHP_EOL;
            echo('</script>').PHP_EOL;
            //추가 Footer 출력
            if($this->mode == 'normal')
            {
                echo($this->addFooter()).PHP_EOL;
            }
            echo('</body>').PHP_EOL;
        }
        echo('</html>').PHP_EOL;
    }

    /**
     * 프로토타입 목록 로드
     */
    function loadPrototype()
    {
        echo('<p style="page-break-after: always;"></p>
        <hr class="show" /><br /><h1 class="show">구성 요소별 사양 및 요구사항</h1>
        <br />
        <table class="table_basic" summary="프로토타입 구성 요소별 상세정보" style="width:100%;margin-bottom:30px">
        <caption>구성요소별 상세정보</caption>
        <colgroup>
            <col width="50">
            <col width="80">
            <col width="80">
            <col width="80">
            <col width="200">
            <col>
        </colgroup>
        <thead>
            <tr>
                <th class="first"><p class="pd5 center">위치</p></th>
                <th><p class="pd5 center">요소</p></th>
                <th><p class="pd5 center">크기</p></th>
                <th><p class="pd5 center">위젯타입</p></th>
                <th><p class="pd5 center">ALT or FileName</p></th>
                <th><p class="pd5 center">요구사항</p></th>
            </tr>
        </thead>
        <tbody>');
        foreach($this->prototype AS $key=>$val)
        {
            $seq = explode("|", $val);
            $rst = @mysql_query(" SELECT * FROM `display__".$GLOBALS['cfg']['skin']."` WHERE sort='".$seq['1']."' AND position='".$seq['0']."' ORDER BY sort ASC ");
            while($Rows = @mysql_fetch_assoc($rst))
            {
                $attName = ($Rows['form']=='skin') ? $Rows['form'].'_'.strtolower($Rows['position']).$Rows['sort'] : null;
                $config  = unserialize($Rows['config']);
                echo('<tr>
                    <th><p class="pd1 center strong">'.$Rows['position'].'</p></th>
                    <td><p class="pd1 center">'.$Rows['form'].'<br />'.$attName.'</p></td>
                    <td><p class="pd1">W:'.$config['width'].'<br />H:'.$config['height'].'</p></td>
                    <td><p class="pd1">'.$Rows['listing'].'</p></td>
                    <td><p class="pd1">'.$Rows['name'].'</p></td>
                    <td><p class="pd1">'.$Rows['name'].'</p></td>
                </tr>');
            }
        }
        echo('</tbody>
        <tbody>
            <tr><th colspan="7"><p class="pd3 right small_gray">위 정보는 확정된 내용이 아니며 수시로 변경될 수 있습니다.</p></th></tr>
        </tbody>
        </table>');
        unset($Rows, $sq, $rt, $attName, $config);
    }

    /**
     * 추가 헤더
     */
    function addHeader()
    {
        //$result = @mysql_fetch_array(@mysql_query("SELECT content_kr FROM `site__` WHERE cate='000997' AND hidden='Y'"));
        //return stripslashes($result['0']);
        return stripslashes(Functions::matchPHP(@file_get_contents(__HOME__."cache/document/000997.html")));
    }

    /**
     * 추가 풋
     */
    function addFooter()
    {
        //$result = @mysql_fetch_array(@mysql_query("SELECT content_kr FROM `site__` WHERE cate='000998' AND hidden='Y'"));
        //return stripslashes($result['0']);
        return stripslashes(@file_get_contents(__HOME__."cache/document/000998.html"));
    }

    /**
     * 스킨 셀렉터
     */
    function skinSelector()
    {
        return '<div id="skin">
        <div class="btn"><a href="javascript:;" onclick="new_window(\''.$this->droot.'_Admin/modules/editorFile.php?dir='.$GLOBALS['cfg']['skin'].'/cache&cached=stylesheet.css\',\'config\',1024,600,\'no\',\'yes\');" class="actaqua"><img src="'.$this->droot.'common/image/button/btn_selector_style.gif" /></a></div>
        <div class="btn"><a href="javascript:;" onclick="new_window(\''.$this->droot.'_Admin/wftp/ftp.php?dir='.$GLOBALS['cfg']['skin'].'/image\',\'wftp\',800,600,\'no\',\'yes\');" class="actaqua"><img src="'.$this->droot.'common/image/button/btn_selector_ftp.gif" /></a></div>
        <div id="setLayout" class="btn"><img src="'.$this->droot.'common/image/button/btn_selector_layout.gif" /></div>
        <div class="selector" id="skinSelector"></div>
        </div>';
    }

    /**
     * Display 출력
     */
    function setPrint($displayPos)
    {
        //화면에서의 디스플레이 설정 버튼 노출여부
        if($_SESSION['ulevel'] && $_SESSION['ulevel'] <= $GLOBALS['cfg']['operator'] && !$this->caching)
        {
            echo('<div class="displaySet" style="min-height:18px;"><div class="displaySetButton"><div onclick="return $.setDisplay(\''.$GLOBALS['cfg']['skin'].'\',\''.$displayPos.'\',\''.$this->cateParent.'\');"><strong>{{'.$displayPos.'}}</strong></div></div>');
        }
        //디스플레이 캐시파일명 설정
        $this->cacheFile  = __PATH__."_Site/".__DB__."/".$GLOBALS['cfg']['skin']."/cache/display/";
        $this->cacheFile .= ($this->cateParent) ? (!$this->caching) ? $displayPos.".".__CATE__.".html" : $displayPos.".".$this->cacheCate.".html" : $displayPos.".html";
        //캐시가 존재할 경우 캐시파일 INCLUDE
        if(is_file($this->cacheFile) && !$this->caching)
        {
            include $this->cacheFile;
        }
        else
        {
            $this->tabCount        = 1;
            $this->displayPos     = $displayPos;
            $this->squery        = ($_SESSION['ulevel'] && $_SESSION['ulevel'] < $GLOBALS['cfg']['operator']) ? "AND useHidden<>'Y'" : "AND useHidden in ('N','H')";

            $rst = @mysql_query(" SELECT * FROM `display__".$GLOBALS['cfg']['skin']."` WHERE position='".$this->displayPos."' ".$this->squery." ORDER BY sort ASC ");
            while($Rows = @mysql_fetch_assoc($rst))
            {
                $this->sort            = $Rows['sort'];
                $this->cate         = $Rows['cate'];
                $this->menuCate    = $Rows['cate'];    //2013-07-04 추가
                $this->cateName    = htmlspecialchars($Rows['name']);
                $this->config        = unserialize($Rows['config']);
                $this->share         = ($this->config['share']) ? $this->config['share'] : $this->cate;
                $this->form            = $Rows['form'];
                $this->listing    = $Rows['listing'];
                $this->useHidden= $Rows['useHidden'];
                $this->target     = ($this->form == 'frame') ? "_parent" : $this->config['target'];
                $this->prefix        = $this->displayPos.$this->sort;
                switch($this->form)
                {
                    case 'skin' :
                        echo($this->setSkin());
                        break;
                    case 'box' :
                        echo($this->setModuleBox());
                        break;
                    case 'tab1' : case 'tab2' : case 'tab3' : case 'tab4' : case 'tab5' :
                        echo($this->setModuleTab());
                        break;
                    case 'frame' :
                        echo($this->setModuleFrame());
                        break;
                    case 'menu' :
                        echo($this->setNavigation());
                        break;
                    case 'html' :
                        echo($this->setHtml());
                        break;
                    case 'inc' :
                        echo($this->setInclude());
                        break;
                    case 'sns' :
                        echo($this->setSns());
                        break;
                    case 'clear' :
                        echo('<!-- display(clear) start --><div class="clear"></div><!-- display(clear) end -->').PHP_EOL;
                        break;
                }
                $this->dpCount++;
                array_push($this->prototype, $Rows['position']."|".$Rows['sort']);
            }
        }
        if($_SESSION['ulevel'] && $_SESSION['ulevel'] <= $GLOBALS['cfg']['operator'] && !$this->caching)
        {
            echo('</div>').PHP_EOL;
        }
        if(substr($this->displayPos,1,1) != 'Q') { echo('<div class="clear"></div>').PHP_EOL; }
        unset($this->dpCount,$this->tabCount,$this->config,$this->skin,$this->result,$this->cate,$this->squery);
    }

    /**
     * SKIN 타입 설정
     */
    function setSkin()
    {
        if(!preg_match('/,'.__PARENT__.',/', $this->config['commonExcept']))
        {
            if($this->config['common'] == 'N')
            {
                $this->skinName = "skin_".strtolower($this->prefix).$this->cateParent;
            }
            else if($this->config['common'] != 'Y' && $this->config['common'] != 'N')
            {
                $this->skinName = (!$this->caching) ? "skin_".strtolower($this->prefix).__CATE__ : strtolower($this->prefix).$this->cacheCate;
            }
            else
            {
                $this->skinName = "skin_".strtolower($this->prefix);
            }

            $this->result = PHP_EOL.'<!-- display(skin) start -->'.PHP_EOL;
            $this->result .= '<div id="'.str_replace("skin","skinWrap",$this->skinName).'" class="visual" style="float:left; width:'.$this->config['width'].'px; height:'.$this->config['height'].'px; background:'.$this->config['colorBg'].' url('.__SKIN__.'image/background/bg_skin_'.strtolower($this->prefix).'.jpg) no-repeat left top; margin:'.$this->config['mgt'].'px '.$this->config['mgr'].'px '.$this->config['mgb'].'px '.$this->config['mgl'].'px; padding:'.$this->config['pdt'].'px '.$this->config['pdr'].'px '.$this->config['pdb'].'px '.$this->config['pdl'].'px; '.(($this->useHidden == 'H')?"display:none":"display:block").'">'.PHP_EOL;
            $this->result .= ($this->useHidden == 'T') ? '<p style="position:absolute"><img src="/common/image/icon/icon_today_s.gif" /></p>' : null;
            $this->result .= $this->printSkin();
            $this->result .= '</div>'.PHP_EOL;
            $this->result .= '<!-- display(skin) end -->'.PHP_EOL;

            return (!$this->rst && $this->config['common'] != 'Y') ? null : $this->result;
        } else 
        {
            return null;
        }
    }

    /**
     * BOX 타입 설정
     */
    function setModuleFrame()
    {
        if($this->config['common'] != 'Y' && $this->config['common'] != defined('__CATE__')) { return null; }
        $this->result = PHP_EOL.'<!-- display(module) start -->'.PHP_EOL;
        $this->result .= '<div style="float:left;"><iframe title="'.$this->cateName.'" src="'.$GLOBALS['cfg']['droot'].'modules/'.$this->config['module'].'/widgets/docFrameType.php?sort='.$this->sort.'&position='.$this->displayPos.'" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="width:'.$this->config['width'].'px; height:'.$this->config['height'].'px; margin:'.$this->config['mgt'].'px '.$this->config['mgr'].'px '.$this->config['mgb'].'px '.$this->config['mgl'].'px; overflow:hidden;"></iframe></div>';
        $this->result .= '<!-- display(module) end -->'.PHP_EOL;
        return $this->result;
    }

    /**
     * BOX 타입 설정
     */
    function setModuleBox()
    {
        $display = true;

        //서브페이지일 경우
        if(__CATE__ && __CATE__ != "__CATE__") 
        {
            //노출제외 카테고리인지 확인
            if(!preg_match('/,'.__CATE__.',/', $this->config['commonExcept'])) 
            {
                if(!preg_match('/,'.__PARENT__.',/', $this->config['commonExcept'])) 
                {
                    //지역노출이 아닌 경우 기본 카테고리를 현재 카테고리로 지정
                    if($this->config['common'] == 'Y' && $this->cate == '') { $this->cate = __CATE__; }
                } 
                else {
                    $display = false;
                }
            } 
            else {
                $display = false;
            }
        }
        if($this->config['common'] == 'N' && $this->cate != __PARENT__) { return null; }
        //if($this->config['common'] != 'N' && $this->config['common'] != 'Y' && $this->config['common'] != substr(__CATE__,0,strlen($this->config['common']))) { return null; }//설정된 카테고리의 하위 포함 노출이 아닐 경우로 변경(2013-07-15 - 오혜진)
        if($this->config['common'] != 'N' && $this->config['common'] != 'Y' && $this->config['common'] != __CATE__ ) { return null; }

        if($display) 
        {
            $this->result  = PHP_EOL.'<!-- display(widget) start -->'.PHP_EOL;
            //$this->result .= '<!-- cate='.__CATE__.'-->'.PHP_EOL;
            //$this->result .= '<!-- father='.__FATHER__.'-->'.PHP_EOL;
            //$this->result .= '<!-- commonExcept='.$this->config['commonExcept'].'-->'.PHP_EOL;
            $this->result .= '<div id="recent'.$this->prefix.'" class="recent" style="width:'.$this->config['width'].'px; height:'.$this->config['height'].'px; background-color:'.$this->config['colorBg'].'; margin:'.$this->config['mgt'].'px '.$this->config['mgr'].'px '.$this->config['mgb'].'px '.$this->config['mgl'].'px; padding:'.$this->config['pdt'].'px '.$this->config['pdr'].'px '.$this->config['pdb'].'px '.$this->config['pdl'].'px; overflow:hidden;">'.PHP_EOL;

            //타이틀 설정
            $this->result .= ($this->config['useTitle'] != 'N') ? $this->printTitle("recent",$this->cateName) : null;

            //슬라이드 설정 (2012년 10월 10일 수요일 : 기존 사용된 슬라이드 코드 F,R은 미적용으로 설정 - 이성준)
            $this->config['docType'] = ($this->config['docType'] && $this->config['docType'] != 'F' && $this->config['docType'] != 'R') ? $this->config['docType'] : "false";

            if(!$this->listing) { $this->result .= '<p class="small_blue center"><strong>ERROR:</strong> 디스플레이 => 리스팅 선택</p>'; }
            $this->result .= '<!-- listing : '.$this->listing.' -->'.PHP_EOL;

            if(is_file(__PATH__."modules/".$this->config['module']."/widgets/doc".$this->listing."Type.php"))
            {
                include __PATH__."modules/".$this->config['module']."/widgets/doc".$this->listing."Type.php";
            } else
            {
                $this->result .= '형태와 리스트가 잘못 연결되었습니다';
            }
            $this->result .= '</div>'.PHP_EOL;
            $this->result .= '<!-- display(widget) end -->'.PHP_EOL;
            return $this->result;
        } else {
            return null;
        }

    }

    /**
     * TAB 타입 설정
     */
    function setModuleTab()
    {
        if($this->tabCount == substr($this->form, -1))
        {
            $this->result = '<!-- display(tab) start -->'.PHP_EOL;
            include __PATH__."addon/system/displayTab.php";
            $this->result .= '<!-- display(tab) end -->'.PHP_EOL;
            $this->tabCount++;
        }
        else
        {
            $this->result = null;
        }
        return $this->result;
    }

    /**
     * 네비게이션 설정
     */
    function setNavigation()
    {
        if(!preg_match('/,'.__PARENT__.',/', $this->config['common']))
        {
            $this->result = PHP_EOL.'<!-- display(navigation) start -->'.PHP_EOL;
            $this->result .= '<div class="visual" style="float:left; width:'.$this->config['width'].'px; height:'.$this->config['height'].'px; background-color:'.$this->config['colorBg'].'; margin:'.$this->config['mgt'].'px '.$this->config['mgr'].'px '.$this->config['mgb'].'px '.$this->config['mgl'].'px; padding:'.$this->config['pdt'].'px '.$this->config['pdr'].'px '.$this->config['pdb'].'px '.$this->config['pdl'].'px; z-index:90;">'.PHP_EOL;

            if(substr($this->displayPos, 1, 1) == 'L' || substr($this->displayPos, 1, 1) == 'R')
            {
                if($this->listing == 'Text')
                {
                    include __PATH__."addon/navigation/left".$this->listing."_".$GLOBALS['cfg']['skin'].".php";
                }
                else
                {
                    include __PATH__."addon/navigation/left".$this->listing.".php";
                }
            }
            else
            {
                include __PATH__."addon/navigation/local".$this->listing.".php";
            }

            $this->result .= '</div>'.PHP_EOL;
            $this->result .= '<!-- display(navigation) end -->'.PHP_EOL;
            return $this->result;
        } else
        {
            return null;
        }
    }

    /**
     * HTML 삽입 설정
     */
    function setHtml()
    {
        $this->result = PHP_EOL.'<!-- display(html) start -->'.PHP_EOL;
        $this->result .= '<div class="recent textContent" style="width:'.$this->config['width'].'px; height:'.$this->config['height'].'px; background-color:'.$this->config['colorBg'].'; margin:'.$this->config['mgt'].'px '.$this->config['mgr'].'px '.$this->config['mgb'].'px '.$this->config['mgl'].'px; padding:'.$this->config['pdt'].'px '.$this->config['pdr'].'px '.$this->config['pdb'].'px '.$this->config['pdl'].'px; overflow:hidden;">'.PHP_EOL;
        //$query = "SELECT content_"." FROM `site__` WHERE cate='".$this->cate."'";
        //$rst = @mysql_fetch_array(@mysql_query($query));
        $this->result .= trim(stripslashes(file_get_contents(__HOME__."inc/".$this->cateName)));
        $this->result .= '</div>'.PHP_EOL;
        $this->result .= '<!-- display(html) end -->'.PHP_EOL;
        if($this->config['common'] != 'Y' && $this->config['common'] != 'N')
        {
            return ($this->config['common'] == __CATE__) ? $this->result : null;
        }
        else
        {
            return $this->result;
        }
    }

    /**
     * INCLUDE 삽입 설정
     */
    function setInclude()
    {
        if(!preg_match('/,'.__PARENT__.',/', $this->config['commonExcept']))
        {
            $this->result = PHP_EOL.'<!-- display(inc) start -->'.PHP_EOL;
            $this->result .= '<div style="float:left; width:'.$this->config['width'].'px; height:'.$this->config['height'].'px; background-color:'.$this->config['bgColor'].'; margin:'.$this->config['mgt'].'px '.$this->config['mgr'].'px '.$this->config['mgb'].'px '.$this->config['mgl'].'px; padding:'.$this->config['pdt'].'px '.$this->config['pdr'].'px '.$this->config['pdb'].'px '.$this->config['pdl'].'px; overflow:hidden; z-index:'.$this->config['zindex'].'">'.PHP_EOL;
            if(is_file(__HOME__."inc/".$this->cateName))
            {
                include __HOME__."inc/".$this->cateName;
            }
            else
            {
                $this->result .= '('.__HOME__."inc/".$this->cateName.') 파일이 존재하지 않습니다.';
            }
            $this->result .= '</div>'.PHP_EOL;
            $this->result .= '<!-- display(inc) end -->'.PHP_EOL;
            if($this->config['common'] != 'Y' && $this->config['common'] != 'N')
            {
                return ($this->config['common'] == substr(__CATE__,0,strlen($this->config['common']))) ? $this->result : null;
            }
            else
            {
                return $this->result;
            }
        } else 
        {
            return null;
        }
    }

    /**
     * SNS 삽입 설정 : 이성준 - 2013년 7월 15일 월요일
     */
    function setSns()
    {
        if(!preg_match('/,'.__PARENT__.',/', $this->config['commonExcept']))
        {
            $this->result = PHP_EOL.'<!-- display(sns) start -->'.PHP_EOL;
            $this->result .= '<div class="recent" style="float:left; width:'.$this->config['width'].'px; height:'.$this->config['height'].'px; background-color:'.$this->config['bgColor'].'; margin:'.$this->config['mgt'].'px '.$this->config['mgr'].'px '.$this->config['mgb'].'px '.$this->config['mgl'].'px; padding:'.$this->config['pdt'].'px '.$this->config['pdr'].'px '.$this->config['pdb'].'px '.$this->config['pdl'].'px; overflow:hidden; z-index:'.$this->config['zindex'].'">'.PHP_EOL;
            switch($this->cateName)
            {
                case "facebookLikebox" : 
                    $this->result .= '<div class="fb-like-box" data-href="https://www.facebook.com/'.$this->config['facebookId'].'" data-width="'.str_replace('px',null,$this->config['width']).'" data-height="'.str_replace('px',null,$this->config['height']).'" data-show-faces="false" data-stream="true" data-show-border="'.$this->config['facebookBorder'].'" data-header="'.$this->config['facebookHeader'].'"></div>'.PHP_EOL;
                break;
                case "twitterTimeline" : 
                    $this->result .= '<a class="twitter-timeline" href="https://twitter.com/'.$this->config['twitterId'].'" width="'.str_replace('px',null,$this->config['width']).'" height="'.str_replace('px',null,$this->config['height']).'" data-widget-id="'.$this->config['twitterWidgetId'].'"></a>'.PHP_EOL;
                break;
                default : 
                    $this->result .= '<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/'.$this->config['twitterId'].'" width="'.str_replace('px',null,$this->config['width']).'" height="'.str_replace('px',null,$this->config['height']).'" data-widget-id="'.$this->config['twitterWidgetId'].'"></a>'.PHP_EOL;
                break;
            }
            $this->result .= '</div>'.PHP_EOL;
            $this->result .= '<!-- display(sns) end -->'.PHP_EOL;
            if($this->config['common'] != 'Y' && $this->config['common'] != 'N')
            {
                return ($this->config['common'] == substr(__CATE__,0,strlen($this->config['common']))) ? $this->result : null;
            }
            else
            {
                return $this->result;
            }
        } else 
        {
            return null;
        }
    }

    /**
     * 디스플레이 캐시설정
     * @param string $displayPos : 포지션코드
     */
    function setCache($displayPos)
    {
        if($this->cateParent)
        {
            $rst = @mysql_query(" SELECT cate FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' GROUP BY cate ASC ");
            while($Rows = @mysql_fetch_assoc($rst))
            {
                $this->cacheCate = $Rows['cate'];
                $this->setCacheSave($displayPos);
            }
        }
        else
        {
            $this->setCacheSave($displayPos);
        }
        unset($this->cacheCate);
        return true;
    }

    /**
     * 캐시생성
     */
    function setCacheSave($displayPos)
    {
        $this->caching = true;
        @ob_start();
            $this->setPrint($displayPos);
            $buffer = ob_get_contents();
        @ob_end_clean();
        if(is_writable(__PATH__."_Site/".__DB__."/".$GLOBALS['cfg']['skin']."/cache/display/"))
        {
            //@file_put_contents($this->cacheFile, $buffer);
            $fp = fopen($this->cacheFile, 'w');
            fwrite($fp, $buffer);
            fclose($fp);
            return true;
        }
        else
        {
            Functions::ajaxMsg("cache 디렉토리의 쓰기 권한이 설정되지 않았습니다.","", 20);
            return false;
        }
    }

    /**------------------------------------------------------------------------------------
     * 스킨 출력하기
     *-------------------------------------------------------------------------------------
     * 지원파일 : swf, jpg ,png ,gif, text
     * $this->listing : 스킨파일 확장자
     */
    function printSkin()
    {
        $this->skinDir = __HOME__."image/";
        unset($this->skin);
        if($cate !="")
        {
            $pageNum = substr($cate,2,1);
            $subNum = substr($cate,5,1);
        }
        if(is_file($this->skinDir.$this->skinName.".".$this->listing))
        {
            $this->skinSize    = @getimagesize($this->skinDir.$this->skinName.".".$this->listing);
            switch($this->listing)
            {
                case "swf" :
                    $this->skin = '<div id="'.$this->skinName.'" class="design" style="width:'.$this->config['width'].'px; height:'.$this->config['height'].'px;"><script type="text/javascript">flashWrite("'.__SKIN__.'image/'.$this->skinName.'.swf","'.$this->config['width'].'px","'.$this->config['height'].'px","'.$this->skinName.'")</script>';
                    break;
                case "gif" : case "jpg" : case "png" :
                    $this->skin = '<div id="'.$this->skinName.'" class="design" style="width:'.$this->config['width'].'px; height:'.$this->config['height'].'px;">';
                    if($this->config['url']) { $this->skin .= '<a href="'.$this->config['url'].'" target="'.$this->target.'">'; }
                    $this->skin .= '<img src="'.__SKIN__.'image/'.$this->skinName.'.'.$this->listing.'" width="'.$this->config['width'].'px" height="'.$this->config['height'].'px" alt="'.$this->cateName.'" usemap="#'.$this->skinName.'" />';
                    if($this->config['url']) { $this->skin .= '</a>'; }
                    break;
            }
            $this->skin .= '</div>'.PHP_EOL;
            $this->rst = 1;
        }
        else
        {
            if($GLOBALS['cfg']['site']['openSkin'] == 'Y' && !$this->config['colorBg'])
            {
                $this->skin    = '<div id="'.$this->skinName.'" class="pattern" style="width:'.$this->config['width'].'px; height:'.$this->config['height'].'px;" title="'.$this->skinName.'.'.$this->listing.'">';
                $this->skin .= '<div class="patternWrap" style="width:'.$this->config['width'].'px; height:'.$this->config['height'].'px;" title="'.$this->skinName.' ('.$this->config['width'].'pxX'.$this->config['height'].'px) '.$this->cateName.'"><p class="patternInfo"><strong>'.$this->skinName.'</strong><span class="small_dgray">('.$this->config['width'].'px_'.$this->config['height'].'px)</span></p></div>';
                $this->skin .= '</div>'.PHP_EOL;
                $this->rst = 1;
            } else
            {
                $this->rst = 0;
            }
        }
        return $this->skin;
    }

    /**------------------------------------------------------------------------------------
     * 메뉴 스킨 출력하
     *-------------------------------------------------------------------------------------
     * $prefix : 해당 메뉴의 고유값
     * $cate : 카테고리 코드
     * $name : 메뉴명
     * 로드순서: swf > jpg > png >gif > tex
     */
    function printMenu($prefix, $name=null, $cate=null)
    {
        $this->skinDir    = __HOME__."/image/menu/";
        $prefix2    = ($cate) ? $cate : strtolower($this->prefix);
        $file        = (strlen($cate) == 3 && (__CATE__ == $cate || substr(__CATE__,0,3) == $cate)) ? $prefix."_over_".$prefix2 : $prefix."_".$prefix2;
        $file        = (strlen($cate) > 3 && (__CATE__ == $cate || substr(__CATE__,0,6) == $cate)) ? $prefix."_over_".$prefix2 : $file;
        $fileOver    = $prefix."_over_".$prefix2;
        $alt        = $name;

        if($this->config['useTitle'] == 'image')
        {
            if(is_file($this->skinDir.$file.".swf")) {
                $this->skinSize    = @getimagesize($this->skinDir.$file.".swf");
                $this->skin = '<script type="text/javascript">flashWrite("'.__SKIN__.'image/menu/'.$file.'.swf",'.$this->skinSize['0'].','.$this->skinSize['1'].',"'.$file.'")</script>';
            } else if(is_file($this->skinDir.$file.".jpg")) {
                $this->skinSize = @getimagesize($this->skinDir.$file.".jpg");
                $this->skin     = '<img src="'.__SKIN__.'image/menu/'.$file.'.jpg" width="'.$this->skinSize['0'].'" height="'.$this->skinSize['1'].'"';
                $this->skin .= (is_file($this->skinDir.$fileOver.".jpg")) ? ' onmouseover="this.src=\''.__SKIN__.'image/menu/'.$fileOver.'.jpg\'" onmouseout="this.src=\''.__SKIN__.'image/menu/'.$file.'.jpg\'"' : '';
                $this->skin .= ' id="'.$prefix.'_'.$prefix2.'" alt="'.$alt.'" usemap="#'.$file.'" />';
            } else if(is_file($this->skinDir.$file.".png")) {
                $this->skinSize = @getimagesize($this->skinDir.$file.".png");
                $this->skin     = '<img src="'.__SKIN__.'image/menu/'.$file.'.png" width="'.$this->skinSize['0'].'" height="'.$this->skinSize['1'].'"';
                $this->skin .= (is_file($this->skinDir.$fileOver.".png")) ? ' onmouseover="this.src=\''.__SKIN__.'image/menu/'.$fileOver.'.png\'" onmouseout="this.src=\''.__SKIN__.'image/menu/'.$file.'.png\'"' : '';
                $this->skin .= ' id="'.$prefix.'_'.$prefix2.'" alt="'.$alt.'" usemap="#'.$file.'" />';
            } else if(is_file($this->skinDir.$file.".gif")) {
                $this->skinSize = @getimagesize($this->skinDir.$file.".gif");
                $this->skin     = '<img src="'.__SKIN__.'image/menu/'.$file.'.gif" width="'.$this->skinSize['0'].'" height="'.$this->skinSize['1'].'"';
                $this->skin .= (is_file($this->skinDir.$fileOver.".gif")) ? ' onmouseover="this.src=\''.__SKIN__.'image/menu/'.$fileOver.'.gif\'" onmouseout="this.src=\''.__SKIN__.'image/menu/'.$file.'.gif\'"' : '';
                $this->skin .= ' id="'.$prefix.'_'.$prefix2.'" alt="'.$alt.'" usemap="#'.$file.'" />';
            } else {
                $this->skin = $file;
            }
        }
        else
        {
            if($prefix == "submenu") {
                $this->skin = '<div class="cube"><div class="line"><p title="'.$alt.'">'.$name.'</p></div></div>';
            } else {
                $this->skin = $name;
            }
        }
        return $this->skin;
    }

    /**------------------------------------------------------------------------------------
     * 메뉴 스킨 출력하기
     *-------------------------------------------------------------------------------------
     * $prefix : 해당 메뉴의 고유값
     * $cate : 카테고리 코드
     * $name : 메뉴명
     * 로드순서: jpg > png >gif > tex
     */
    function printTab($prefix, $name=null, $cate=null)
    {
        $this->skinDir    = __HOME__."/image/menu/";
        $prefix2    = ($cate) ? $cate : strtolower($this->prefix);
        $file        = (__CATE__ == $cate) ? $prefix."_over_".$prefix2 : $prefix."_".$prefix2;
        $fileOver    = $prefix."_over_".$prefix2;
        $alt        = $name;

        if($this->config['useTitle'] == 'image')
        {
            if(is_file($this->skinDir.$file.".jpg")) {
                $this->skinSize = @getimagesize($this->skinDir.$file.".jpg");
                $this->skin     = '<img src="'.__SKIN__.'image/menu/'.$file.'.jpg" width="'.$this->skinSize['0'].'" height="'.$this->skinSize['1'].'"';
                $this->skin .= (is_file($this->skinDir.$fileOver.".jpg")) ? ' onmouseover="this.src=\''.__SKIN__.'image/menu/'.$fileOver.'.jpg\'"' : '';
                $this->skin .= ' id="'.$prefix.'_'.$prefix2.'" alt="'.$alt.'" usemap="#'.$file.'" />';
            } else if(is_file($this->skinDir.$file.".png")) {
                $this->skinSize = @getimagesize($this->skinDir.$file.".png");
                $this->skin     = '<img src="'.__SKIN__.'image/menu/'.$file.'.png" width="'.$this->skinSize['0'].'" height="'.$this->skinSize['1'].'"';
                $this->skin .= (is_file($this->skinDir.$fileOver.".png")) ? ' onmouseover="this.src=\''.__SKIN__.'image/menu/'.$fileOver.'.png\'"' : '';
                $this->skin .= ' id="'.$prefix.'_'.$prefix2.'" alt="'.$alt.'" usemap="#'.$file.'" />';
            } else if(is_file($this->skinDir.$file.".gif")) {
                $this->skinSize = @getimagesize($this->skinDir.$file.".gif");
                $this->skin     = '<img src="'.__SKIN__.'image/menu/'.$file.'.gif" width="'.$this->skinSize['0'].'" height="'.$this->skinSize['1'].'"';
                $this->skin .= (is_file($this->skinDir.$fileOver.".gif")) ? ' onmouseover="this.src=\''.__SKIN__.'image/menu/'.$fileOver.'.gif\'"' : '';
                $this->skin .= ' id="'.$prefix.'_'.$prefix2.'" alt="'.$alt.'" usemap="#'.$file.'" />';
            } else {
                $this->skin = $file;
            }
        }
        else
        {
            $this->skin = $name;
        }
        return $this->skin;
    }

    /**------------------------------------------------------------------------------------
     * 타이틀 스킨 출력하기
     *-------------------------------------------------------------------------------------
     * $prefix : 해당 메뉴의 고유값
     * $cate : 카테고리 코드
     * $name : 타이틀명
     * 로드순서: swf > jpg > png > gif > tex
     */
    function printTitle($prefix, $name=null, $cate=null, $type=null) {
        $prefix2        = ($cate) ? $cate : strtolower($this->prefix);
        $this->skinDir    = __HOME__."/image/title/";
        $file                        = $prefix."_".$prefix2;
        $droot                    = ($GLOBALS['cfg']['skin'] != 'default') ? $GLOBALS['cfg']['droot'].$GLOBALS['cfg']['skin'].'/' : $GLOBALS['cfg']['droot'];
        if($this->config['useTitle'] == 'image')
        {
            //2012-12-13 type에 따른 이미지가 다르게 나오도록 변경(장바구니와 주문내역 이미지 구분 위해서)
            if($cate == '000002004' && $type) {
                $file = $file.'_'.$type;
            }

            if(is_file($this->skinDir.$file.".swf")) {
                $this->skinSize    = @getimagesize($this->skinDir.$file.".swf");
                $this->skin = '<h3><script type="text/javascript">flashWrite("'.__SKIN__.'image/title/'.$file.'.swf",'.$this->skinSize['0'].','.$this->skinSize['1'].',"'.$file.'")</script><noscript>'.$name.'</noscript></h3>'.PHP_EOL;
                return $this->skin;
            } else if(is_file($this->skinDir.$file.".jpg")) {
                $this->skinSize = @getimagesize($this->skinDir.$file.".jpg");
                $this->skin     = '<h3>';
                if($this->cate && $this->listing != 'C') { $this->skin    .= '<a href="'.$droot.'?cate='.$this->cate.'" target="'.$this->target.'">'; }
                $this->skin    .= '<img src="'.__SKIN__.'image/title/'.$file.'.jpg" width="'.$this->skinSize['0'].'" height="'.$this->skinSize['1'].'" alt="'.$name.'" usemap="#'.$file.'" />';
                if($this->cate && $this->listing != 'C') { $this->skin    .= '</a>'; }
                $this->skin    .= '</h3>'.PHP_EOL;
                return $this->skin;
            } else if(is_file($this->skinDir.$file.".png")) {
                $this->skinSize = @getimagesize($this->skinDir.$file.".png");
                $this->skin     = '<h3>';
                $this->skin .= '<!-- listing : '.$this->listing.' -->'.PHP_EOL;
                $this->skin .= '<!-- cate : '.$this->cate.' -->'.PHP_EOL;
                if($this->cate && $this->listing != 'C') { $this->skin    .= '<a href="'.$droot.'?cate='.$this->cate.'" target="'.$this->target.'">'; }
                $this->skin    .= '<img src="'.__SKIN__.'image/title/'.$file.'.png" width="'.$this->skinSize['0'].'" height="'.$this->skinSize['1'].'" alt="'.$name.'" />';
                if($this->cate && $this->listing != 'C') { $this->skin    .= '</a>'; }
                $this->skin    .= '</h3>'.PHP_EOL;
                return $this->skin;
            } else if(is_file($this->skinDir.$file.".gif")) {
                $this->skinSize = @getimagesize($this->skinDir.$file.".gif");
                $this->skin     = '<h3>';
                $this->skin .= '<!-- listing : '.$this->listing.' -->'.PHP_EOL;
                $this->skin .= '<!-- cate : '.$this->cate.' -->'.PHP_EOL;
                if($this->cate && $this->listing != 'C') { $this->skin    .= '<a href="'.$droot.'?cate='.$this->cate.'" target="'.$this->target.'">'; }
                $this->skin    .='<img src="'.__SKIN__.'image/title/'.$file.'.gif" width="'.$this->skinSize['0'].'" height="'.$this->skinSize['1'].'" alt="'.$name.'" usemap="#'.$file.'" />';
                if($this->cate && $this->listing != 'C') { $this->skin    .= '</a>'; }
                $this->skin    .= '</h3>'.PHP_EOL;
                return $this->skin;
            }
        }
        else
        {
            $this->skin  = '<div class="'.$prefix.'Header" title="'.$name.' ('.$file.')"><div class="'.$prefix.'HeaderBorder"><h3>'.($this->config['useCateName']=="Y" ? '<span class="point03">'.$this->title.'</span> ':'').$name.'</h3>';
            $this->skin .= ($prefix == "recent" && $this->cate && $this->config['useMore']=="Y") ? '<p title="more" class="more"><a href="'.$GLOBALS['cfg']['droot'].'?cate='.$this->cate.'" target="'.$this->target.'">more</a></p>' : '';
            $this->skin .= '</div></div>'.PHP_EOL;
            return $this->skin;
        }
    }

    /**------------------------------------------------------------------------------------
     * 스타일시트 캐시 생성
     */
    function cacheCss($skin='default')
    {
        $data    = @file_get_contents(__PATH__."_Site/".__DB__."/".$skin."/cache/stylesheet.css");
        foreach($GLOBALS['cfg']['modules'] AS $val)
        {
            if(is_file(__PATH__."modules/".$val."/cache/style.css") && !preg_match('/'.$val.'/', $data))
            {
                $data .= @file_get_contents(__PATH__."modules/".$val."/cache/style.css");
            }
        }
        $data     = str_replace("/_Site/".$GLOBALS['cfg']['default']."/".$skin."/", "/user/".$skin."/", $data);
        $data     = str_replace("url(/", "url(".$GLOBALS['cfg']['droot'], $data);
        //php5.x 이하버전
        $fp    = fopen(__PATH__."_Site/".__DB__."/".$skin."/cache/stylesheet.css", "w");
        fwrite($fp, $data);
        fclose($fp);
        //php5.x 이상버전
        //file_put_contents($dir.'cache/stylesheet.css', $data);
    }

    /**------------------------------------------------------------------------------------
     * 네비게이션 XML 캐시 생성
     */
    function cacheXml($skin='default')
    {
        $header = '<?xml version="1.0" encoding="euc-kr"?>'.PHP_EOL;
        $n        = 1;
        $droot    = ($skin != 'default') ? $GLOBALS['cfg']['droot'].$skin."/" : $GLOBALS['cfg']['droot'];

        //1차
        $rst = @mysql_query(" SELECT * FROM `site__` WHERE skin='".$skin."' AND LENGTH(cate)='3' AND SUBSTRING(cate,1,3)<>'000' AND status='normal' ORDER BY sort ");
        while($Rows = @mysql_fetch_array($rst))
        {
            $Rows['nameExtra'] = (!$Rows['nameExtra']) ? $Rows['name'] : $Rows['nameExtra'];
            $xml = explode(",", $Rows['xml']);
            if($Rows['mode'] == 'url')
            {
                $link = str_replace('default', $skin, $Rows['url']);
                $link = str_replace('mobile', $skin, $link);
                $target = $Rows['target'];
            }
            else
            {
                $link = $droot.'index.php?cate='.$Rows['cate'].'&menu='.$Rows['sort'];
                $target = $Rows['target'];
            }
            if($n == 1) { $body = '<menu homeURL="'.$droot.'index.php" siteURL="'.$droot.'?cate=000999" mailURL="'.$GLOBALS['cfg']['site']['email'].'" color="'.$xml['1'].'">'.PHP_EOL; }

            $data .= '    <주메뉴 col="100" link="'.$link.'" target="'.$target.'" kText="'.$Rows['name'].'" eText="'.$Rows['nameExtra'].'" subX="'.$xml['0'].'" color="'.$xml['1'].'">'.PHP_EOL;

            //2차
            $rst2 = @mysql_query("SELECT * FROM `site__` WHERE skin='".$skin."' AND LENGTH(cate)='6' AND SUBSTRING(cate,1,3)='".$Rows['cate']."' AND status='normal' ORDER BY sort");
            while($sRows = mysql_fetch_array($rst2))
            {
                if($sRows['mode'] == 'url')
                {
                    $link2 = $sRows['url'];
                    $target2 = $sRows['target'];
                }
                else
                {
                    $link2 = $droot.'index.php?cate='.$sRows['cate'].'&menu='.$Rows['sort'].'&sub='.$sRows['sort'];
                    $target2 = $sRows['target'];
                }
                $data .= '    <서브메뉴 link="'.$link2.'" target="'.$target2.'" text="'.$sRows['name'].'">'.PHP_EOL;

                //3차
                $rst3 = @mysql_query("SELECT * FROM `site__` WHERE skin='".$skin."' AND LENGTH(cate)='9' AND SUBSTRING(cate,1,6)='".$sRows['cate']."' AND status='normal' ORDER BY sort");
                while($ssRows = @mysql_fetch_array($rst3))
                {
                    if($ssRows['mode'] == 'url')
                    {
                        $link3 = $ssRows['url'];
                        $target3 = $ssRows['target'];
                    }
                    else
                    {
                        $link3 = $droot.'index.php?cate='.$ssRows['cate'].'&menu='.$Rows['sort'].'&sub='.$sRows['sort'];
                        $target3 = $ssRows['target'];
                    }
                    $data .= '        <소메뉴 link="'.$link3.'" target="'.$target3.'" text="'.$ssRows['name'].'"/>'.PHP_EOL;
                }
                $data .= '    </서브메뉴>'.PHP_EOL;
            }
            $data .= '    </주메뉴>'.PHP_EOL;
            $n++;
        }
        $data    .= '</menu>'.PHP_EOL;

        //php5.x 이하버전
        /*
        $fp   = fopen(__PATH__."_Site/".__DB__."/".$skin."/cache/menu.xml", 'w');
        fwrite($fp, $top);
        fclose($fp);
        */

        //php5.x 이상버전
         @file_put_contents(__PATH__."_Site/".__DB__."/".$skin."/cache/menu.xml", iconv("UTF-8", "CP949", $header.$body.$data)); //swf가 euc-kr을 인식하는 문제로 인코딩 변환)
    }
}
?>
