import sys
sys.path.append('/home/ron/framework')
import drawer
from mod_python import apache
from igraph import *

def inputs(req, shape):
    def color(var, info):
        if var in info:
            c = list(color_name_to_rgb("#%s" % info[var][:6]))
            c.reverse()
            if len(info[var])>6 and (info[var][6:8]).isdigit():
                c.append(0.01*(int)(info[var][6:8])) #alpha is just numeric
            else:
                c.append(1) #assume if alpha not specified
        else:
            c=[0.5, 0.5, 0.5, 0.5]
        return c
    # Radius
    # Dimension
    # Border
    # Color1/Color2
    # Extra
    info = req.form
    r = min(100, max(10, ('r' in info) and (int)(info['r']) or 100))
    d = 'd' in info and [int(info['d']), int(info['d'])] or [32,32]
    b = 'b' in info and int(info['b']) or 8
    c1 = color('c1', req.form)
    c2 = color('c2', req.form)
    ex = 'ex' in info and int(info['ex']) or None;

    drawer.drawShape(req, shape=shape, dimens=d, border=b, radius=r, bottom=True, color1=c1, color2=c2, extra=ex)

def normal(req):
    inputs(req, "marker")
    return apache.OK

def circle(req):
    inputs(req, "circle")
    return apache.OK

def square(req):
    inputs(req, "square")
    return apache.OK

