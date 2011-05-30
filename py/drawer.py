import sys
sys.path.append('/home/ron/framework')
import igraph.drawing 
import cairo, math, random
from PIL import Image
from cStringIO import StringIO

class MarkerDrawer(igraph.drawing.ShapeDrawer):
    def draw_path(ctx, cx, cy, w, extra):
        w = w /(3/2.)
        cy = cy - w/4.
        adj = math.pi/6
        cxx = math.pi-adj
        cyy = 2*math.pi+adj
        ctx.arc(cx, cy, w/2., cxx, cyy)
        ctx.move_to(cx+w/2.*math.cos(cxx), cy+w/2.*math.sin(cxx))
        ctx.curve_to(cx+w/2.*math.cos(cxx), cy+w/2.*math.sin(cxx),
                     cx, cy+0.5*w+w/2.*math.sin(cxx),
                     cx, cy+w)
        ctx.curve_to(cx, cy+w,
                     cx, cy+0.5*w+w/2.*math.sin(cxx),
                     cx-w/2.*math.cos(cxx), cy+w/2.*math.sin(cxx))
        #ctx.line_to(cx, cy+w)
        #ctx.line_to(cx+w/2.*math.cos(cyy), cy+w/2.*math.sin(cyy))        
    draw_path=staticmethod(draw_path)
igraph.drawing.known_shapes['marker'] = MarkerDrawer

class ImageDrawer(igraph.drawing.ShapeDrawer):
    def draw_path(ctx, cx, cy, w, extra):
        image = cairo.ImageSurface.create_from_png ("image.png")
        sc = float(w)/max(image.get_width(), image.get_height())
        if cx==cy:
            adjH = (w/8.)*(1-image.get_height()/image.get_width())
        else:
            adjH = (w/2.)*(1-image.get_height()/image.get_width())
        adjW = (w/4.)*(1-image.get_width()/image.get_height())
        ctx.scale (sc, sc)
        ctx.set_source_surface (image, (cx-w/2.+adjW)/sc, (cy-w/2.+adjH)/sc)
        ctx.paint()
        ctx.scale(1/sc, 1/sc)
    draw_path=staticmethod(draw_path)
igraph.drawing.known_shapes['image'] = ImageDrawer


def drawShape(req, shape="marker", bottom=False, dimens=[32,32], canvas=[100,100], border=5, radius=100, color1=[1.,1.,1.], color2=[0.,0.,0.], extra=None):
    radius = 0.01*radius*canvas[0]
    surface = cairo.ImageSurface (cairo.FORMAT_ARGB32, dimens[0], dimens[1])
    ctx = cairo.Context (surface)
    ctx.scale (float(dimens[0])/canvas[0], float(dimens[1])/canvas[1])
    ctx.set_line_width (border)
    vshp = igraph.drawing.known_shapes[shape]
    cx = canvas[0]/2
    cy = bottom and canvas[1]-radius/2-1e-8 or canvas[1]/2
    w = radius-border
    vshp.draw_path (ctx, cx, cy, w, extra)
    ctx.set_source_rgba (*color1)
    ctx.fill_preserve ()
    ctx.set_source_rgba (*color2)
    ctx.stroke()

    if extra==1 and shape=="marker": #outside shape thingie
        w2 = w /(3/2.)
        cy2 = cy - w2/4.
        igraph.drawing.known_shapes["circle"].draw_path (ctx, cx, cy2, w2/2.5)
        ctx.set_source_rgba (*color2)
        ctx.fill_preserve ()
        ctx.stroke()
    
    s = StringIO()
    img = Image.frombuffer("RGBA", (surface.get_width(), surface.get_height()), surface.get_data(), "raw", "RGBA", 0, 1)
    s.seek(0)
    req.content_type = 'image/png'
    img.save(req, 'png')
