<?php
require_once "../../_config.php";
?>
<div class="sitemap">
<div class="cell">
	<ul>
    <li><a href="<?=$cfg[droot]?>index.php" class="act">HOME</a>
    <?php
    if($func->checkModule('mdMember') && $cfg[site][lang] == 'kr')
    {
      echo '<ul><li class="depth2">·<a href="'.$cfg[droot].'index.php?cate=000002001">'.$lang[member_login].'</a></li>
      <li class="depth2">·<a href="'.$cfg[droot].'index.php?cate=000002002">'.$lang[member_regist].'</a></li>
      <li class="depth2">·<a href="'.$cfg[droot].'index.php?cate=000002003">'.$lang[member_find].'</a></li></ul>';
    }
    ?>
</div>
<?php
$db->query("SELECT cate,name_".$cfg[site][lang]." AS name,mode FROM `site__` WHERE LENGTH(cate)='3' AND cate<>'000' AND Hidden='N' ORDER BY cate ASC");
while($Rows = $db->Fetch())
{
  /* 링크 */
  $moveCate = (substr($Rows[mode],0,2) == "md" || $Rows[mode] == "") ? $Rows[cate] : $Rows[mode];
  print('<div class="cell">');
  print('<ul><li><a href="'.$cfg[droot].'index.php?cate='.$moveCate.'" class="act">'.$Rows[name].'</a>
  <ul>
  ');

    $db->Query("SELECT cate,name_".$cfg[site][lang]." AS name FROM `site__` WHERE LENGTH(cate)>='6' AND SUBSTRING(cate,1,3)='".$Rows[cate]."' AND Hidden='N'  ORDER BY cate ASC", 2);
    $n = 1;
    $total = $db->GetNumRows(2);
    while($sRows = $db->Fetch(2))
    {
      $sRows[name] = $func->cutStr($sRows[name],25,"...");
      $depth		= intval((strlen($sRows[cate])/3)-3);
      for($i=1;$i<=$depth;$i++) $blank .= '<img src="'.$cfg[droot].'image/cate/vline.gif" style="vertical-align:middle;" />';
      $moveCate = (substr($sRows[mode],0,2) == "md" || $sRows[mode] == "") ? $sRows[cate] : $sRows[mode];
      $icon			= ($total > $n) ? "file" : "filebottom";

      switch(strlen($sRows[cate])):
        case '9':
          printf('<li class="depth3"><span><img src="'.$cfg[droot].'image/cate/'.$icon.'.gif" style="vertical-align:middle;" /></span>&nbsp;<a href="'.$cfg[droot].'index.php?cate='.$moveCate.'" class="actSmallGray" title="'.$sRows[sort].'">'.$sRows[name].'</a></li>');
        break;
        case '12': case '15':
          printf('<li class="depth3">'.$blank.'<span><img src="'.$cfg[droot].'image/cate/'.$icon.'.gif" style="vertical-align:middle;" /></span>&nbsp;<a href="'.$cfg[droot].'index.php?cate='.$moveCate.'" class="actSmallGray" title="'.$sRows[sort].'">'.$sRows[name].'</a></li>');
        break;
        default:
          printf('<li class="depth2"><strong>·</strong> <a href="'.$cfg[droot].'index.php?cate='.$moveCate.'" title="'.$sRows[sort].'">'.$sRows[name].'</a></li>');
        break;
      endswitch;

    $n++;
    unset($blank);
    }

  print('</ul></li></ul></div>');
}
?>
	<div class="clear"></div>
</div>
<div class="right"><p class="closeBtn">close</p></div>
<script type="text/javascript">
$(document).ready(function(){
  $(".closeBtn").click(function(){
    $("#siteMap").animate({height:"toggle"}, "fast");
  });
});
</script>
