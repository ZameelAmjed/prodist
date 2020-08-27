###OPEN ROUTES DOES NOT REQUIRE TOKEN

URL:{url}/api/auth/login  
TYPE:POST  
DATA:[nic, telephone]  
RESPONSE:
```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2NoXC9hcGlcL2F1dGhcL2xvZ2luIiwiaWF0IjoxNTg0NDI0NDUxLCJleHAiOjE1ODQ0MjgwNTEsIm5iZiI6MTU4NDQyNDQ1MSwianRpIjoiU1pIQkpzN2V4RU4ya2MxNyIsInN1YiI6NCwicHJ2IjoiZGI0YTc5MzQ3MTZhZWUyMTA3ZTNmNGIwNGU3MTQ0YWNlNTUxNWU4ZCJ9.cLqUlUANoBNXo3X7ONfpf6CtkPpaQYf4zKG0vb8ERk8",
    "token_type": "bearer",
    "expires_in": 3600
}
```

URL:{url}/api/auth/signup  
TYPE:POST  
DATA:[name,nic,celebration,telephone,block,street,city,language,date_of_birth]  
RESPONSE:
```json
{
    "access_token": "TOKEN",
    "token_type": "bearer",
    "expires_in": 3600
}
```

###GURDED ROUTES REQUIRE BEARER TOKEN
####Logout
URL:{url}/api/auth/logout  
TYPE:GET  
RESPONSE:
```json
{
    "message": "Successfully logged out"
}
```    

URL:{url}/api/auth/profile  
TYPE:POST  
RESPONSE:
```json
{
    "id": 1,
    "name": "Arshad Ahley",
    "nic": "911080674v",
    "celebration": "eid",
    "telephone": "077585858",
    "block": "520",
    "street": "",
    "city": "Colombo",
    "province": "Western",
    "region": null,
    "area": null,
    "dealer_id": null,
    "language": null,
    "bank_account_no": null,
    "bank_name": null,
    "bank_city": null,
    "bank_code": null,
    "photo": null,
    "date_of_birth": "1992-05-11",
    "approved_by": null,
    "status": "pending",
    "products_count": 0,
    "points": 0,
    "float_points": 0,
    "created_at": "2020-03-16 06:39:55",
    "updated_at": "2020-03-16 06:39:55"
}
```

URL:{url}/api/auth/profile  
TYPE:PUT  
DATA:[name,block,street,city,language,date_of_birth]  
RESPONSE:
```json
{
    "name": "Ahley Rashad",
    "block": "4458",
    "language": "english",
    "street": "inr Street",
    "city": "Galle",
    "date_of_birth": "1990-04-17",
    "updated_at": "2020-03-17 04:55:44"
}
```

URL:{url}/api/auth/addproduct?  
TYPE:POST  
DATA:[barcode]  
RESPONSE:
Success
```json
{
    "status": true,
    "data": {
        "_id": "5e6c5bad127f000054004757",
        "product_name": "Switch",
        "model": "Eum asperiores eos",
        "textcode": "CC",
        "description": "Sunt dolore rerum si",
        "points": "15",
        "updated_at": "2020-03-17 05:50:39",
        "created_at": "2020-03-14 04:21:00",
        "units_issued": 11,
        "last_barcode": "CC-0000000002-A2W",
        "units_active": 2
    }
}  
```
Failure
```json
{
    "status": false,
    "message": "Invalid Barcode entry"
}  
```
URL:{url}/api/auth/myrewards  
TYPE:GET  
RESPONSE:
```json
{
    "rewards": [
        {
            "_id": "5e706299f7120000ed0074f2",
            "barcode": "CC-0000000001-SWQ",
            "electrician": 4,
            "product": "5e6c5bad127f000054004757",
            "model": "Eum asperiores eos",
            "points": "15",
            "user": "0",
            "ip": "::1",
            "updated_at": "2020-03-17 05:39:37",
            "created_at": "2020-03-17 05:39:37"
        }
    ],
    "payments": [
        {
            "id": 1,
            "electrician_id": 4,
            "points": 15,
            "transfer_type": "Bank Deposit",
            "comment": "asd",
            "confirmation_message": "Sent",
            "payed_on": "2020-03-17",
            "created_at": "2020-03-17 05:40:59",
            "updated_at": "2020-03-17 05:40:59"
        }
    ]
}
```

