#!/bin/bash

source_dir="CEI-Reg"
destination_dir="data"

if ! [ -d "$destination_dir" ]; then
	mkdir "$destination_dir"
fi

for dir in "$source_dir"/*
do
	if [ -d "$dir" ]; then
		for file in "$dir"/*
		do
			if [ -f "$file" ]; then
				base=`basename $file`
				echo "$file"
				java -jar xalan-j_2_7_1/xalan.jar -IN "$file" -XSL info_extraction.xsl -OUT "$destination_dir/$base"
			fi
		done
	fi
done
