import _ from 'lodash';
import axios from 'axios';
import $ from 'jquery';
import AOS from 'aos';
import select2 from 'select2';
import Cropper from 'cropperjs';
import Dropzone from 'dropzone';
import { Swiper, Navigation, Pagination } from "swiper";
import "bootstrap";
import { Modal } from "bootstrap";
import Toastify from 'toastify-js';
import { TempusDominus } from "@eonasdan/tempus-dominus";

window._ = _;
window.bootstrap = import('bootstrap');
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.$ = window.jquery = window.jQuery = $;
window.gallery = import('blueimp-gallery');
select2();
window.Cropper = Cropper;
window.Dropzone = Dropzone;
window.Swiper = Swiper;
Swiper.use([Navigation, Pagination]);
window.Modal = Modal;
window.toast = Toastify;
window.TempusDominus = TempusDominus;

// Init application
document.addEventListener('DOMContentLoaded', () => {
    "use strict";

    /**
     * Preloader
     */
    const preloader = document.querySelector('#preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                preloader.classList.add('loaded');
            }, 1000);
            setTimeout(() => {
                preloader.remove();
            }, 2000);
        });
    }

    /**
     * Mobile nav toggle
     */
    const mobileNavShow = document.querySelector('.mobile-nav-show');
    const mobileNavHide = document.querySelector('.mobile-nav-hide');

    document.querySelectorAll('.mobile-nav-toggle').forEach(el => {
        el.addEventListener('click', function(event) {
            event.preventDefault();
            mobileNavToogle();
        })
    });

    function mobileNavToogle() {
        document.querySelector('body').classList.toggle('mobile-nav-active');
        mobileNavShow.classList.toggle('d-none');
        mobileNavHide.classList.toggle('d-none');
    }

    /**
     * Hide mobile nav on same-page/hash links
     */
    document.querySelectorAll('#navbar a').forEach(navbarlink => {

        if (!navbarlink.hash) return;

        let section = document.querySelector(navbarlink.hash);
        if (!section) return;

        navbarlink.addEventListener('click', () => {
            if (document.querySelector('.mobile-nav-active')) {
                mobileNavToogle();
            }
        });

    });

    /**
     * Toggle mobile nav dropdowns
     */
    const navDropdowns = document.querySelectorAll('.navbar .dropdown > a');

    navDropdowns.forEach(el => {
        el.addEventListener('click', function(event) {
            if (document.querySelector('.mobile-nav-active')) {
                event.preventDefault();
                this.classList.toggle('active');
                this.nextElementSibling.classList.toggle('dropdown-active');

                let dropDownIndicator = this.querySelector('.dropdown-indicator');
                dropDownIndicator.classList.toggle('bi-chevron-up');
                dropDownIndicator.classList.toggle('bi-chevron-down');
            }
        })
    });

    /**
     * Scroll top button
     */
    const scrollTop = document.querySelector('.scroll-top');
    if (scrollTop) {
        const togglescrollTop = function() {
            window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
        }
        window.addEventListener('load', togglescrollTop);
        document.addEventListener('scroll', togglescrollTop);
        scrollTop.addEventListener('click', window.scrollTo({
            top: 0,
            behavior: 'smooth'
        }));
    }

    /**
     * Animation on scroll function and init
     */
    function aosInit() {
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    }
    window.addEventListener('load', () => {
        aosInit();
    });

    const timepickers = document.querySelectorAll('.timepicker');
    timepickers.forEach(el => {
        new TempusDominus(el, {
            localization: {
                locale: 'lt',
                hourCycle: 'h23',
                format: "HH:mm"
            },
            display: {
                viewMode: 'clock',
                components: {
                    calendar: false,
                    date: false,
                    month: false,
                    year: false,
                    decades: false,
                    clock: true,
                    hours: true,
                    minutes: true,
                    seconds: false,
                },
                inline: false,
                theme: 'auto'
            },
            allowInputToggle: true,
            useCurrent: true,
            defaultDate: undefined,
        });
    });
});

