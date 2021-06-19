<?php

$mysqli = new mysqli("martinnaj.ddns.net", "czechify", 'ZnXwO4kfhitpVDxw', "czechify");

$userLanguages = ["CS_CZ", "EN_GB", "DE_DE", "SV_SE"];
$languages = $languageCombinations = $translationQueue = $translationQueues = $langData = [];

$query = $mysqli->query("SELECT * FROM `language_meta`");
if ($query) while ($data = mysqli_fetch_assoc($query)) $languages[$data['language_code']] = $data['text']; else die("Query 1 Failed");

foreach ($userLanguages as $lan1) foreach ($userLanguages as $lan2) if (!($lan1 == $lan2)) {
    if (!(isset($langData[$lan1]))) $langData[$lan1] = [];
    if (!(isset($langData[$lan1][$lan2]))) $langData[$lan1][$lan2] = [];
    $languageCombinations[$lan1.'-'.$lan2] = $languages[$lan1].' ➜ '.$languages[$lan2];
    $translationQueues[$lan1.'-'.$lan2] = [];
}

$query = $mysqli->query("SELECT `base_language`, `tmp_word_id`, `phrase`, `languages_done` FROM `translation_queue`");
if ($query) while ($row = mysqli_fetch_assoc($query)) $translationQueue[] = $row; else die("Query 2 Failed");

foreach ($translationQueue as $q) if (in_array($q['base_language'], $userLanguages)) foreach ($languageCombinations as $langCode => $langText) if ((explode('-', $langCode, 2)[0] == $q['base_language'])&&(!(in_array(explode('-', $langCode, 2)[1], json_decode($q['languages_done'], 1))))) $translationQueues[$langCode][] = ['id' => $q['tmp_word_id'], 'phrase' => $q['phrase']];

