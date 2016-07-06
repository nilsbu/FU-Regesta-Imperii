from os import listdir
from os.path import isfile

PATH = 'CEI-reg'
DESTINATION = 'all.xml'

def read_directory_recursively(path, write_to):
	directory = listdir(path)
	for f in directory:
		full_path = path + '/' + f

		if not isfile(full_path):
			print('dir: ' + full_path)
			read_directory_recursively(full_path, write_to)
		else:
			if not full_path.endswith('.xml'):
				continue

			print('file: ' + full_path)

			write_to.write('<document>')
			with open(full_path) as xml:
				for line in xml:
					if(line.startswith('<?xml')):
						continue
					write_to.write(xml.read())
			write_to.write('</document>')

if __name__ == '__main__':
	write_to = open(DESTINATION, 'w')
	write_to.write('<list>')
	read_directory_recursively(PATH, write_to)
	write_to.write('</list>')
	write_to.close()
