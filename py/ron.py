import math
import cairo
from mod_python import apache
from igraph import *
import MySQLdb

def index():
	WIDTH, HEIGHT = 32, 32

	surface = cairo.ImageSurface.create_for_data (cairo.FORMAT_ARGB32, WIDTH, HEIGHT)
	ctx = cairo.Context (surface)

	ctx.scale (WIDTH/200.0, HEIGHT/200.0) # Normalizing the canvas

	##pat = cairo.LinearGradient (0.0, 0.0, 0.0, 1.0)
	##pat.add_color_stop_rgba (1, 0.7, 0, 0, 0.5) # First stop, 50% opacity
	##pat.add_color_stop_rgba (0, 0.9, 0.7, 0.2, 1) # Last stop, 100% opacity

	##ctx.rectangle (0, 0, 1, 1) # Rectangle(x0, y0, x1, y1)
	##ctx.set_source (pat)
	##ctx.fill ()

	##ctx.translate (0.1, 0.1) # Changing the current transformation matrix

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

	ctx.set_source_rgb (1, 0, 0.2) # Solid color
	ctx.set_line_width (6)
	ctx.stroke ()
	surface.write_to_png ("img/example.png") # Output to PNG

