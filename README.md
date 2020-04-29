# BBot

# **Инструкция по установке**

Клонируем себе репозиторий:

`git clone https://github.com/xxNpCxx/BBot.git` 

Переходим в папку с только что склонированным проектом

`cd BBot`

Создаем файл окружения .env на основе .env.dev

`cp .env.dev .env`

Редактируем файл окружения

`vi .env`

 Запускаем билд контейнеров
 
`docker-compose build`

Запускаем контейнеры в фоне

`docker-compose up -d`

Заходим внутрь контейнера (alpine)

`docker-compose exec php_data_collector bash`

Выполняем установку зависимостей

`composer install`

Проверяем доступность веб интерфейса для работы с хранилицем mongodb http://localhost:8081

# **Инструкция по работе**

- Провайдер служит для трансляции данных с биржи на ipc сокет, к которому могут подключиться как индикаторы так и коллекторы

- Коллектор служит для сохранения полученных данных из ipc сокета в хранилище ( покачто это mongodb )

- Индикатор нужен для того чтобы посылать события о состоянии условий индикатора связанного со стратегией

```bash
php collector.php type=bookTicker mainSymbol=xrp quoteSymbol=btc exchange=binance
```
```bash
php indicator.php type=bookTicker mainSymbol=xrp quoteSymbol=btc exchange=binance
```
```bash
php provider.php type=bookTicker mainSymbol=xrp quoteSymbol=btc exchange=binance
```

## **Примеры коллекций сохраняемых в хранилище mongodb** :

Коллекция [**binance_bookTicker_xrpbtc**] :
```json
{
   "_id":{
      "$oid":"HEX ID выставленный базой данных"
   },
   "t":"Микросекунда float в которую мы получили данные",
   "data":{
      "u":"момент времени на бирже",
      "s":"символ",
      "b":"best bid price",              
      "B":"best bid qty",              
      "a":"best ask price",              
      "A":"best ask qty"   
   }
}
```
```json
{
   "_id":{
      "$oid":"5ea774f7083d4d19bb630403"
   },
   "t":1588032759.843748,
   "data":{
      "u":607867034,
      "s":"XRPBTC",
      "b":"0.00002541",
      "B":"509.00000000",
      "a":"0.00002542",
      "A":"10338.00000000"
   }
}
```
