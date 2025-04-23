require('./bootstrap');

import Alpine from 'alpinejs'
import { createPopper } from "@popperjs/core";
import { TabulatorFull as Tabulator } from 'tabulator-tables';

window.Alpine = Alpine;
Alpine.start()

window.createPopper = createPopper;
window.Tabulator = Tabulator;