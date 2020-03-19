---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#general
<!-- START_1a5481bdcdf0e47e46de2ae0152a770b -->
## docs/{page?}
> Example request:

```bash
curl -X GET -G "http://localhost/docs/1" 
```
```javascript
const url = new URL("http://localhost/docs/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET docs/{page?}`

`POST docs/{page?}`

`PUT docs/{page?}`

`PATCH docs/{page?}`

`DELETE docs/{page?}`

`OPTIONS docs/{page?}`


<!-- END_1a5481bdcdf0e47e46de2ae0152a770b -->

<!-- START_7e3072a9c6d43c05123a799823b02c6d -->
## api/docs
> Example request:

```bash
curl -X GET -G "http://localhost/api/docs" 
```
```javascript
const url = new URL("http://localhost/api/docs");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (500):

```json
{
    "message": "Required @SWG\\Info() not found",
    "code": 500,
    "debug": {
        "line": 38,
        "file": "D:\\www\\demu\\vendor\\zircote\\swagger-php\\src\\Logger.php",
        "class": "ErrorException",
        "trace": [
            "#0 [internal function]: Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(1024, 'Required @SWG\\\\I...', 'D:\\\\www\\\\demu\\\\ven...', 38, Array)",
            "#1 D:\\www\\demu\\vendor\\zircote\\swagger-php\\src\\Logger.php(38): trigger_error('Required @SWG\\\\I...', 1024)",
            "#2 [internal function]: Swagger\\Logger->Swagger\\{closure}('Required @SWG\\\\I...', 1024)",
            "#3 D:\\www\\demu\\vendor\\zircote\\swagger-php\\src\\Logger.php(68): call_user_func(Object(Closure), 'Required @SWG\\\\I...', 1024)",
            "#4 D:\\www\\demu\\vendor\\zircote\\swagger-php\\src\\Annotations\\AbstractAnnotation.php(388): Swagger\\Logger::notice('Required @SWG\\\\I...')",
            "#5 D:\\www\\demu\\vendor\\zircote\\swagger-php\\src\\Annotations\\Swagger.php(150): Swagger\\Annotations\\AbstractAnnotation->validate(Array, Array, '#')",
            "#6 D:\\www\\demu\\vendor\\zircote\\swagger-php\\src\\Analysis.php(331): Swagger\\Annotations\\Swagger->validate()",
            "#7 D:\\www\\demu\\vendor\\zircote\\swagger-php\\src\\functions.php(46): Swagger\\Analysis->validate()",
            "#8 D:\\www\\demu\\vendor\\appointer\\swaggervel\\src\\Appointer\\Swaggervel\\Http\\Controllers\\SwaggervelController.php(84): Swagger\\scan('D:\\\\www\\\\demu\\\\app', Array)",
            "#9 D:\\www\\demu\\vendor\\appointer\\swaggervel\\src\\Appointer\\Swaggervel\\Http\\Controllers\\SwaggervelController.php(36): Appointer\\Swaggervel\\Http\\Controllers\\SwaggervelController->regenerateDefinitions()",
            "#10 [internal function]: Appointer\\Swaggervel\\Http\\Controllers\\SwaggervelController->ui(Object(Dingo\\Api\\Http\\Request))",
            "#11 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Controller.php(54): call_user_func_array(Array, Array)",
            "#12 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\ControllerDispatcher.php(45): Illuminate\\Routing\\Controller->callAction('ui', Array)",
            "#13 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php(219): Illuminate\\Routing\\ControllerDispatcher->dispatch(Object(Illuminate\\Routing\\Route), Object(Appointer\\Swaggervel\\Http\\Controllers\\SwaggervelController), 'ui')",
            "#14 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php(176): Illuminate\\Routing\\Route->runController()",
            "#15 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(680): Illuminate\\Routing\\Route->run()",
            "#16 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(30): Illuminate\\Routing\\Router->Illuminate\\Routing\\{closure}(Object(Dingo\\Api\\Http\\Request))",
            "#17 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(104): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Dingo\\Api\\Http\\Request))",
            "#18 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(682): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))",
            "#19 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(657): Illuminate\\Routing\\Router->runRouteWithinStack(Object(Illuminate\\Routing\\Route), Object(Dingo\\Api\\Http\\Request))",
            "#20 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(623): Illuminate\\Routing\\Router->runRoute(Object(Dingo\\Api\\Http\\Request), Object(Illuminate\\Routing\\Route))",
            "#21 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(612): Illuminate\\Routing\\Router->dispatchToRoute(Object(Dingo\\Api\\Http\\Request))",
            "#22 D:\\www\\demu\\vendor\\dingo\\api\\src\\Routing\\Adapter\\Laravel.php(88): Illuminate\\Routing\\Router->dispatch(Object(Dingo\\Api\\Http\\Request))",
            "#23 D:\\www\\demu\\vendor\\dingo\\api\\src\\Routing\\Router.php(514): Dingo\\Api\\Routing\\Adapter\\Laravel->dispatch(Object(Dingo\\Api\\Http\\Request), 'v1')",
            "#24 D:\\www\\demu\\vendor\\dingo\\api\\src\\Http\\Middleware\\Request.php(129): Dingo\\Api\\Routing\\Router->dispatch(Object(Dingo\\Api\\Http\\Request))",
            "#25 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(128): Dingo\\Api\\Http\\Middleware\\Request->Dingo\\Api\\Http\\Middleware\\{closure}(Object(Dingo\\Api\\Http\\Request))",
            "#26 D:\\www\\demu\\vendor\\fideloper\\proxy\\src\\TrustProxies.php(57): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Dingo\\Api\\Http\\Request))",
            "#27 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Fideloper\\Proxy\\TrustProxies->handle(Object(Dingo\\Api\\Http\\Request), Object(Closure))",
            "#28 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php(21): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Dingo\\Api\\Http\\Request))",
            "#29 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Dingo\\Api\\Http\\Request), Object(Closure))",
            "#30 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php(21): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Dingo\\Api\\Http\\Request))",
            "#31 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Dingo\\Api\\Http\\Request), Object(Closure))",
            "#32 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize.php(27): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Dingo\\Api\\Http\\Request))",
            "#33 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize->handle(Object(Dingo\\Api\\Http\\Request), Object(Closure))",
            "#34 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode.php(62): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Dingo\\Api\\Http\\Request))",
            "#35 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode->handle(Object(Dingo\\Api\\Http\\Request), Object(Closure))",
            "#36 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(104): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Dingo\\Api\\Http\\Request))",
            "#37 D:\\www\\demu\\vendor\\dingo\\api\\src\\Http\\Middleware\\Request.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))",
            "#38 D:\\www\\demu\\vendor\\dingo\\api\\src\\Http\\Middleware\\Request.php(103): Dingo\\Api\\Http\\Middleware\\Request->sendRequestThroughRouter(Object(Dingo\\Api\\Http\\Request))",
            "#39 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Dingo\\Api\\Http\\Middleware\\Request->handle(Object(Dingo\\Api\\Http\\Request), Object(Closure))",
            "#40 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))",
            "#41 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(104): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))",
            "#42 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(151): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))",
            "#43 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(116): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter(Object(Illuminate\\Http\\Request))",
            "#44 D:\\www\\demu\\vendor\\mpociot\\laravel-apidoc-generator\\src\\Tools\\ResponseStrategies\\ResponseCallStrategy.php(281): Illuminate\\Foundation\\Http\\Kernel->handle(Object(Illuminate\\Http\\Request))",
            "#45 D:\\www\\demu\\vendor\\mpociot\\laravel-apidoc-generator\\src\\Tools\\ResponseStrategies\\ResponseCallStrategy.php(265): Mpociot\\ApiDoc\\Tools\\ResponseStrategies\\ResponseCallStrategy->callLaravelRoute(Object(Illuminate\\Http\\Request))",
            "#46 D:\\www\\demu\\vendor\\mpociot\\laravel-apidoc-generator\\src\\Tools\\ResponseStrategies\\ResponseCallStrategy.php(37): Mpociot\\ApiDoc\\Tools\\ResponseStrategies\\ResponseCallStrategy->makeApiCall(Object(Illuminate\\Http\\Request))",
            "#47 D:\\www\\demu\\vendor\\mpociot\\laravel-apidoc-generator\\src\\Tools\\ResponseResolver.php(49): Mpociot\\ApiDoc\\Tools\\ResponseStrategies\\ResponseCallStrategy->__invoke(Object(Illuminate\\Routing\\Route), Array, Array)",
            "#48 D:\\www\\demu\\vendor\\mpociot\\laravel-apidoc-generator\\src\\Tools\\ResponseResolver.php(68): Mpociot\\ApiDoc\\Tools\\ResponseResolver->resolve(Array, Array)",
            "#49 D:\\www\\demu\\vendor\\mpociot\\laravel-apidoc-generator\\src\\Tools\\Generator.php(67): Mpociot\\ApiDoc\\Tools\\ResponseResolver::getResponse(Object(Illuminate\\Routing\\Route), Array, Array)",
            "#50 D:\\www\\demu\\vendor\\mpociot\\laravel-apidoc-generator\\src\\Commands\\GenerateDocumentation.php(222): Mpociot\\ApiDoc\\Tools\\Generator->processRoute(Object(Illuminate\\Routing\\Route), Array)",
            "#51 D:\\www\\demu\\vendor\\mpociot\\laravel-apidoc-generator\\src\\Commands\\GenerateDocumentation.php(74): Mpociot\\ApiDoc\\Commands\\GenerateDocumentation->processRoutes(Object(Mpociot\\ApiDoc\\Tools\\Generator), Array)",
            "#52 [internal function]: Mpociot\\ApiDoc\\Commands\\GenerateDocumentation->handle()",
            "#53 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(32): call_user_func_array(Array, Array)",
            "#54 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(90): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()",
            "#55 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(34): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))",
            "#56 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(576): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)",
            "#57 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(183): Illuminate\\Container\\Container->call(Array)",
            "#58 D:\\www\\demu\\vendor\\symfony\\console\\Command\\Command.php(255): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))",
            "#59 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(170): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))",
            "#60 D:\\www\\demu\\vendor\\symfony\\console\\Application.php(921): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))",
            "#61 D:\\www\\demu\\vendor\\symfony\\console\\Application.php(273): Symfony\\Component\\Console\\Application->doRunCommand(Object(Mpociot\\ApiDoc\\Commands\\GenerateDocumentation), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))",
            "#62 D:\\www\\demu\\vendor\\symfony\\console\\Application.php(149): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))",
            "#63 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Application.php(90): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))",
            "#64 D:\\www\\demu\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(133): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))",
            "#65 D:\\www\\demu\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))",
            "#66 {main}"
        ]
    }
}
```

### HTTP Request
`GET api/docs`


<!-- END_7e3072a9c6d43c05123a799823b02c6d -->

<!-- START_0bfd90e643e49117746d270f35d4851b -->
## Display a listing of the Post.

GET|HEAD /posts

> Example request:

```bash
curl -X GET -G "http://localhost/api/post" 
```
```javascript
const url = new URL("http://localhost/api/post");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "data": {
        "success": true,
        "data": [
            {
                "id": 1,
                "title": "title",
                "content": "content",
                "created_at": null,
                "updated_at": null
            },
            {
                "id": 2,
                "title": "题目",
                "content": "内容",
                "created_at": null,
                "updated_at": null
            }
        ],
        "message": "Posts retrieved successfully"
    },
    "code": 200,
    "message": "成功"
}
```

### HTTP Request
`GET api/post`


<!-- END_0bfd90e643e49117746d270f35d4851b -->

<!-- START_73111c6124911ea6dee8bfec2ab7b91a -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/photos" 
```
```javascript
const url = new URL("http://localhost/api/photos");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
null
```

