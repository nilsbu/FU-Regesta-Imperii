import xml.etree.ElementTree as ET
from SPARQLWrapper import SPARQLWrapper, JSON
import json
import operator

sparql = SPARQLWrapper("http://dbpedia.org/sparql")

count = {}

tree = ET.parse('data/full.xml')
root = tree.getroot()

for child in tree.getroot():
	if(child.find('issuer') is None):
		continue

	text = child.find('issuer').text
	if text not in count:
		count[text] = 1
	else:
		count[text] += 1

sorted_count = sorted(count.items(), key=operator.itemgetter(1), reverse=True)

has = 0
fail = 0
total = 0
hasNot = 0
post = 0

for (name, c) in sorted_count:
	assert(c > 0)
	total += c
	if name is None or name.find('\n') > -1:
		fail += c
		continue

	sparql.setQuery('''
	select  distinct  ?label ?z
	where {
	?z rdf:type dbo:Person;
	rdfs:label ?label;
	dbo:abstract ?abstract.
	FILTER(?label ="''' + name + '''"@de)

	} LIMIT 2
	''')

	sparql.setReturnFormat(JSON)
	results = sparql.query().convert()

	if len(results["results"]["bindings"]) > 0:
		for result in results["results"]["bindings"]:
			print('O\t' + str(c) + '\t' + name)
			has += c
	else:
		print('X\t' + str(c) + '\t' + name)
		hasNot += c

manual = ET.parse('data/manualNames.xml')
mroot = manual.getroot()
for child in mroot:
	if child.text is None:
		continue

	if child.text not in count:
		continue

	post += count[child.text]

has += post
hasNot -= post

print()
print(total)
print(has)
print(hasNot)
print(post)
print(fail)

print()
print(100.0 * has / total)
