# Select выборки с кешированием по отдельным таблицам

## Запуск сборки
 
- `cp .env.dist .env`  
- `docker-compose up -d --build`
- `docker exec -it api_php composer create-project --prefer-dist yiisoft/yii2-app-basic basic`

Команда клонирует yii в папку basic, а надо в корень src. Или переделать команду, или руками вынести файлы Yii в корень src

Запуск тестов:

docker exec -it api_php vendor/bin/codecept run unit

Запустить отдельный тест

docker exec -it api_php vendor/bin/codecept run unit orm/OrmTest.php

Миграции

docker exec -it api_php php yii migrate
