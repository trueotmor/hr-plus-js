<?php
namespace App\Controllers;
use Zoomx\Controllers;

class Company extends \Zoomx\Controllers\Controller
{
    public $columns;


    /**
     * 
     */
    public function __construct($modx)
	{
		parent::__construct($modx);

        if (!$this->modx->user->isAuthenticated('web')) {
            return abortx(401);
        }

        $this->modx->addPackage('app', MODX_CORE_PATH . 'app/model/');

        $this->columns = str_replace('`', '', explode(', ', $this->modx->getSelectColumns('Company')));
	}


    /**
     * 
     */
    public function new()
    {
        if (!isset($_POST['name'])) {
            return jsonx(['success' => false, 'message' => 'name is required']);
        }

        $obj = $this->modx->newObject('Company');
        $obj->set('user', $this->modx->user->id);

        foreach ($_POST as $key => $val) {
            if (in_array($key, $this->columns) && !in_array($key, ['id', 'createdon', 'user', 'logo'])) {
                $obj->set($key, trim($val));
            }
        }

        $success = (bool)$obj->save();

        return jsonx([
            'success' => $success,
        ]);
    }


    /**
     * 
     */
    public function list()
    {
        $q = $this->modx->newQuery('Company');
        $q->select($this->columns);
        $q->where([
            'user' => $this->modx->user->id
        ]);
        $q->sortby('createdon', 'DESC');
        $q->prepare();
        $q->stmt->execute();
        $q_result = $q->stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        
        return jsonx(['success' => true, 'data' => $q_result]);
    }


    /**
     * 
     */
    public function get($id)
    {
        $q = $this->modx->newQuery('Company');
        $q->select($this->columns);
        $q->where([
            'user' => $this->modx->user->id,
            'id' => $id,
        ]);
        $q->prepare();
        $q->stmt->execute();
        $q_result = $q->stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $data = $q_result[0];

        if ($data && $data['logo']) {
            $data['logo'] = $this->modx->getOption('site_url') . $data['logo'];
        }
        
        return jsonx(['success' => !!$data, 'data' => $data]);
    }


    /**
     * 
     */
    public function update()
    {
        if (!isset($_POST['id'])) {
            return jsonx(['success' => false, 'message' => 'id is required']);
        }

        $obj = $this->modx->getObject('Company', [
            'user' => $this->modx->user->id,
            'id' => (int)$_POST['id'],
        ]);

        if (!$obj) {
            return jsonx(['success' => false, 'message' => "company with id {$_POST['id']} not found"]);
        }

        foreach ($_POST as $key => $val) {
            if (in_array($key, $this->columns) && !in_array($key, ['id', 'createdon', 'user', 'logo'])) {
                $obj->set($key, trim($val));
            }
        }

        if ($_FILES && $_FILES["logo"]["error"] === UPLOAD_ERR_OK) {
            $path = "assets/site/company/{$_POST['id']}";
            if (!file_exists(MODX_BASE_PATH . $path)) {
                mkdir($path, 0777, true);
            }
            $path_info = pathinfo($_FILES["logo"]["name"]);
            $file = "{$path}/logo.{$path_info['extension']}";
            move_uploaded_file($_FILES["logo"]["tmp_name"], MODX_BASE_PATH . $file);
            $obj->set('logo', $file);
        }

        $success = (bool)$obj->save();

        return jsonx([
            'success' => $success,
            'files' => $_FILES
        ]);
    }


    /**
     * 
     */
    public function delete()
    {
        if (!isset($_POST['id'])) {
            return jsonx(['success' => false, 'message' => 'id is required']);
        }

        $obj = $this->modx->getObject('Company', [
            'user' => $this->modx->user->id,
            'id' => (int)$_POST['id'],
        ]);

        if (!$obj) {
            return jsonx(['success' => false, 'message' => "company with id {$_POST['id']} not found"]);
        }

        $success = (bool)$obj->remove();

        return jsonx([
            'success' => $success,
        ]);
    }

}
