<?php
require_once $_SERVER[DOCUMENT_ROOT]."/_config.php";
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
	<title><?=$cfg['site']['siteName']?> :: <?=$cfg['site']['siteTitle']?> - <?=$cfg['skin']?></title>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=<?=$cgf[charset]?>" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta http-equiv="pragma" content="no-cache" />
	<link rel="stylesheet" href="/common/css/default.css" type="text/css" charset="<?=$cgf[charset]?>" media="all" />
	<style type="text/css">
   td {padding:3px;}
   .mypage_list_title
   {
     font-weight: bold;
     height:30px;
     background-color:333333;
     color:666666;
   }
   .mypage_list_mouseover{
     color:666666;
     background-color:;
   }
   .mypage_list_mouseout{
     font-size:12px;
     color:666666;
     background-color:;
   }
   .mypage_list_mouseover_text{
     text-decoration:none;
     font-size:12px;
     color:000000;
   }
   .mypage_list_mouseover_normal{
     text-decoration:none;
     font-size:12px;
     color:666666;
   }



   .mypage_view_title{
     height:30px;
     font-size:14px;
     font-weight: bold;
     color:000000;
     background-color:;

   }
   .mypage_view_title_line{
     height:4px;
     background-color:333333;

   }
   .mypage_view_normal{
     height:20px;
     font-size:12px;
     color:666666;
     background-color:;
   }
   .mypage_view_body{
     height:100px;
     font-size:12px;
     background-color:#ffffff;
   }



   .mypage_write_title_line{
     height:4px;
     background-color:333333;
   }
   .mypage_write_input{
     font-size:12px;
     color:666666;
     BORDER-RIGHT: #bdbdbd 1px solid;
     BORDER-TOP: #bdbdbd 1px solid;
     BORDER-LEFT: #bdbdbd 1px solid;
     BORDER-BOTTOM: #bdbdbd 1px solid;
   }
   .mypage_write_text{
     text-align:center;
     font-size:12px;
     background-color:;
     color:000000;
   }
   .mypage_write_datafield{

     font-size:12px;
     color:;
   }
	</style>
</head>
<body>
<!--사용자 페이지-->

<TABLE width=100% border=5>
<TBODY>
<TR>
<TD>

