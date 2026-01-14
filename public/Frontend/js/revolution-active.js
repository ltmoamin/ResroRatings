"use strict";

var tpj=jQuery;
var revapi1052;
tpj(document).ready(function() {
    if(tpj("#rev_slider_1052_1").revolution == undefined){
        revslider_showDoubleJqueryError("#rev_slider_1052_1");
    }else{
        revapi1052 = tpj("#rev_slider_1052_1").show().revolution({
            sliderType:"fullscreen",
            jsFileLocation:"js/revolution",
            sliderLayout:"fullscreen",
            dottedOverlay:"none",
            delay:6000,
            navigation: {
                keyboardNavigation:"on",
                keyboard_direction: "vertical",
                mouseScrollNavigation:"off",
                mouseScrollReverse:"default",
                onHoverStop:"off",
                hide_onleave: true,
                hide_delay: 200,
                touch:{
                    touchenabled:"off",
                    swipe_threshold: 75,
                    swipe_min_touches: 1,
                    swipe_direction: "vertical",
                    drag_block_vertical: false
                },
                arrows: {
                    enable: false,
                    style: 'dot',
                },
                bullets: {

                    enable: true,
                    style: 'hesperiden',
                    tmp: '',
                    direction: 'horizontal',
                    rtl: false,

                    container: 'slider',
                    h_align: 'center',
                    v_align: 'bottom',
                    h_offset: 0,
                    v_offset: 20,
                    space: 5,

                    hide_onleave: false,
                    hide_onmobile: false,
                    hide_under: 0,
                    hide_over: 9999,
                    hide_delay: 200,
                    hide_delay_mobile: 1200

                }
            },
            responsiveLevels:[1240,1025,778,480],
            visibilityLevels:[1240,1025,778,480],
            gridwidth:[1400,1240,778,480],
            gridheight:[600,500,400,300],
            lazyType:"none",
            parallax: {
                type:"mouse",
				origo:"slidercenter",
				speed:200,
				levels:[2,3,4,5,6,7,12,16,10,50,46,47,48,49,50,55],
                // disable_onmobile: 'on'
            },
            shadow:0,
            spinner:"on",
            stopLoop:"on",
            stopAfterLoops:-1,
            stopAtSlide:-1,
            shuffle:"off",
            autoHeight:"off",
            fullScreenAutoWidth:"off",
            fullScreenAlignForce:"off",
            fullScreenOffsetContainer: "",
            fullScreenOffset: "",
            disableProgressBar:"on",
            hideThumbsOnMobile:"off",
            hideSliderAtLimit:0,
            hideCaptionAtLimit:0,
            hideAllCaptionAtLilmit:0,
            debugMode:false,
            fallbacks: {
                simplifyAll:"on",
                nextSlideOnWindowFocus:"off",
                disableFocusListener:false,
            }
        });
    }
});	/*ready*/
