# Wallet App

Небольшое приложение на Laravel для управления балансом пользователей через HTTP API.

## Функционал

* Пополнение баланса пользователя (`/api/deposit`)
* Списание средств (`/api/withdraw`)
* Перевод между пользователями (`/api/transfer`)
* Получение текущего баланса (`/api/balance/{user_id}`)

Все ответы возвращаются в формате JSON с корректными HTTP-кодами:

* **200** — успешный ответ
* **400 / 422** — ошибки валидации
* **404** — пользователь не найден
* **409** — конфликт (например, недостаточно средств)

---

## Технологии

* PHP 8+
* Laravel 12
* PostgreSQL
* Docker & Docker Compose

---

## Быстрый старт через Docker

1. Клонируем репозиторий:

```bash
git clone <your-repo-url>
cd wallet-app
```

2. Создаем `.env` из шаблона:

```bash
cp .env.example .env
```

3. Собираем и запускаем контейнеры:

```bash
docker-compose build
docker-compose up -d
```

4. Выполняем миграции и сиды:

```bash
docker-compose exec app php artisan migrate --seed
```

5. Приложение доступно по адресу:

```
http://localhost:8000
```

---

## Настройка `.env.example`

```dotenv
APP_NAME=WalletApp
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=wallet_db
DB_USERNAME=wallet
DB_PASSWORD=secret
```

> После клонирования репозитория нужно скопировать `.env.example` в `.env` и при необходимости настроить свои параметры.

---

## API эндпоинты

### 1. Пополнение средств

```
POST /api/deposit
```

**Body (JSON):**

```json
{
  "user_id": 1,
  "amount": 500.00,
  "comment": "Пополнение через карту"
}
```

**Response (JSON):**

```json
{
  "user_id": 1,
  "balance": 500.00,
  "message": "Deposit successful"
}
```

---

### 2. Списание средств

```
POST /api/withdraw
```

**Body (JSON):**

```json
{
  "user_id": 1,
  "amount": 200.00,
  "comment": "Покупка подписки"
}
```

**Response (JSON):**

```json
{
  "user_id": 1,
  "balance": 300.00,
  "message": "Withdraw successful"
}
```

---

### 3. Перевод между пользователями

```
POST /api/transfer
```

**Body (JSON):**

```json
{
  "from_user_id": 1,
  "to_user_id": 2,
  "amount": 150.00,
  "comment": "Перевод другу"
}
```

**Response (JSON):**

```json
{
  "from_user_id": 1,
  "to_user_id": 2,
  "amount": 150.00,
  "sender_balance": 150.00,
  "recipient_balance": 150.00,
  "message": "Transfer successful"
}
```

---

### 4. Получение баланса пользователя

```
GET /api/balance/{user_id}
```

**Response (JSON):**

```json
{
  "user_id": 1,
  "balance": 350.00
}
```

---

## Примечания

* Баланс не может быть отрицательным.
* Все денежные операции выполняются в транзакциях.
* Если у пользователя нет записи о балансе — она создаётся при первом пополнении.
* Транзакции имеют следующие типы: `deposit`, `withdraw`, `transfer_in`, `transfer_out`.
* Для работы проекта через Docker **не требуется устанавливать PHP или PostgreSQL локально**.

---

## Gitignore / файлы, которые не нужно пушить

```
*.log
.DS_Store
.env
.env.backup
.env.production
.phpactor.json
.phpunit.result.cache
/.fleet
/.idea
/.nova
/.phpunit.cache
/.vscode
/.zed
/auth.json
/node_modules
/public/build
/public/hot
/public/storage
/storage/*.key
/storage/pail
/vendor
Homestead.json
Homestead.yaml
Thumbs.db
```

---

## Автор
Erik Vartanov
* Разработано на Laravel 12 с использованием Docker и PostgreSQL
