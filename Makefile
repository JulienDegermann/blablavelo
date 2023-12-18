SHELL := /bin/bash

cities:
	curl -L -o import/cities.csv https://www.data.gouv.fr/fr/datasets/r/51606633-fb13-4820-b795-9a2a575a72f1
	symfony console app:import-cities
.PHONY: cities