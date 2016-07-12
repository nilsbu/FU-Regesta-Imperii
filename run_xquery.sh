#!/bin/sh

# requires saxon 9 (obviousely)
java -Xmx8192M -classpath "saxon9he.jar" net.sf.saxon.Query -q:names.xquery > data/names.xml
