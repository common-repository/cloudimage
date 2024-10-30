if (ciSettings.lazy_loading) {
    document.addEventListener("DOMContentLoaded", function(event) {
        window.lazySizesConfig = window.lazySizesConfig || {};
        window.lazySizesConfig.init = false;
    });
}

if (ciSettings.token_or_cname !== '' && !ciSettings.standard_mode) {
    document.addEventListener("DOMContentLoaded", function(event) {
        //do work
        let ciToken = ciSettings.token_or_cname;
        let explodedToken = ciToken.split(".");
        let token = explodedToken.shift();
        let domain = explodedToken.join('.');

        let config = {};
        if (ciSettings.lazy_loading) {
            config.lazyLoading = true;
        }

        config.doNotReplaceURL = false;
        if (ciSettings.use_original_url && ciSettings.custom_library === '') {
            config.doNotReplaceURL = true;
        }

        let params = '';
        if (ciSettings.prevent_image_upsize) {
            params = 'org_if_sml=1&' + ciSettings.custom_library;
        } else {
            params = ciSettings.custom_library
        }
        config.params = params;

        if (ciSettings.remove_v7) {
            config.apiVersion = null;
        }

        let devicePixelRatioList = [];
        if (ciSettings.maximum_pixel_ratio === '1') {
            devicePixelRatioList = [1];
        } else if (ciSettings.maximum_pixel_ratio === '1.5') {
            devicePixelRatioList = [1, 1.5];
        } else if (ciSettings.maximum_pixel_ratio === '2') {
            devicePixelRatioList = [1, 1.5, 2]
        }
        config.devicePixelRatioList = devicePixelRatioList;

        if (ciSettings.custom_function !== '') {
            let x = "function (props) " + ciSettings.custom_function;
            config.processQueryString = eval("(" + x + ")");
        }

        let imageSizeAttributes = 'use';
        if (ciSettings.image_size_attributes !== 'use') {
            imageSizeAttributes = ciSettings.image_size_attributes;
        }
        config.imageSizeAttributes = imageSizeAttributes;

        config.token = token;

        if (domain !== '') {
            config.domain = domain;
        }

        config.limitFactor = 10;
        config.ratio = 1;
        const myInterval = setInterval(ciRender, 400);

        window.onload = function() {
            var carousels = jQuery('.carousel');
            var number = jQuery('.carousel').length;
            if (number > 0) {
                carousels.on('slide.bs.carousel', function () {
                    window.dispatchEvent(new Event('resize'));
                });
            }
        };

        function ciRender() {
            if (window.CIResponsive) {
                clearInterval(myInterval);
                window.ciResponsive = new window.CIResponsive(config);

                var s_ajaxListener = new Object();
                s_ajaxListener.tempOpen = XMLHttpRequest.prototype.open;
                s_ajaxListener.tempSend = XMLHttpRequest.prototype.send;
                s_ajaxListener.callback = function () {
                    let rerenderImage = setInterval(function () {
                        window.ciResponsive.process();
                    }, 400);

                    setTimeout(function () {
                        clearInterval(rerenderImage);
                    }, 5000);
                }

                XMLHttpRequest.prototype.open = function(a,b) {
                    if (!a) var a='';
                    if (!b) var b='';
                    s_ajaxListener.tempOpen.apply(this, arguments);
                    s_ajaxListener.method = a;
                    s_ajaxListener.url = b;
                    if (a.toLowerCase() == 'get') {
                        s_ajaxListener.data = b.split('?');
                        s_ajaxListener.data = s_ajaxListener.data[1];
                    }
                }

                XMLHttpRequest.prototype.send = function(a,b) {
                    if (!a) var a='';
                    if (!b) var b='';
                    s_ajaxListener.tempSend.apply(this, arguments);
                    if(s_ajaxListener.method.toLowerCase() == 'post')s_ajaxListener.data = a;
                    s_ajaxListener.callback();
                }

                if (ciSettings.lazy_loading) {
                    window.lazySizes.init();
                }
            }
        }
    });
}