<TABLE cellPadding=0 width="96%" align=center border=0>
<TBODY>
<TR>
<TD style="FONT-SIZE: 14px; FONT-FAMILY: 돋움" height=50><FONT size=2><IMG height=13 src="http://okryun.ms.kr/image/doc/ico_rnd.gif" width=5 align=absMiddle> </FONT><B><FONT size=2>저작권 보호 지침</FONT></B></TD></TR>
<TR>
<TD><FONT face=돋움 size=2>문화관광부는 디지털 기술 발전, 이용환경 변화에 따른 다양한 저작권 침해행위에 대한 효과적인 저작권 보호체계를 확립하고 저작권에 대한 교육, 인식 강화사업을 수행하기 위해 『저작권보호센터(www.cleancopyright.or.kr)』를 설치하고 저작권보호 활동을 하고 있습니다.<BR><BR>저작권보호센터의 단속활동이 본격화 되면서 학생 여러분이 단속활동의 선의의 피해자가 되지 않도록 다음과 같은 사항을 유의하시기 바랍니다. </FONT></TD></TR>
<TR>
<TD>
<TABLE cellPadding=5 width="100%" bgColor=#efefef border=0>
<TBODY>
<TR>
<TD><FONT face=돋움><FONT color=#006699><STRONG><FONT size=2>※ 학생들이 주의하여야 할 저작권침해 유형</FONT></STRONG></FONT><FONT size=2><BR><BR>( 저작권자의 허락없이 혹은 이용에 대한 정당한 사용료의 지불없이 무단으로 올릴 경우에 해당 )<BR><BR>· 음악 파일을 미니 홈피나 블로그의 배경 음악으로 이용하는 경우<BR>· 음악 파일, 동영상 파일, 각종 이미지 파일, 시 파일, 사진저작물 등 저작물을 무단으로 웹사이트, 미니 홈피, 카페, 블로그 등에 올리는 경우 <BR>· 각종 저작물을 포털 사이트나 웹사이트의 게시판, 자료실, 방명록 등에 올리는 경우<BR>· 저작물을 특정 가입자들만 접근할 수 있는 폐쇄적인 웹사이트, 미니홈피, 카페, 블로그 등에 공유 목적으로 올리는 경우<BR>· 여러 경로를 통하여 수집한 저작물을 다른 사람들과 공유할 목적으로 웹기드에 저장하거나 내려받는 경우<BR>· 다른 사용자와 공유할 목적으로 P2P 프로그램을 통하여 저작물을 올리거나 내려받는 경우<BR>· 음악 CD 등을 여러 장 복제 (일명 ‘굽기’)하여 친구들에게 나눠주는 행위 <BR>· 노래가사, 스타사진 등을 웹사이트(예를 들어 가수 팬클럽 웹사이트)에 올리는 행위<BR><BR><A href="http://1318.copyright.or.kr/" target=blank><B>[청소년 저작권 교실 바로가기]</B></A> </FONT></FONT></TD></TR></TBODY></TABLE></TD></TR>
<TR>
<TD><FONT face=돋움 size=2>저작권을 침해한 경우에는 민사상 손해배상책임을 질뿐만 아니라 5년 이하의 징역 또는 5천만원 이하의 벌금을 병과할 수 있도록 저작권법에서 규정하고 있으므로 타인의 저작물사용 시 이용허락을 받거나 정당한 대가를 지불하고 사용하시기 바랍니다. </FONT>
<P><FONT face=돋움 size=2>저작권에 대해 궁금한 점은 저작권보호센터(02-2166-2521~3)로 문의하시고 문화관광부 홈페이지 공지사항 『저작권, 그안에 무엇이 있길래』내용 중 “네티즌이 알아야할 저작권 상식”을 참고하시기 바랍니다. </FONT></P></TD></TR></TBODY></TABLE>
<TABLE cellPadding=0 width="96%" align=center border=0>
<TBODY>
<TR>
<TD style="FONT-SIZE: 14px; FONT-FAMILY: 돋움" height=50><FONT size=2><IMG height=13 src="http://okryun.ms.kr/image/doc/ico_rnd.gif" width=5 align=absMiddle> </FONT><B><FONT size=2>저작권 신고</FONT></B></TD></TR>
<TR>
<TD>
<TABLE cellPadding=5 width="100%" bgColor=#e8f2d5 border=0>
<TBODY>
<TR>
<TD><FONT face=돋움 size=2>본 서비스는 게시된 저작물로 인하여 저작권을 침해받은 경우 이를 처리하기 위한 서비스입니다.<BR>다음 사항을 참고하여 신고하여 주시기 바랍니다. </FONT></TD></TR></TBODY></TABLE></TD></TR>
<TR>
<TD><FONT face=돋움><FONT color=#009999 size=2><BR><STRONG><FONT color=#006699>저작권 신고 접수 수령인(담당자)</FONT></STRONG></FONT><FONT size=2><BR></FONT></FONT><SPAN style="COLOR: #000000; LINE-HEIGHT: 23px; TEXT-ALIGN: justify"><FONT face=돋움 size=2>총괄보호책임관 : <BR>분야별 보호책임관 : <BR>전화번호 :Tel) <BR>주소 : 우) </FONT></SPAN><FONT face=돋움 size=2><BR><BR>　</FONT></TD></TR>
<TR>
<TD><FONT face=돋움><FONT color=#009999><STRONG><FONT color=#006699 size=2>게시중단요청신청</FONT></STRONG></FONT><FONT size=2><BR><IMG height=3 src="http://okryun.ms.kr/image/doc/icon.gif" width=3 align=absMiddle> 게시중단요청 : 게시된 저작물에 의해 권리를 침해당하였다고 판단되면 해당 절차에 따라서 게시중단을 요청합니다.<BR><IMG height=3 src="http://okryun.ms.kr/image/doc/icon.gif" width=3 align=absMiddle> 게시된 저작물의 게시중단(복제.전송 중단)을 요청할 경우에는 아래 문서를 다운로드 하여 작성한 후 '저작권 신고 접수 수령인'에게 방문 또는 전자우편(e-mail)으로 신청합니다.<BR><IMG height=3 src="http://okryun.ms.kr/image/doc/icon.gif" width=3 align=absMiddle> 정당한 권리 없이 게시중단을 요청하면 법에 의해 손해배상의 책임이 있습니다.<BR><IMG height=3 src="http://okryun.ms.kr/image/doc/icon.gif" width=3 align=absMiddle> <A style="FONT-WEIGHT: bold; COLOR: #ff6600" href="/user/data/게시중단요청서.hwp">양식다운로드</A><BR><BR><BR>　</FONT></FONT></TD></TR>
<TR>
<TD><FONT face=돋움><STRONG><FONT color=#006699 size=2>재게시요청 신청</FONT></STRONG><FONT size=2><BR><IMG height=3 src="http://okryun.ms.kr/image/doc/icon.gif" width=3 align=absMiddle> 게시가 중단된 저작물을 게시한 이용자가 해당 저작물이 정당한 권리에 의한 게시라고 판단되면 해당 절차에 따라서 재 게시를 요청합니다.<BR><IMG height=3 src="http://okryun.ms.kr/image/doc/icon.gif" width=3 align=absMiddle> 게시중단을 통보받은 저작물에 대하여 재 게시를 요청할 경우에는 아래 문서를 다운로드 하여 작성한 후 '저작권 신고 접수 수령인' 에게 방문 또는 전자우편(e-mail)으로 접수합니다.<BR><IMG height=3 src="http://okryun.ms.kr/image/doc/icon.gif" width=3 align=absMiddle> 정당한 권리 없이 게시중단을 요청하면 법에 의해 손해배상의 책임이 있습니다.<BR><IMG height=3 src="http://okryun.ms.kr/image/doc/icon.gif" width=3 align=absMiddle> <A style="FONT-WEIGHT: bold; COLOR: #ff6600" href="/user/data/재게시요청신청서.hwp">양식다운로드</A> <BR><BR>　</FONT></FONT></TD></TR>
<TR>
<TD height=10></TD></TR>
<TR>
<TD>
<TABLE cellPadding=5 width="100%" bgColor=#e8f2d5 border=0>
<TBODY>
<TR>
<TD><FONT face=돋움><FONT color=red><B><FONT size=2>저작물의 무단 전재 및 배포시 저작권법 136조에 의거 최고 5년 이하의 징역 또는 5천만원 이하의 벌금에 처하거나 이를 병과할 수 있습니다.</FONT></B></FONT><FONT size=2> </FONT></FONT></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<TABLE cellPadding=0 width="96%" align=center border=0>
<TBODY>
<TR>
<TD height=30>　</TD></TR>
<TR>
<TD>
<P style="FONT-FAMILY: 돋움"><FONT size=2><IMG height=13 src="http://okryun.ms.kr/image/doc/ico_rnd.gif" width=5 align=absMiddle> </FONT><B><FONT size=2>관련법규</FONT></B></P>
<P><FONT face=돋움><STRONG><FONT size=2>개정저작권법 </FONT></STRONG><A href="http://www.mct.go.kr/index.html" target=blank><FONT size=2>[전문다운받기]</FONT></A></FONT></P><FONT face=돋움><STRONG><FONT size=2>[ 주요내용 ]</FONT></STRONG><FONT size=2> </FONT></FONT>
<P><FONT face=돋움><STRONG><FONT size=2>제2장. 저작권</FONT></STRONG><FONT size=2><BR></FONT><STRONG><FONT size=2>제 25 조 (학교교육목적 등에의 이용) </FONT></STRONG></FONT></P></TD></TR>
<TR>
<TD>
<TABLE cellSpacing=0 cellPadding=2 width="100%" align=center border=0>
<TBODY>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>①</FONT></TD>
<TD><FONT face=돋움 size=2>고등학교 및 이에 준하는 학교 이하의 학교의 교육목적상 필요한 교과용도서에는 공표된 저작물을 게재할 수 있다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>②</FONT></TD>
<TD><FONT face=돋움 size=2>특별법에 의하여 설립되었거나 「초·중등교육법」 또는 「고등교육법」에 따른 교육기관 또는 국가나 지방자치단체가 운영하는 교육기관은 그 수업목적상 필요하다고 인정되는 경우에는 공표된 저작물의 일부분을 복제·공연·방송 또는 전송할 수 있다. 다만, 저작물의 성질이나 그 이용의 목적 및 형태 등에 비추어 저작물의 전부를 이용하는 것이 부득이한 경우에는 전부를 이용할 수 있다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>③</FONT></TD>
<TD><FONT face=돋움 size=2>제2항의 규정에 따른 교육기관에서 교육을 받는 자는 수업목적상 필요하다고 인정되는 경우에는 제2항의 범위 내에서 공표된 저작물을 복제하거나 전송할 수 있다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>④</FONT></TD>
<TD><FONT face=돋움 size=2>제1항 및 제2항의 규정에 따라 저작물을 이용하고자 하는 자는 문화관광부장관이 정하여 고시하는 기준에 의한 보상금을 당해 저작재산권자에게 지급하여야 한다. 다만, 고등학교 및 이에 준하는 학교 이하의 학교에서 제2항의 규정에 따른 복제·공연·방송 또는 전송을 하는 경우에는 보상금을 지급하지 아니한다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>⑤</FONT></TD>
<TD><FONT face=돋움 size=2>제4항의 규정에 따른 보상을 받을 권리는 다음 각 호의 요건을 갖춘 단체로서 문화관광부장관이 지정하는 단체를 통하여 행사되어야 한다. 문화관광부장관이 그 단체를 지정할 때에는 미리 그 단체의 동의를 얻어야 한다.</FONT></TD></TR>
<TR>
<TD>　</TD>
<TD><FONT face=돋움 size=2>1. 대한민국 내에서 보상을 받을 권리를 가진 자(이하 “보상권리자”라 한다)로 구성된 단체</FONT></TD></TR>
<TR>
<TD>　</TD>
<TD vAlign=top><FONT face=돋움 size=2>2. 영리를 목적으로 하지 아니할 것</FONT></TD></TR>
<TR>
<TD>　</TD>
<TD><FONT face=돋움 size=2>3. 보상금의 징수 및 분배 등의 업무를 수행하기에 충분한 능력이 있을 것 </FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>⑥</FONT></TD>
<TD><FONT face=돋움 size=2>제5항의 규정에 따른 단체는 그 구성원이 아니라도 보상권리자로부터 신청이 있을 때에는 그 자를 위하여 그 권리행사를 거부할 수 없다. 이 경우 그 단체는 자기의 명의로 그 권리에 관한 재판상 또는 재판 외의 행위를 할 권한을 가진다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>⑦</FONT></TD>
<TD><FONT face=돋움 size=2>문화관광부장관은 제5항의 규정에 따른 단체가 다음 각 호의 어느 하나에 해당하는 경우에는 그 지정을 취소할 수 있다.</FONT></TD></TR>
<TR>
<TD vAlign=top>　</TD>
<TD><FONT face=돋움 size=2>1. 제5항의 규정에 따른 요건을 갖추지 못한 때</FONT></TD></TR>
<TR>
<TD vAlign=top>　</TD>
<TD><FONT face=돋움 size=2>2. 보상관계 업무규정을 위배한 때</FONT></TD></TR>
<TR>
<TD vAlign=top>　</TD>
<TD><FONT face=돋움 size=2>3. 보상관계 업무를 상당한 기간 휴지하여 보상권리자의 이익을 해할 우려가 있을 때</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>⑧</FONT></TD>
<TD><FONT face=돋움 size=2>제5항의 규정에 따른 단체는 보상금 분배 공고를 한 날로부터 3년이 경과한 미분배 보상금에 대하여 문화관광부장관의 승인을 얻어 공익목적을 위하여 사용할 수 있다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>⑨</FONT></TD>
<TD><FONT face=돋움 size=2>제5항, 제7항 및 제8항의 규정에 따른 단체의 지정과 취소 및 업무규정, 보상금 분배 공고, 미분배 보상금의 공익목적 사용 승인 등에 관하여 필요한 사항은 대통령령으로 정한다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>⑩</FONT></TD>
<TD><FONT face=돋움 size=2>제2항의 규정에 따라 교육기관이 전송을 하는 경우에는 저작권 그 밖에 이 법에 의하여 보호되는 권리의 침해를 방지하기 위하여 복제방지조치 등 대통령령이 정하는 필요한 조치를 하여야 한다.</FONT></TD></TR></TBODY></TABLE>
<P><FONT face=돋움><STRONG><FONT size=2>제 32 조 (시험문제로서의 복제) </FONT></STRONG><FONT size=2><BR>학교의 입학시험 그 밖에 학식 및 기능에 관한 시험 또는 검정을 위하여 필요한 경우에는 그 목적을 위하여 정당한 범위 안에서 공표된 저작물을 복제할 수 있다. 다만, 영리를 목적으로 하는 경우에는 그러하지 아니하다. </FONT></FONT></P>
<P><FONT face=돋움><STRONG><FONT size=2>제6장. 온라인서비스 제공자의 책임 제한</FONT></STRONG><FONT size=2><BR></FONT><STRONG><FONT size=2>제 102 조 (온라인서비스제공자의 책임 제한)</FONT></STRONG></FONT></P>
<TABLE cellSpacing=0 cellPadding=2 width="100%" align=center border=0>
<TBODY>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>①</FONT></TD>
<TD><FONT face=돋움 size=2>온라인서비스제공자가 저작물등의 복제·전송과 관련된 서비스를 제공하는 것과 관련하여 다른 사람에 의한 저작물등의 복제·전송으로 인하여 그 저작권 그 밖에 이 법에 따라 보호되는 권리가 침해된다는 사실을 알고 당해 복제·전송을 방지하거나 중단시킨 경우에는 다른 사람에 의한 저작권 그 밖에 이 법에 따라 보호되는 권리의 침해에 관한 온라인서비스제공자의 책임을 감경 또는 면제할 수 있다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>②</FONT></TD>
<TD><FONT face=돋움 size=2>온라인서비스제공자가 저작물등의 복제·전송과 관련된 서비스를 제공하는 것과 관련하여 다른 사람에 의한 저작물등의 복제·전송으로 인하여 그 저작권 그 밖에 이 법에 따라 보호되는 권리가 침해된다는 사실을 알고 당해 복제·전송을 방지하거나 중단시키고자 하였으나 기술적으로 불가능한 경우에는 그 다른 사람에 의한 저작권 그 밖에 이 법에 따라 보호되는 권리의 침해에 관한 온라인서비스제공자의 책임은 면제된다.</FONT></TD></TR></TBODY></TABLE>
<P><FONT face=돋움><STRONG><FONT size=2>제 103 조 (복제·전송의 중단)</FONT></STRONG><FONT size=2> </FONT></FONT></P>
<TABLE cellSpacing=0 cellPadding=2 width="96%" align=center border=0>
<TBODY>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>①</FONT></TD>
<TD><FONT face=돋움 size=2>온라인서비스제공자의 서비스를 이용한 저작물등의 복제·전송에 따라 저작권 그 밖에 이 법에 따라 보호되는 자신의 권리가 침해됨을 주장하는 자(이하 이 조에서 “권리주장자”라 한다)는 그 사실을 소명하여 온라인서비스제공자에게 그 저작물등의 복제·전송을 중단시킬 것을 요구할 수 있다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>②</FONT></TD>
<TD><FONT face=돋움 size=2>온라인서비스제공자는 제1항의 규정에 따라 복제·전송의 중단요구가 있는 경우에는 즉시 그 저작물등의 복제·전송을 중단시키고 당해 저작물등을 복제·전송하는 자(이하 “복제·전송자”라 한다) 및 권리주장자에게 그 사실을 통보하여야 한다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>③</FONT></TD>
<TD><FONT face=돋움 size=2>제2항의 규정에 따른 통보를 받은 복제·전송자가 자신의 복제·전송이 정당한 권리에 의한 것임을 소명하여 그 복제·전송의 재개를 요구하는 경우 온라인서비스제공자는 재개요구사실 및 재개예정일을 권리주장자에게 지체 없이 통보하고 그 예정일에 복제·전송을 재개시켜야 한다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>④</FONT></TD>
<TD><FONT face=돋움 size=2>온라인서비스제공자는 제1항 및 제3항의 규정에 따른 복제·전송의 중단 및 그 재개의 요구를 받을 자(이하 이 조에서 “수령인”이라 한다)를 지정하여 자신의 설비 또는 서비스를 이용하는 자들이 쉽게 알 수 있도록 공지하여야 한다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>⑤</FONT></TD>
<TD><FONT face=돋움 size=2>온라인서비스제공자가 제4항의 규정에 따른 공지를 하고, 제2항 및 제3항의 규정에 따라 그 저작물등의 복제·전송을 중단시키거나 재개시킨 경우에는 다른 사람에 의한 저작권 그 밖에 이 법에 따라 보호되는 권리의 침해에 대한 온라인서비스제공자의 책임 및 복제·전송자에게 발생하는 손해에 대한 온라인서비스제공자의 책임을 감경 또는 면제할 수 있다. 다만, 이 항의 규정은 온라인서비스제공자가 다른 사람에 의한 저작물등의 복제·전송으로 인하여 그 저작권 그 밖에 이 법에 따라 보호되는 권리가 침해된다는 사실을 안 때부터 제1항의 규정에 따른 중단을 요구받기 전까지 발생한 책임에는 적용하지 아니한다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>⑥</FONT></TD>
<TD><FONT face=돋움 size=2>정당한 권리 없이 제1항 및 제3항의 규정에 따른 그 저작물등의 복제·전송의 중단이나 재개를 요구하는 자는 그로 인하여 발생하는 손해를 배상하여야 한다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>⑦</FONT></TD>
<TD><FONT face=돋움 size=2>제1항 내지 제4항의 규정에 따른 소명, 중단, 통보, 복제·전송의 재개, 수령인의 지정 및 공지 등에 관하여 필요한 사항은 대통령령으로 정한다. 이 경우 문화관광부장관은 관계중앙행정기관의 장과 미리 협의하여야 한다.</FONT></TD></TR></TBODY></TABLE>
<P><STRONG><FONT face=돋움 size=2>제 104 조 (특수한 유형의 온라인 서비스제공자의 의무 등)</FONT></STRONG></P>
<TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
<TBODY>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>①</FONT></TD>
<TD><FONT face=돋움 size=2>다른 사람들 상호 간에 컴퓨터 등을 이용하여 저작물등을 전송하도록 하는 것을 주된 목적으로 하는 온라인서비스제공자(이하 “특수한 유형의 온라인서비스제공자”라 한다)는 권리자의 요청이 있는 경우 당해 저작물등의 불법적인 전송을 차단하는 기술적인 조치 등 필요한 조치를 하여야 한다. 이 경우 권리자의 요청 및 필요한 조치에 관한 사항은 대통령령으로 정한다.</FONT></TD></TR>
<TR>
<TD vAlign=top><FONT face=돋움 size=2>②</FONT></TD>
<TD><FONT face=돋움 size=2>문화관광부장관은 제1항의 규정에 따른 특수한 유형의 온라인서비스제공자의 범위를 정하여 고시할 수 있다.</FONT></TD></TR></TBODY></TABLE></TD></TR>
<TR>
<TD height=30>　</TD></TR>
<TR>
<TD>
<P style="FONT-FAMILY: 돋움"><FONT size=2><IMG height=13 src="http://okryun.ms.kr/image/doc/ico_rnd.gif" width=5 align=absMiddle> </FONT><B><FONT size=2>관련기관</FONT></B></P></TD></TR>
<TR>
<TD>
<TABLE cellSpacing=1 cellPadding=1 width="100%" align=center bgColor=#dfdfdf>
<TBODY>
<TR bgColor=#e8f8d0>
<TD align=middle height=26>
<DIV align=center><STRONG><FONT face=돋움 size=2>분 야</FONT></STRONG></DIV></TD>
<TD align=middle>
<DIV align=center><STRONG><FONT face=돋움 size=2>단 체 명</FONT></STRONG></DIV></TD>
<TD align=middle>
<DIV align=center><STRONG><FONT face=돋움 size=2>전화번호</FONT></STRONG></DIV></TD>
<TD align=middle>
<DIV align=center><STRONG><FONT face=돋움 size=2>홈페이지 및 주소</FONT></STRONG></DIV></TD></TR>
<TR bgColor=#ffffff>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>음악저작물</FONT></DIV></TD>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>한국음악저작권협회</FONT></DIV></TD>
<TD>
<DIV align=center><FONT face=돋움 size=2>(02)3660-0900</FONT></DIV></TD>
<TD><A hideFocus href="http://www.komca.or.kr/" target=_blank><FONT face=돋움 size=2>www.komca.or.kr</FONT></A></TD></TR>
<TR bgColor=#ffffff>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>어문저작물 일반</FONT></DIV></TD>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>한국문예학술저작권협회</FONT></DIV></TD>
<TD>
<DIV align=center><FONT face=돋움 size=2>(02)508-0440</FONT></DIV></TD>
<TD><A hideFocus href="http://www.copyrightkorea.or.kr/" target=_blank><FONT face=돋움 size=2>www.copyrightkorea.or.kr</FONT></A></TD></TR>
<TR bgColor=#ffffff>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>방송시나리오</FONT></DIV></TD>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>한국방송작가협회</FONT></DIV></TD>
<TD>
<DIV align=center><FONT face=돋움 size=2>(02)782-1696</FONT></DIV></TD>
<TD><A hideFocus href="http://www.ktrwa.or.kr/" target=_blank><FONT face=돋움 size=2>www.ktrwa.or.kr</FONT></A></TD></TR>
<TR bgColor=#ffffff>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>영화시나리오</FONT></DIV></TD>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>한국시나리오작가협회</FONT></DIV></TD>
<TD>
<DIV align=center><FONT face=돋움 size=2>(02)2275-0566</FONT></DIV></TD>
<TD><A hideFocus href="http://www.scenario.or.kr/" target=_blank><FONT face=돋움 size=2>www.scenario.or.kr</FONT></A></TD></TR>
<TR bgColor=#ffffff>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>어문저작물<BR>(복사, 전송)</FONT></DIV></TD>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>한국복사전송권관리센터</FONT></DIV></TD>
<TD>
<DIV align=center><FONT face=돋움 size=2>(02)733-9032</FONT></DIV></TD>
<TD><A hideFocus href="http://www.copycle.or.kr/" target=_blank><FONT face=돋움 size=2>www.copycle.or.kr</FONT></A></TD></TR>
<TR bgColor=#ffffff>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>음악실연자</FONT></DIV></TD>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>한국예술실연자단체연합회</FONT></DIV></TD>
<TD>
<DIV align=center><FONT face=돋움 size=2>(02)745-8286</FONT></DIV></TD>
<TD><A hideFocus href="http://www.pak.or.kr/" target=_blank><FONT face=돋움 size=2>www.pak.or.kr</FONT></A></TD></TR>
<TR bgColor=#ffffff>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>방송실연자</FONT></DIV></TD>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>한국방송실연자협회</FONT></DIV></TD>
<TD>
<DIV align=center><FONT face=돋움 size=2>(02)784-3411</FONT></DIV></TD>
<TD><FONT face=돋움 size=2>서울시 영등포구 여의도동 43-6<BR>미원빌딩1501호</FONT></TD></TR>
<TR bgColor=#ffffff>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>음반제작자</FONT></DIV></TD>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>한국음원제작자협회</FONT></DIV></TD>
<TD>
<DIV align=center><FONT face=돋움 size=2>(02)711-9731</FONT></DIV></TD>
<TD><A hideFocus href="http://www.kapp.or.kr/" target=_blank><FONT face=돋움 size=2>www.kapp.or.kr</FONT></A></TD></TR>
<TR bgColor=#ffffff>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>저작권 정책</FONT></DIV></TD>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>문화관광부 저작권과</FONT></DIV></TD>
<TD>
<DIV align=center><FONT face=돋움 size=2>(02)3704-9470</FONT></DIV></TD>
<TD><A hideFocus href="http://www.mct.go.kr/" target=_blank><FONT face=돋움 size=2>www.mct.go.kr</FONT></A></TD></TR>
<TR bgColor=#ffffff>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>저작권 상담,<BR>분쟁조정, 등록</FONT></DIV></TD>
<TD align=middle>
<DIV align=center><FONT face=돋움 size=2>저작권심의조정위원회</FONT></DIV></TD>
<TD>
<DIV align=center><FONT face=돋움 size=2>(02)2669-9900</FONT></DIV></TD>
<TD><A hideFocus href="http://www.copyright.or.kr/" target=_blank><FONT face=돋움 size=2>www.copyright.or.kr</FONT></A></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></DIV></DIV>
</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE><!-- 본문 끝 --></TD></TR></TBODY></TABLE>
</body>
</html>
