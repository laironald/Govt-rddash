import sys
sys.path.append('/home/ron/framework')
sys.path.append('../config')
from senGraph import *
from mod_python import apache
import config

dbconfig = config.cfgS

def index():
	return ""

def state(req):
	req.content_type = 'application/vnd.google-earth.kml+xml'
	db = senDB(table="state", dbconfig=dbconfig)
	s = senGraph(db)
	s.g.vs["name"] = s.g.vs["id"]
	s.g.vs["layout"] = [[x["lng"], x["lat"]] for x in s.g.vs]
	rng = [min(s.g.vs["cnt"]), max(s.g.vs["cnt"])]
	s.g.vs["size"] = [(int)(round(float(x-rng[0])/(rng[1]-rng[0])*65+35,0)) for x in s.g.vs["cnt"]]

	return s.KML(scale=None, edgeDraw=False)

def state2(req):
	req.content_type = 'application/vnd.google-earth.kml+xml'
	db = senDB(table="zipca", dbconfig=dbconfig)
	s = senGraph(db)
	s.g.vs["name"] = s.g.vs["id"]
	s.g.vs["layout"] = [[x["lng"], x["lat"]] for x in s.g.vs]
	rng = [min(s.g.vs["cnt"]), max(s.g.vs["cnt"])]
	s.g.vs["size"] = [(int)(round(float(x-rng[0])/(rng[1]-rng[0])*65+35,0)) for x in s.g.vs["cnt"]]

	return s.KML(scale=None)

def zipcode(req):
	info = req.form
	if 'b' in info:		
		bbox = [float(x) for x in info["b"].split("|")]
	
	req.content_type = 'application/vnd.google-earth.kml+xml'
	db = senDB(table=["zipcode","zip"], bbox=bbox, dbconfig=dbconfig)
	s = senGraph(db)
	s.g.vs["name"] = s.g.vs["id"]
	s.g.vs["layout"] = [[x["lng"], x["lat"]] for x in s.g.vs]
	rng = [min(s.g.vs["cnt"]), max(s.g.vs["cnt"])]
	s.g.vs["size"] = [(int)(round(float(x-rng[0])/(rng[1]-rng[0])*65+35,0)) for x in s.g.vs["cnt"]]

	return s.KML(scale=None)

def zipcode3(req):
	info= req.form
	if 'BBOX' in info:
		bbox2 = [float(x) for x in info["BBOX"].split(",")]
		bbox = [bbox2[1], bbox2[0], bbox2[3], bbox2[2]]
	else:
		bbox = [37, -122, 37.5, -121.5]

	#req.content_type = 'application/vnd.google-earth.kml+xml'
	db = senDB(table=["zipcode","zip"], bbox=bbox, dbconfig=dbconfig)
	if len(db.vList)>0 and len(db.vList)<400:
		s = senGraph(db)
		s.g.vs["name"] = s.g.vs["id"]
		s.g.vs["layout"] = [[x["lng"], x["lat"]] for x in s.g.vs]
		rng = [min(s.g.vs["cnt"]), max(s.g.vs["cnt"])]
		s.g.vs["size"] = [(int)(round(float(x-rng[0])/(rng[1]-rng[0])*65+35,0)) for x in s.g.vs["cnt"]]

		return s.KML(scale=None, edgeDraw=False)
	else:
		return """<?xml version="1.0" encoding="UTF-8"?>
            <kml xmlns="http://www.opengis.net/kml/2.2"> 
                <Document>
                    <name></name>
                    <description>
                    </description>

	   <Placemark>
	   <name>VC</name>
	   <description><![CDATA[
	   		%d <br/>
			BBOX_IN  = %s <br/>
			BBOX_OUT = %s
		]]></description>
	   <Point>
		   <coordinates>%.6f,%.6f</coordinates>
	   </Point>
	   </Placemark>
	   </Document>
	   </kml>""" %(len(db.vList), str(bbox2), str(bbox), bbox2[0], bbox2[1])
	
def zipcode2(req):
	url= req.form
	if 'BBOX' in url:
		bbox = url['BBOX']
		bbox = bbox.split(',')
		west = float(bbox[0])
		south = float(bbox[1])
		east = float(bbox[2])
		north = float(bbox[3])

		center_lng = ((east - west) / 2) + west
		center_lat = ((north - south) / 2) + south
	else:
		center_lng = 39.10960
		center_lat = -96.5

	kml = """
            <?xml version="1.0" encoding="UTF-8"?>
            <kml xmlns="http://earth.google.com/kml/2.0"> 
                <Document>
                    <name></name>
                    <description></description>

	   <Placemark>
	   <name>View-centered placemark</name>
	   <Point>
		   <coordinates>%.6f,%.6f</coordinates>
	   </Point>
	   </Placemark>
	   </Document>
	   </kml>""" %(center_lng, center_lat)

	req.content_type = 'application/vnd.google-earth.kml+xml'
	return kml
	
def zipcode4(req):
	info= req.form
	if 'BBOX' in info:
		bbox2 = [float(x) for x in info["BBOX"].split(",")]
		if 'src' in info:
			bbox = bbox2
		else:
			bbox = [bbox2[1], bbox2[0], bbox2[3], bbox2[2]]
	else:
		bbox = [37, -122, 37.5, -121.5]

	req.content_type = 'application/vnd.google-earth.kml+xml'
	import MySQLdb
	conn = MySQLdb.connect(passwd="patbox123!", db="NSF", user="root")
	c = conn.cursor()
	c.execute("SELECT * FROM v_zip WHERE lat>%f AND lng>%f AND lat<%f AND lng<%f" % tuple(bbox))
	vList = c.fetchall()
	if len(vList)>0:	
	
		vSize = [x[3] for x in vList]
		vRng = [min(vSize), max(vSize)]
	
		KMLstr = ""
		for i,x in enumerate(vList):
		    KMLstr = """%s
		        <Placemark>
		            <name>#%d</name>
		            <description>%s</description>
		            <Style>
		                <IconStyle>
		                    <Icon>
		                        <href>http://140.247.116.250/mptest.py/image2?c0=%s&amp;r=%d</href>
		                    </Icon>
		                </IconStyle>
		            </Style>
		            <Point><coordinates>%f,%f</coordinates></Point>
		        </Placemark>""" % (KMLstr, i, x[0], "dd8833", (int)(round(float(x[3]-vRng[0])/(vRng[1]-vRng[0])*80+20,0)), x[2], x[1])
		                           

		return """
			<?xml version="1.0" encoding="UTF-8"?>
            <kml xmlns="http://www.opengis.net/kml/2.2"> 
                <Document>
                    <name></name>
                    <description>
                    </description>
	                %s
                </Document>
            </kml>""" % KMLstr
	else:
		return """<?xml version="1.0" encoding="UTF-8"?>
            <kml xmlns="http://www.opengis.net/kml/2.2"> 
                <Document>
                    <name></name>
                    <description>
                    </description>

	   <Placemark>
	   <name>VC</name>
	   <description><![CDATA[
	   		%d <br/>
			BBOX_IN  = %s <br/>
			BBOX_OUT = %s
		]]></description>
	   <Point>
		   <coordinates>%.6f,%.6f</coordinates>
	   </Point>
	   </Placemark>
	   </Document>
	   </kml>""" %(len(vList), str(bbox2), str(bbox), bbox2[0], bbox2[1])

