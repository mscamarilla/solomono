### usage
- copy all repo to your computer
- run 
  
  `docker-compose up -d`
  
  `docker-compose exec php sh`
  
  `composer install`
- go to localhost:8081
- enjoy.

Notice: if you have a problem with db connect on launch, check if file /application/config.ini is available and readable. You may need to wait a few minutes for all files to be indexed.

### Задание 1
За основу движка был взят мой mvc отсюда: https://github.com/mscamarilla/mvc и это не должно попадать под условие не использовать фреймворки. За верстку отвечает bootstrap.

Файлы, касающиеся задания, это *Index.???, *Category.??? и *Product.???, остальные - движок.

По умолчанию со страта запускается ControllerIndex с методом Index. Он проверяет, существует ли в бд таблица category. Если нет - создает и наполняет ее данными из файла "dataset.txt".

На главной странице выводятся все категории и все товары. При сортировке или фильтрации, отправляется AJAX запрос в метод actionReload контроллера ControllerIndex. Все изменение данных на странице происходит без перезагрузки, URL страницы меняется с помощью History API HTML5.

Использовано: php, mysql, HTML5, js, jQuery, Ajax, json, bootstrap 5.1.3

Задание заняло 12,5 часов

### Задание 2
Файл /task2.php - цельный скрипт. Импортирует данные из файла test.sql в отдельную базу, не связанную с первым заданием, выводит на страницу время, потраченное на импорт данных. Далее забирает эти данные с бд, рекурсивно строит дерево каталогов, ниже выводит время, потраченное от начала выборки строк из бд до вывода последней сттроки дерева. У меня это значение не превышало 1,3 секунды.

Запуск: localhost:8081/task2.php

Задание заняло 2 часа.