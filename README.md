# hr-plus-back

## API

### Auth

```
GET /api/user

Response: {
    data: {
        success: bool,
        data: array
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



### Company

Доступные поля: name, brand, address, email, phone, site, contacts(json), inn, ogrn, description.


```
POST /api/company/new

Request: {
    name: string,
    ...
}

Response: {
    data: {
        success: bool,
        message: string (if error),
    }
}
```


```
POST /api/company/list

Response: {
    data: {
        success: bool,
        data: array
    }
}

```


```
POST /api/company/update

Request: {
    id: integer,
    ...
}

Response: {
    data: {
        success: bool,
        message: string (if error),
    }
}
```


```
POST /api/company/delete

Request: {
    id: integer
}

Response: {
    data: {
        success: bool,
        message: string (if error),
    }
}