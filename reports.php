<?php
session_start();
	// dBase file
    include "dbConfig.php";
    
	//not logged
	if (!$_SESSION["valid_user"]){Header("Location: index.php");}
	require('resources/scripts/fpdf.php');

class PDF extends FPDF{
	//Current column
	var $col=0;
	//Ordinate of column start
	var $y0;
	var $head;
	var $cols;
	var $aa=array();
	
	
	function Header(){
		if($this->PageNo()==1){
			//Logo
			$this->Image('resources/images/header.jpg',5,0,195);
			//Arial bold 15
			$this->SetFont('Helvetica','B',10);
			//Move to the right
			$this->Ln(0);
			$this->Cell(161);
			//Title
			$this->Cell(30,10,'Reporte',0,0,'R');
			$this->Ln(10);
			//Line break
			$this->y0=$this->GetY();
		} else {
			$this->fHeader();
		}
	}

	function Footer(){
		//Page footer
		$this->SetY(-15);
		$this->SetFont('Arial','I',6);
		$this->SetTextColor(128);
		$this->Cell(0,10,'Fujiyama SushiBar & Asian Cuisine -- StockOnline',0,0,'L');
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
	}

	//Load data
	function LoadData($id, $cols){
		$this->cols=$cols;
		switch ($id) {
    		case 1:
    			$this->aa=array(40,20);
    			$this->head=array('Nombre','Disponible');
        		$result=mysql_query("select * from product ORDER BY name");
				$line = "";

				while($row = mysql_fetch_array($result)){
					$name = $row["name"];
					$stock = $row["instock"];
	
					$line = $name.";".$stock;
					$data[]=explode(';',chop($line));
				}
				return $data;
        		break;
    		case 2:
    			$this->aa=array(40,20,20);
    			$this->head=array('Nombre','Fecha','Entrada');
    			$count=mysql_query("select DISTINCT producto from entrada ORDER BY producto");
  				while ($a = mysql_fetch_array($count)) {
  					$name=$a['producto'];
  					$result=mysql_query("SELECT * FROM entrada WHERE producto = '$name' AND date = (SELECT MAX( date )FROM entrada where producto = '$name')");
					$line = "";
					while($row = mysql_fetch_array($result)){
						$fecha = $row["date"];
						$stock = $row["cantidad"];
						$name = $row["producto"];
					
						$line = $name.";".$fecha.";".$stock."\n";
						$data[]=explode(';',chop($line));
					}
				}
				return $data;
        		break;
    		case 3:
    			$this->aa=array(40,20,15,20);
    			$name=$_GET["prod"];
        		$this->head=array('Nombre','Fecha','Entrada', 'Factura');
  				$result=mysql_query("SELECT * FROM entrada WHERE producto = '$name' ORDER BY date");
				$line = "";
				while($row = mysql_fetch_array($result)){
					$fecha = $row["date"];
					$stock = $row["cantidad"];
					$name = $row["producto"];
					$fact = $row["factura"];
				
					$line = $name.";".$fecha.";".$stock.";".$fact."\n";
					$data[]=explode(';',chop($line));
				}
				return $data;
        		break;
        	case 4:
        		$this->aa=array(40,20,15,20);
    			$this->head=array('Nombre','Fecha','Cantidad','Sede');
    			$count=mysql_query("select DISTINCT producto from salida ORDER BY producto");
  				while ($a = mysql_fetch_array($count)) {
  					$name=$a['producto'];
  					$result=mysql_query("SELECT * FROM salida WHERE producto = '$name' AND date = (SELECT MAX( date )FROM salida where producto = '$name')");
					$line = "";
					while($row = mysql_fetch_array($result)){
						$fecha = $row["date"];
						$stock = $row["cantidad"];
						$name = $row["producto"];
						$sedeid = $row["sede"];
						$a=mysql_query("SELECT * FROM sede WHERE id=$sedeid");
						while($q = mysql_fetch_array($a)){
							$sede=$q["place"];
						}
						$line = $name.";".$fecha.";".$stock.";".$sede."\n";
						$data[]=explode(';',chop($line));
					}
				}
				return $data;
        		break;
        	case 5:
        		$name=$_GET["prod"];
        		$this->aa=array(40,20,15,20);
    			$this->head=array('Nombre','Fecha','Cantidad','Sede');
  				$result=mysql_query("SELECT * FROM salida WHERE producto = '$name' ORDER BY date");
				$line = "";
				while($row = mysql_fetch_array($result)){
					$fecha = $row["date"];
					$stock = $row["cantidad"];
					$name = $row["producto"];
					$sedeid = $row["sede"];
					$a=mysql_query("SELECT * FROM sede WHERE id=$sedeid");
					while($q = mysql_fetch_array($a)){
						$sede=$q["nick"];
					}
					$line = $name.";".$fecha.";".$stock.";".$sede."\n";
					$data[]=explode(';',chop($line));
				}
				return $data;
        		break;
        	case 6:
        		$name=$_GET["sede"];
        		$a=mysql_query("SELECT * FROM sede WHERE place='$name'");
				while($q = mysql_fetch_array($a)){
					$sedeid=$q["id"];
					$sede=$q["nick"];
				}
        		$this->aa=array(40,20,15,20);
    			$this->head=array('Nombre','Fecha','Cantidad','Sede');
  				$result=mysql_query("SELECT * FROM salida WHERE sede = '$sedeid' Order BY date");
				$line = "";
				while($row = mysql_fetch_array($result)){
					$fecha = $row["date"];
					$stock = $row["cantidad"];
					$name = $row["producto"];
					
					$line = $name.";".$fecha.";".$stock.";".$sede."\n";
					$data[]=explode(';',chop($line));
				}
				return $data;
        		break;
        	case 7:
        		$sede=$_GET["sede"];
        		$date=$_GET["fecha"];

        		$this->aa=array(40,15,20,20);
    			$this->head=array('Nombre','Entrada','Merma','Real');
  				$result=mysql_query("SELECT * FROM `entrada_sede` where date='$date' and sede='$sede' and procesado='4' order by producto;");
				$line = "";
				while($row = mysql_fetch_array($result)){
					$name = $row["producto"];
					$ent = $row["entrada"];
					$merma = $row["merma"];
					$realpeso = $row["realpeso"];
					
					$line = $name.";".$ent.";".$merma.";".$realpeso."\n";
					$data[]=explode(';',chop($line));
				}
				for($i=0;$i<100;$i++){
					$line = "sddfasdasD;ASSDASSDAS;ADEWREF;FERGR\n";
						$data[]=explode(';',chop($line));
				}
				return $data;
        		break;
		}
	}

