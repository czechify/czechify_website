<?php
if(isset($_GET['username'], $_GET['password'])){$username = $_GET['username']; $password = $_GET['password'];}

$usernames = array("plankto");
$passwords = array("plankto");

if (isset($username, $password)) {
    if ((array_search($username, $usernames) !== false)&&(array_search($password, $passwords) !== false)&&(array_search($username, $usernames) == array_search($password, $passwords) )) {
        $userData = json_decode(file_get_contents("./userData.json"), 1);
        $wordData = json_decode(file_get_contents("./allWords.json"), 1);
        $optionBox = "";
        foreach($wordData as $x) {
            $optionBox = $optionBox."<option value=\"".$x['wordEnglish']."\">English: ".$x['wordEnglish']." Czech: ".$x['wordCzech']." Value: ".$x['value']."</option>";
        }

        $usersAndWords1 = [];
        foreach ($userData as $user) {
            if (!(isset($usersAndWords1[array_search($user, $userData)]))) {
                $usersAndWords1[array_search($user, $userData)] = [];
            }
            foreach ($wordData as $word) {
                if (array_search(strval(array_search($word, $wordData)), $user['words']) !== false) {
                    $word['id'] = array_search($word, $wordData);
                    array_push($usersAndWords1[array_search($user, $userData)], $word);
                }
            }
        }
        
        $usersAndWords2 = [];
        foreach ($userData as $user) {
            if (!(isset($usersAndWords2[array_search($user, $userData)]))) {
                $usersAndWords2[array_search($user, $userData)] = [];
            }
            foreach ($wordData as $word) {
                if (!(array_search(strval(array_search($word, $wordData)), $user['words']) !== false)) {
                    $word['id'] = array_search($word, $wordData);
                    array_push($usersAndWords2[array_search($user, $userData)], $word);
                }
            }
        }
    }else{
        header("Location: ./auth.php");
    }
}else{
    header("Location: ./auth.php");
}

?>

