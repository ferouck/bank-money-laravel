<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Sobre o projeto

O projeto baseia-se em um API restful, focada na simulação de uma transação entre usuários, contando com método de cadastro, login, autenticação e transferência de saldo entre os mesmos, são utilizados neste sistemas as seguintes linguagens, frameworks e pacotes:

- [Docker](https://www.docker.com).
- [Laravel v9](https://laravel.com/docs/9.x).
- [Laravel Eloquent ORM](https://laravel.com/docs/9.x/eloquent#retrieving-or-creating-models).
- [Laravel JWT](https://github.com/tymondesigns/jwt-auth).
- [Laravel Queue](https://laravel.com/docs/9.x/queues).
- [Laravel Sail](https://laravel.com/docs/9.x/sail).
- [JWT](https://jwt.io).
- [MySQL v8](https://www.mysql.com).
- [PHP v8](https://www.php.net).


Além destes o aplicativo também contém outras funcionalidades importantes providas pelo framework, como controle de rotas, middlewares e retorno de exceptions.

## Estrutura

O projeto segue como estrutura alguns conceitos, como o [repository pattern](https://renicius-pagotto.medium.com/entendendo-o-repository-pattern-fcdd0c36b63b), organização de repositórios que realizam a consulta no banco de dados através dos models e também sendo "chamados" pela interface criada para cada repositório.

Exemplo de uma interface, com funções criadas de acordo com a necessidade do projeto, sendo possivel repeti-las a qualquer momento e em qualquer escopo, não gerando repetições de querys, dificil manutenção e outros erros da má utilização de recursos.
````
interface TransferRepositoryInterface
{
    public function getTransferByUuid($uuId);
    public function getAllTransferByUserId($userId);
    public function getProtocolTransferById($id);
    public function deleteTransferByProtocol($protocol);
    public function createTransfer(array $transferData);
    public function updateTransferByProtocol($protocol, array $transferData);
}
````

Além desta é utilizada o service pattern, onde são divididas o controle da requisição, como validação dos campos, retornos de dados e etc no controller, e os services sendo aplicadas as regras de negócios, comunicação com as interfaces, request externas (authorization) e inicio de jobs, dentro de cada service é organizado de maneira que cada função tenha propósitos curtos, objetivos e de fácil execução, seguindo como [design pattern](https://www.opus-software.com.br/design-patterns/) o [SOLID](https://medium.com/desenvolvendo-com-paixao/o-que-é-solid-o-guia-completo-para-você-entender-os-5-princípios-da-poo-2b937b3fc530).

Como no trecho de código abaixo onde está função é utilizada para obter a estrutura de dados que serão inseridos no banco de dados, a própria inserção, atualização da transferência e disparo do job para notificação.

````
    public function registerBalanceToPayee($userId, $value, $protocol)
    {
        $dataPayee = $this->makePayeeData($userId, $value, $protocol);
        $this->insertBalance($dataPayee);
        $this->updateStatusTransfer($protocol);
        NotificationPayee::dispatch();
    }
````
O projeto contém uma estrutura de [jobs](https://laravel.com/docs/9.x/queues) utilizada para a geração da notificação no momento de conclusão da transação.

O mesmo possui também [Traits](https://www.treinaweb.com.br/blog/quando-usar-traits-no-php), sendo necessários no tratamento das exception e padronização nos retornos disponiveis pela aplicação.

Exemplificando abaixo, está são as funções utilizadas para retorno em caso de erro ou sucesso de uma request, para evitar as vãs repetições é criado esse arquivo e replicado nos controllers para usar estas mesmas funções. 

````
    protected function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status'=> 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function errorResponse($message = null, $code)
	{
		return response()->json([
			'status'=>'Error',
			'message' => $message,
			'data' => null
		], $code);
	}
````

## Execução

Para rodar o projeto é bem simples, como o mesmo conta com o uso do docker e também do pacote disponilizado do laravel [sail](https://laravel.com/docs/9.x/sail), há duas maneiras de se executar o mesmo.

#### Docker

Na execução através do docker é bem simples uma vez com o [docker instalado](https://docs.docker.com/get-docker/), basta acessar a raiz do projeto e executar os seguintes comandos:

````
docker-compose build 
````
````
docker-compose up -d
````
O primeiro comando irá buildar as imagens necessárias para que o projeto possa rodar, já o segundo irá levantar os containers com essas imagens para que a aplicação se comunique com as mesmas.

#### Sail

Já utilizando o sail é necessário já ter instalado o pacote do projeto [sail](https://github.com/laravel/sail), finalizado a instalação pode-se executar os seguintes comandos:

````
sail build
````

````
sail up -d
````

Recomenda-se criar um alias para utilizar o comando sail desta maneira.


## Rotas

Todas as rotas deste projeto possuem um prefixo antes "api/v1", são estas as rotas disponiveis:
````
POST user/register/

POST auth/login/ 
POST auth/logout/ nessária autenticação

POST transfer/make/ nessária autenticação
````

## Modelos payload

Cadastro usuário:
````
{
    "name": string,
    "email": string,
    "cpf_cnpj": string,
    "type": string [client, shop],
    "password": string
}
````
Login:
````
{
    "email": string,
    "password": string
}
````
Criar transferencia (necessário bearer token):
````
{
    "payee": int,
    "value": float (ex 10.10)
}
````

### Links uteis

- **[Repository Pattern Laravel](https://www.twilio.com/blog/repository-pattern-in-laravel-application)**
- **[Service Patter Laravel](https://medium.com/levantelab/repository-pattern-contracts-e-service-layer-no-laravel-6-670aa9f50173)**
- **[Traits PHP](https://www.treinaweb.com.br/blog/quando-usar-traits-no-php)**
- **[Conceitos Solid](https://medium.com/desenvolvendo-com-paixao/o-que-é-solid-o-guia-completo-para-você-entender-os-5-princípios-da-poo-2b937b3fc530)**
- **[Retorno de exceptions Laravel API](https://laracasts.com/discuss/channels/code-review/best-way-to-handle-rest-api-errors-throwed-from-controller-or-exception)**
- **[Laravel Queue](https://laravel.com/docs/9.x/queues)**
- **[Guzzle Http](https://docs.guzzlephp.org/en/latest/overview.html#requirements)**
