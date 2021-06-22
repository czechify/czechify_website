const urlParams = new URLSearchParams(window.location.search);
const sessionId = urlParams.get("session_id")
if (sessionId) {
    fetch("/donate/get-checkout-session.php?sessionId=" + sessionId).then(function(result){ return result.text(); }).then(function(session){ document.getElementsByClassName("sr-content")[0].innerHTML = session; }).catch(function(err){ });
    const manageBillingForm = document.querySelector('#manage-billing-form');
    manageBillingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('/donate/customer-portal.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json',},
            body: JSON.stringify({sessionId: sessionId}),
        }).then((response) => response.json()).then((data) => { window.location.href = data.url; }).catch((error) => { console.error('Error:', error); });
    });
}
