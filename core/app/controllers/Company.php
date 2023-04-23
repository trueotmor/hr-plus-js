<?php
namespace App\Controllers;
use Zoomx\Controllers;

class Company extends \Zoomx\Controllers\Controller
{
    /**
     * 
     */
    public function get()
    {
        return jsonx([json_decode(file_get_contents('php://input'))]);
    }

}
