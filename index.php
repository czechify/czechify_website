<?php

$navData = json_decode(file_get_contents($ref.'assets/json/navbar.json'), 1);
$postsData = json_decode(file_get_contents($ref.'assets/json/posts.json'), 1);

$navHTML = '';
if ($navData) {
    $navHTML .= '<div><a href="'.$ref.'#/home/">'.$navData['titleName'].'</a></div>';
    if ((isset($navData['navItems']))&&($navData['navItems'])) {
        $navHTML .= '<div>';
        foreach ($navData['navItems'] as $navItem) {
            $navHTML .= '<div><a';
            if (isset($navItem['hrefType'], $navItem['hrefLocation'])) {
                if ($navItem['hrefType'] == "absolute") $navHTML .= ' href="'.$navItem['hrefLocation'].'"';
                if ($navItem['hrefType'] == "relative") $navHTML .= ' href="'.$ref.$navItem['hrefLocation'].'"';
                $navHTML .= ' id="'.substr(str_replace('/', '---', $navItem['hrefLocation']), 1).'-nav"';
            }
            $navHTML .= '><div>'.$navItem['label'].'</div></a>';
            if ((isset($navItem['dropdownItems']))&&($navItem['dropdownItems'])) {
                $navHTML .= '<div>';
                foreach ($navItem['dropdownItems'] as $ddItem) {
                    if (($x['status'] == 200)&&($navItem['label'] == 'Account')) {
                        if ($ddItem['label'] == 'Login') {
                            $ddItem['hrefLocation'] = '#/account/';
                            $ddItem['label'] = 'Settings';
                        }elseif ($ddItem['label'] == 'Register') {
                            $ddItem['hrefLocation'] = '#/logout/';
                            $ddItem['label'] = 'Logout';
                        }
                    }
                    $navHTML .= '<a';
                    if (isset($ddItem['hrefType'], $ddItem['hrefLocation'])) {
                        if ($ddItem['hrefType'] == "absolute") $navHTML .= ' href="'.$ddItem['hrefLocation'].'"';
                        if ($ddItem['hrefType'] == "relative") $navHTML .= ' href="'.$ref.$ddItem['hrefLocation'].'"';
                        $navHTML .= 'id="'.substr(str_replace('/', '---', $ddItem['hrefLocation']), 1).'-nav"';
                    }
                    $navHTML .= '><div>'.$ddItem['label'].'</div></a>';
                }
                $navHTML .= '</div>';
            }
            $navHTML .= '</div>';
        }
        $navHTML .= '</div>';
    }
}

$homeTabHTML = '<h1>Home</h1><div>';
foreach (array_reverse($postsData, true) as $postID => $post) $homeTabHTML .= '<a href="#/home/posts/'.$postID.'/" postID="'.$postID.'"><div><div><div><div><div>CZECHIFY</div><div><svg viewBox="0 200 612 375"><path></svg></div></div></div><div><div>'.$post['name'].'</div></div><div '.strtolower(substr($post['type'], 0, 1)).'><div>'.$post['type'].'</div></div></div></div></a>';
$homeTabHTML .= '</div>';

$allVideosHTML = '<h1>All Videos</h1><div>';
foreach ($postsData as $postID => $post) $allVideosHTML .= '<a href="#/videos/posts/'.$postID.'/" postID="'.$postID.'"><div><div><div><div><div>CZECHIFY</div><div><svg viewBox="0 200 612 375"><path></svg></div></div></div><div><div>'.$post['name'].'</div></div><div '.strtolower(substr($post['type'], 0, 1)).'><div>'.$post['type'].'</div></div></div></div></a>';
$allVideosHTML .= '</div>';

$beginnerVideosHTML = '<h1>Beginner Videos</h1><div>';
foreach ($postsData as $postID => $post) if (substr($post['type'], 0, 1) == 'A') $beginnerVideosHTML .= '<a href="#/videos/beginner/posts/'.$postID.'/" postID="'.$postID.'"><div><div><div><div><div>CZECHIFY</div><div><svg viewBox="0 200 612 375"><path></svg></div></div></div><div><div>'.$post['name'].'</div></div><div '.strtolower(substr($post['type'], 0, 1)).'><div>'.$post['type'].'</div></div></div></div></a>';
$beginnerVideosHTML .= '</div>';

