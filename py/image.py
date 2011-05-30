from cStringIO import StringIO

def handler(req):
	s = StringIO()
	img.save(s, 'jpeg')
	s.seek(0)
	req.content_type = 'image/jpeg'
	req.write(s.getvalue())
	return apache.OK
