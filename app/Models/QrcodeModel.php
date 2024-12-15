<?php

namespace App\Models;

use CodeIgniter\Model;

class QrCodeModel extends Model
{
    protected $table = 'tbl_qrcodes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['data', 'qrcode_path', 'created_at'];


    
}
