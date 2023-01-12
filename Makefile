# Create database
createDatabase:
	# TODO Where is database running, how to change location of database, how to connect into database, how to insert data into database
	podman run src/backend/client --name db --env MARIADB_ROOT_PASSWORD=admin --env MARIADB_USER=admin --env MARIADB_PASSWORD=admin --env MARIADB_DATABASE=db -p 3306:3306 mariadb:latest

# Kill and delete database
cleanDatabase:
	podman kill db
	podman rm db

docBuild:
	latexmk -pdf doc/latex/documentary.tex

docClean:
	rm -f documentary.aux
	rm -f documentary.fdb_latexmk
	rm -f documentary.fls
	rm -f documentary.lof
	rm -f documentary.log
	rm -f documentary.lot
	rm -f documentary.out
	rm -f documentary.toc
	rm -f doc/latex/*.aux
	rm -f doc/latex/modules/*.aux

doc: docBuild docClean openDoc

clean: docClean

# Jus for now
openDoc:
	evince documentary.pdf

pack: packClean
	mkdir handle
	cp  js/client.js handle
	cp jquery/jquery-3.6.1.min.js handle
	cp css/client.css handle
	cp client/index.html handle
	cp imgs/home.svg handle

packClean:
	rm -rf handle