$intermediateVideosHTML = '<h1>Intermediate Videos</h1><div>';
foreach ($postsData as $postID => $post) if (substr($post['type'], 0, 1) == 'B') $intermediateVideosHTML .= '<a href="#/videos/intermediate/posts/'.$postID.'/" postID="'.$postID.'"><div><div><div><div><div>CZECHIFY</div><div><svg viewBox="0 200 612 375"><path></svg></div></div></div><div><div>'.$post['name'].'</div></div><div '.strtolower(substr($post['type'], 0, 1)).'><div>'.$post['type'].'</div></div></div></div></a>';
$intermediateVideosHTML .= '</div>';

$advancedVideosHTML = '<h1>Advanced Videos</h1><div>';
foreach ($postsData as $postID => $post) if (substr($post['type'], 0, 1) == 'C') $advancedVideosHTML .= '<a href="#/videos/advanced/posts/'.$postID.'/" postID="'.$postID.'"><div><div><div><div><div>CZECHIFY</div><div><svg viewBox="0 200 612 375"><path></svg></div></div></div><div><div>'.$post['name'].'</div></div><div '.strtolower(substr($post['type'], 0, 1)).'><div>'.$post['type'].'</div></div></div></div></a>';
$advancedVideosHTML .= '</div>';

$aboutUsHTML = '<div><div>';
foreach (json_decode(file_get_contents('./assets/json/social.json'), 1) as $social) $aboutUsHTML .= '<div><a target="_blank" href="'.$social['url'].'"><svg viewBox="0 0 615 615" '.$social['type'].'>'.$social['svg_code'].'</svg></a><a href="'.$social['url'].'">'.$social['text'].'</a></div>';
$aboutUsHTML .= '</div></div>';

$loginTabHTML = '<div><div>Login</div><form method="post" action="./api/login.php"><input name="username" placeholder="Username"><input type="password" name="password" placeholder="Password"><input value="Login" type="submit" required><div><input value="Login" type="submit" required><input value="Login" type="submit"><input value="Login" type="submit"><input value="Login" type="submit"></div></form></div>';

$registerTabHTML = '<div><div>Register</div><form method="post" action="./api/register.php"><input name="username" placeholder="Username" required><input name="email" placeholder="Email Address" required><input type="password" name="password" placeholder="Password" required><input value="Register" type="submit"><div><a href="">Google</a><a href="">Facebook</a><a href="https://discord.com/oauth2/authorize?client_id=530470790190071810&redirect_uri=https%3A%2F%2Fauth.czechify.com%2Fdiscord%2F&response_type=code&scope=identify%20email%20connections">Discord</a><a href="https://github.com/login/oauth/authorize?client_id=c73e21ffb2966179cc7c&redirect_uri=https://auth.czechify.com/github/">GitHub</a><a href="https://id.twitch.tv/oauth2/authorize?response_type=code&client_id=3dz162czfvysilud6zu7txk2s74pwz&redirect_uri=https://auth.czechify.com/twitch/&scope=user:read:email">Twitch</a><a href=>Twitter</a></div></form></div>';

?>
<!--
███╗   ██╗ ██████╗ ██╗    ██╗    ██╗    ██╗██╗████████╗██╗  ██╗    ███████╗ ██████   ██████  ██╗ ██╗
████╗  ██║██╔═══██╗██║    ██║    ██║    ██║██║╚══██╔══╝██║  ██║    ╚════██║██╔═████╗██╔═████╗╚═╝██╔╝
██╔██╗ ██║██║   ██║██║ █╗ ██║    ██║ █╗ ██║██║   ██║   ███████║    ███████║██║██╔██║██║██╔██║  ██╔╝
██║╚██╗██║██║   ██║██║███╗██║    ██║███╗██║██║   ██║   ██╔══██║    ╚════██║████╔╝██║████╔╝██║ ██╔╝
██║ ╚████║╚██████╔╝╚███╔███╔╝    ╚███╔███╔╝██║   ██║   ██║  ██║    ███████║ ██████╔╝ ██████╔╝██╔╝██╗
╚═╝  ╚═══╝ ╚═════╝  ╚══╝╚══╝      ╚══╝╚══╝ ╚═╝   ╚═╝   ╚═╝  ╚═╝    ╚══════╝ ╚═════╝  ╚═════╝ ╚═╝ ╚═╝

