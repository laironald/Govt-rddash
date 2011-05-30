import cairo, re, sys
sys.path.append("/home/ron/PythonBase")
sys.path.append("../config")
import MySQL, geoFunc, config
from cStringIO import StringIO

cfg = config.cfg
m = MySQL.MySQL(cfg=cfg)
mp = geoFunc.MercatorProjection()

def drawTile(tile, size=256, category="state", color1=[0.1,0.3,0.8,0.2], color2=[0.4,0.2,0.2,0.6], zoom=0):
    import re

    surface = cairo.ImageSurface (cairo.FORMAT_ARGB32, size, size)
    ctx = cairo.Context (surface)
    ctx.scale (1, 1)
    ctx.set_line_width (1)

    draw = False
    if tile['x']!=-1 and tile['y']!=-1:
        tileBlk = mp.Tile_LL(tile=tile, zoom=zoom)
        #note polygon needs to close
        geom = "GeomFromText('Polygon((%s))')" % ", ".join([str(x)[1:-1].replace(",", "") for x in tileBlk['tile']])
        m.c.execute("SELECT label, AsText(shape) FROM mapPoly WHERE category='{category}' AND (MBRContains(shape, {geom}) or MBRIntersects(shape, {geom}))".format(geom=geom, category=category))
        multipolys = m.c.fetchall()

        for mps in multipolys:
            multipoly = re.findall("[(]+(.*?)[)]+", mps[1])
            for poly in multipoly:
                m.c.execute("SELECT Contains({g1}, {g2})+Intersects({g1}, {g2})".format(g1="GeomFromText('Polygon(({poly}))')".format(poly=poly), g2=geom))
                if m.c.fetchone()[0]>0:
                    draw = True
                    for i,x in enumerate([[float(point) for point in pair.split(" ")] for pair in poly.split(",")][1:-1]):
                        c = mp.LL_Pt( latLng={'lng':x[0], 'lat':x[1]}, zoom=zoom)
                        if i==0:
                            ctx.move_to ( c['x']-tileBlk['x'], c['y']-tileBlk['y'] )
                            ctx.line_to ( c['x']-tileBlk['x'], c['y']-tileBlk['y'] )
                        else:
                            ctx.line_to ( c['x']-tileBlk['x'], c['y']-tileBlk['y'] )

            ctx.set_source_rgba (*color1)
            ctx.fill_preserve ()
            ctx.set_source_rgba (*color2)
            ctx.stroke()

    thedata = StringIO()
    surface.write_to_png (thedata)
    m.c.execute("INSERT IGNORE INTO mapTile (coordx, coordy, zoom, category, img) VALUES (%s, %s, %s, %s, %s)", (tile['x'], tile['y'], zoom, category, thedata.getvalue(),))

drawTile(tile={'x':int(sys.argv[1]), 'y':int(sys.argv[2])}, zoom=int(sys.argv[3]), category=sys.argv[4])
