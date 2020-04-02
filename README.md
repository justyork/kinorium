## 1. Отличаете ли вы Тома Круза от Тома Хэнкса?
Да! Первый снимался в Мисси Невыполнима, второй во многих других интересных фильмах, мои любимые Форест Гамп и Терминал.
## 2. Покажите пример своего кода (PHP)
Ссылка на репозиторий с проектом
https://github.com/justyork/words

Весь бекенд был на мне, на втором фронтенд тоже.
https://www.prime-property.com/
http://bazar-cy.com/

Портфолио
http://web-y.ru/portfolio

## 3. PHP
1. https://github.com/justyork/db
2. [HTML selectbox](https://github.com/justyork/kinorium/html)
3. 
```
/data-id=[\'\"]+(\d+)[\'\"]+ data-type=[\'\"]+film.*\<h3>([^<]+)<.*<h4>([^<]+)</Usi
```

## 3. MYSQL
Нужно сделать два запроса:
 
* 10 фильмов без фото
```SQL
SELECT * FROM movie 
WHERE movie_id NOT IN (SELECT movie_id FROM pictures) 
LIMIT 10;
```
* количество фильмов без фото
```SQL
SELECT COUNT(*) FROM movie 
WHERE movie_id NOT IN (SELECT movie_id FROM pictures);
```

### 5. Тест задание JavaScript: Написать раскрывающееся дерево любым способам
http://kinorium.web-y.ru/
## 6. Все собрать в работающую страницу, выводящую дерево с перечнем фильмов с кадрами и без
Написать раскрывающееся дерево любым способам
http://kinorium.web-y.ru/
