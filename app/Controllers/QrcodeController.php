<?php

namespace App\Controllers;

use App\Models\QrCodeModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class QrCodeController extends BaseController
{
    public function index()
    {
        return view('qrcode_form');
    }

    public function generate()
    {
        $data = $this->request->getPost('data');
        if (!$data) {
            return redirect()->back()->with('error', 'Please enter data to generate QR code.');
        }

        // Generate QR code
        $writer = new PngWriter();
        $qrCode = new QrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        // Save QR code to file
        $fileName = uniqid() . '.png';
        $filePath = WRITEPATH . 'qrcodes/' . $fileName;
        $result = $writer->write($qrCode);
        $result->saveToFile($filePath);

        // Save to database
        $qrCodeModel = new QrCodeModel();
        $qrCodeModel->insert([
            'data' => $data,
            'qrcode_path' => 'writable/qrcodes/' . $fileName,
        ]);

        return redirect()->to('/qrcode/list')->with('success', 'QR Code generated successfully.');
    }

    public function list()
    {
        $qrCodeModel = new QrCodeModel();
        $data['qrcodes'] = $qrCodeModel->findAll();

        return view('qrcode/list', $data);
    }
}
