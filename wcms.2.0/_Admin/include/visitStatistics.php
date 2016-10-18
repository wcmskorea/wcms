<?php
require_once "../../_config.php";
require_once '../../addon/chart/php-ofc-library/open-flash-chart.php';

/*
$visitCount = $db->queryFetch('SELECT
																		IFNULL(SUM(if(MONTH(date)="1" ,1,0)),0) AS s1,
																		IFNULL(SUM(if(MONTH(date)="2" ,1,0)),0) AS s2,
																		IFNULL(SUM(if(MONTH(date)="3" ,1,0)),0) AS s3,
																		IFNULL(SUM(if(MONTH(date)="4" ,1,0)),0) AS s4,
																		IFNULL(SUM(if(MONTH(date)="5" ,1,0)),0) AS s5,
																		IFNULL(SUM(if(MONTH(date)="6" ,1,0)),0) AS s6,
																		IFNULL(SUM(if(MONTH(date)="7" ,1,0)),0) AS s7,
																		IFNULL(SUM(if(MONTH(date)="8" ,1,0)),0) AS s8,
																		IFNULL(SUM(if(MONTH(date)="9" ,1,0)),0) AS s9,
																		IFNULL(SUM(if(MONTH(date)="10" ,1,0)),0) AS s10,
																		IFNULL(SUM(if(MONTH(date)="11" ,1,0)),0) AS s11,
																		IFNULL(SUM(if(MONTH(date)="12" ,1,0)),0) AS s12
																	FROM `mdAnalytic__track` WHERE YEAR(date)="'.date('Y').'" ');
*/
$start	= $db->queryFetchOne(" SELECT DAY(date) FROM `mdAnalytic__track` WHERE YEAR(date)='".date('Y')."' AND MONTH(date)='".date('m')."' ORDER BY date ASC LIMIT 1 ");
$db->query(" SELECT COUNT(*) AS counts, SUM(counting) AS visits FROM `mdAnalytic__track` WHERE YEAR(date)='".date('Y')."' AND MONTH(date)='".date('m')."' group by date ");
//$d			= (date('d') == $dates) ? 0 : date('d') - $start;
$i			= 0;
$line_1	= array(0);
$line_2	= array(0);

while($count = $db->fetch())
{
	$line_1[$i] = (int)$count['counts'];
	$line_2[$i] = (int)$count['visits'];
	$dataName[$i] = ($start + $i)."일";
	$i++;
}

/*
	$data[0]  = (int)$visitCount['s1'];
	$data[1]  = (int)$visitCount['s2'];
	$data[2]  = (int)$visitCount['s3'];
	$data[3]  = (int)$visitCount['s4'];
	$data[4]  = (int)$visitCount['s5'];
	$data[5]  = (int)$visitCount['s6'];
	$data[6]  = (int)$visitCount['s7'];
	$data[7]  = (int)$visitCount['s8'];
	$data[8]  = (int)$visitCount['s9'];
	$data[9]  = (int)$visitCount['s10'];
	$data[10] = (int)$visitCount['s11'];
	$data[11] = (int)$visitCount['s12'];
*/
	$title = $title = new title( "[ ".date('Y년 m월')." ]" );
	$title->set_style( "{font-size: 12px; color: #660099; font-weight:bold; font-family:dotum; text-align: center; padding:10px 0 10px 21px;}" );
	$maxCount = @max($line_2);
	$term = ($maxCount > 1000) ? ($maxCount > 10000) ? 10000 : 1000 : 100;

	//$animation_1= isset($_GET['animation_1'])?$_GET['animation_1']:'grow-up';
	//$delay_1    = isset($_GET['delay_1'])?$_GET['delay_1']:0.5;
	//$cascade_1    = isset($_GET['cascade_1'])?$_GET['cascade_1']:1;


	//$bar = new bar_cylinder();
	//$bar->set_values( $data );
	//$bar->set_on_show(new bar_on_show($animation_1, $cascade_1, $delay_1));

	$hol = new hollow_dot();
	$hol->size(4)->halo_size(1)->tooltip('#x_label#<br>#val#명');

	$line1 = new line();
	$line1->set_colour( '#0066ff' );
	$line1->set_default_dot_style($hol);
	$line1->set_values( $line_1 );

	$hol = new hollow_dot();
	$hol->size(4)->halo_size(1)->tooltip('#x_label#<br>#val#회');

	$line2 = new line();
	$line2->set_colour( '#669900' );
	$line2->set_default_dot_style($hol);
	$line2->set_values( $line_2 );

	$chart = new open_flash_chart();
	//$chart->set_title( $title );
	$chart->add_element( $line1 );
	$chart->add_element( $line2 );

	$area = new area();
	$area->set_colour( '#0066ff' );
	$area->set_key( '이달 방문자수', 12 );
	$chart->add_element( $area );

	$area = new area();
	$area->set_colour( '#669900' );
	$area->set_key( '이달 방문횟수', 12 );
	$chart->add_element( $area );

// Y축값 설정
	$y_axis = new y_axis();
	$y_axis->set_colour( '#999999' );
	$y_axis->set_grid_colour( '#efefef' );
	$y_axis->set_range( 0, $maxCount+100, $term);
	$chart->set_y_axis( $y_axis );

// X축값 설정
	$x_axis = new x_axis();
	$x_axis->set_colour( '#999999' );
	$x_axis->set_grid_colour( '#efefef' );
	$x_axis->set_labels_from_array($dataName);

	$x_axis->colour = '#999999';
	$chart->set_x_axis( $x_axis );
	$chart->set_bg_colour('#ffffff');

	echo $chart->toPrettyString();
?>