███╗   ███╗ ██████╗ ██████╗ ███████╗      ██╗██████  ██╗██╗   ██╗██╗  ███████╗
████╗ ████║██╔═══██╗██╔══██╗██╔════╝     ██╔╝██╔══██╗██║██║   ██║╚██╗ ██╔════╝
██╔████╔██║██║   ██║██████╔╝█████╗      ██╔╝ ██║  ██║██║██║   ██║ ╚██╗███████╗
██║╚██╔╝██║██║   ██║██╔══██╗██╔══╝      ╚██╗ ██║  ██║██║╚██╗ ██╔╝ ██╔╝╚════██║
██║ ╚═╝ ██║╚██████╔╝██║  ██║███████╗     ╚██╗██████╔╝██║ ╚████╔╝ ██╔╝ ███████║
╚═╝     ╚═╝ ╚═════╝ ╚═╝  ╚═╝╚══════╝      ╚═╝╚═════╝ ╚═╝  ╚═══╝  ╚═╝  ╚══════╝
-->
<html>
    <head>
        <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.1/css/pro.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="./assets/css/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link rel="shortcut icon" href="/favicon.ico">
    	<link rel="icon" sizes="16x16 32x32 64x64" href="/favicon.ico">
    	<link rel="icon" type="image/png" sizes="196x196" href="./assets/favicon/192.png">
    	<link rel="icon" type="image/png" sizes="160x160" href="./assets/favicon/160.png">
    	<link rel="icon" type="image/png" sizes="96x96" href="./assets/favicon/96.png">
    	<link rel="icon" type="image/png" sizes="64x64" href="./assets/favicon/64.png">
    	<link rel="icon" type="image/png" sizes="32x32" href="./assets/favicon/32.png">
    	<link rel="icon" type="image/png" sizes="16x16" href="./assets/favicon/16.png">
    	<link rel="apple-touch-icon" href="./assets/favicon/57.png">
    	<link rel="apple-touch-icon" sizes="114x114" href="./assets/favicon/114.png">
    	<link rel="apple-touch-icon" sizes="72x72" href="./assets/favicon/72.png">
    	<link rel="apple-touch-icon" sizes="144x144" href="./assets/favicon/144.png">
    	<link rel="apple-touch-icon" sizes="60x60" href="./assets/favicon/60.png">
    	<link rel="apple-touch-icon" sizes="120x120" href="./assets/favicon/120.png">
    	<link rel="apple-touch-icon" sizes="76x76" href="./assets/favicon/76.png">
    	<link rel="apple-touch-icon" sizes="152x152" href="./assets/favicon/152.png">
    	<link rel="apple-touch-icon" sizes="180x180" href="./assets/favicon/180.png">
    	<meta name="msapplication-TileColor" content="#FFFFFF">
    	<meta name="msapplication-TileImage" content="./assets/favicon/144.png">
    	<meta name="msapplication-config" content="./assets/favicon/browserconfig.xml">
    </head>
    <body>
        <div><?php echo $navHTML; ?></div><div><div id="---home----tab"><?php echo $homeTabHTML ?></div><div id="---about-us----tab"><?php echo $aboutUsHTML ?></div><div id="---videos----tab"><?php echo $allVideosHTML ?></div><div id="---videos---beginner----tab"><?php echo $beginnerVideosHTML ?></div><div id="---videos---intermediate----tab"><?php echo $intermediateVideosHTML ?></div><div id="---videos---advanced----tab"><?php echo $advancedVideosHTML ?></div><div id="---flashcards---your-collection----tab">Ur flashcards</div><div id="---login----tab"><?php echo $loginTabHTML; ?></div><div id="---register----tab"><?php echo $registerTabHTML; ?></div></div><div id="postViewer"></div><script src="./assets/js/0.js"></script>
        <script>
            function menuCleanup() {
                var els1 = document.getElementsByClassName("active");
                for (i = 0; i < els1.length; i++) if (els1[i]) els1[i].className = "";
            }
            tabs = ['home', 'about-us', 'videos', 'videos/beginner', 'videos/intermediate', 'videos/advanced', 'flashcards/your-collection'/*, 'flashcards/play'*/, 'login', 'register'];
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
            }else setTimeout(function() { location.hash = '/' + tabs[0] + '/'; }, 1);
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
                }else setTimeout(function() { location.hash = '/' + tabs[0] + '/'; }, 1);
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
                        postOBJ['words'].forEach((word) => { wordsHTML = wordsHTML + '<div style="min-width: calc(' + longestWordLength + 'ch);">' + word + '</div>'; })
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
        </script>
    </body>
</html>
