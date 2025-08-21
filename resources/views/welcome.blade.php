
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Spring Wheel</title>
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

<div class="container h-100 d-flex flex-column justify-content-center align-items-center" style="padding-top: 70px;">
    <!-- Logo -->
    <div class="mb-4">
        <a href="#">
            <img alt="Spring Wheel logo" class="mx-auto d-block img-fluid" src="static/img/logo.png" style="max-width: 200px;">
        </a>
    </div>
    
    <!-- Title -->
    <div class="mb-5">
        <h1 class="h1 font-weight-normal" style="color:white;" id="main-title">SPIN THE SPRING WHEEL!</h1>
        <h4 class="h4 font-weight-normal" style="color: white;" id="main-message">
            Click the button below to spin the wheel!
        </h4>
        <div class="mt-3">
            <h5 class="h5 font-weight-normal" style="color: #ffd700;">
                🏆 MUGs Available Today: <span id="mug-counter">75</span>
            </h5>
        </div>
    </div>

    <!-- Wheel -->
    <div class="mb-4">
        <div id="chart"></div>
        <!-- Fallback wheel image if D3.js doesn't load -->
        <div id="fallback-wheel" style="display: none; text-align: center;">
            <img src="static/img/wheel.png" alt="Spring Wheel" style="max-width: 600px; height: auto;">
        </div>
    </div>

    <!-- Spin Button -->
    <div class="mb-4">
        <button class="btn btn-lg btn-primary" id="spin-wheel" type="button" style="font-size: 24px; padding: 15px 40px;">
            SPIN THE WHEEL!
        </button>
    </div>

    <!-- Messages -->
    <div id="good-luck" style="display:none">
        <h3 style="color:white;">Good Luck!</h3>
    </div>

    <div id="try-again-message" style="display:none">
        <h1 class="h1 mt-2 mb-5 font-weight-normal" style="color:white;">
            TRY AGAIN!
        </h1>
        <h4 class="h4 mb-5 font-weight-normal" style="color: white;">
            Better luck next time! Click the button below to try again!
        </h4>
    </div>

    <div id="winning-card" style="display:none">
        <h1 class="blinking h1 mt-2 mb-5 font-weight-normal"
            style="color: white; text-transform:uppercase;">
            Congratulations!!</h1>
        <div class="card mb-4">
            <div class="card-body" style="background: #0048a5;color: white;">
                <p style="color: #ffffff;" class="col-form-label col-form-label-lg">
                    <h1 id="winning-title">Winner!</h1>
                    <h2 style="font-size: 50px;"> <span id="winning-prize"></span></h2>
                    <h3 id="winning-message" style="color: #ffffff;"></h3>
                </p>
            </div>
        </div>
    </div>

    <button class="btn btn-lg btn-primary" id="retry-it" type="button"
            style="display:none; font-size: 20px; padding: 12px 30px;">
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

    <button class="btn btn-lg btn-primary" id="spin-again" type="button" onclick="location.reload()"
            style="display:none; font-size: 20px; padding: 12px 30px;">
        Done
    </button>
</div>

<script charset="utf-8" src="https://d3js.org/d3.v3.min.js"></script>
<script crossorigin="anonymous" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script crossorigin="anonymous" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script crossorigin="anonymous" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="static/js/confetti.js"></script>




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

<!-- Load jQuery first, then D3.js, then our scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://d3js.org/d3.v3.min.js"></script>
<script src="static/js/confetti.js"></script>

<!-- Load our wheel script and make functions globally accessible -->
<script>
    // Make sure these variables are globally accessible
    // Updated with cleaner, more readable text labels
    window.prizes = [
        {
            label: "TRY AGAIN",        // Top segment (Black)
            id: 1,
            type: "try_again",
            probability: 0.7 // 70% chance
        },
        {
            label: "BONUS SPIN",       // Second segment (Red)
            id: 2,
            type: "bonus",
            probability: 0.2 // 20% chance
        },
        {
            label: "TRY AGAIN",        // Third segment (Black)
            id: 3,
            type: "try_again",
            probability: 0.7 // 70% chance
        },
        {
            label: "MUG",              // Fourth segment (Red)
            id: 4,
            type: "win",               // MUG prize
            probability: 0.1 // 10% chance
        },
        {
            label: "TRY AGAIN",        // Fifth segment (Black)
            id: 5,
            type: "try_again",
            probability: 0.7 // 70% chance
        },
        {
            label: "BONUS SPIN",       // Sixth segment (Red)
            id: 6,
            type: "bonus",
            probability: 0.2 // 20% chance
        }
    ];
    
    // Global media variables
    window.WHEEL_IMG = "static/img/wheel.png";
    window.OUT_WHEEL_IMG = "static/img/outwheel.png";
    window.ROULETTE_MEDIA = "static/media/roulette.mp3";
    
    // Daily MUG counter (stored in localStorage for demo purposes)
    // In production, this would come from a database
    let dailyMugCount = localStorage.getItem('dailyMugCount') || 75;
    let lastResetDate = localStorage.getItem('lastResetDate') || new Date().toDateString();
    
    // Check if it's a new day and reset the counter
    if (lastResetDate !== new Date().toDateString()) {
        dailyMugCount = 75;
        localStorage.setItem('lastResetDate', new Date().toDateString());
    }
    
    // Update the display
    document.getElementById('mug-counter').textContent = dailyMugCount;
    
    // Function to update MUG counter
    window.updateMugCounter = function() {
        if (dailyMugCount > 0) {
            dailyMugCount--;
            localStorage.setItem('dailyMugCount', dailyMugCount);
            document.getElementById('mug-counter').textContent = dailyMugCount;
        }
    };
