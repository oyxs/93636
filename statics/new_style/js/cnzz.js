var userAgentInfo = navigator.userAgent;
var Agents = ["Android", "iPhone",
                "SymbianOS", "Windows Phone",
                "iPad", "iPod"];
var flag = true;
for (var v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) > 0) {
            flag = false;
            break;
        }
    }
var vhost = window.location.host;
var digtal = null;

if(flag==true&&vhost=="www.93636.com"){
	digtal='1256663213';
	}
if(flag==false&&vhost=="m.93636.com"){
	digtal='1256719711';
	}
	var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
	document.write(unescape("%3Cspan id='cnzz_stat_icon_"+digtal+"'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/z_stat.php%3Fid%3D"+digtal+"%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));