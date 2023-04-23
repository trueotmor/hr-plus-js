<?php
namespace App\Controllers;
use Zoomx\Controllers;

class Company extends \Zoomx\Controllers\Controller
{
    /**
     * 
     */
    public function new()
    {
        $this->modx->addPackage('app', MODX_CORE_PATH . 'app/model/');
        $q = $this->modx->newQuery('Company');
        $q->select('*');
        $q->where([

        ]);
        $q->sortby('createdon', 'DESC');
        $q->prepare();
        $q->stmt->execute();
        $q_result = $q->stmt->fetchAll(\PDO::FETCH_ASSOC) ?: 0;
        return $q_result;
        return jsonx([
            'success' => true,
            'data' => $q_result
        ]);
    }


    /**
     * 
     */
    public function list()
    {
        $this->modx->addPackage('app', MODX_CORE_PATH . 'app/model/');
        $q = $this->modx->newQuery('Company');
        $q->select('*');
        $q->where([

        ]);
        $q->sortby('createdon', 'DESC');
        $q->prepare();
        $q->stmt->execute();
        $q_result = $q->stmt->fetchAll(\PDO::FETCH_ASSOC) ?: 0;
        return $q_result;
        return jsonx([
            'success' => true,
            'data' => $q_result
        ]);
    }

}
