<!-- Main Setup -->
<script type='text/javascript'>
/*
 * By Andrew Simon
 * To add single sign on authentication from a non mtahq.org site:
 * 1) Check for a local auth cookie, if none, 2 AND 3 below must be true
 * 2) Verify referer is from MY referrer
 * 3) Verify the URI has a 'hash' that is between 'time -900' and 'time +3600'
 * 4) If both 2 and 3 above are met, set local auth coookie, otherwise redirect
 *
 * The first thing we have to do is build a function to get the cookie by name
*/

var auth_site = "http://www.example.com/";
var auth_url = "https://www.example.com/auth.php";
var vui_home = "http://www.example.com/vagrant/index.php";

var u = document.location.search.split("?")[1];
var d = new Date();
var n = d.getTime();
/* Since time between machines may differ, allow 15 minutes difference */
var m = Math.round(n / 1000)-900;
var t = Math.round(n / 1000)+3600;

function getCookie(c_name) {
var c_value = " " + document.cookie;
var c_start = c_value.indexOf(" " + c_name + "=");
if (c_start == -1) {
    c_value = null;
}
else {
    c_start = c_value.indexOf("=", c_start) + 1;
    var c_end = c_value.indexOf(";", c_start);
    if (c_end == -1) {
        c_end = c_value.length;
    }
    c_value = unescape(c_value.substring(c_start,c_end));
}
return c_value;
}

var x = getCookie("STATUS");
var y = document.referrer;

 /* If not uri arguments and not cookie, send to auth page */
if(u===undefined && x !== "authenticated") {
	window.location.replace(auth_url);
}

 /* If refer IS our auth site AND the uri fits, set a cookie */
if(y===auth_site && m<u && u<t) {
        document.cookie="STATUS=authenticated";
        var x = "authenticated";
}

 /* All URIs need to be tested as there are redirects that require both to be tested */
if(y===vui_home && m<u && u<t) {
        document.cookie="STATUS=authenticated";
        var x = "authenticated";
}
if(y===auth_url && m<u && u<t) {
        document.cookie="STATUS=authenticated";
        var x = "authenticated";
}


 /* If you have a valid vui_home cookie, don't test for anything else */
if(x !== "authenticated") {
/* alert(x); alert(y); */

alert('If you just signed in, click the Vagrant link one more time!\n\nIf you have not signed in, please do so now.');
window.location.replace(auth_url);
}

if(x === "authenticated") {
;	
}
</script>
