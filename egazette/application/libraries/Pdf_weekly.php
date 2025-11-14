<?php

require_once 'fpdf/fpdf.php';
require_once 'FPDI/src/autoload.php';

use \setasign\Fpdi\Fpdi;


class PDF_weekly extends FPDI {
	
	public $sl_no, $issue_date, $saka_month, $saka_date, $saka_year, $part_no, $section;
	public $header_image, $line_top_image, $line_bottom_image, $qr_image;
	
	/* 
	 * Set dynamic parameters 
	 * @Author Shivaram Mahapatro
	 * @Date 16/8/2021
	 * Dynamically generate the Govt. Press PDF file by inserting the original PDF file from department using FPDI template
	 */
	public function set_parameters($header_image, $line_top_image, $line_bottom_image, $sl_no, $issue_date, $saka_month, $saka_date, $saka_year, $qr_image, $part_no, $section) {
		
		$this->header_image = $header_image;
		$this->line_top_image = $line_top_image;
		$this->line_bottom_image = $line_bottom_image;
		$this->sl_no = $sl_no;
		$this->issue_date = $issue_date;
		$this->saka_month = $saka_month;
		$this->saka_date = $saka_date;
		$this->saka_year = $saka_year;
		$this->qr_image = $qr_image;
		$this->part_no = $part_no;
		$this->section = $section;
	}
	
	function Header() {

		/*
		if ($this->PageNo() == 1) {
			
			$this->Image($this->header_image, 20, 20, 175, 30); // X start, Y start, X width, Y width in mm
			
			$this->SetFont('Helvetica', 'B', 12);
			$this->SetXY(80, 48);
			$this->Write(12, 'PUBLISHED BY AUTHORITY');
			
			if (!empty($this->qr_image)) {
				$this->SetXY(85, 50); // X start, Y start in mm
				$this->Image($this->qr_image, 155, 40, 20, 20);
			}
			
			$this->Image($this->line_top_image, 20, 60, 175, 0); // X start, Y start, X width, Y width in mm
			
			$this->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(31, 67); // X start, Y start in mm
			$this->Write(0, "No. " . $this->sl_no . ",");
			
			$this->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(55, 67); // X start, Y start in mm
			$this->Write(0, "CUTTACK,");
			
			$this->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(80, 67); // X start, Y start in mm
			$this->Write(0, strtoupper($this->issue_date) . " ");
			
			$this->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(140, 67); // X start, Y start in mm
			$this->Write(0, "/ " . strtoupper($this->saka_month) . "  " . $this->saka_date . ",  " . $this->saka_year);
			
			$this->Image($this->line_bottom_image, 20, 72, 175, 0); // X start, Y start, X width, Y width in mm
			
			$this->SetFont('Helvetica', '', 8); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(30, 78); // X start, Y start in mm
			$this->Write(0, "SEPARATE PAGING IS GIVEN TO THIS PART IN ORDER THAT IT MAY BE FILED AS A SEPARATE COMPILATION");
			
			$this->SetXY(19, 70);
			// Font Style
			$this->SetFont('Arial', 'B', 9);
			// Page Number
			$this->Cell(0, 20, '___________________________________________________________________________________________________', 0, 0, '');

			$this->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(20, 90); // X start, Y start in mm
			$this->Cell(0, 0, $this->part_no, 0, 0, 'C');

			$this->Ln(5);
			$this->SetX(15);
			$this->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$this->SetXY(20, 95); // X start, Y start
			$this->MultiCell(0, 6, $this->section, 0, 'C', 0);
			$this->Ln(0);

			$this->SetY(105);
			// Font Style
			$this->SetFont('Arial', 'B', 10);
			// Page Number
			$this->Cell(0, 10, '__________', 0, 0, 'C');
			
		} else {

			$this->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(20, 25); // X start, Y start in mm
			$this->Write(0, $this->part_no);
			
			$this->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(55, 25); // X start, Y start in mm
			$this->Write(0, "THE (StateName) GAZETTE,");
			
			$this->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(100, 25); // X start, Y start in mm
			$this->Write(0, strtoupper(substr($this->issue_date, strpos($this->issue_date, ',') + 1)));
			
			$this->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(140, 25); // X start, Y start in mm
			$this->Write(0, "/" . strtoupper($this->saka_month) . ", " . $this->saka_date . ", " .  $this->saka_year);
			
			$this->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			$this->SetXY(185, 25); // X start, Y start in mm
			$this->Write(0, $this->PageNo());
			
			$this->Image($this->line_bottom_image, 20, 30, 175, 0); // X start, Y start, X width, Y width in mm
				
		}

		*/
	}

	function Footer(){
		if($this->isFinished){
			// Draw Line
			// Position
			$this->SetY(-35);
			// Font Style
			$this->SetFont('Arial', 'B', 10);
			// Page Number
			$this->Cell(0, 10, '__________', 0, 0, 'C');
			// Position
			$this->SetY(-30);
			// Font Style
			$this->SetFont('Arial', 'B', 10);
			// Page Numbers
			$this->Cell(0, 10, 'Processed and e-Published by the Director, Directorate of Printing, Stationery and Publication, (StateName), Cuttack - 10', 0, 0, 'C');
		}
		
	}
}