### HTTP Request
`GET api/photos`


<!-- END_73111c6124911ea6dee8bfec2ab7b91a -->

<!-- START_e785f3e8a2e58e455c7f749417724d13 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "http://localhost/api/photos" 
```
```javascript
const url = new URL("http://localhost/api/photos");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/photos`


<!-- END_e785f3e8a2e58e455c7f749417724d13 -->

<!-- START_536b0c040e35a2031cb3d0b49592893f -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/photos/1" 
```
```javascript
const url = new URL("http://localhost/api/photos/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
null
```

### HTTP Request
`GET api/photos/{photo}`


<!-- END_536b0c040e35a2031cb3d0b49592893f -->

<!-- START_3d3793a5600b7060c5fa2d4d0f6ef6fc -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/photos/1" 
```
```javascript
const url = new URL("http://localhost/api/photos/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`PUT api/photos/{photo}`

`PATCH api/photos/{photo}`


<!-- END_3d3793a5600b7060c5fa2d4d0f6ef6fc -->

<!-- START_389f5609c943a51ac63ad4160afa0f27 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/photos/1" 
```
```javascript
const url = new URL("http://localhost/api/photos/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE api/photos/{photo}`


<!-- END_389f5609c943a51ac63ad4160afa0f27 -->

#主业务类型维护001
<!-- START_1f1b9f97f12e538f963aa8dfaaca33f8 -->
## 列表00101

> Example request:

```bash
curl -X GET -G "http://localhost/api/businesses" 
```
```javascript
const url = new URL("http://localhost/api/businesses");

    let params = {
            "page": "accusamus",
            "per_page": "et",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "data": [
        {
            "id": 4,
            "name": "主业务类型名称",
            "status": "0-禁止1-启用",
            "created_at": "生成时间",
            "updated_at": "修改时间"
        }
    ],
    "links": {
        "first": "http:\/\/demu.tao3w.com\/api\/business.index?page=1",
        "last": "http:\/\/demu.tao3w.com\/api\/business.index?page=5",
        "prev": null,
        "next": "http:\/\/demu.tao3w.com\/api\/business.index?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "path": "http:\/\/demu.tao3w.com\/api\/business.index",
        "per_page": 10,
        "to": 10,
        "total": 49
    }
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`GET api/businesses`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | 第几页，默认为1
    per_page |  optional  | 每页数，默认为10

<!-- END_1f1b9f97f12e538f963aa8dfaaca33f8 -->

<!-- START_2e94a0bb167727e17ae5d2823b46afab -->
## 插入00102

> Example request:

```bash
curl -X POST "http://localhost/api/businesses" \
    -H "Content-Type: application/json" \
    -d '{"parent_id":10,"name":"et"}'

```
```javascript
const url = new URL("http://localhost/api/businesses");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "parent_id": 10,
    "name": "et"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 4,
    "name": "业务类型",
    "parent_id": "主业务id",
    "created_at": "生成时间",
    "updated_at": "修改时间",
    "status": "0-禁止1-启用"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`POST api/businesses`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    parent_id | integer |  optional  | 父业务类型id，默认为0
    name | string |  required  | 业务类型

<!-- END_2e94a0bb167727e17ae5d2823b46afab -->

<!-- START_60667ab9f3fcaa4b3e45a5a70d1ac187 -->
## 显示00103

> Example request:

```bash
curl -X GET -G "http://localhost/api/businesses/1" 
```
```javascript
const url = new URL("http://localhost/api/businesses/1");

    let params = {
            "$business": "quam",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 1,
    "name": "业务名称",
    "parent_id": 0,
    "created_at": "2019-06-12 07:48:10",
    "updated_at": "2019-06-12 07:48:10"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`GET api/businesses/{business}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    $business |  required  | 业务id

<!-- END_60667ab9f3fcaa4b3e45a5a70d1ac187 -->

<!-- START_8fb32a7b7685cfca20e69c233264dc7b -->
## 更新00104

> Example request:

```bash
curl -X PUT "http://localhost/api/businesses/1" \
    -H "Content-Type: application/json" \
    -d '{"parent_id":"nesciunt","name":"voluptas","status":4}'

```
```javascript
const url = new URL("http://localhost/api/businesses/1");

    let params = {
            "business": "sit",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "parent_id": "nesciunt",
    "name": "voluptas",
    "status": 4
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 1,
    "name": "业务名称",
    "parent_id": 0,
    "created_at": "2019-06-12 07:48:10",
    "updated_at": "2019-06-12 07:48:10"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`PUT api/businesses/{business}`

`PATCH api/businesses/{business}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    parent_id | required |  optional  | 主业务id
    name | string |  required  | 业务名称
    status | integer |  required  | 业务状态
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    business |  required  | 业务id

<!-- END_8fb32a7b7685cfca20e69c233264dc7b -->

<!-- START_bf904d94a438825c3022b24f421a5c73 -->
## 删除00105

> Example request:

```bash
curl -X DELETE "http://localhost/api/businesses/1" 
```
```javascript
const url = new URL("http://localhost/api/businesses/1");

    let params = {
            "business": "sapiente",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{}
```

### HTTP Request
`DELETE api/businesses/{business}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    business |  required  | 业务id

<!-- END_bf904d94a438825c3022b24f421a5c73 -->

#公司组织架构002
<!-- START_cea28ae409b72696459b9f05c16a473b -->
## 列表00201

> Example request:

```bash
curl -X GET -G "http://localhost/api/companyOrganizes" 
```
```javascript
const url = new URL("http://localhost/api/companyOrganizes");

    let params = {
            "parent_id": "consequatur",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`GET api/companyOrganizes`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    parent_id |  optional  | 父组织id，默认为0

<!-- END_cea28ae409b72696459b9f05c16a473b -->

<!-- START_b6457bc00950b47eefe88b5479b730a5 -->
## 插入00202

> Example request:

```bash
curl -X POST "http://localhost/api/companyOrganizes" \
    -H "Content-Type: application/json" \
    -d '{"parent_id":13,"name":"incidunt"}'

```
```javascript
const url = new URL("http://localhost/api/companyOrganizes");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "parent_id": 13,
    "name": "incidunt"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 4,
    "name": "组织架构名称",
    "created_at": "生成时间",
    "updated_at": "修改时间",
    "status": "0-禁止1-启用"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`POST api/companyOrganizes`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    parent_id | integer |  optional  | 父组织id，默认为0
    name | string |  required  | 组织架构名称

<!-- END_b6457bc00950b47eefe88b5479b730a5 -->

<!-- START_63de0012ccb9c935ca745433bf8b13fd -->
## 详情页00203

> Example request:

```bash
curl -X GET -G "http://localhost/api/companyOrganizes/1" 
```
```javascript
const url = new URL("http://localhost/api/companyOrganizes/1");

    let params = {
            "companyOrganize": "natus",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 4,
    "name": "组织架构名称",
    "created_at": "生成时间",
    "updated_at": "修改时间"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`GET api/companyOrganizes/{companyOrganize}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    companyOrganize |  required  | 组织架构id

<!-- END_63de0012ccb9c935ca745433bf8b13fd -->

<!-- START_103cb7d0173c7023386bddae7fbb7ecd -->
## 更新00204

> Example request:

```bash
curl -X PUT "http://localhost/api/companyOrganizes/1" 
```
```javascript
const url = new URL("http://localhost/api/companyOrganizes/1");

    let params = {
            "companyOrganize": "unde",
            "parent_id": "dolorum",
            "name": "iure",
            "status": "officiis",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 4,
    "parent_id": 1,
    "name": "组织架构名称",
    "status": 1,
    "created_at": "生成时间",
    "updated_at": "修改时间"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`PUT api/companyOrganizes/{companyOrganize}`

`PATCH api/companyOrganizes/{companyOrganize}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    companyOrganize |  required  | 组织架构id
    parent_id |  required  | 组织架构父id
    name |  required  | 组织架构名称
    status |  optional  | 组织架构状态 0-禁止1-启用

<!-- END_103cb7d0173c7023386bddae7fbb7ecd -->

<!-- START_131c199c6f84620824188d838a7aa055 -->
## 删除002005

> Example request:

```bash
curl -X DELETE "http://localhost/api/companyOrganizes/1" 
```
```javascript
const url = new URL("http://localhost/api/companyOrganizes/1");

    let params = {
            "companyOrganize": "fugit",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 4,
    "name": "组织架构名称",
    "created_at": "生成时间",
    "updated_at": "修改时间"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`DELETE api/companyOrganizes/{companyOrganize}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    companyOrganize |  required  | 组织架构id

<!-- END_131c199c6f84620824188d838a7aa055 -->

#清算公司003
<!-- START_cb737b6049300a75810776fb95ea1b31 -->
## 列表页00301

> Example request:

```bash
curl -X GET -G "http://localhost/api/clearCompanys" 
```
```javascript
const url = new URL("http://localhost/api/clearCompanys");

    let params = {
            "page": "perferendis",
            "per_page": "hic",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "data": [
        {
            "id": 4,
            "name": "清算公司名称",
            "status": "0-禁止1-启用",
            "created_at": "生成时间",
            "updated_at": "修改时间"
        }
    ],
    "links": {
        "first": "http:\/\/demu.tao3w.com\/api\/ports?page=1",
        "last": "http:\/\/demu.tao3w.com\/api\/ports?page=5",
        "prev": null,
        "next": "http:\/\/demu.tao3w.com\/api\/ports?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "path": "http:\/\/demu.tao3w.com\/api\/ports",
        "per_page": 10,
        "to": 10,
        "total": 49
    }
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`GET api/clearCompanys`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | 第几页，默认第一页
    per_page |  optional  | 每页记录数，默认是10

<!-- END_cb737b6049300a75810776fb95ea1b31 -->

<!-- START_7e3d3f55e5708b130d1ca2cf59379108 -->
## 插入00302

> Example request:

```bash
curl -X POST "http://localhost/api/clearCompanys" \
    -H "Content-Type: application/json" \
    -d '{"id":15}'

```
```javascript
const url = new URL("http://localhost/api/clearCompanys");

    let params = {
            "name": "excepturi",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "id": 15
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`POST api/clearCompanys`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    id | integer |  optional  | 清算公司id
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    name |  required  | 组织架构名称

<!-- END_7e3d3f55e5708b130d1ca2cf59379108 -->

<!-- START_ed3489ddf8931fc34497b4a5d562c3fa -->
## 详情页00303

> Example request:

```bash
curl -X GET -G "http://localhost/api/clearCompanys/1" \
    -H "Content-Type: application/json" \
    -d '{"id":19,"name":"a","created_at":"similique","updated_at":"laboriosam"}'

```
```javascript
const url = new URL("http://localhost/api/clearCompanys/1");

    let params = {
            "id": "vero",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "id": 19,
    "name": "a",
    "created_at": "similique",
    "updated_at": "laboriosam"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 4,
    "name": "清算公司名称",
    "created_at": "生成时间",
    "updated_at": "修改时间"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`GET api/clearCompanys/{clearCompany}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    id | integer |  required  | 清算公司id
    name | string |  required  | 清算公司名字
    created_at | string |  required  | 生成时间
    updated_at | string |  required  | 修改时间
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    id |  optional  | 清算公司id

<!-- END_ed3489ddf8931fc34497b4a5d562c3fa -->

<!-- START_2d17531107a4e1ca0e4db9e2e1334a8b -->
## 更新00304

> Example request:

```bash
curl -X PUT "http://localhost/api/clearCompanys/1" \
    -H "Content-Type: application/json" \
    -d '{"id":7,"name":"ipsum","created_at":"sapiente","updated_at":"beatae"}'

```
```javascript
const url = new URL("http://localhost/api/clearCompanys/1");

    let params = {
            "id": "delectus",
            "name": "nam",
            "status": "quasi",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "id": 7,
    "name": "ipsum",
    "created_at": "sapiente",
    "updated_at": "beatae"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 4,
    "name": "清算公司名称",
    "created_at": "生成时间",
    "updated_at": "修改时间"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`PUT api/clearCompanys/{clearCompany}`

`PATCH api/clearCompanys/{clearCompany}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    id | integer |  required  | 组织id
    name | string |  required  | 组织名字
    created_at | string |  required  | 生成时间
    updated_at | string |  required  | 修改时间
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    id |  optional  | 组织架构id
    name |  optional  | 组织架构名称
    status |  optional  | 组织架构状态 -1-删除0-禁止1-启用

<!-- END_2d17531107a4e1ca0e4db9e2e1334a8b -->

<!-- START_7720e43e1c50e280793a3673cd51bc82 -->
## 删除00305

> Example request:

```bash
curl -X DELETE "http://localhost/api/clearCompanys/1" 
```
```javascript
const url = new URL("http://localhost/api/clearCompanys/1");

    let params = {
            "clearCompany": "eius",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 4,
    "name": "清算公司名称",
    "created_at": "生成时间",
    "updated_at": "修改时间"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`DELETE api/clearCompanys/{clearCompany}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    clearCompany |  required  | 清算公司id

<!-- END_7720e43e1c50e280793a3673cd51bc82 -->

#港口004
Class PortController
<!-- START_aacf9b8d5ef419a7d78159d8b3b1db0a -->
## 列表页00401

> Example request:

```bash
curl -X GET -G "http://localhost/api/ports" 
```
```javascript
const url = new URL("http://localhost/api/ports");

    let params = {
            "page": "in",
            "per_page": "doloremque",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "data": [
        {
            "id": 4,
            "name": "港口名称",
            "address": "港口地址",
            "status": "0-禁止1-启用",
            "created_at": "生成时间",
            "updated_at": "修改时间"
        }
    ],
    "links": {
        "first": "http:\/\/demu.tao3w.com\/api\/ports?page=1",
        "last": "http:\/\/demu.tao3w.com\/api\/ports?page=5",
        "prev": null,
        "next": "http:\/\/demu.tao3w.com\/api\/ports?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "path": "http:\/\/demu.tao3w.com\/api\/ports",
        "per_page": 10,
        "to": 10,
        "total": 49
    }
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`GET api/ports`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | 第几页，默认第一页
    per_page |  optional  | 每页记录数，默认是10

<!-- END_aacf9b8d5ef419a7d78159d8b3b1db0a -->

<!-- START_81a7b2f4f85ef286c2d54f17e04420dd -->
## 插入00402

> Example request:

```bash
curl -X POST "http://localhost/api/ports" \
    -H "Content-Type: application/json" \
    -d '{"name":"quaerat","address":"quasi"}'

```
```javascript
const url = new URL("http://localhost/api/ports");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "name": "quaerat",
    "address": "quasi"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{
    "id": 4,
    "name": "港口名称",
    "address": "港口地址",
    "created_at": "生成时间",
    "updated_at": "修改时间",
    "status": "0-禁止1-启用"
}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`POST api/ports`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | 港口名称
    address | string |  required  | 港口地址

<!-- END_81a7b2f4f85ef286c2d54f17e04420dd -->

<!-- START_51aeebc5a8870408033bbcbc01dff978 -->
## 详情页00303

> Example request:

```bash
curl -X GET -G "http://localhost/api/ports/1" 
```
```javascript
const url = new URL("http://localhost/api/ports/1");

    let params = {
            "id": "voluptas",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (200):

```json
{}
```
> Example response (404):

```json
{
    "message": "No query results"
}
```

### HTTP Request
`GET api/ports/{port}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    id |  optional  | 港口id

<!-- END_51aeebc5a8870408033bbcbc01dff978 -->

<!-- START_cb1331cea9c387c59b714749648657e5 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/ports/1" 
```
```javascript
const url = new URL("http://localhost/api/ports/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`PUT api/ports/{port}`

`PATCH api/ports/{port}`


<!-- END_cb1331cea9c387c59b714749648657e5 -->

<!-- START_a4e450b2c1696ca6ca044fea7ebd2240 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/ports/1" 
```
```javascript
const url = new URL("http://localhost/api/ports/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE api/ports/{port}`


<!-- END_a4e450b2c1696ca6ca044fea7ebd2240 -->


