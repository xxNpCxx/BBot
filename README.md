# BBot

# **Инструкция по установке**

`cp .env.dev .env`

modify `vi .env`

run `docker-compose build`

run `docker-compose up -d`

use followings endpoints to check works

http://localhost:8081  // Веб субд для mongodb

# **Инструкция по работе**

- Провайдер служит для трансляции данных с биржи на ipc сокет, к которому могут подключиться как индикаторы так и коллекторы

- Коллектор служит для сохранения полученных данных из ipc сокета в хранилище ( покачто это mongodb )

- Индикатор ( в разработке )

```bash
php collector.php type=bookTicker mainSymbol=xrp quoteSymbol=btc exchange=binance
```
```bash
php provider.php type=bookTicker mainSymbol=xrp quoteSymbol=btc exchange=binance
```
--
```bash
php collector.php type=bookTicker mainSymbol=eos quoteSymbol=btc exchange=binance
```
```bash
php provider.php type=bookTicker mainSymbol=eos quoteSymbol=btc exchange=binance
```
--
```bash
php collector.php type=bookTicker mainSymbol=matic quoteSymbol=btc exchange=binance
```
```bash
php provider.php type=bookTicker mainSymbol=matic quoteSymbol=btc exchange=binance
```

#### **Примеры коллекций сохраняемых в хранилище mongodb** :

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