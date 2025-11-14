<?php

require_once 'fpdf/fpdf.php';
require_once 'FPDI/src/autoload.php';

use \setasign\Fpdi\Fpdi;


class PDF extends FPDI {
	
	public $sl_no, $sro_no, $issue_date, $saka_month, $saka_date, $saka_year, $qr_image;
	public $header_image, $line_top_image, $line_bottom_image;
	
	/* 
	 * Set dynamic parameters 
	 * @Author Shivaram Mahapatro
	 * @Date 7/8/2021
	 * Dynamically generate the Govt. Press PDF file by inserting the original PDF file from department using FPDI template
	 */
	public function set_parameters($header_image, $line_top_image, $line_bottom_image, $sl_no, $sro_no, $issue_date, $saka_month, $saka_date, $saka_year, $qr_image) {
		
		$this->header_image = $header_image;
		$this->line_top_image = $line_top_image;
		$this->line_bottom_image = $line_bottom_image;
		$this->sl_no = $sl_no;
		$this->sro_no = $sro_no;
		$this->issue_date = $issue_date;
		$this->saka_month = $saka_month;
		$this->saka_date = $saka_date;
		$this->saka_year = $saka_year;
		$this->qr_image = $qr_image;
	}
	
	function Header() {
		if ($this->PageNo() == 1) {
			
			$this->Image($this->header_image, 20, 25, 175, 30); // X start, Y start, X width, Y width in mm
			
			$this->SetFont('Helvetica', 'B', 12);
			$this->SetXY(91, 58);
			$this->Write(12, 'EXTRAORDINARY');
			$this->SetFont('Helvetica','B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(81, 70); // X start, Y start in mm
			$this->Write(0, "PUBLISHED BY AUTHORITY");
			
			// QR Code
			$this->SetXY(85, 76); // X start, Y start in mm
			$this->Image($this->qr_image, 155, 60, 20, 20);

			// SRO NO
			if (!empty($this->sro_no)) {
				$this->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
				//$pdf->SetTextColor(0,0,0); // RGB 
				$this->SetXY(88, 76); // X start, Y start in mm
				$this->Write(0, "S.R.O. No. - " . $this->sro_no);
			}
			
			// Line Bottom Image
			$this->Image($this->line_top_image, 20, 82, 175, 0); // X start, Y start, X width, Y width in mm
			
			// Sl No
			$this->SetFont('Helvetica', 'B', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(31, 88); // X start, Y start in mm
			$this->Write(0, "No. " . $this->sl_no . ",");
			
			// Docket Fixed Text
			$this->SetFont('Helvetica', 'B', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(48, 88); // X start, Y start in mm
			$this->Write(0, "CUTTACK,");
			
			// Issue Date
			$this->SetFont('Helvetica', 'B', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(75, 88); // X start, Y start in mm
			$this->Write(0, strtoupper($this->issue_date) . " ");
			
			// SAKA Month
			$this->SetFont('Helvetica', 'B', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
			//$pdf->SetTextColor(0,0,0); // RGB 
			$this->SetXY(140, 88); // X start, Y start in mm
			$this->Write(0, "/ " . strtoupper($this->saka_month) . "  " . strtoupper($this->saka_date) . ",  " . $this->saka_year);
			// X start, Y start, X width, Y width in mm
			$this->Image($this->line_bottom_image, 20, 92, 175, 0);
		}
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
			// Page Number
			$this->Cell(0, 10, 'Processed and e-Published by the Director, Directorate of Printing, Stationery and Publication, (StateName), Cuttack - 10', 0, 0, 'C');
		}
		
		// Position
		$this->SetY(-25);
		$this->SetTextColor(211, 211, 211);
		// Font Style
		$this->SetFont('Arial', '', 10);
		// Page Number
		$this->Cell(0, 10, 'Page '.$this->PageNo(), 0, 0, 'C');
	}
}