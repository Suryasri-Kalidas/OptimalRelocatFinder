import sys
import json
from bs4 import BeautifulSoup
import mysql.connector as mariadb

connection = mariadb.connect(user='cs411project', password='54321', database='relocate')
cursor = connection.cursor()
cursor.execute("SELECT fips_id, name FROM Locations L WHERE type = 1;")

state_ids = {}
for i, n in cursor:
    state_ids[n] = i

xml_file = sys.argv[1]
btree = BeautifulSoup(open(xml_file, 'r'), "xml")
objs = btree.select('svg > g > path')
json_obj = []

for obj in objs:
    if obj['id']:
        name = obj.find('title').text
        cDetail = {
            "fips_id": state_ids[name],
            "name": name,
            "edges": obj['d'],
        }
        json_obj.append(cDetail)

print(json.dumps(json_obj, indent=4))
