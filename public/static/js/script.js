let padding = {top: 50, right: 100, bottom: 50, left: 100},
    w = 800 - padding.left - padding.right,
    h = 800 - padding.top - padding.bottom,
    r = Math.min(w, h) / 2,
    initialrotation = 0,
    rotation = 0,
    oldrotation = 0,
    picked = 100000,
    oldpick = [],
    color = d3.scale.category20();

// Loads the tick audio sound in to an audio object.
let audio = new Audio(ROULETTE_MEDIA);

let svg = d3.select('#chart')
    .append("svg")
    .data([prizes])
    .attr("viewBox", "0 0 800 800")
// .attr("width", w + padding.left + padding.right)
// .attr("height", h + padding.top + padding.bottom);

let container = svg.append("g")
    .attr("class", "chartholder")
    .attr("transform", "translate(" + (w / 2 + padding.left) + "," + (h / 2 + padding.top) + ")");

let vis = container
    .append("g");

let myimage = vis.append('image')
    .attr('xlink:href', WHEEL_IMG)
    .attr('width', 800)
    .attr('height', 800)
    .attr('x', -400)
    .attr('y', -400);

let outwheel = svg.append('image')
        .attr('xlink:href', OUT_WHEEL_IMG)
        .attr('width', 800)
        .attr('height', 800)
    // .on("click", spin) // For debug only
;


let pie = d3.layout.pie().value(function (d) {
    return 1;
});

// declare an arc generator function
let arc = d3.svg.arc().outerRadius(r);

// select paths, use arc generator to draw
let arcs = vis.selectAll("g.slice")
    .data(pie)
    .enter()
    .append("g")
    .append("text")
    .attr("x", -110)
    .attr("y", 5)
    .attr("class", "wheelText")
    .attr("text-anchor", "middle")
    .attr("text-rendering", "optimizeLegibility")
    .text(function (d, i) {
        return prizes[i].label;
    })

    .attr("transform", function (d) {
        d.innerRadius = 0;
        d.outerRadius = r;
        d.angle = (d.startAngle + d.endAngle) / 2;
        return "rotate(" + (d.angle * 180 / Math.PI - 90) + ")translate(" + (d.outerRadius - 10) + ")";
    });
// .attr("class", "slice");

arcs.append("path")
    .attr("fill-opacity", "0.0")
    .attr("fill", function (d, i) {
        return color(i);
    })
    .attr("d", function (d) {
        return arc(d);
    });

// Debug highlights
// d3.select(".slice:nth-child(" + (2) + ") path")
//     .attr("fill-opacity","0.5")
//     .attr("")
//     .attr("fill", "#e9e9e9");


function spinToResult(r) {
    initialrotation = r
    vis.transition()
        .duration(0)
        .ease("linear")
        .attrTween("transform", rotInitial)
}

function introRotation(much) {
    vis.transition().duration(4000)
        .attr('transform', ' rotate(' + (much ? 10 : 200) + ')')
        .each('end', function () {
            setTimeout(function () {
                introRotation(!much)
            }, 4000);
        })
}

