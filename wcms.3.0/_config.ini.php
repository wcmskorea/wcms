<?php
;header("HTTP/1.0 404 Not found");
;die();
;/*
;시스템 환경설정 및 배열 데이터.

operator        = 3;                                    ;운영자 권한
operatorip      = "/127.0.0.1/";                   ;운영자 아이피
master          = "4d99dabb7bfb0aa26591aa5bd383c817";   ;최고관리자 비밀번호(MD5)
version         = "V3.0";								;솔루션 버전
charset         = "utf-8";                              ;변경시 우편번호/아이디 검색 체크할것
droot           = "/";                                  ;최상위 디렉토리
mroot           = "/usr/local/mysql/bin/";              ;MySql DB백업을 위한 절대경로
sleep           = 0;
default         = "samplewcmskoreacom";

[sys]
api             = "wcms";
apiKey          = "wcmskorea";

[db]
dbUser          = "root";                                                   ;데이터베이스 계정 아이디
dbPass          = "wcms4865";                                               ;데이터베이스 계정 비밀번호
dbName          = "";

[upload]                                                                    ;Flex 멀티 업로더 설정
max_size        = 10240;                                                    ;파일하나(단위 KB) 10240 => 10MB
max_size_total  = 102400;                                                   ;전체파일(단위 KB) 10240 => 10MB
limit_ext       = "asp;php;php3;php4;html;htm;inc;js;class;cgi;jsp;exe";    ;업로드 불가 확장자
file_overwrite  = 0;


[solution]                                                                  ;제공되는 모듈
mdDocument      = "문서·게시물";
mdMember        = "회원·고객";
mdBanner        = "배너·이미지";
mdApp01         = "상담·문의";
mdPopup         = "팝업·공지";
mdSms           = "문자·SMS";
mdAnalytic      = "방문자분석";
mdSitemap       = "사이트맵";

[solutionFree]                                                              ;기본제공 모듈
0               = mdDocument;
1               = mdMember;
2               = mdApp01;
3               = mdBanner;
4               = mdPopup;
5               = mdSms;
6               = mdAnalytic;
7               = mdSitemap;


[solutionExcept]                                                            ;선택모듈 제한
0               = mdAnalytic;
1               = mdPopup;
2               = mdSms;
3               = mdBanner;
4               = mdMember;

[skinName]                                                                  ;제공되는 스킨
default         = "기본 사이트";
mobile          = "모바일 사이트";
english         = "영문 사이트";
chinese         = "중문 사이트";

[language]                                                                  ;제공되는 언어셋
kr              = "Korean";
en              = "English";
jp              = "Japanes";
cn              = "Chines";

[pgCompany]                                                                 ;PG사
lgdacom         = "LG U+";

[payType]                                                                   ;전자결제 종류
ST0000          = "무통장 결제";
ST0010          = "적립금 결제";
ST0020          = "예치금 결제";
SC0010          = "신용카드 결제";
SC0030          = "실시간 계좌이체";
SC0040          = "(가)무통장 결제";
SC0060          = "휴대폰 결제";

[payStatus]                                                                 ;결제처리 순서
0               = "결제중";
1               = "결제(입금)확인";
2               = "결제완료";
3               = "결제취소";
4               = "교환요청";
5               = "교환처리중";
6               = "교환완료";
7               = "환불요청";
8               = "환불처리중";
9               = "환불완료";

[orderStatus]                                                               ;주문처리 순서
0               = "입금대기";
1               = "결제완료";
2               = "상품준비";
3               = "배송준비";
4               = "발송완료";
5               = "구매완료";
6               = "취소접수";
7               = "취소(환불)완료";
8               = "반품접수";
9               = "반품완료";
10              = "교환접수";

[orderStatus1]                                                              ;발주처리 순서
0                = "발주접수";
1                = "배송준비";
2                = "배송완료";
3                = "발주취소";

[reserveStatus]                                                             ;예약처리 순서
0                = "결제대기";
1                = "결제완료";
2                = "부분결제";
3                = "결제취소";
4                = "예약취소";
5                = "예약완료";

[surveyStatus]                                                              ;설문처리 순서
0                = "결제대기";
1                = "결제완료";
2                = "별도결제";
3                = "취소신청";
4                = "취소완료";

[snsCompany]                                                                    ;SNS 종류
twitter          = "트위터";
facebook         = "페이스북";
me2day           = "미투데이";
yozm             = "요즘";
;*/
?>
