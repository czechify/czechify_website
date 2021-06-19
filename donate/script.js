var createCheckoutSession = function(priceId) {
    return fetch("/donate/create-checkout-session.php?priceId=" + priceId, {
        method: "GET",
        headers: {"Content-Type": "application/json"},
    }).then(function(result) { return result.json(); });
};
var handleResult = function(result) {
    if (result.error) {
        var displayError = document.getElementById("error-message");
        displayError.textContent = result.error.message;
    }
};
fetch("/donate/config.php").then(function(result) { return result.json(); }).then(function(json) {
    var publishableKey = json.publishableKey;
    var priceID1 = json.price_1;
    var priceID2 = json.price_2;
    var priceID3 = json.price_3;
    var priceID4 = json.price_4;
    var priceID5 = json.price_5;
    var priceID6 = json.price_6;
    var stripe = Stripe(publishableKey);
    if (document.getElementById('tidal-plan-btn')) document.getElementById("tidal-plan-btn").addEventListener("click", function(evt) {
        createCheckoutSession(priceID6).then(function(data) {
            stripe.redirectToCheckout({sessionId: data.sessionId}).then(handleResult);
        });
    });
    document.getElementById("beginner-plan-btn").addEventListener("click", function(evt) {
        createCheckoutSession(priceID1).then(function(data) {
            stripe.redirectToCheckout({sessionId: data.sessionId}).then(handleResult);
        });
    });
    document.getElementById("intermediate-plan-btn").addEventListener("click", function(evt) {
        createCheckoutSession(priceID2).then(function(data) {
            stripe.redirectToCheckout({sessionId: data.sessionId}).then(handleResult);
        });
    });
    document.getElementById("advanced-plan-btn").addEventListener("click", function(evt) {
        createCheckoutSession(priceID3).then(function(data) {
            stripe.redirectToCheckout({sessionId: data.sessionId}).then(handleResult);
        });
    });
    document.getElementById("fluent-plan-btn").addEventListener("click", function(evt) {
        createCheckoutSession(priceID4).then(function(data) {
            stripe.redirectToCheckout({sessionId: data.sessionId}).then(handleResult);
        });
    });
    document.getElementById("native-speaker-plan-btn").addEventListener("click", function(evt) {
        createCheckoutSession(priceID5).then(function(data) {
            stripe.redirectToCheckout({sessionId: data.sessionId}).then(handleResult);
        });
    });
});