</script>

<script src="static/js/script.js"></script>

<!-- {% block extra-scripts %}{% endblock %} -->
<script>
    // Wait for the page to fully load before initializing the wheel
    $(document).ready(function() {
        console.log("Page loaded, initializing wheel...");
        
        // Check if D3.js is loaded
        if (typeof d3 === 'undefined') {
            console.error("D3.js not loaded!");
            // Show fallback wheel if D3.js fails to load
            $('#fallback-wheel').css('display', 'block');
            return;
        }
        
        // Check if our wheel script is loaded
        if (typeof introRotation === 'function') {
            console.log("Intro rotation function found, but disabled for static wheel");
            // introRotation(true); // Commented out to disable automatic rotation
        } else {
            console.error("Wheel functions not loaded!");
            // Show fallback wheel if wheel functions fail to load
            $('#fallback-wheel').css('display', 'block');
        }
        
        // Debug: Check what functions are available
        console.log("Available functions:", {
            'spin': typeof spin,
            'window.spin': typeof window.spin,
            'introRotation': typeof introRotation,
            'window.introRotation': typeof window.introRotation,
            'd3': typeof d3,
            'prizes': typeof prizes,
            'WHEEL_IMG': typeof WHEEL_IMG
        });
        
        // Also check the global window object
        console.log("Global window functions:", Object.keys(window).filter(key => 
            typeof window[key] === 'function' && 
            ['spin', 'introRotation', 'spinToResult'].includes(key)
        ));
        
        // Add some CSS to ensure the wheel is visible
        $('#chart').css({
            'min-height': '600px',
            'display': 'block',
            'margin': '0 auto'
        });
        
        // Check if the chart div has content
        setTimeout(function() {
            if ($('#chart svg').length === 0) {
                console.warn("No SVG found in chart div, showing fallback wheel");
                $('#fallback-wheel').css('display', 'block');
            } else {
                console.log("Wheel SVG found and loaded successfully");
            }
        }, 2000);
        
        console.log("Wheel initialization complete");
    });

    $('#spin-wheel').on('click', function () {
        console.log("Spin button clicked!");
        
        // Check if MUGs are still available
        let currentMugCount = parseInt(document.getElementById('mug-counter').textContent);
        if (currentMugCount <= 0) {
            alert("Sorry! All MUGs for today have been won. Please try again tomorrow!");
            return;
        }
        
        // Check if the spin function is available
        if (typeof spin !== 'function') {
            console.error("Spin function not found! Trying to reload scripts...");
            // Try to reload the script
            location.reload();
            return;
        }
        
        // Hide the form and show good luck message
        $('#main-title').css('display', 'none');
        $('#main-message').css('display', 'none');
        $('#spin-wheel').css('display', 'none');
        
        // Stop any existing confetti
        if (typeof confetti !== 'undefined' && confetti.stop) {
            confetti.stop();
        }
        
        console.log("Starting wheel spin...");
        
        // Generate random rotation for demo
        let randomRotation = Math.floor(Math.random() * 360) + 720; // At least 2 full rotations
        
        // Create a simple winner object - the actual prize will be determined by where the wheel lands
        let demoWinner = {
            winner: false, // Will be determined by the actual segment landed on
            winner_type: "unknown", // Will be determined by the actual segment landed on
            message: "Spinning...",
            email: "Demo User",
            code: "1234567890"
        };
        
        console.log("Spinning with rotation:", randomRotation);
        
        try {
            // Test if we can call the spin function
            if (typeof window.spin === 'function') {
                console.log("Using window.spin function");
                window.spin(randomRotation, demoWinner);
            } else if (typeof spin === 'function') {
                console.log("Using local spin function");
                spin(randomRotation, demoWinner);
            } else {
                throw new Error("Spin function not accessible");
            }
            
            $('#good-luck').css('display', 'block');
        } catch (error) {
            console.error("Error during spin:", error);
            // Show error message to user
            $('#good-luck').html('<h3 style="color:red;">Error spinning wheel. Please refresh the page.</h3>');
            $('#good-luck').css('display', 'block');
            
            // Show the spin button again for debugging
            $('#spin-wheel').css('display', 'block');
        }
    });

    $('#retry-it').on('click', function () {
        window.location.reload();
    })
</script>
</body>
</html>
