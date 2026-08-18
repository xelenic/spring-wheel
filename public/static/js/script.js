let padding = {top: 50, right: 100, bottom: 50, left: 100},
    w = 800 - padding.left - padding.right,
    h = 800 - padding.top - padding.bottom,
    r = Math.min(w, h) / 2,
    initialrotation = 0,
    rotation = 0,
    oldrotation = 0,
    picked = 100000,
    oldpick = [],
    sliceColors = ["#0d0d0d", "#e2231a"]; // alternating black / red, matches the brand rim

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

// The rim, pointer and center hub are brand artwork with no per-segment
// text baked in, so they stay a static overlay regardless of how many
// prizes are on the wheel.
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
let arc = d3.svg.arc().outerRadius(r).innerRadius(0);

// One <g class="slice"> per prize, each holding a colored wedge (drawn
// straight from the prizes array, not a raster image) plus its label —
// so the wheel always matches whatever prizes it was given.
let arcs = vis.selectAll("g.slice")
    .data(pie)
    .enter()
    .append("g")
    .attr("class", "slice");

arcs.append("path")
    .attr("fill", function (d, i) {
        return sliceColors[i % sliceColors.length];
    })
    .attr("d", function (d) {
        d.innerRadius = 0;
        d.outerRadius = r;
        return arc(d);
    });

arcs.append("text")
    .attr("x", -110)
    .attr("y", 5)
    .attr("class", "wheelText")
    .attr("text-anchor", "middle")
    .attr("text-rendering", "optimizeLegibility")
    .text(function (d, i) {
        return prizes[i].label;
    })
    .attr("transform", function (d) {
        d.angle = (d.startAngle + d.endAngle) / 2;
        return "rotate(" + (d.angle * 180 / Math.PI - 90) + ")translate(" + (d.outerRadius - 10) + ")";
    });


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

/**
 * Work out how many degrees the wheel needs to turn, from wherever it's
 * currently resting, so it ends up on segment `targetIndex` — using the
 * exact same "picked = length - ceil((rotation % 360) / segmentSize)"
 * relationship that spin()'s own landing calculation uses, just solved
 * backwards. `extraTurns` just adds full spins for visual flourish.
 */
function rotationForIndex(targetIndex, extraTurns) {
    extraTurns = extraTurns || 5;
    let ps = 360 / prizes.length;
    let k = prizes.length - targetIndex;
    let targetMod = (k - 0.5) * ps; // midpoint of the segment's angular range
    let current = ((rotation % 360) + 360) % 360;
    let delta = ((targetMod - current) % 360 + 360) % 360;
    return extraTurns * 360 + delta;
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
            vis.selectAll('g.slice')
                .filter(function (d, i) {
                    return i === picked;
                })
                .select('path')
                .attr('fill-opacity', 0.6);

            oldrotation = rotation;
            $('#main-title').css('display', 'none');
            $('#good-luck').css('display', 'none');

            // Get the actual prize from the visual segment that was landed on
            let actualPrize = prizes[picked];
            console.log("Wheel landed on segment:", picked, "which shows:", actualPrize.label);

            if (actualPrize.type === "win") {
                $('#winning-title').text("Winner!");
                $('#winning-prize').text(actualPrize.label);
                $('#winning-message').text("Congratulations! You won a " + actualPrize.label + "!");
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
window.rotationForIndex = rotationForIndex;
