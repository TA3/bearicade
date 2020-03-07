var basicTimeline = anime.timeline({
    autoplay: false,
    complete: function(anim) {
        logoSVGLoader();
    }
});
var d1 = ["M302,424.5c0,8.2,0,16.3,0,24.5c-8.5,0-16.9,0-25.6,0c0-8.1,0-16.3,0-24.5C284.8,424.5,293.1,424.5,302,424.5z", "M338.3,424.5c8.7,0,16.8,0,25.4,0c0,8.2,0,16.3,0,24.6c-8.4,0-16.7,0-25.4,0 C338.3,440.9,338.3,432.8,338.3,424.5z"];
var d2 = ["M245.1,411.4c8.5,0,16.9,0,25.8,0c0,8.7,0,17.1,0,26c-8.6,0-17.2,0-25.8,0 C245.1,428.8,245.1,420.1,245.1,411.4z", "M395.1,437.4c-8.5,0-16.9,0-25.8,0c0-8.8,0-17.2,0-26c8.6,0,17.2,0,25.8,0 C395.1,420.1,395.1,428.8,395.1,437.4z"];
var d3 = ["M302.05,401.84c0,23.46,0,46.53,0,70.02c-8.53,0-16.88,0-25.61,0c0-23.25,0-46.43,0-70.02 C284.77,401.84,293.13,401.84,302.05,401.84z", "M338.29,401.6c8.7,0,16.82,0,25.4,0c0,23.38,0,46.68,0,70.34c-8.39,0-16.74,0-25.4,0 C338.29,448.47,338.29,425.29,338.29,401.6z"];
var d4 = ["M245.11,398.43c8.46,0,16.91,0,25.77,0c0,17.32,0,34.18,0,52c-8.6,0-17.19,0-25.77,0 C245.11,433.09,245.11,415.76,245.11,398.43z", "M395.11,450.43c-8.45,0-16.9,0-25.76,0c0-17.5,0-34.35,0-52c8.59,0,17.18,0,25.76,0 C395.11,415.76,395.11,433.09,395.11,450.43z"];
basicTimeline
    .add({
        targets: ["#logo_svg .l2", "#logo_svg .r2"],
        duration: 500,
        //fill:['rgba(0,0,0,0)', '#D8C184'],
        easing: "easeInOutQuad",
        d: function(el, i, l) {
            return d1[i];
        },
        delay: function(el, i, l) {
            return i * 200;
        }
    }).add({
        targets: ["#logo_svg .l1", "#logo_svg .r1"],
        duration: 500,
        easing: "easeInOutQuad",
        delay: function(el, i, l) {
            return i * 200;
        },
        d: function(el, i, l) {
            return d2[i];
        }
    }).add({
        targets: ["#logo_svg .l2", "#logo_svg .r2"],
        duration: 500,
        easing: "easeInOutQuad",
        d: function(el, i, l) {
            return d3[i]
        }
    }).add({
        targets: ["#logo_svg .m1"],
        duration: 700,
        easing: "easeInOutQuad",
        d: "M307.11,510.43c0-43.45,0-86.91,0-130.7c8.69,0,16.94,0,26,0c0,43.56,0,87.13,0,130.7 C324.45,510.43,315.78,510.43,307.11,510.43z"
    }).add({
        targets: ["#logo_svg .t1"],
        duration: 500,
        easing: "easeInOutQuad",
        d: "M322.11,345.43c0,5.78,0,11.56,0,17.61c4.42,0,9.56,1.5,12.25-0.42c2.69-1.93,2.94-7.28,4.26-11.13 c0.48-0.14,0.97-0.27,1.45-0.41c0.53,1.28,1.72,2.69,1.49,3.82c-1.34,6.49-2.91,12.94-4.69,19.32c-0.26,0.95-2.03,2.08-3.11,2.09 c-10.29,0.16-20.58,0.09-31.23,0.09c-1.8-7.26-3.65-14.27-5.2-21.35c-0.22-1.02,0.97-2.34,1.51-3.52c0.78,0.83,1.94,1.53,2.28,2.52 c3.27,9.67,3.23,9.69,13.63,9.41c0.82-0.02,1.64-0.15,3.36-0.31c0-5.91,0-11.82,0-17.72C319.45,345.43,320.78,345.43,322.11,345.43 z",
        opacity: 1,
    })
