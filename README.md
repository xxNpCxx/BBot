# BBot

`cp .env.dev .env`

modify `vi .env`

run `docker-compose build`

run `docker-compose up -d`

use followings endpoints to check works

http://localhost:8081  // Веб субд для mongodb

```
binance:{                                              //Биржа коллекция в монге
    "USDTBTC":{                                        //Пара
        "p":{                                          //Структура изменения цены в реальном времени
                                                       // https://binance-docs.github.io/apidocs/spot/en/#individual-symbol-book-ticker-streams
                    "id":{                             //Порядковый уникальный номер изменения (НАШ)
                          "t":1231231233,              // Момент в который мы получили данные
                          "e":{
                                "b":"25.35190000",              // best bid price
                                "B":"31.21000000",              // best bid qty
                                "a":"25.36520000",              // best ask price
                                "A":"40.66000000"               // best ask qty
                          }
                    }
        },
        "o":{  //Ордербук 
            // https://binance-docs.github.io/apidocs/spot/en/#all-book-tickers-stream
          
        },
        "k":{  //Свечи
            //https://binance-docs.github.io/apidocs/spot/en/#kline-candlestick-streams
        },
        "at":{ // Когда у торговца закрылся ордер.  1 или больше покупателей/продавцов одного ордера
        // https://binance-docs.github.io/apidocs/spot/en/#aggregate-trade-streams
        }
        "t":{ // 1 покупатель 1 продавец. 1 конкретная сделка.
           //https://binance-docs.github.io/apidocs/spot/en/#trade-streams
        }
}
```



```bash
php collector.php type=bookTicker mainSymbol=btc quoteSymbol=xrp exchange=binance
```
```bash
php provider.php type=bookTicker mainSymbol=btc quoteSymbol=xrp exchange=binance
```
- 
```bash
php collector.php type=bookTicker mainSymbol=btc quoteSymbol=eos exchange=binance
```
```bash
php provider.php type=bookTicker mainSymbol=btc quoteSymbol=eos exchange=binance
```
-
```bash
php collector.php type=bookTicker mainSymbol=btc quoteSymbol=matic exchange=binance
```
```bash
php provider.php type=bookTicker mainSymbol=btc quoteSymbol=matic exchange=binance
```
