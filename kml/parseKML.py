import sys
sys.path.append('/home/ron/PythonBase')
sys.path.append('../config')

import SQLite, datetime, re, zipfile, json, config
from cStringIO import StringIO

cfg = config.cfg

###################################
#                                 #
#      S E N S I T I V I T Y      #
#                                 #
###################################

tolA = 0.5
tolB = 4.0
cdBool = True
usBool = False

stCode = {'45': 'SC', '24': 'MD', '25': 'MA', '26': 'MI', '27': 'MN', '20': 'KS', '21': 'KY', '22': 'LA', '23': 'ME', '28': 'MS', '29': 'MO', '11': 'DC', '10': 'DE', '13': 'GA', '12': 'FL', '15': 'HI', '17': 'IL', '16': 'ID', '19': 'IA', '18': 'IN', '56': 'WY', '51': 'VA', '50': 'VT', '53': 'WA', '55': 'WI', '54': 'WV', '49': 'UT', '02': 'AK', '01': 'AL', '06': 'CA', '04': 'AZ', '05': 'AR', '46': 'SD', '47': 'TN', '44': 'RI', '09': 'CT', '42': 'PA', '40': 'OK', '41': 'OR', '72': 'PR', '39': 'OH', '38': 'ND', '48': 'TX', '33': 'NH', '32': 'NV', '31': 'NE', '30': 'MT', '37': 'NC', '36': 'NY', '35': 'NM', '34': 'NJ', '08': 'CO'}

def dist(c, tol):
    #lng, lat
    if len(c)<=1:
        return True
    return (((53.0*(float(c[-2][0])-float(c[-1][0])))**2+(69.1*(float(c[-2][1])-float(c[-1][1])))**2)**0.5)>tol

print datetime.datetime.now()

###################################
#                                 #
#    C O N G R E S S I O N A L    #
#         D I S T R I C T         #
#                                 #
###################################

if cdBool:
    f = open('cd99_110.kml', 'rb')
    txt = "".join(f.readlines())
    f.close()

    pmarks = re.findall('<placemark>.*?</placemark>', txt, re.I+re.S)
    schema = re.findall('<schemadata.*?schemadata>', txt, re.I+re.S)
    sdata = [re.findall('[=]["].*?["]>(.*?)<', x) for x in schema]

    sc = {}
    for i,x in enumerate(sdata):
        if x[0] not in sc:
            sc[x[0]] = {}
        sc[x[0]][x[1]] = i

    def coords(data, st, cd):
        coord = re.findall("<coordinates>(.*?)</coordinates>", pmarks[sc[st][cd]], re.I+re.S)
        marks = [stCode[st], int(cd)]
        s = {}
        cs = []
        for c in coord: 
            cs2 = []
            for x in re.sub("\n|\t", "", c).strip().split(" "):
                cs2.append(['%.5f' % float(y) for y in x.split(",")[:2]])
                if dist(cs2, tolA)==False:
                    cs2.pop()
            if len(cs2)>2:
                cs.append(cs2)
            #cs.append([['%.5f' % float(y) for y in x.split(",")[:2]] for x in re.sub("\n|\t", "", c).strip().split(" ")])
        data['marks'].append(marks)
        data['coords'].append(cs)
        return data

    allMarks = []
    for x in sc.keys():
        if x in stCode.keys():
            data = {'marks':[], 'coords':[]}
            for y in sc[x].keys():
                data = coords(data, x, y);

            f = open('json/%s-cd.json' % stCode[x], 'wb')
            f.write(json.dumps(data))
            f.close()

##            allMarks.extend(data['marks'])
##
##    for x in sorted(sorted(allMarks, key=lambda x:x[1])):
##        print """<option value="%s-%s">%s-%s</option>""" % (x[0], x[1], x[0], x[1])


    CDs = []
    for x in sc.keys():
        if x in stCode.keys():
            for y in sc[x].keys():
                CDs.append([stCode[x], int(y)])
    s = SQLite.SQLite(tbl="AllCD");
    s.c.execute("CREATE TABLE AllCD (State VARCHAR, CD INTEGER);")
    s.index(['State', 'CD'], unique=True)
    s.c.executemany("INSERT OR IGNORE INTO AllCD VALUES (?, ?)", CDs)
    s.mysql_output(cfg=cfg)
    print datetime.datetime.now()

###################################
#                                 #
#            S T A T E            #
#                                 #
###################################

if usBool:
    f = open('st99_d00.kml', 'rb')
    txt = "".join(f.readlines())
    f.close()

    pmarks = re.findall('<placemark>.*?</placemark>', txt, re.I+re.S)
    schema = re.findall('<schemadata.*?schemadata>', txt, re.I+re.S)
    sdata = [re.findall('[=]["].*?["]>(.*?)<', x) for x in schema]

    sc = {}
    for i,x in enumerate(sdata):
        if x[4] not in sc:
            sc[x[4]] = []
        sc[x[4]].append(i)

    def coords(data, st):
        for xx in sc[st]:
            marks = [stCode[st], sdata[xx][5]]
            coord = re.findall("<coordinates>(.*?)</coordinates>", pmarks[xx], re.I+re.S)
            s = {}
            cs = []
            for c in coord:
                cs2 = []
                for x in re.sub("\n|\t", "", c).strip().split(" "):
                    cs2.append(['%.5f' % float(y) for y in x.split(",")[:2]])
                    if dist(cs2, tolB)==False:
                        cs2.pop()
                if len(cs2)>2:
                    cs.append(cs2)
            if len(cs)>0:
                data['marks'].append(marks)
                data['coords'].append(cs)
        return data

    data = {'marks':[], 'coords':[]}
    for x in sc.keys():
        if x in stCode.keys():
            data = coords(data, x);

##    for x in sorted(list(dict(data['marks']).items())):
##        print """<option value="%s">%s</option>""" % x

    f = open('json/USA.json', 'wb')
    f.write(json.dumps(data))
    f.close()

    print datetime.datetime.now()
