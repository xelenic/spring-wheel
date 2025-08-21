
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Voucher Wheel</title>
    <meta charset="UTF-8"/>
    <link href="static/css/styles.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <link rel="apple-touch-icon" sizes="57x57" href="static/img/favicon.ico/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="static/img/favicon.ico/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="static/img/favicon.ico/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="static/img/favicon.ico/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="static/img/favicon.ico/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="static/img/favicon.ico/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="static/img/favicon.ico/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="static/img/favicon.ico/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="static/img/favicon.ico/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="static/img/favicon.ico/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="static/img/favicon.ico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="static/img/favicon.ico/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="static/img/favicon.ico/favicon-16x16.png">
    <link rel="manifest" href="static/img/favicon.ico/manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="static/img/favicon.ico/ms-icon-144x144.png">

    <style>
        body {
            background-color: #002535;
        }
    </style>
</head>

<body ondblclick="openFullscreen()" class="text-center" style="background: url('{{url('static/img/Background.jpg')}}');background-size: cover">

<div class="row h-100 mx-auto" style="max-width: 1536px;padding-top: 70px;">
    <!-- Wheel -->
    <div class="col-sm-7 my-auto">
        <div class="row no-gutters no-border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col d-flex flex-column position-static">
                <div id="chart"></div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="col-sm-5 my-auto">
        <div style="background: #0d6efd9c;border-radius: 30px;">
            <div class="row no-gutters no-border rounded overflow-hidden flex-md-row mb-4 ml-4 mr-4 shadow-sm h-md-250 position-relative">
                <div class="container">
                    <a href="#">
                        <img alt="Voucher Wheel logo" class="mx-auto d-block img-fluid" src="static/img/logo.png">
                    </a>
                    <div style="margin:20px 0 25px 0;">
                        <h1 class="h1 mt-2 mb-5 font-weight-normal" style="color:white;" id="main-title">SPIN THE WHEEL TO
                            WIN!</h1>
                        <h4 class="h4 mb-5 font-weight-normal" style="color: white;" id="main-message">
                            Enter your name and phone number to spin the wheel for a chance to win
                        </h4>

                        <h1 class="h1 mt-2 mb-5 font-weight-normal" style="color:white;display:none;"
                            id="better-lucky-title">BETTER LUCK NEXT TIME!</h1>

                        <div id="wheel-form">
                            <div style="">
                                <form class="form-signin" method="POST" action="">
                                    <div class="form-group row">
                                        <label style="color: white" class="col-sm-2 col-form-label col-form-label-lg"
                                               for="id_email">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" class="form-control form-control-lg" id="id_email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label style="color: white" class="col-sm-2 col-form-label col-form-label-lg"
                                               for="id_code">Phone</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="phone_number" maxlength="10"
                                                   class="form-control form-control-lg"
                                                   required="" id="id_code">
                                        </div>
                                    </div>
                                    <div class="alert alert-danger mt-3" role="alert" style="display: none;" id="code-verify">
                                        Invalid or expired code!
                                    </div>
                                    <div class="mt-5">
                                        <button class="mt2 btn btn-lg btn-primary btn-block" id="spin-wheel" type="button">
                                            SPIN!
                                        </button>
                                    </div>
                                </form>
                            </div>


                        </div>

                        <!-- Spinning  -->
                        <div id="good-luck" style="display:none">
                            <h3 style="color:white;">Good Luck</h3>
                        </div>

                        <div id="winning-card" style="display:none">
                            <h1 class="blinking h1 mt-2 mb-5 font-weight-normal"
                                style="color: white; text-transform:uppercase;">
                                Congratulations!!</h1>
                            <div class="card mb-4">
                                <div class="card-body" style="background: #0048a5;color: white;">
                                    <p style="color: #ffffff;" class="col-form-label col-form-label-lg">
                                        <h1>Winner</h1>

                                        <h2><span id="winning-email"></span></h2>
                                        <br>
                                        <h2 style="font-size: 50px;"> <span id="winning-prize"></span></h2>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button class="mt2 btn btn-lg btn-primary btn-block" id="retry-it" type="button"
                                style="display:none;">
                           Spin Again
                        </button>

                        <h4 class="h4 mb-5 font-weight-normal" style="color: white;display: none;" id="retry-used">
                            <br>Your retry was already used...</h4>

                        <div id="retry-message" style="display:none">
                            <h1 class="blinking h1 mt-2 mb-5 font-weight-normal"
                                style="color: white; text-transform:uppercase;">
                                Try again!</h1>
                            <h4 class="h4 mb-5 font-weight-normal" style="color: white;">
                                You have just won a free spin! Please click on the button below to try again!</h4>
                        </div>

                        <button class="mt2 btn btn-lg btn-primary btn-block" id="spin-again" type="button" onclick="location.reload()"
                                style="display:none;">
                            Done
                        </button>


                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script charset="utf-8" src="https://d3js.org/d3.v3.min.js"></script>
