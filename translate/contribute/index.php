<?php

$arr = array_slice(explode('/', substr(explode('?', $_SERVER['REQUEST_URI'], 2)[0], strlen(dirname($_SERVER['PHP_SELF'])))), 1, -1);

$languages = json_decode(file_get_contents('../languages.json'), 1);

$HTMLref = str_repeat('../', count($arr));

$languageResolver = json_decode(file_get_contents('../language_resolver.json'), 1);

$languageSelectorHTML1 = '';
$languageSelectorHTML2 = '';

$fromLanguages = [];
$toLanguages = [];

foreach ($languages as $l) {
    array_push($fromLanguages, explode('-', $l)[0]);
    array_push($toLanguages, explode('-', $l)[1]);
}

foreach (array_values(array_filter(array_unique($fromLanguages))) as $k => $fl) if (!($k)) $languageSelectorHTML1 .= '<div selected  id="lanSelector_from_'.$fl.'" onclick="changeFromLang(\''.$fl.'\')"><div>'.$languageResolver[$fl].'</div></div>'; else $languageSelectorHTML1 .= '<div id="lanSelector_from_'.$fl.'" onclick="changeFromLang(\''.$fl.'\')"><div>'.$languageResolver[$fl].'</div></div>';

foreach (array_values(array_filter(array_unique($toLanguages))) as $k => $tl) if (!($k)) $languageSelectorHTML2 .= '<div selected id="lanSelector_to_'.$tl.'" onclick="changeToLang(\''.$tl.'\')"><div>'.$languageResolver[$tl].'</div></div>'; else $languageSelectorHTML2 .= '<div id="lanSelector_to_'.$tl.'" onclick="changeToLang(\''.$tl.'\')"><div>'.$languageResolver[$tl].'</div></div>';

?>
<html>
    <head>
        <link rel="stylesheet" href="../assets/css/0.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <style>* { font-family: 'Montserrat', sans-serif; }</style>
    </head>
    <body>
        <div>
            <div>
                <a href="<?php echo $HTMLref; ?>">Transalate</a>
                <a href="<?php echo $HTMLref; ?>contribute/">Contribute</a>
                <a href="https://czechify.com/">Czechify</a>
            </div>
        </div>
        <div>
            <div>
                <div>
                    <div>
                        <div><?php echo $languageSelectorHTML1; ?></div>
                    </div>
                    <div>
                        <div><?php echo $languageSelectorHTML2; ?></div>
                        <div>This translation is <span id="translation_accuracy">100%</span> accurate</div>
                    </div>
                    <div onclick="tButtonHandler(this)">Translate</div>
                </div>
                <div>
                    <textarea id="translate_from" placeholder="Enter text"></textarea>
                    <textarea id="translate_to" readonly placeholder="Translation"></textarea>
                </div>
                <div onclick="swapLanguages()">
                    <svg viewBox="0 0 615 585">
                        <path d="M137.52,479.37c15.08,11.7,30.31,23.21,45.19,35.15,18.5,14.85,21.63,34.47,8.49,50.5-12.57,15.33-31.45,16.55-49.82,2.43Q78.85,519.38,16.75,470.71c-22.6-17.79-22.32-41.29.77-58.84Q81.77,363,146.7,315.1c18.1-13.44,37.63-11.48,49.76,4.21,11.83,15.31,8.24,35.2-9.13,49.12-8,6.43-16.39,12.4-24.57,18.62-7.57,5.76-15.1,11.58-25.89,19.87,10,.43,16.29.95,22.55.95q202.32.06,404.62.05c6,0,12.17-.35,18.11.52,17.45,2.55,30,16.9,29.85,33.5s-12.68,30.66-30.3,33c-6,.78-12.08.45-18.12.45q-201.41,0-402.8,0H139.12C138.59,476.72,138.06,478,137.52,479.37Z"></path>
                        <path d="M475.3,98.24C460.12,86.53,444.82,75,429.78,63.1,410.94,48.21,407.32,29,420,12.77,432.27-3.06,452.73-4.4,471.38,10q61.67,47.67,123,95.81c23.64,18.62,23.4,41.67-.59,59.88q-62.81,47.67-126.09,94.71c-20.08,14.95-40.38,13.37-52.71-3.37-11.88-16.15-7.5-35.23,11.79-50.07,15.14-11.65,30.48-23,44.91-38.34h-18.9c-137.82,0-275.66-.72-413.48.62C12.7,169.52-9.14,145.42,3.91,120c7.6-14.8,21-18.14,36.64-18.09,90.07.29,180.15.14,270.22.14h162.9C474.21,100.79,474.76,99.51,475.3,98.24Z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </body>
    <script>
        function returnChildNodes(element) {
            var children = [];
            for (i = 0; i < element.childNodes.length; i++) if (element.childNodes[i].tagName) children.push(element.childNodes[i]);
            return children;
        }
        function htmlToArr(h) {
            var arr = [];
            for (i = 0; i < h.length; i++) arr.push(h[i]);
            return arr;
        }
        function elsToArr(elements) {
            var els = [];
            for (i = 0; i < elements.length; i++) els.push(elements[i]);
            return els;
        }
    </script>
</html>
