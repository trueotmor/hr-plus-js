# hr-plus

## API

В заголовках запроса указать `Content-Type: application/json`

```
POST /api/auth/login
{ username: string (email), password: string }
{ success: bool, message: string (сообщение об ошибке) }
```
@TODO rememberme

```
POST /api/auth/register
{ email: string, specifiedpassword: string, confirmpassword: string }
{ success: bool, message: string, errors: array }
```
@TODO валидация email на бэке