?><!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Contribute</title>
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div>
			<div class="languageNameContainer notSelectable">
				<div class="topLanguage"><?php echo $languages[array_keys($langData)[0]]; ?></div>
				<div class="bottomLanguage"><?php echo $languages[array_keys($langData[array_keys($langData)[0]])[0]]; ?></div>
				<div class="languageArrowContainer"><span class="material-icons md-36">arrow_drop_down</span></div>
				<div class="languageDropdownContent dropdownHidden">
					<ul>
                        <?php foreach ($languageCombinations as $l) echo '<li>'.$l.'</li>'; ?>
					</ul>
		 		</div>
			</div>
			<div>
				<div class="notSelectable tooltip">
					<span class="material-icons statsIcon">local_fire_department</span><br><span class="statsContainerText">1680</span><span class="tooltiptext">Streak</span>
				</div>
				<div id="addedWords" class="notSelectable tooltip">
					<span class="material-icons statsIcon">inventory_2</span><br><span class="statsContainerText" id="addedWordsCounter">640</span><span class="tooltiptext">Your All-Time Contributions</span>
				</div>
				<div class="notSelectable tooltip">
					<span class="material-icons statsIcon">calendar_today</span><br><span class="statsContainerText">55</span><span class="tooltiptext">Your Contributions Today</span>
				</div>
				<div class="notSelectable tooltip">
					<span class="material-icons statsIcon">public</span><br><span class="statsContainerText">55</span><span class="tooltiptext">Contributions Today</span>
				</div>
				<label class="notSelectable colorPicker">
					<span class="material-icons">colorize</span>
					<input type="color" id="colorPicker" name="head" value="#e66465">
				</label>
			</div>
			<div class="lastWordsContainer notSelectable">
                 <!-- foreach ($translationQueues1)  -->
				<div id="word_12325432587564w3568" class="lastWordContainer" onclick="wordToTextboxes(this, true)">
                    <div>Jabko</div>
                    <div></div>
                    <div>Epl</div>
                </div>
				<div id="word_12325432587564w3564" class="lastWordContainer" onclick="wordToTextboxes(this, true)"><div>Hloupec</div><div></div><div>Idiot</div></div>
				<div id="word_12325432587564w3566" class="lastWordContainer" onclick="wordToTextboxes(this, true)"><div>Blb</div><div></div><div>Pedo</div></div>
				<div id="word_12325432587564w3569" class="lastWordContainer" onclick="wordToTextboxes(this, true)"><div>Auto</div><div></div><div>Kára</div></div>
			</div>
			<div class="mainSectionContainer">
				<div class="mainSection">
					<div class="left mainSideContainer">
					</div>
						<div class="center">
							<input type="text" name="topText" id="topTextArea" class="textArea topTextArea" readonly>
							<input type="text" name="bottomText" id="bottomTextArea" class="textArea bottomTextArea">
							<div class="bottomButtonsContainer">
								<div class="bottomButton bottomButtonSmall button wordArrowLeft tooltip" >
									<span class="material-icons md-64">keyboard_arrow_left</span>
									<span class="tooltiptextBottom">Previous</span>
								</div>
								<div class="bottomButton bottomButtonLeft button tooltip">
									<span class="material-icons md-64">clear</span>
									<span class="tooltiptextBottom">Delete</span>
								</div>
								<div class="bottomButton bottomButtonRight button tooltip">
									<span class="material-icons md-64">done</span>
									<span class="tooltiptextBottom">Approve</span>
								</div>
								<div class="bottomButton bottomButtonSmall button wordArrowRight tooltip">
									<span class="material-icons md-64">keyboard_arrow_right</span>
									<span class="tooltiptextBottom">Next</span>
								</div>
							</div>
						</div>
						<div class="right mainSideContainer">
					</div>
				</div>
			</div>
		</div>

		<script>
            function returnChildNodes(element) {
                var children = [];
                for (i = 0; i < element.childNodes.length; i++) if (element.childNodes[i].tagName) children.push(element.childNodes[i]);
                return children;
            }
	        var buttonAccept = document.getElementsByClassName('bottomButtonRight')[0];
			var buttonDiscard = document.getElementsByClassName('bottomButtonLeft')[0];
	        var section = document.getElementsByClassName('mainSection')[0];
	        var icon = document.getElementById('addedWords');
	        var addedWordsCounter = document.getElementById('addedWordsCounter');
	        var buttonLeft = document.getElementsByClassName('wordArrowLeft')[0];
	        var buttonRight = document.getElementsByClassName('wordArrowRight')[0];
	        var dropdown = document.getElementsByClassName('languageNameContainer')[0];
	        var dropdownContent = document.getElementsByClassName('languageDropdownContent')[0];
	        var dropdownToggled = false;
	        var dropdownCanBeToggled = true;
	        var submitLocked = false;
	       	var statsIcons = returnChildNodes(returnChildNodes(document.body.getElementsByTagName('div')[0])[0]);
	       	var lastWordsContainer = document.getElementsByClassName('lastWordsContainer')[0]
		    var topTextArea = document.getElementById('topTextArea');
		    var bottomTextArea = document.getElementById('bottomTextArea');

			var currentWordId;

	        buttonAccept.onclick = function () {
	        	if (!(submitLocked)) {
	        		lockSubmit();
		        	if(topTextArea.value == ''){
		        		topTextArea.classList.add('textAreaRedBorder');
		        		topTextArea.focus();
		        		topTextArea.onanimationend = function () {topTextArea.classList.remove('textAreaRedBorder');}
		        	}else if (bottomTextArea.value == '' ) {
		        		bottomTextArea.classList.add('textAreaRedBorder');
		        		bottomTextArea.focus();
		        		bottomTextArea.onanimationend = function () {bottomTextArea.classList.remove('textAreaRedBorder')}
		        	}else addWordToList();
	        	}
	        }

			buttonDiscard.onclick = function () {
	            section.classList.add('animatedMainSectionDiscard');
				bottomTextArea.focus();
				setTimeout(function() { section.classList.remove('animatedMainSectionDiscard'); }, 600);
	            fullyRemove = true;
	        }

			buttonLeft.onclick = function () { showPreviousWord(); }
		    buttonRight.onclick = function () { showNextWord(); }

		    dropdown.onclick = function () {
		    	if (dropdownCanBeToggled){
		    		dropdownCanBeToggled = false;
			    	if(!(dropdownToggled)){
			    		dropdownContent.classList.remove('dropdownHidden');
			    		dropdownToggled = true;
						setTimeout(function() { if(dropdownCanBeToggled) dropdownCanBeToggled = false; else dropdownCanBeToggled = true; }, 300);
			    	}else{
			    		dropdownContent.classList.add('dropdownHidden' );
			    		dropdownToggled = false;
						setTimeout(function() { if(dropdownCanBeToggled) dropdownCanBeToggled = false; else dropdownCanBeToggled = true; }, 300);
			    	}
		    	}else return;
		    }

	    	var tooltips = document.getElementsByClassName('tooltip');

	    	for (var i = 0; i < tooltips.length; i++) tooltips[i].addEventListener('mouseenter', e => { showTooltip(e.target.querySelector('.material-icons')); })

			function lockSubmit() {
				if (!(submitLocked)) {
                    submitLocked = true;
					setTimeout(unlockSubmit, 1000);
				}
			}
			function unlockSubmit () { if (submitLocked) submitLocked = false;}

			function showTooltip(icon) {
	    		icon.classList.add('statsIconAnimation')
	    		icon.onanimationend = function () { icon.classList.remove('statsIconAnimation'); }
	    	}

	    	function addWordToList() {
		    	var addedWordContainer = document.createElement('div');
		    	addedWordContainer.id = "word_" + Math.random().toString(36).substr(2, 9);
				addedWordContainer.classList = 'lastWordContainer';
				addedWordContainer.setAttribute("onclick", "wordToTextboxes(this, true)");

		    	var addedWordOriginal = topTextArea.value;
		    	var addedWordTranslation = bottomTextArea.value;

		    	addedWordContainer.innerHTML = '<div>' + addedWordOriginal + '</div><div></div><div>' + addedWordTranslation + '</div>';

		    	lastWordsContainer.prepend(addedWordContainer);
				bottomTextArea.focus();
				topTextArea.value = '';
				bottomTextArea.value = '';

				section.classList.add('animatedMainSectionStore');

				setTimeout(function() {
					icon.classList.add('buttonAnimation');
					addedWordsCounter.innerHTML = parseInt(addedWordsCounter.innerHTML) + 1;
				}, 360);

				setTimeout(function() {
					section.classList.remove('animatedMainSectionStore');
					icon.classList.remove('buttonAnimation');
				}, 800);

		    }

			function wordToTextboxes(elem, animation) {
				currentWordId = elem.id;
				var topText = elem.getElementsByTagName('div')[0].innerHTML;
				var bottomText = elem.getElementsByTagName('div')[2].innerHTML;

				if (animation) {
					topTextArea.value = topText;
					topTextArea.classList.add('textAreaWordAdded');

					setTimeout(function() {
						bottomTextArea.value = bottomText;
						bottomTextArea.classList.add('textAreaWordAdded');
					}, 200);

					topTextArea.onanimationend = function () { topTextArea.classList.remove('textAreaWordAdded'); }

					bottomTextArea.onanimationend = function () { bottomTextArea.classList.remove('textAreaWordAdded'); }
				}else {
					topTextArea.value = topText;
					bottomTextArea.value = bottomText;
				}
			}

			function showPreviousWord() {
				var currentWord = document.getElementById(currentWordId);
				var previousWord = currentWord.previousElementSibling;
				if (previousWord == null) return; else {
					section.classList.add('animatedMainSectionMoveLeft');
					setTimeout(function() {
						buttonRight.style.visibility  = 'visible';
						wordToTextboxes(previousWord);
						currentWord = previousWord.id;
						var previousPreviousWord = document.getElementById(currentWordId).previousElementSibling;
						console.log(previousPreviousWord);
						if (previousPreviousWord == null) buttonLeft.style.visibility  = 'hidden'; else buttonLeft.style.visibility  = 'visible';
					}, 300);

					setTimeout(function() { section.classList.remove('animatedMainSectionMoveLeft'); }, 600);
				}
			}

			function showNextWord() {
				var currentWord = document.getElementById(currentWordId);
				var nextWord = currentWord.nextElementSibling;

				if (nextWord == null) return; else {
					section.classList.add('animatedMainSectionMoveRight');
					setTimeout(function() {
						buttonLeft.style.visibility  = 'visible';
						wordToTextboxes(nextWord);
						currentWord = nextWord.id;
						var nextNextWord = document.getElementById(currentWordId).nextElementSibling;

						if (nextNextWord == null) buttonRight.style.visibility  = 'hidden'; else buttonRight.style.visibility  = 'visible';
					}, 300);

					setTimeout(function() {
						section.classList.remove('animatedMainSectionMoveRight');
					}, 600);
				}
			}

			var colorPicker = document.getElementById('colorPicker');
			colorPicker.addEventListener("change", watchColorPicker, false);

			function watchColorPicker(event) { hexToHSL(event.target.value); }

			function invertColor(hex) {
			    if (hex.indexOf('#') === 0) hex = hex.slice(1);
			    if (hex.length === 3) hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
			    if (hex.length !== 6) throw new Error('Invalid HEX color.');
			    var r = parseInt(hex.slice(0, 2), 16), g = parseInt(hex.slice(2, 4), 16), b = parseInt(hex.slice(4, 6), 16);
			    return (r * 0.299 + g * 0.587 + b * 0.114) > 186 ? '#0e0e0e' : '#FFFFFF';
			}

			function hexToHSL(H) {
                let r = 0, g = 0, b = 0;
				if (H.length == 4) {
                    r = "0x" + H[1] + H[1];
                    g = "0x" + H[2] + H[2];
                    b = "0x" + H[3] + H[3];
                } else if (H.length == 7) {
                    r = "0x" + H[1] + H[2];
                    g = "0x" + H[3] + H[4];
                    b = "0x" + H[5] + H[6];
				}
				r /= 255;
				g /= 255;
				b /= 255;
				let cmin = Math.min(r,g,b), cmax = Math.max(r,g,b), delta = cmax - cmin, h = 0, s = 0, l = 0;
				if (delta == 0) h = 0; else if (cmax == r) h = ((g - b) / delta) % 6; else if (cmax == g) h = (b - r) / delta + 2; else h = (r - g) / delta + 4;
                h = Math.round(h * 60);

				if (h < 0) h += 360;
				l = (cmax + cmin) / 2;
				s = delta == 0 ? 0 : delta / (1 - Math.abs(2 * l - 1));
				s = +(s * 100).toFixed(1);
				l = +(l * 100).toFixed(1);
				var textColor = invertColor(event.target.value);
				if (textColor == "#FFFFFF") {
					var textAreaBackgroundColor = "#0e0e0e";
					var backgroundColor = "#1b1b1b";
				}else {
					var textAreaBackgroundColor = "#FFFFFF";
					var backgroundColor = "#f1f1f1";
				}
				console.log(textColor);
				console.log(textAreaBackgroundColor);
				document.querySelector(":root").style.setProperty("--primary-color-h", h);
				document.querySelector(":root").style.setProperty("--primary-color-s", s + "%");
				document.querySelector(":root").style.setProperty("--primary-color-l", l + "%");
				document.querySelector(":root").style.setProperty("--clr-text", textColor);
				document.querySelector(":root").style.setProperty("--clr-textarea-bg", textAreaBackgroundColor);
				document.querySelector(":root").style.setProperty("--clr-bg", backgroundColor);
			}
            document.body.onload = function() {
                document.getElementsByClassName('lastWordsContainer notSelectable')[0].getElementsByTagName('div')[0].click()
            }
		</script>
	</body>
</html>