<html>
    <body>
        <label>Selected Action: </label>

        <br>

        <select id="action" onchange="selectBox1(this)" autocomplete="off">
            <option value="newWord">Add new word to allWords.json</option>
            <option value="editWord">Edit word in allWords.json</option>
            <option value="deleteWord">Delete word from allWords.json</option>
            <option value="addToUser">Add word to a user</option>
            <option value="removeFromUser">Remove word from a user</option>
        </select>

        <br><br><br>

        <div id="newWord" style="display: block">
            <form action="./" autocomplete="off">
                <input type="hidden" autocomplete="false">
                <input type="hidden" name="redirect" value="./edit.php?username=<?php echo $username; ?>&password=<?php echo $password; ?>">
                <input type="hidden" value="newWord" name="action">
                <input type="hidden" value="Add new word to allWords.json">

                <label>Word in English:</label>
                <input type="text" placeholder="This is a test phrase" name="wordEnglish" style="width: 600px;" required>
                
                <br><br>

                <label>Word in Czech:</label>
                <input type="text" placeholder="Tohle je zkušební věta" name="wordCzech" style="width: 600px;" required>

                <br><br>

                <label>Word Value:</label>
                <input type="number" placeholder="5" name="value" min="1" max="10" required>

                <br><br><br>

                <input type="submit">
            </form>
        </div>

        <div id="editWord" style="display: none;">
            <form action="./" autocomplete="off">
                <input type="hidden" autocomplete="false">
                <input type="hidden" name="redirect" value="./edit.php?username=<?php echo $username; ?>&password=<?php echo $password; ?>">
                <input type="hidden" value="editWord" name="action">
                <input type="hidden" value="wordEnglish" name="identifier">

                <label>Select a wordset to edit: </label>
                <select name="identifiedValue">
                    <?php echo $optionBox; ?>
                </select>
                
                <br><br><br>

                <label>Word in English:</label>
                <input type="text" placeholder="This is a test phrase" name="wordEnglish" style="width: 600px;" required>
                
                <br><br>

                <label>Word in Czech:</label>
                <input type="text" placeholder="Tohle je zkušební věta" name="wordCzech" style="width: 600px;" required>

                <br><br>

                <label>Word Value:</label>
                <input type="number" placeholder="5" name="value" min="1" max="10" required>

                <br><br><br>

                <input type="submit">
            </form>
        </div>

        <div id="deleteWord" style="display: none;">
            <form action="./" autocomplete="off">
                <input type="hidden" autocomplete="false">
                <input type="hidden" name="redirect" value="./edit.php?username=<?php echo $username; ?>&password=<?php echo $password; ?>">
                <input type="hidden" value="deleteWord" name="action">
                <input type="hidden" value="wordEnglish" name="identifier">

                <label>Select a wordset to delete: </label>
                <select name="identifiedValue">
                    <?php echo $optionBox; ?>
                </select>

                <br><br>

                <input type="submit">

            </form>
        </div>
        
        <div id="addToUser" style="display: none;">
            <form action="./" autocomplete="off">
                <input type="hidden" autocomplete="false">
                <input type="hidden" name="redirect" value="./edit.php?username=<?php echo $username; ?>&password=<?php echo $password; ?>">
                <input type="hidden" value="addToUser" name="action">

                <input type="checkbox" name="random" onchange="checkBox1(this)">
                <label>Use random words</label>

                <br><br>

                <div id="useMinMaxDIV11" style="display: none;">

                    <input type="checkbox" name="minmax" id="useMinMax1" onchange="checkBox2(this)">
                    <label>Use Min/Max values</label>
                
                    <br><br>

                </div>

                <div id="useMinMaxDIV12" style="display: none;">

                    <label>Min: (inclusive)</label>
                    <input type="number" value="3" name="minValue" min="1" max="10">

                    <br><br>

                    <label>Max: (inclusive)</label>
                    <input type="number" value="7" name="maxValue" min="1" max="10">

                    <br><br>

                </div>

                <label>Select a User ID</label>
                <select id="people2" name="userID" onchange="selectBox2(this)" autocomplete="off">
                    <?php foreach (array_keys($usersAndWords2) as $userID) { echo "<option value=\"$userID\">$userID</option>"; } ?>
                </select>

                <br><br>

                <div id="specialDiv1">

                    <label>Select a wordset to add: </label>
                    <select id="words2" name="wordID"></select>

                </div>
                
                <br><br>

                <input type="submit">

            </form>
        </div>
        
        <div id="removeFromUser" style="display: none;">
            <form action="./" autocomplete="off">
            <input type="hidden" autocomplete="false">
                <input type="hidden" name="redirect" value="./edit.php?username=<?php echo $username; ?>&password=<?php echo $password; ?>">
                <input type="hidden" value="removeFromUser" name="action">

                <input type="checkbox" name="random" onchange="checkBox3(this)">
                <label>Use random words</label>

                <br><br>

                <div id="useMinMaxDIV21" style="display: none;">

                    <input type="checkbox" name="minmax" id="useMinMax2" onchange="checkBox4(this)">
                    <label>Use Min/Max values</label>
                
                    <br><br>

                </div>

                <div id="useMinMaxDIV22" style="display: none;">

                    <label>Min: (inclusive)</label>
                    <input type="number" value="3" name="minValue" min="1" max="10">

                    <br><br>

                    <label>Max: (inclusive)</label>
                    <input type="number" value="7" name="maxValue" min="1" max="10">

                    <br><br>

                </div>

                <label>Select a User ID</label>
                <select id="people1" name="userID" onchange="selectBox3(this)" autocomplete="off">
                    <?php foreach (array_keys($usersAndWords1) as $userID) { echo "<option value=\"$userID\">$userID</option>"; } ?>
                </select>

                <br><br>

                <div id="specialDiv2">

                    <label>Select a wordset to remove: </label>
                    <select id="words1" name="wordID"></select>

                </div>
                
                <br><br>

                <input type="submit">

            </form>
        </div>

        <script>
            var usersAndWords2 = JSON.parse('<?php echo json_encode($usersAndWords2); ?>')
            var usersAndWords1 = JSON.parse('<?php echo json_encode($usersAndWords1); ?>')
            var wordsData = JSON.parse('<?php echo json_encode($wordData); ?>')

            function selectBox2(sel) {
                var data = usersAndWords2[sel.value];
                var html = "";
                data.forEach((val) => {
                    html = html + "<option value=\"" + val['id'] + "\">English: " + val['wordEnglish'] + " Czech: " + val['wordCzech'] + " Value: " + val['value'] + "</option>";
                })
                document.getElementById("words2").innerHTML = html
            }

            function selectBox3(sel) {
                var data = usersAndWords1[sel.value];
                var html = "";
                data.forEach((val) => {
                    html = html + "<option value=\"" + val['id'] + "\">English: " + val['wordEnglish'] + " Czech: " + val['wordCzech'] + " Value: " + val['value'] + "</option>";
                })
                document.getElementById("words1").innerHTML = html
            }

            function checkBox1(sel) {
                if (sel.checked) {
                    document.getElementById("specialDiv1").style.display = "none"
                    document.getElementById("useMinMaxDIV11").style.display = "block"
                    if (document.getElementById("useMinMax1").checked) {
                        document.getElementById("useMinMaxDIV12").style.display = "block"
                    }else{
                        document.getElementById("useMinMaxDIV12").style.display = "none"
                    }
                }else{
                    document.getElementById("specialDiv1").style.display = "block"
                    document.getElementById("useMinMaxDIV11").style.display = "none"
                    document.getElementById("useMinMaxDIV12").style.display = "none"
                }
            }

            function checkBox2(sel) {
                if (sel.checked) {
                    document.getElementById("useMinMaxDIV12").style.display = "block"
                }else{
                    document.getElementById("useMinMaxDIV12").style.display = "none"
                }
            }

            function checkBox3(sel) {
                if (sel.checked) {
                    document.getElementById("specialDiv2").style.display = "none"
                    document.getElementById("useMinMaxDIV21").style.display = "block"
                    if (document.getElementById("useMinMax2").checked) {
                        document.getElementById("useMinMaxDIV22").style.display = "block"
                    }else{
                        document.getElementById("useMinMaxDIV22").style.display = "none"
                    }
                }else{
                    document.getElementById("specialDiv2").style.display = "block"
                    document.getElementById("useMinMaxDIV21").style.display = "none"
                    document.getElementById("useMinMaxDIV22").style.display = "none"
                }
            }

            function checkBox4(sel) {
                if (sel.checked) {
                    document.getElementById("useMinMaxDIV22").style.display = "block"
                }else{
                    document.getElementById("useMinMaxDIV22").style.display = "none"
                }
            }

            function selectBox1(sel) {
                d = document.getElementById("action");
                if (d.value == "newWord") {
                    document.getElementById("newWord").style.display = "block";
                    document.getElementById("editWord").style.display = "none";
                    document.getElementById("deleteWord").style.display = "none";
                    document.getElementById("addToUser").style.display = "none";
                    document.getElementById("removeFromUser").style.display = "none";
                }else if (d.value == "editWord") {
                    document.getElementById("newWord").style.display = "none";
                    document.getElementById("editWord").style.display = "block";
                    document.getElementById("deleteWord").style.display = "none";
                    document.getElementById("addToUser").style.display = "none";
                    document.getElementById("removeFromUser").style.display = "none";
                }else if (d.value == "deleteWord") {
                    document.getElementById("newWord").style.display = "none";
                    document.getElementById("editWord").style.display = "none";
                    document.getElementById("deleteWord").style.display = "block";
                    document.getElementById("addToUser").style.display = "none";
                    document.getElementById("removeFromUser").style.display = "none";
                }else if (d.value == "addToUser") {
                    document.getElementById("newWord").style.display = "none";
                    document.getElementById("editWord").style.display = "none";
                    document.getElementById("deleteWord").style.display = "none";
                    document.getElementById("addToUser").style.display = "block";
                    document.getElementById("removeFromUser").style.display = "none";
                }else if (d.value == "removeFromUser") {
                    document.getElementById("newWord").style.display = "none";
                    document.getElementById("editWord").style.display = "none";
                    document.getElementById("deleteWord").style.display = "none";
                    document.getElementById("addToUser").style.display = "none";
                    document.getElementById("removeFromUser").style.display = "block";
                }
            }
            window.addEventListener('load', (event) => {
                selectBox1(document.getElementById("action"))
                selectBox2(document.getElementById("people2"))
                selectBox3(document.getElementById("people1"))
            })
        </script>
    </body>
</html>