function spin(r, winner) {
    container.on("click", null);

    if (oldpick.length === prizes.length) {
        console.log("done");
        container.on("click", null);
        return;
    }
    let ps = 360 / prizes.length;

    let result = r;
    result += rotation;
    picked = prizes.length - Math.ceil((result % 360) / ps);

    if (oldpick.indexOf(picked) !== -1) {
        d3.select(this).call(spin);
        return;
    }
    oldpick.push(picked);

    rotation = result;
    playSound();

    vis.transition()
        .duration(9000)
        .attrTween("transform", rotTween)
        .each("end", function () {
            d3.select(".slice:nth-child(" + (picked + 2) + ") path")
                .attr("fill-opacity","0.5")
                .attr("fill", "#e9e9e9");

            // d3.select("#question h1").text(data[picked].question);
            oldrotation = rotation;
            // container.on("click", spin);
            $('#main-title').css('display', 'none');
            $('#good-luck').css('display', 'none');
            
            // Get the actual prize from the visual segment that was landed on
            let actualPrize = prizes[picked];
            console.log("Wheel landed on segment:", picked, "which shows:", actualPrize.label);
            console.log("Segment details:", {
                id: actualPrize.id,
                label: actualPrize.label,
                type: actualPrize.type,
                probability: actualPrize.probability
            });
            
            // Also log the rotation and picked calculation for debugging
            console.log("Rotation calculation:", {
                totalRotation: rotation,
                segmentSize: 360 / prizes.length,
                calculatedSegment: Math.ceil((rotation % 360) / (360 / prizes.length)),
                picked: picked
            });
            
            // Handle different winner types based on the actual visual segment
            if (actualPrize.type === "win") {
                // MUG winner
                $('#winning-title').text("Winner!");
                $('#winning-prize').text("MUG");
                $('#winning-message').text("Congratulations! You won a MUG!");
                $('#winning-card').css('display', 'block');
                $('#retry-it').css('display', 'block');
                $('#spin-wheel').css('display', 'none');
                $('#try-again-message').css('display', 'none');
                
                // Update MUG counter
                if (typeof window.updateMugCounter === 'function') {
                    window.updateMugCounter();
                }
                
                confetti.start();
            } else if (actualPrize.type === "bonus") {
                // BONUS SPIN winner
                $('#winning-title').text("Bonus!");
                $('#winning-prize').text("BONUS SPIN");
                $('#winning-message').text("You got a BONUS SPIN! Click below to spin again!");
                $('#winning-card').css('display', 'block');
                $('#retry-it').css('display', 'block');
                $('#spin-wheel').css('display', 'none');
                $('#try-again-message').css('display', 'none');
                confetti.start();
            } else {
                // TRY AGAIN
                $('#winning-card').css('display', 'none');
                $('#try-again-message').css('display', 'block');
                $('#retry-it').css('display', 'block');
                $('#spin-wheel').css('display', 'none');
            }
            
            // Old logic for backward compatibility
            if (!winner.winner) {
                $('#good-luck h1').html('Try it again.');
                if (winner.try_again) {
                    if (winner.retry_used) {
                        $('#retry-used').css('display', 'block');
                        $('#retry-message').css('display', 'none');
                        $('#retry-it').css('display', 'block');
                        $('#better-luck-message').css('display', 'block');
                        $('#spin-wheel').css('display', 'none');
                    } else {
                        $('#id_email').val(winner.email);
                        $('#id_code').val(winner.code);
                        $('#retry-message').css('display', 'block');
                        $('#spin-again').css('display', 'block');
                    }
                } else {
                    $('#better-lucky-title').css('display', 'block');
                    $('#better-luck-message').css('display', 'block');
                    $('#retry-it').css('display', 'block');
                    $('#spin-wheel').css('display', 'none');
                }
            } else {
                $('#better-luck-message').css('display', 'block');
                $('#retry-it').css('display', 'block');
                $('#spin-wheel').css('display', 'none');
                $('#winning-card').css('display', 'block');
                $('#winning-email').html(winner.email);
                $('#winning-prize').html(prizes[picked].label);
                confetti.start();
            }
            //window.location.href = '/';
            //return true;
        });
}

// This function is called when the sound is to be played.
function playSound() {
    // Stop and rewind the sound if it already happens to be playing.
    audio.pause();
    audio.currentTime = 0;

    // Play the sound.
    audio.play();
}

function rotTween(to) {
    let i = d3.interpolate(oldrotation % 360, rotation);
    return function (t) {
        // playSound();
        return "rotate(" + i(t) + ")";
    };
}

function rotInitial(to) {
    let i = d3.interpolate(0, initialrotation);
    return function (t) {
        return "rotate(" + i(t) + ")";
    };
}

// Make functions globally accessible
window.spin = spin;
window.introRotation = introRotation;
window.spinToResult = spinToResult;
window.playSound = playSound;
window.rotTween = rotTween;
window.rotInitial = rotInitial;
