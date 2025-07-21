docker compose up -d

docker exec -t php82 composer run test
docker exec -t php83 composer run test
docker exec -t php84 composer run test
