import sys
sys.path.append('/home/ron/PythonBase')
sys.path.append('../config')
import config, math, senGraph, random, geoPut
from mod_python import apache

#for images
import cairo
from igraph import *
from PIL import Image
from cStringIO import StringIO

cfg =config.cfgB
cfgF=config.cfgF

def json_v0(req):
    random.seed(19820817)
    info = req.form
    x = senGraph.senDBMySQL(dbconfig=cfg, table="invpat")
    InvList = [info['inv']]
    Inv = x.nhood(InvList)

    x.graph(vertex_list=Inv, flag=InvList)
    sG = senGraph.senGraph(x.tab, varType='vertex')
    sG.g.vs["size"] = [math.log(x)*3+4 for x in sG.g.vs["cnt"]]
    sG.vs_color("AsgNum", dbl=True)
    sG.g.vs["layout"] = [[x["Lat"], x["Lng"]] for x in sG.g.vs]
    json = sG.json(output=None, vBool=["flag"], title="", scale=None)
    return json
    #return apache.OK

def json(req):
    random.seed(19820817)
    info = req.form
    x = senGraph.senDBMySQL(dbconfig=cfg, table="invpat")

    #x.graph(vertex_list=Inv, flag=InvList)

    Inv = []

    if "mode" not in info and "State" in info:
        info["mode"] = "congdist"

    if "mode" in info:
        if info["mode"] == "inventor":
            InvList = [info['inv']]
            Inv = x.nhood(InvList)
            where = None
            output = "/home/ron/web/py/sqlite/%s.s3" % info['inv']
        elif info["mode"] == "congdist":
            Inv = x.congdist(cd=(int)(info['CD']), state=info['State']);
            where = "a.CD=%d AND a.State='%s'" % ((int)(info['CD']), info['State'])
            output = "/home/ron/web/py/sqlite/%s%s.s3" % (info['State'], info['CD'])

    if len(Inv)>0:
        x.graph(vertex_list=Inv, where=where, output=output)
        sG = senGraph.senGraph(x.tab, varType='vertex')
        sG.g.vs["size"] = [math.log(x)*3+4 for x in sG.g.vs["cnt"]]
        sG.vs_color("AsgNum", dbl=True)
        sG.g.vs["layout"] = [[x["Lat"], x["Lng"]] for x in sG.g.vs]

        #year gets expanded
        if 'year' in info.keys():
            yr = info['year'].split('-')
            if len(yr)==1:
                yr.append(yr[0])
            sG.g = sG.g.vs.select(AppYear_ge=yr[0], AppYear_le=yr[1]).subgraph()

        #sG.g = sG.g.vs.select(AsgNum_eq="A000010088904").subgraph()


        json = sG.json(output=None, vBool=["flag"], title="", scale=None)
        return json
    else:
        return ""
    #return apache.OK

def json_v1(req):
    random.seed(19820817)
    x = geoPut.geo(cfg=cfgF)
    json = x.search(req.form, __file__);
    return json
    
def data(req):
    x = geoPut.geo(cfg=cfgF)
    json = x.dataBoxed(req.form);
    return json

def kml(req):
    f = open("/home/ron/web/kml/cd99_110.kml", "rb")
    txt = "".join(f.readlines())
    f.close()
    
def map(req):
    random.seed(19820817)
    info = req.form
    x = senGraph.senDBMySQL(dbconfig=cfg, table="invpat")
    InvList = [info['inv']]
    Inv = x.nhood(InvList)
    x.graph(vertex_list=Inv, flag=InvList)

    sG = senGraph.senGraph(x.tab, varType='vertex')
    sG.g.vs["size"] = [math.log(x)*3+4 for x in sG.g.vs["cnt"]]
    sG.vs_color("AsgNum", dbl=True)

    sG.g.vs["layout"] = [[x["Lat"], x["Lng"]] for x in sG.g.vs]
    surface = cairo.ImageSurface (cairo.FORMAT_ARGB32, 200, 200)
    ctx = cairo.Context (surface)
    plot(sG.g, "/home/ron/web/py/img/1.png",
         layout=sG.g.vs["layout"],
         bbox=[200, 200],
         vertex_label="",
         vertex_layer=True,
         vertex_size2=[x['size']+5 for x in sG.g.vs()])

    image = cairo.ImageSurface.create_from_png ("/home/ron/web/py/img/1.png")
    ctx = cairo.Context (surface)
    ctx.set_source_surface (image, 0, 0)
    ctx.paint()
 
    s = StringIO()
    img = Image.frombuffer("RGBA", (surface.get_width(), surface.get_height()), surface.get_data(), "raw", "RGBA", 0, 1)
    s.seek(0)    
    req.content_type = 'image/png'
    img.save(req, 'png')
    return apache.OK
