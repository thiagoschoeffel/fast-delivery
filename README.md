# Fast Delivery

App em PHP/CodeIgniter 3 para gestão de pedidos e entregas de fast food — com cadastro de produtos/clientes, acompanhamento de produção/embalagem/entrega, painel de pedidos em tempo real e relatórios básicos.

<img width="1420" height="774" alt="pagina inicial" src="https://github.com/user-attachments/assets/f71065da-fc96-4e30-8689-e82b07652c26" />

## 🚀 Stack
- PHP (CodeIgniter 3)
- Docker & Docker Compose (execução local)
- MySQL (via serviço do Compose)

## 📦 Rodando com Docker

### Pré-requisitos
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/)
- [NodeJS/npm](https://nodejs.org/en)

### Passos rápidos

```bash
# 1) Clonar o repositório e entrar na pasta do projeto
git clone https://github.com/thiagoschoeffel/business-control.git
cd business-control

# 2) Instalar as dependências do JavaScript
npm install

# 3) Subir os serviços (web + db)
docker compose up -d --build
```

Quando subir, acesse:

- App: [http://localhost:8000](http://localhost:8000)
- Banco: host/porta/credenciais definidos no ```docker-compose.yml```

ℹ️ Por padrão a porta configurada para o app é 8000 e banco de dados 8001 no ```docker-compose.yml```, ajuste conforme sua necessidade.

> Dica: no diretório docker-compose/mysql/ existe um script de inicialização, ele cria e popula o banco no primeiro start (verifique e ajuste conforme necessidade).

### Comandos úteis

Abaixo alguns comandos úteis para controlar os containers da aplicação.

```bash
# Parar
docker compose stop

# Subir novamente
docker compose up -d

# Derrubar tudo (remove os containers e os dados criados são apagados)
docker compose down
```

⚠️ Toda vez que os contâiners são removidos os dados que foram criados são apgados, não estamos usando volumes nesse ```docker-compose.yml```, então cuidado!

### Estrutura

- ```www/``` – código da aplicação (CodeIgniter 3)
- ```docker-compose.yml``` – orquestração dos serviços (web/db)
- ```Dockerfile``` – imagem da aplicação PHP/Apache (ou equivalente)
- ```docker-compose/mysql/``` – init scripts/seed do MySQL (se aplicável)

## 📄 Licença

**Sem licença (No license).** 

Este repositório é disponibilizado apenas para **visualização**. **Não é permitido** usar, copiar, modificar ou distribuir o código sem autorização **por escrito** do autor. 

Todos os direitos reservados.

