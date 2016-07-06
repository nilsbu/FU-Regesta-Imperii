import xml.etree.ElementTree as ET
from SPARQLWrapper import SPARQLWrapper, JSON
import json
import operator

sparql = SPARQLWrapper("http://dbpedia.org/sparql")

names = {}
print('load file...')
tree = ET.parse('data/full.xml')
root = tree.getroot()

multiple = []

print('extract issuers...')
for child in root:
	if(child.find('issuer') is None):
		continue

	text = child.find('issuer').text
	if text not in names:
		names[text] = -1

for name in names.keys():
	if name is None or name.find('\n') > -1:
		continue

	print('lookup "' + name + '"...')

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

	if len(results['results']['bindings']) > 1:
		multiple.append(name)
		continue

	for result in results['results']['bindings']:
		names[name] = result['z']['value']

print('load manual names...')
manual = ET.parse('data/manualNames.xml')
mroot = manual.getroot()
for child in mroot:
	if child.text is None:
		continue

	names[child.text] = 'http://dbpedia.org/resource/' + child.get('resource')

print('write resource ids...')
for child in tree.getroot():
	if(child.find('issuer') is None):
		continue

	text = child.find('issuer').text
	if names[text] != -1 and names[text] != '':
		child.find('issuer').set('resource', names[text])

print('write file...')
tree.write('data/index_n.xml', encoding='UTF-8')

print()
print('not unique:')
for name in multiple:
	print(name)