	function SetCol($col){
		//Set position at a given column
		$this->col=$col;
		if($this->cols>2 && $this->col>0){
			$x=50+$col*65;
		} else {
			if($this->cols==4 && $this->col>0){
				$x=50+$col*65;
			} else {
				$x=10+$col*65;
			}
		}
		$this->SetLeftMargin($x);
		$this->SetX($x);
	}

	function fHeader(){
		$this->SetFillColor(150,150,150);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		//Header
		
		for($i=0;$i<count($this->head);$i++)
		$this->Cell($this->aa[$i],5,$this->head[$i],1,0,'C',1);
		$this->Ln();
		$this->SetFillColor(215,215,215);
		$this->SetTextColor(0);
		$this->SetFont('');
	}
	
	
	function AcceptPageBreak(){
		//Method accepting or not automatic page break
		if($this->cols>2){
			$cc=1;
		} else {
			$cc=2;
		}
		if($this->col<$cc){
			//Go to next column
			$this->SetCol($this->col+1);
			//Set ordinate to top
			if($this->PageNo()==1){
				$this->SetY($this->y0);
			}  else {
				$this->SetY(10);
			}
			//Keep on page
			$this->fHeader();
			return false;
		}else{
			//Go back to first column
			$this->SetCol(0);
			//$this->fHeader();
			//Page break
			return true;
		}
	}

	//Colored table
	function FancyTable($data, $cols){
		$this->cols=$cols;
		//Colors, line width and bold font
		$this->SetFillColor(150,150,150);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		//Header
		/*$w=array();
		for($i=0;$i<$cols;$i++){
			if($i==0){$w[]='40';} else {$w[]='20';}
		}*/
		for($i=0;$i<count($this->head);$i++)
		$this->Cell($this->aa[$i],5,$this->head[$i],1,0,'C',1);
		$this->Ln();
		//Color and font restoration
		$this->SetFillColor(215,215,215);
		$this->SetTextColor(0);
		$this->SetFont('');
		//Data
		$fill=0;
		foreach($data as $row){
			for($i=0;$i<$cols;$i++){
				$this->Cell($this->aa[$i],5,$row[$i],'LR',0,'L',$fill);
			}
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(array_sum($this->aa),0,'','T');
	}
}

$repType=$_GET["id"];
$cols=$_GET["cols"];

$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
//Data loading
$data=$pdf->LoadData($repType, $cols);
$pdf->SetFont('Helvetica','',7);
$pdf->AddPage();
$pdf->FancyTable($data, $cols);
$pdf->Output( "Inventario.pdf", "I" );
?> 