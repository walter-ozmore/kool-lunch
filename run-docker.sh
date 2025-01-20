docker build -t apache-php .
DIR=$(pwd)
echo $DIR
docker run -p 80:80 -v $DIR:/var/www/html --name kool-lunches apache-php
docker rm kool-lunches