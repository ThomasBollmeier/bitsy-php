#!/bin/bash

generate="php ../vendor/tbollmeier/parsian/scripts/parsiangen.php"

$generate \
	--parser=BaseParser \
	--namespace=tbollmeier\\bitsy \
	--out=../src/BaseParser.php \
	bitsy.parsian