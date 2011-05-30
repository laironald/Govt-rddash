import cairo, drawer, random
from PIL import Image
from cStringIO import StringIO
import cgi
from mod_python import apache
from igraph import *
import MySQLdb

def index():
	return "Hello World"

#def handler(req):
#	req.content_type = 'text/plain'
#	req.write("hello alex is the world!")
#
#	g = Graph([[1,2],[2,3]])
#	req.write(str(g.get_edgelist()))
#
#	return apache.OK

def get_info(req):
	info = req.form
	return str(info.keys()) + info['q'] + " " + req.uri;	
#	form = cgi.FieldStorage()
#	return str(form)

def get_picture(req):
	import ron
	return "HELLO"
	
def harv(req):
	data = StringIO()

	info = req.form
	if ('size' in info):
		WIDTH, HEIGHT = (int)(info['size']), (int)(info['size'])
		radius = (int)(info['size'])
	else:
		WIDTH, HEIGHT = 64, 64
		radius = 100

	surface = cairo.ImageSurface (cairo.FORMAT_ARGB32, WIDTH, HEIGHT)
	ctx = cairo.Context (surface)

	ctx.scale (WIDTH/200.0, HEIGHT/200.0) # Normalizing the canvas

	cx,cy = (100, 100)
	w, h  = (180, 180)

	ctx.move_to(cx-w*0.15, cy-h*0.35)
	ctx.line_to(cx-w*0.15, cy+h*0.20)
	ctx.move_to(cx+w*0.15, cy-h*0.35)
	ctx.line_to(cx+w*0.15, cy+h*0.20)
	ctx.move_to(cx+w*0.15, cy-h*0.10)
	ctx.line_to(cx-w*0.15, cy-h*0.10)

	ctx.move_to(cx-w*0.4, cy-h*0.5)
	ctx.line_to(cx-w*0.4, cy)
	ctx.curve_to(cx-w*0.4, cy, cx-w*0.4, cy+h*0.25, cx, cy+h*0.5)
	ctx.curve_to(cx, cy+h*0.5, cx+w*0.4, cy+h*0.25, cx+w*0.4, cy)
	ctx.line_to(cx+w*0.4, cy-h*0.5)
	ctx.line_to(cx-w*0.4, cy-h*0.5)
	ctx.line_to(cx-w*0.4, cy-h*0.49)

	ctx.set_source_rgb (0, 0, 1) # Solid color
	ctx.fill_preserve()
	ctx.set_line_width (5)
	ctx.set_source_rgb (0.9, 0.9, 0.9) # Solid color
	ctx.stroke ()
		
	s = StringIO()
	img = Image.frombuffer("RGBA", (surface.get_width(), surface.get_height()), surface.get_data(), "raw", "RGBA", 0, 1)
	s.seek(0)
	req.content_type = 'image/png'
	img.save(req, 'png')
	return apache.OK	
	
def image2(req):
	info = req.form
	r = min(100, max(10, ('r' in info) and (int)(info['r']) or 100))
	if 'c0' in info:
		c = list(color_name_to_rgb("#%s" % info['c0'][:6]))
		c.reverse()
		#c = [float(x) for x in info['c0'].split("|")]
		if len(c)==3:
			c.append(1) #alpha, although we should change this
	else:
		colors = {0: [1, 1, 1, 0.5], 1:[0.8, 0, 0, 0.5], 2:[0.2, 0.8, 0, 0.5], 3:[0, 0.2, 0.8, 0.5]}
		c = colors[('c' in info) and (int)(info['c']) or 0]
	
	drawer.drawShape(req, dimens=[32,32], radius=r, bottom=True, color1=c, color2=[0.2, 0.2, 0.2, 1])
	return apache.OK	

def image3(req):
	info = req.form
	r = min(100, max(10, ('r' in info) and (int)(info['r']) or 100))
	d = 'd' in info and [int(info['d']), int(info['d'])] or [32,32]
	def color(var):
		if var in info:
			c = list(color_name_to_rgb("#%s" % info[var][:6]))
			c.reverse()
			#c = [float(x) for x in info['c0'].split("|")]
			if len(c)==3:
				c.append(1) #alpha, although we should change this
		else:
			c=[0.2, 0.2, 0.2, 1]
		return c
	c1 = color('c1')
	c2 = color('c2')
	
	drawer.drawShape(req, dimens=d, radius=100, bottom=True, color1=c1, color2=c2)
	return apache.OK	


def KML(req):
	req.content_type = 'application/vnd.google-earth.kml+xml'
	lat = random.randrange(-90, 90)
	lng = random.randrange(-180, 180)
	KMLstr = """
		<?xml version="1.0" encoding="UTF-8"?>
		<kml xmlns="http://www.opengis.net/kml/2.2">
			<Document>
				<name>%s</name>
				<description>%s</description>
				%s
			</Document>
		</kml>""" % ("", "", "<Placemark><Point><coordinates>%d,%d</coordinates></Point></Placemark>" % (lat, lng))
	return KMLstr
	

