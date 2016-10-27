<?php
echo('<frameset rows="0,*" frameborder="no" framespacing="0" scrolling="no" noresize>');
echo('<frame name="top" src="'.str_replace("kr/","",__SKIN__).'frameTop.php" frameborder="no" />');
if(!$intro || $db->getNumRows() < 1 || __CATE__)
{
  echo('<frame name="center" src="./index.php?'.$_SERVER['QUERY_STRING'].'" frameborder="no" />');
} else
{
  echo('<frame name="center" src="./intro.php?'.$_SERVER['QUERY_STRING'].'" frameborder="no" />');
}
#--- Bottom까지 사용할경우 아래 주석해제 후 frameset의 rows="0,*,0" 으로 수정할것.
#--- echo('<frame name="bottom" src="'.str_replace("kr/","",__SKIN__).'frameBottom.php" frameborder="no" />');
echo('</frameset>');
?>
