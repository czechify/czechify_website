function menuCleanup() {
    var els1 = document.getElementsByClassName("active");
    for (i = 0; i < els1.length; i++) if (els1[i]) els1[i].className = "";
}
tabs = ['home', 'about-us', 'videos', 'videos/beginner', 'videos/intermediate', 'videos/advanced', 'flashcards/your-collection'/*, 'flashcards/play'*/];
posts = <?php echo json_encode($postsData); ?>;
if ((location.hash)&&(document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-tab"))) {
    if ((!(document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.offsetHeight))&&((!(document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.parentElement.getElementsByTagName('a')[0] == document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav")))&&(!(document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.parentElement.getElementsByTagName('a')[0].id)))) setTimeout(function() { document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.parentElement.getElementsByTagName('a')[0].click() }, 500)
    document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-tab").style.display = "block";
    if ((location.hash.split('posts')[0].substring(1).split('/').length > 3)&&(!(document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.offsetHeight))) {
        setTimeout(function() {
            document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.parentElement.getElementsByTagName('a')[0].click()
        }, 500)
    }
    menuCleanup();
    if (document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav")) document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").className = "active";
}else location.hash = '/' + tabs[0] + '/';
if (location.hash.split('posts').length > 1) {
    if (posts[location.hash.split('posts')[1].replace(/\/+/g, '')]) {
        var ii = document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-tab").getElementsByTagName('*');
        for (i = 0; i < ii.length; i++) if (ii[i].getAttribute('postID') == location.hash.split('posts')[1].replace(/\/+/g, '')) { openPost(ii[i]); break; }
    }else location.hash = location.hash.substring(1).split('posts')[0]
}
onpopstate = function(event) {
    if (document.getElementById('postViewer').innerHTML) {
        setTimeout(function() {
            var x = document.body.childNodes[1].childNodes;
            for (i = 0; i < x.length; i++) if (x[i].offsetHeight) var p = x[i]
            if ((p)&&(document.getElementById('postViewer').getAttribute('postID'))) {
                var el1 = document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-tab").getElementsByTagName('*');
                for (i = 0; i < el1.length; i++) if (el1[i].getAttribute('postID') == document.getElementById('postViewer').getAttribute('postID')) var el2 = el1[i];
                if (el2) closePost(el2, 0); else closePost(el2, 1)
            }else {
                document.getElementById('postViewer').innerHTML = '';
                if (document.getElementById('postViewer').getAttribute('style')) document.getElementById('postViewer').removeAttribute('style');
                if (document.getElementById('postViewer').getAttribute('postID')) document.getElementById('postViewer').removeAttribute('postID');
            }
        }, 1)
    }
    tabs.forEach((item) => { document.getElementById(('/' + item + '/').replace(/\/+/g, "---") + "-tab").style.display = "none"; })
    if ((location.hash)&&(document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-tab"))) {
        document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-tab").style.display = "block";
        if ((!(document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.offsetHeight))&&((!(document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.parentElement.getElementsByTagName('a')[0] == document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav")))&&(!(document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.parentElement.getElementsByTagName('a')[0].id)))) document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.parentElement.getElementsByTagName('a')[0].click()
        if ((location.hash.split('posts')[0].substring(1).split('/').length > 3)&&(!(document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.offsetHeight))) document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").parentElement.parentElement.getElementsByTagName('a')[0].click()
    }else location.hash = '/' + tabs[0] + '/';
    menuCleanup();
    if (document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav")) document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-nav").className = "active";
    if (location.hash.split('posts').length > 1) {
        if (posts[location.hash.split('posts')[1].replace(/*/([\/]+(beginner)|[\/]+(intermediate)|[\/]+(advanced)|[\/]+)/g*//\/+/g, '')]) {
            var ii = document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-tab").getElementsByTagName('*');
            for (i = 0; i < ii.length; i++) if (ii[i].getAttribute('postid') == location.hash.split('posts')[1].replace(/*/([\/]+(beginner)|[\/]+(intermediate)|[\/]+(advanced)|[\/]+)/g*//\/+/g, '')) { openPost(ii[i]); break; }
        }else location.hash = location.hash.substring(1).split('posts')[0];
    }
}
function closePost(el, mode) {
    document.getElementById('postViewer').style.transition = '.5s all cubic-bezier(0.48,-0.01, 0, 1)'
    document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-tab").parentElement.removeAttribute('style')
    if (document.getElementById('postViewer').getAttribute('postID')) document.getElementById('postViewer').removeAttribute('postID')
    document.getElementById('postViewer').getElementsByTagName('div')[0].style.height = '0'
    setTimeout(function() {
        document.getElementById('postViewer').innerHTML = '';
        document.getElementById('postViewer').style.backgroundColor = '#fff';
        if (!(mode)) {
            document.getElementById('postViewer').style.top = el.getBoundingClientRect()['top'] + 'px';
            document.getElementById('postViewer').style.left = el.getBoundingClientRect()['left'] + 'px';
            document.getElementById('postViewer').style.height = el.getBoundingClientRect()['height'] + 'px';
            document.getElementById('postViewer').style.width = el.getBoundingClientRect()['width'] + 'px';
        }
        document.getElementById('postViewer').style.opacity = 0;
        setTimeout(function() {
            if (document.getElementById('postViewer').getAttribute('style')) document.getElementById('postViewer').removeAttribute('style')
        }, 500)
    }, 1500)
}
function openPost(el) {
    document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-tab").parentElement.style.maxHeight = '100%';
    document.getElementById(location.hash.split('posts')[0].substring(1).replace(/\/+/g, "---") + "-tab").parentElement.style.overflow = 'hidden';
    document.getElementById('postViewer').setAttribute('postID', el.getAttribute('postID'))
    document.getElementById('postViewer').style.opacity = 1;
    if (el.offsetHeight) {
        document.getElementById('postViewer').style.top = el.getBoundingClientRect()['top'] + 'px';
        document.getElementById('postViewer').style.left = el.getBoundingClientRect()['left'] + 'px';
        document.getElementById('postViewer').style.height = el.getBoundingClientRect()['height'] + 'px';
        document.getElementById('postViewer').style.width = el.getBoundingClientRect()['width'] + 'px';
    }else {
        document.getElementById('postViewer').style.backgroundColor = '#1b1b1b';
    }
    setTimeout(function() {
        document.getElementById('postViewer').style.top = '0px';
        document.getElementById('postViewer').style.left = '15vw';
        document.getElementById('postViewer').style.height = '100vh';
        document.getElementById('postViewer').style.width = '85vw';
        document.getElementById('postViewer').style.pointerEvents = 'auto';
        setTimeout(function() {
            document.getElementById('postViewer').style.backgroundColor = '#1b1b1b';
            var postOBJ = posts[el.getAttribute('postID')];
            var wordsHTML = '';
            var longestWordLength = 0;
            postOBJ['words'].forEach((word) => { if (word.length > longestWordLength) longestWordLength = word.length; })
            postOBJ['words'].forEach((word) => { wordsHTML = wordsHTML + '<div style="min-width: calc(' + longestWordLength + 'ch);" align="center">' + word + '</div>'; })
            if (postOBJ['fs']) var fsLine = ' style="font-size: ' + postOBJ['fs'] + '"'; else fsLine = '';
            document.getElementById('postViewer').innerHTML = '<div style="height: 0;"><div><div><h1>' + postOBJ['name'] + '</h1></div><div><iframe src="https://www.youtube.com/embed/' + postOBJ['video'] + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div><div><div' + fsLine + '>' + wordsHTML + '</div></div><div><div><h1>' + postOBJ['text'] + '</h1></div></div></div><a href="#/videos/' + postOBJ['difficulty'] + '/" ' + postOBJ['type'].substr(0, 1) + '><div>' + postOBJ['type'] + '</div></a><a href="#' + location.hash.substring(1).split('posts')[0] + '"><div>X</div></a></div>';
            setTimeout(function() {
                document.getElementById('postViewer').getElementsByTagName('div')[0].style.height = '100%';
                document.getElementById('postViewer').style.transition = 'none';
            }, 500)
        }, 100)
    }, 1)
}
function vw(v) {
    var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
    return (v * w) / 100;
}
function showText() {
    if (document.getElementById('postViewer')) {
        returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[1].style.transition = '1s'
        returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[2].style.transition = '1s'
        returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[3].style.height = 'auto'
        var s = returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[3].offsetHeight
        returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[3].style.height = '0'
        setTimeout(function() {
            returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[1].style.width = '41vw'
            returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[1].style.height = '23.05vw'
            returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[2].style.height = '22.5vh'
            returnChildNodes(returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[3])[0].style.padding = '0 2vw 2vw 2vw';
            setTimeout(function() {
                setTimeout(function() {
                    returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[1].style.transition = 'none'
                    returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[2].style.transition = 'none'
                }, 650)
                returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[3].style.height = s;
                setTimeout(function() {
                    returnChildNodes(returnChildNodes(returnChildNodes(document.getElementById('postViewer'))[0])[0])[3].style.height = 'auto'
                }, 1000)
            }, 350)
        }, 1)
    }
}
