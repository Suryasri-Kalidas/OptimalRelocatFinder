import sys
import json
from bs4 import BeautifulSoup

xml_file = sys.argv[1]
btree = BeautifulSoup(open(xml_file, 'r'), "xml")
objs = btree.select('svg > g > path')
json_obj = {}

for obj in objs:
    if obj['id']:
        try:
            int(obj['id'][5:])
            cDetail = {
                "fips_id": int(obj['id'][5:]),
                "name": obj.find('title').text,
                "edges": obj['d'],
            }
            json_obj[cDetail['fips_id']] = cDetail
        except:
            next

sorted_obj = []
sorted_obj = sorted([json_obj[i] for i in json_obj], key=lambda x : x['fips_id'])

print(json.dumps(sorted_obj, indent=4))
