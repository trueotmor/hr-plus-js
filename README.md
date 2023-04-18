# hr-plus

## API

```
GET /api/auth/is-auth

Response: {
    data: {
        success: bool
    }
}
```


```
POST /api/auth/login

Request: {
    username: string (email),
    password: string
}

Response: {
    data: {
        success: bool,
        message: string (сообщение об ошибке)
    }
}
```
@TODO rememberme


```
POST /api/auth/register

Request: {
    email: string,
    specifiedpassword: string,
    confirmpassword: string
}

Response: {
    data: {
        success: bool,
        message: string,
        errors: array
    }
}
```


```
POST /api/auth/forgot

Request: {
    email: string
}

Response: {
    data: {
        success: bool,
        message: string,
    }
}
```


```
GET /api/auth/logout
```
Открывать в браузере, делать запрос не нужно