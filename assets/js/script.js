function expandElement(element, height) {
    setTimeout(function() { returnChildNodes(element)[1].style.maxHeight = height; }, 1);
}
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

$(document).ready(function() {
    window.dropdowns1 = {};
    window.dropdowns2 = {};
    window.dropdowns3 = {};
    var p = 0;
    var elements = elsToArr(returnChildNodes(returnChildNodes(document.getElementsByTagName('body')[0].getElementsByTagName('div')[0])[1]));
    elements.forEach((element) => {
        if ((returnChildNodes(element)[0].getAttribute('href') == null)&&(returnChildNodes(element)[1])) {
            returnChildNodes(element)[0].addEventListener('click', function() { dropdownController(returnChildNodes(element)[0], false) } );
            returnChildNodes(element)[1].style.height = "auto";
            var height = returnChildNodes(element)[1].offsetHeight;
            returnChildNodes(element)[1].style.height = 0;
            expandElement(element, height);
            window.dropdowns1[p.toString()] = element;
            window.dropdowns2[p.toString()] = false;
            window.dropdowns3[p.toString()] = false;
            p++;
        }
    })
})

function dropdownController(element) {
    var el = returnChildNodes(element.parentElement)[1];
    var maxH = el.style.maxHeight;
    var dropdownID = Object.keys(window.dropdowns1)[Object.values(window.dropdowns1).indexOf(el.parentElement)];
    if (!(window.dropdowns2[dropdownID])) {
        if (window.dropdowns3[dropdownID]) {
            el.style.height = 0;
            setTimeout(function() { window.dropdowns3[dropdownID] = false; }, 175)
        }else {
            el.style.height = maxH;
            setTimeout(function() { window.dropdowns3[dropdownID] = true; }, 175)
        }
        window.dropdowns2[dropdownID] = true;
        setTimeout(function() { window.dropdowns2[dropdownID] = false; }, 350)
    }
}