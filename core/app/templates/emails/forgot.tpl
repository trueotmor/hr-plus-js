{extends 'emails/email.tpl'}

{block 'main'}
    <p>
        Для сброса пароля на сайте {'site_name'|config} перейдите по ссылке ниже.
    </p>

    <p><a href="{$reset_url}">{$reset_url}</a></p>

    <p>После этого вы сможете зайти в <a href="[[++site_url]]profile">личный кабинет (вставить ссылку)</a>,
    используя ваш email <strong>{$email}</strong> и новый пароль <b>{$password}</b></p>

    <p>Если вы не сбрасывали пароль на сайте, проигнорируйте это сообщение.</p>
{/block}