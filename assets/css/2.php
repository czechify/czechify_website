<?php
/*
header("Content-type: text/css; charset: UTF-8");
$stuff = str_replace(' {', '{', explode("?>\r\n", file_get_contents('./1.css'), 2)[1]);
echo $stuff;
exit();
$item = [];
foreach (json_decode(file_get_contents($ref.'assets/json/navbar.json'), 1)['navItems'] as $nav) {
    if ($nav['label'] == 'Videos') foreach ($nav['dropdownItems'] as $dd) {
        var_dump(str_replace('/', '---', $dd['hrefLocation']).'-tab');
    }
}
$kUsed = [];
$inSelector = false;
$inMedia = false;
foreach (explode("\r\n", $stuff) as $k => $item) {
    if (!($item)) continue;
    if (in_array($k, $kUsed)) continue;
    $x = 0;
    while (trim($item)) {
        if ($x > 10) break;
        $x++;
        if (!(($inSelector)||($inMedia))) if (strpos($item, '@media') !== false) { $inMedia = true; echo trim(explode('{', $item, 2)[0]).'{'; $item = trim(explode('{', $item, 2)[1]); }
        if (!($item)) break;
        if (!($inSelector)) if (strpos($item, '{') !== false) { $inSelector = true;

            //if (!($inSelector)) if (strpos($item, '{'))
            //if ($inSelector) if (strpos($item, '{'))
            //if (strpos())
        }
    }
    var_dump(trim($item));
}
*/
?>
