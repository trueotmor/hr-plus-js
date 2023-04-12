<?php
namespace App\Controllers;
use Zoomx\Controllers;

class Auth extends \Zoomx\Controllers\Controller
{
    /**
     * 
     */
    public function login()
    {
        $response = $this->modx->runProcessor('/security/login', [
            'username' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'rememberme' => 1,
            'login_context' => $this->modx->context->key ?? 'web',
        ]);
        
        return jsonx([
            'success' => !$response->isError(),
            'message' => $response->getMessage() ?? '',
        ]);
    }


    /**
     * 
     */
    public function logout()
    {
       //return jsonx();
    }


    /**
     * 
     */
    public function register()
    {
        $data = [
            "active" => 0,
            'username' => trim($_POST['email'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'specifiedpassword' => trim($_POST['specifiedpassword'] ?? ''),
            'confirmpassword' => trim($_POST['confirmpassword'] ?? ''),
            'newpassword' => 'passwordgenmethod',
            'passwordgenmethod' => false,
            'passwordnotifymethod'  => 's',
        ];

        $response = $this->modx->runProcessor('security/user/create', $data);

        // @todo распарсить ошибки
        return jsonx([
            'success' => !$response->isError(),
            'message' => $response->getMessage() ?? '',
            'errors' => $response->getAllErrors() ?? [],
        ]);

        if ($response->isError()) {
            $errors = $response->getAllErrors();
            return[0, $errors];
        } else {
            return[1, $response->getMessage()];
        }
    }
}
