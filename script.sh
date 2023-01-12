podman rm -f db
podman build -t mydb .
podman run --name db -p 3306:3306 -d mydb:latest