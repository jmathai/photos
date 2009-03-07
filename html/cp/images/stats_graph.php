<?php
  include '../jpgraph/src/jpgraph.php';
  include '../jpgraph/src/jpgraph_line.php';
  //$datay = array('january', 'february');
  $title = $_GET['title'];
  
  $graph = new Graph(700,300,'auto');
  $graph->img->SetMargin(40,40,40,40);	
  $graph->SetMarginColor('gray9');	
  $graph->SetScale('textlin');
  
  $a = $gDateLocale->GetShortMonth();
  $graph->xaxis->SetTickLabels($a);
  $graph->xaxis->SetFont(FF_FONT2);
  
  $graph->img->SetAntiAliasing();
  //$graph->SetScale("textlin");
  $graph->SetShadow();
  $graph->title->Set($title);
  //$graph->title->SetFont(FF_TREBUCHE,FS_BOLD);
  $graph->SetColor('cornsilk');
  
  // Add 10% grace to top and bottom of plot
  $graph->yscale->SetGrace(10,10);
  
  $p1 = new LinePlot($datay);
  $p1->mark->SetType(MARK_DIAMOND);
  $p1->mark->SetFillColor("red");
  $p1->mark->SetWidth(4);
  $p1->SetColor("blue");
  $p1->SetCenter();
  $graph->Add($p1);
  
  $graph->Stroke();
?>