/*.add({
        targets: ["#logo_svg .l1", "#logo_svg .r1"],
        duration: 300,
        easing: "easeInOutQuad",
        d: function(el, i, l) {
            return d4[i]
        },
        delay: function(el, i, l) {
            return i * 700;
        },

    })*/
;

$("#logo_svg").click(function() {

    loadingTimeline.pause();
    basicTimeline.play();
});


var loadingTimeline = anime.timeline({
    autoplay: true,
    loop: true,
});
var l2_0 = ["M302,431.9c0,8.6,0,17.1,0,25.7c-8.5,0-16.9,0-25.6,0c0-8.5,0-17.1,0-25.7C284.8,431.9,293.1,431.9,302,431.9z", "M338.3,431.8c8.7,0,16.8,0,25.4,0c0,8.6,0,17.2,0,25.8c-8.4,0-16.7,0-25.4,0 C338.3,449.1,338.3,440.6,338.3,431.8z"];
var l1_0 = ["M245.1,432.7c8.5,0,16.9,0,25.8,0c0,8.5,0,16.7,0,25.5c-8.6,0-17.2,0-25.8,0 C245.1,449.7,245.1,441.2,245.1,432.7z", "M395.1,458.2c-8.5,0-16.9,0-25.8,0c0-8.6,0-16.8,0-25.5c8.6,0,17.2,0,25.8,0 C395.1,441.2,395.1,449.7,395.1,458.2z"];
var lmt_0 = ["M307.1,458.1c0-8.7,0-17.4,0-26.1c8.7,0,16.9,0,26,0c0,8.7,0,17.4,0,26.1 C324.5,458.1,315.8,458.1,307.1,458.1z", "M320.8,437.2c0,2.9,0,5.8,0,8.8c2.2,0,4.8,0.8,6.1-0.2c1.3-1,1.5-3.6,2.1-5.6c0.2-0.1,0.5-0.1,0.7-0.2 c0.3,0.6,0.9,1.3,0.7,1.9c-0.7,3.2-1.5,6.5-2.3,9.7c-0.1,0.5-1,1-1.6,1c-5.1,0.1-10.3,0-15.6,0c-0.9-3.6-1.8-7.1-2.6-10.7 c-0.1-0.5,0.5-1.2,0.8-1.8c0.4,0.4,1,0.8,1.1,1.3c1.6,4.8,1.6,4.8,6.8,4.7c0.4,0,0.8-0.1,1.7-0.2c0-3,0-5.9,0-8.9 C319.4,437.2,320.1,437.2,320.8,437.2z"];
loadingTimeline
    .add({
        targets: ["#logo_svg .l2", "#logo_svg .r2"],
        duration: 300,
        //fill:['rgba(0,0,0,0)', '#D8C184'],
        easing: "easeInOutQuad",
        d: function(el, i, l) {
            return d1[i];
        },
        delay: function(el, i, l) {
            return i * 200;
        }
    }).add({
        targets: ["#logo_svg .l1", "#logo_svg .r1"],
        duration: 300,
        easing: "easeInOutQuad",
        delay: function(el, i, l) {
            return i * 200;
        },
        d: function(el, i, l) {
            return d2[i];
        }
    }).add({
        targets: ["#logo_svg .l2", "#logo_svg .r2"],
        duration: 300,
        easing: "easeInOutQuad",
        d: function(el, i, l) {
            return l2_0[i]
        }
    }).add({
        targets: ["#logo_svg .l1", "#logo_svg .r1"],
        duration: 300,
        easing: "easeInOutQuad",
        d: function(el, i, l) {
            return l1_0[i]
        },
        delay: function(el, i, l) {
            return i * 200;
        },
    });

function logoSVGLoader() {
    /*anime({
        targets: '#logo_svg path',
        duration: 100,
        opacity: 0,
        delay: function(el, i, l) {
            return i * 100;
        },
        complete: function() {

        }
    });*/
    setTimeout(function() {
        document.getElementById('svgLoader').style.top = "-100vh";
    }, 100);



    setTimeout(function() {
        document.getElementById('svgLoader').remove();
    }, 2500);

}



window.onload = function() {
    loadingTimeline.pause();
    basicTimeline.play();
    if (root.LoggedInUser.userIsAdmin) {
        Menu.menu[1].show = true;
        Menu.menu[2].show = true;
        Menu.menu[3].show = true;
        Menu.menu[4].show = true;
    }
}
