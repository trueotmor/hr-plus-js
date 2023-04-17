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
            'username' => trim($_POST['username'] ?? ''),
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
     * @todo распарсить ошибки
     */
    public function register()
    {
        $email = trim($_POST['email'] ?? '');

        $data = [
            "active" => 0,
            'username' => $email,
            'email' => $email,
            'specifiedpassword' => trim($_POST['specifiedpassword'] ?? ''),
            'confirmpassword' => trim($_POST['confirmpassword'] ?? ''),
            'newpassword' => 'passwordgenmethod',
            'passwordgenmethod' => false,
            'passwordnotifymethod'  => 's',
        ];

        $response = $this->modx->runProcessor('security/user/create', $data);

        if ($response->isError()) {
            return jsonx([
                'success' => !$response->isError(),
                'message' => $response->getMessage() ?? '',
                'errors' => $response->getAllErrors() ?? [],
            ]);
        }

        $user = $this->modx->getObject('modUser', ['username' => $email]);
        $confirm_key = md5($user->username . ':' .$user->password);
        $confirm_link = $this->modx->getOption('site_url') . "auth/confirm?user={$user->username}&key={$confirm_key}";

        $email_params = [
            'subject' => 'Подтверждение регистрации',
            'content' => 'Ссылка для подтверждения: ' . $confirm_link,
        ];
        email($email, $email_params);

        return jsonx([
            'success' => !$response->isError(),
            'message' => $response->getMessage() ?? '',
            'errors' => $response->getAllErrors() ?? [],
        ]);
    }
}
