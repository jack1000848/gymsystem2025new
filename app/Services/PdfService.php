<?php
namespace App\Services;

use Dompdf\Dompdf;

class PdfService
{
    protected $dompdf;

    public function __construct()
    {
        $this->dompdf = new Dompdf();
    }

    public function generateTaskCompletionPdf($task, $client, $coach)
    {
        $html = '
        <h1>Task Completion Report</h1>
        <h2>Task: ' . esc($task['TaskTitle']) . '</h2>
        <p><strong>Client:</strong> ' . esc($client['Firstname'] . ' ' . $client['Lastname']) . '</p>
        <p><strong>Coach:</strong> ' . esc($coach['Firstname'] . ' ' . $coach['Lastname']) . '</p>
        <p><strong>Description:</strong> ' . esc($task['TaskDescription']) . '</p>
        <p><strong>Due Date:</strong> ' . date('F j, Y', strtotime($task['DueDate'])) . '</p>
        <p><strong>Status:</strong> ' . ucfirst($task['Status']) . '</p>
        <p><strong>Progress:</strong> ' . $task['Progress'] . '%</p>
        <p><strong>Completed At:</strong> ' . ($task['CompletedAt'] ? date('F j, Y H:i:s', strtotime($task['CompletedAt'])) : 'N/A') . '</p>
        ';

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
    }

    public function savePdf($filename)
    {
        $output = $this->dompdf->output();
        $pdfPath = FCPATH . 'public/pdfs/' . $filename;
        if (!is_dir(FCPATH . 'public/pdfs')) {
            mkdir(FCPATH . 'public/pdfs', 0755, true);
        }
        file_put_contents($pdfPath, $output);
        return 'public/pdfs/' . $filename;
    }

    public function streamPdf($filename)
    {
        $this->dompdf->stream($filename, ['Attachment' => true]);
    }
}