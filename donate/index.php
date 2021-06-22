<?php if (isset($_GET['tidal'])) { header("Location: https://najemi.cz/products/"); exit(); } ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Donate | Czechify</title>
        <link rel="stylesheet" href="css/normalize.css" />
        <link rel="stylesheet" href="css/global.css" />
        <script src="https://js.stripe.com/v3/"></script>
        <script src="./script.js" defer></script>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="sr-root">
            <div class="sr-main" style="display: flex;">
                <div class="sr-container">
                    <section class="container basic-photo">
                        <div>
                            <h1>Beginner</h1>
                            <div class="pasha-image">
                                <img src="https://stripe-camo.global.ssl.fastly.net/31e9c2bbdb0bd50c8d90d46e03c7355c531a11f4/68747470733a2f2f66696c65732e7374726970652e636f6d2f6c696e6b732f666c5f6c6976655f7331564468455958343033506d61316d50675456666f6144" width="200">
                            </div>
                        </div>
                        <button id="beginner-plan-btn">€1.00 per month</button>
                    </section>
                    <section class="container basic-photo">
                        <div>
                            <h1>Intermediate</h1>
                            <div class="pasha-image">
                                <img src="https://d1wqzb5bdbcre6.cloudfront.net/570c927f1cea643ca7892fbac31a085a70fac966/68747470733a2f2f66696c65732e7374726970652e636f6d2f6c696e6b732f666c5f6c6976655f6f32523465394b564866324d54484c57563473734e5a7430" width="200">
                            </div>
                        </div>
                        <button id="intermediate-plan-btn">€3.00 per month</button>
                    </section>
                    <section class="container basic-photo">
                        <div>
                            <h1>Advanced</h1>
                            <div class="pasha-image">
                                <img src="https://d1wqzb5bdbcre6.cloudfront.net/4e65a1a4e28a6ef5f0fd44ab5fbe9c53d970e5a5/68747470733a2f2f66696c65732e7374726970652e636f6d2f6c696e6b732f666c5f6c6976655f677a4a566b374862753073474c36523347517837586e6951" width="200">
                            </div>
                        </div>
                        <button id="advanced-plan-btn">€5.00 per month</button>
                    </section>
                    <section class="container basic-photo">
                        <div>
                            <h1>Fluent</h1>
                            <div class="pasha-image">
                                <img src="https://d1wqzb5bdbcre6.cloudfront.net/2178da05a8b70f633b900cf978eb0cc9fdceea0f/68747470733a2f2f66696c65732e7374726970652e636f6d2f6c696e6b732f666c5f6c6976655f44496d4356617a556c70754348796748666233616961366f" width="200">
                            </div>
                        </div>
                        <button id="fluent-plan-btn">€10.00 per month</button>
                    </section>
                    <section class="container basic-photo">
                        <div>
                            <h1>Native Speaker</h1>
                            <div class="pasha-image">
                                <img src="https://d1wqzb5bdbcre6.cloudfront.net/37442ba2bd713ed061bc6b4f4f0cec30e36265b8/68747470733a2f2f66696c65732e7374726970652e636f6d2f6c696e6b732f666c5f6c6976655f77754251487a36587a37676c4d746370624e436d357a4837" width="200">
                            </div>
                        </div>
                        <button id="native-speaker-plan-btn">€20.00 per month</button>
                    </section>
                </div>
                <div id="error-message"></div>
            </div>
        </div>
    </body>
</html>
