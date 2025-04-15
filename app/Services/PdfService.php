<?php
namespace App\Services;

use TCPDF;

class PdfService
{
    protected $pdf;

    public function __construct()
    {
        // Initialize TCPDF
        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor('Gym Management System');
        $this->pdf->SetTitle('Task Completion Report');
        $this->pdf->SetSubject('Task Completion');
        $this->pdf->SetKeywords('Task, Completion, Gym');

        // Set default header data
        $this->pdf->SetHeaderData('', 0, 'Gym Management System', 'Task Completion Report');

        // Set header and footer fonts
        $this->pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $this->pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // Set default monospaced font
        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set font
        $this->pdf->SetFont('helvetica', '', 12);
    }

    public function generateTaskCompletionPdf($task, $client, $coach)
    {
        // Add a page
        $this->pdf->AddPage();

        // Set title
        $this->pdf->SetFont('helvetica', 'B', 16);
        $this->pdf->Cell(0, 10, 'Task Completion Report', 0, 1, 'C');
        $this->pdf->Ln(10);

        // Set font for content
        $this->pdf->SetFont('helvetica', '', 12);

        // Task Details
        $html = '<h2 style="color: #007bff;">Task Details</h2>';
        $html .= '<p><strong>Task Title:</strong> ' . esc($task['TaskTitle']) . '</p>';
        $html .= '<p><strong>Description:</strong> ' . esc($task['TaskDescription']) . '</p>';
        $html .= '<p><strong>Equipment Name:</strong> ' . ($task['EquipmentName'] ? esc($task['EquipmentName']) : 'None') . '</p>';
        $html .= '<p><strong>Status:</strong> ' . ucfirst($task['Status']) . '</p>';
        $html .= '<p><strong>Progress:</strong> ' . $task['Progress'] . '%</p>';
        $html .= '<p><strong>Completed At:</strong> ' . ($task['CompletedAt'] ? date('F j, Y, g:i a', strtotime($task['CompletedAt'])) : 'N/A') . '</p>';
        $html .= '<p><strong>Due Date:</strong> ' . date('F j, Y', strtotime($task['DueDate'])) . '</p>';

        // Client Details
        $html .= '<h2 style="color: #007bff;">Client Details</h2>';
        $html .= '<p><strong>Name:</strong> ' . esc($client['Firstname'] . ' ' . $client['Lastname']) . '</p>';

        // Coach Details
        $html .= '<h2 style="color: #007bff;">Coach Details</h2>';
        $html .= '<p><strong>Name:</strong> ' . esc($coach['Firstname'] . ' ' . $coach['Lastname']) . '</p>';

        // Write HTML content to PDF
        $this->pdf->writeHTML($html, true, false, true, false, '');

        return $this->pdf;
    }

    public function savePdf($filename)
    {
        // Ensure the directory exists
        $directory = FCPATH . 'public/pdfs/';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Save the PDF to a file
        $this->pdf->Output($directory . $filename, 'F');
        return 'public/pdfs/' . $filename;
    }

    public function outputPdfForDownload($filename)
    {
        // Output the PDF for download
        $this->pdf->Output($filename, 'D');
    }
}