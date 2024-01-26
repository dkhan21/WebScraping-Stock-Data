from bs4 import BeautifulSoup
import requests
import pymongo
import sys
import time

for _ in range(5):
    url_link = "https://finance.yahoo.com/most-active?guccounter=1"
    result = requests.get(url_link)
    if result.status_code == 404:
        result = requests.get(url_link)
        if result.status_code == 404:
            sys.exit()

    res = result.text
    doc = BeautifulSoup(res, "html.parser")

    #Extracting Stock Symbols from Site 
    table = doc.find("table", class_="W(100%)")
    elements = table.find_all('td')
    sym = []
    for i in elements:
        a_link = i.find_all("a")
        for j in a_link:
            sym.append(j.string)

    #Extracting Stock Names from Site
    sym2 = []
    tableName = doc.find_all(attrs={"aria-label": "Name"})
    for m in tableName:
            sym2.append(m.string)

    #Extracting Stock Prices
    sym3temp = []
    sym3 = []
    for s in elements:
        a_link = s.find_all('fin-streamer')
        for j in a_link:
            sym3temp.append(j.string)
    for o in sym3temp:
        if o[0].isdigit() and o[-1].isdigit():
            sym3.append(o)

    #Extracting Stock Changes
    tableChange = doc.find_all(attrs={"aria-label": "Change"})
    sym4 = []
    for l in tableChange:
        sym4.append(l.string)

    #Extracting Stock Volumes
    tableVolume = doc.find_all(attrs={"aria-label": "Volume"})
    sym5 = []
    for p in tableVolume:
        sym5.append(p.string)

    myclient = pymongo.MongoClient("mongodb://localhost:27017/")
    mydb = myclient["mydb"]

    dblist = myclient.list_database_names()
    if "mydb" in dblist:
            print("database is in list")

    coll = mydb["Stock_Data"]

    for x in range(0,25):
        temp = { "Symbol": sym[x], "Name": sym2[x], "Prices": sym3[x], "Changes": sym4[x], "Volume": sym5[x] } 
        newt = coll.insert_one(temp)
    time.sleep(180)
