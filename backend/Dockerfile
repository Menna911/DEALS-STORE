FROM php:8.3-cli-alpine

RUN docker-php-ext-install pdo_mysql 

WORKDIR /app

RUN addgroup -S -g 1001 phpgroup && adduser -u 1001 -S -G phpgroup phpuser

COPY . .

USER phpuser

EXPOSE 8000

CMD [ "php" , "-S" , "0.0.0.0:8000" ]