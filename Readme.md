![Capa QueryBuilder PHP+SQL](https://i.ibb.co/VqvSHhj/g877.png)

### Classe desenvolvida para estudo da linguagem e aplicação da lógica usada pelos Query Builders.
#### Técnologias utilizadas:
<br>

* PHP 7.2

<br>
Toda a lógica da classe envolve utilizar o método mágico `__call()` (nativo no php) para gerar instruções SQL dinâmicamente.

<br><br>

__*Todos os métodos da classe retornam a string de uma query em SQL*__

#### Exemplos:

Listagem de dados
```php

$querybuilder = new QueryBuilder()

$querybuilder
        ->table('users')
        ->fields('*')
        ->loadAll();

//Retorna: SELECT * FROM users

```

```php

$querybuilder
        ->table('users')
        ->fields(['name','age'])
        ->where('login')
        ->list('josh');

//Retorna: SELECT name, age FROM users WHERE login = josh

```

Inserção de dados
```php

$querybuilder
        ->table('users')
        ->fields(['name','age'])
        ->values(['josh',25])
        ->insert();

//Retorna: INSERT INTO users (name, age) VALUES ('josh',25)

```

Atualização de dados
```php

$querybuilder
        ->table('users')
        ->id(2)
        ->fields(['name','age'])
        ->update('joshua',32);

//Retorna: UPDATE users set name = 'joshua', age = 32 WHERE id = 2

```

Deletar dados

```php

$querybuilder
        ->table('users')
        ->id(2)
        ->delete()

//Retorna: DELETE users WHERE id = 2

```



