import './bootstrap';

import jQuery from "jquery";
import { AlertColor } from './helper/alert';

window.$ = jQuery;

window.alertType = {
    'success': new AlertColor('#74d119', '#cbff99', '#74d119'),
    'error': new AlertColor('#db4c4c', '#efb2b2', '#e57f7f'),
    'warning': new AlertColor('#ffe084', '#fff3d1', '#ffe8a3'),
}