<script crossorigin="anonymous" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script crossorigin="anonymous" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script crossorigin="anonymous" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="static/js/confetti.js"></script>

<script type="text/javascript">
    console.log("Populating prizes....");
    let prizes = [
        {
            label: "",
            id: 1,
        },
        {
            label: "",
            id: 2,
        },
        {
            label: "",
            id: 3,
        },
        {
            label: "",
            id: 4,
        },
        {
            label: "",
            id: 5,
        },
        {
            label: "",
            id: 6,
        }

    ];
    // GLOBAL MEDIA VARS
    let WHEEL_IMG = "static/img/wheel.png";
    let OUT_WHEEL_IMG = "static/img/outwheel.png";
    let ROULETTE_MEDIA = "static/media/roulette.mp3";
</script>


<script>
    /* Get the documentElement (<html>) to display the page in fullscreen */
    var elem = document.documentElement;

    /* View in fullscreen */
    function openFullscreen() {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
    }

    /* Close fullscreen */
    function closeFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
            document.msExitFullscreen();
        }
    }
</script>


<script src="static/js/script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- {% block extra-scripts %}{% endblock %} -->
<script>
    $('#spin-wheel').on('click', function () {
        $.ajax({
            url: "{{url('spin')}}",
            data: {
                'email': $('#id_email').val(),
                'code': $('#id_code').val()
            },
            dataType: 'json',
            method: "POST",
            success: function (response) {
                // Code already used
                if (response.used) {
                    $('#code-verify').html("Invalid or expired code!");
                    $('#code-verify').css('display', 'block');
                } else {
                    // Ready to go
                    $('#wheel-form').css('display', 'none');
                    $('#retry-it').css('display', 'none');
                    $('#main-message').css('display', 'none');
                    confetti.stop();
                    console.log("Spin the Wheel!");
                    spin(response.spin.rotation, response.spin);
                    $('#good-luck').css('display', 'block');
                }
            },
            error: function () {
                $('#code-verify').html("Invalid or expired code!");
            }
        });
    });

    $('#spin-again').on('click', function () {
        $('#spin-again').css('display', 'none');
        $.ajax({
            url: "{{url('spin')}}",
            data: {
                'email': $('#id_email').val(),
                'phone_number': $('#id_code').val(),
                'retry': true
            },
            dataType: 'json',
            method: "POST",
            success: function (response) {
                // Code already used
                if (response.used) {
                    $('#retry-used').css('display', 'block');
                    $('#retry-message').css('display', 'none');
                } else {
                    // Ready to go
                    $('#main-title').css('display', 'block');
                    $('#wheel-form').css('display', 'none');
                    $('#retry-it').css('display', 'none');
                    $('#main-message').css('display', 'none');
                    $('#retry-message').css('display', 'none');
                    confetti.stop();
                    console.log("Spin the Wheel again!");
                    spin(response.spin.rotation, response.spin);
                    $('#good-luck').css('display', 'block');
                }
            },
            error: function () {
                $('#code-verify').html("Invalid or expired code!");
            }
        });
    })

    $('#retry-it').on('click', function () {
        window.location.reload();
    })
</script>
</body>
</html>
