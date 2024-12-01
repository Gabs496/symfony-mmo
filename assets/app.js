import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
// import './vendor/bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';

import $ from 'jquery';
import 'bootstrap';
import * as polyfill from 'event-source-polyfill';

window.$ = $;
window.EventSource = polyfill.default.EventSourcePolyfill;