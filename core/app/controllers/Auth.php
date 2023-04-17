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
        $confirm_link = $this->modx->getOption('site_url') . "api/auth/confirm?user={$user->username}&key={$confirm_key}";

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


    /**
     * 
     */
    public function confirm_register()
    {
        if (empty($_GET['key']) || empty($_GET['user'])) {
            return jsonx([
                'success' => false,
                'message' => 'Неверная ссылка',
            ]);
        }

        $user = $this->modx->getObject('modUser', ['username' => $_GET['user']]);
        if (!$user) {
            return jsonx([
                'success' => false,
                'message' => 'Пользователь не найден',
            ]);
        }

        $confirm_key = md5($user->username . ':' .$user->password);
        if ($confirm_key !== $_GET['key']) {
            return jsonx([
                'success' => false,
                'message' => 'Неверный ключ',
            ]);
        }

        $user->set('active', 1);
        $user->save();

        return jsonx([
            'success' => true,
            'message' => 'ok',
        ]);
    }


    /**
     * 
     */
    public function forgot()
    {
        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return jsonx([
                'success' => false,
                'message' => 'Введите корректный email',
            ]);
        }

        $profile = $this->modx->getObject('modUserProfile', ['email' => $email]);
        if (!$profile) {
            return jsonx([
                'success' => false,
                'message' => 'Пользователь с таким email не зарегистрирован на сайте',
            ]);
        }

        $pass = bin2hex(random_bytes(4));
        $key = bin2hex(random_bytes(24));
        $_SESSION['new_password'] = $pass;
        $_SESSION['new_password_key'] = $key;
        $_SESSION['new_password_email'] = $email;
        $reset_link = $this->modx->getOption('site_url') . "api/auth/reset-password?key={$key}";

        $email_params = [
            'subject' => 'Сброс пароля',
            'content' => "Для сброса пароля пройдите по ссылке $reset_link. Ваш новый пароль: <b>$pass</b>.",
        ];
        email($email, $email_params);

        return jsonx([
            'success' => true,
            'message' => 'ok ' . $key,
        ]);
    }


    /**
     * 
     */
    public function reset_password()
    {        
        if (empty($_GET['key'])
            || empty($_SESSION['new_password'])
            || empty($_SESSION['new_password_key'])
            || empty($_SESSION['new_password_email'])
            || $_GET['key'] !== $_SESSION['new_password_key']
        ) {
            return jsonx([
                'success' => false,
                'message' => 'Неверная ссылка',
            ]);
        }

        $profile = $this->modx->getObject('modUserProfile', ['email' => $_SESSION['new_password_email']]);
        if (!$profile) {
            return jsonx([
                'success' => false,
                'message' => 'Пользователь с таким email не зарегистрирован на сайте',
            ]);
        }

        $user = $this->modx->getObject('modUser', $profile->internalKey);
        $user->set('password', $_SESSION['new_password']);
        $user->save();
        $profile->set('blocked', 0);
        $profile->save();

        unset($_SESSION['new_password'], $_SESSION['new_password_key'], $_SESSION['new_password_email']);

        return jsonx([
            'success' => true,
            'message' => 'ок ' . $user->username,
        ]);
    }
}
