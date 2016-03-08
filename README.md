# pago-facil-crud


Este repo es un CRUD de usuarios hecho para la prueba de pago facil.

Framework utilizado Symfony, para instalar utilizar composer.phar install.

Se crearon la urls para

POST api/login   -> logearse
POST api/user    -> crear usuario
GET /api/user    -> obtener listado de usuarios
PUT /api/user/id  -> actualizar usuario
DELETE /api/user/id -> borrar usuario

Los campos para el user son
- id
- email
- password
- is_active
- token

Se creo un intefaz single page escrita en jquery y handlerbars

Se incluyo un sistema de autenticacion basado en token.


