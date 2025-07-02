require('./bootstrap');

import Alpine from 'alpinejs'
import { createPopper } from "@popperjs/core";
import { TabulatorFull as Tabulator } from 'tabulator-tables';
import Swal from 'sweetalert2';
import { deleteItem, showLoading } from './utils';
import $ from 'jquery';

// Ahora puedes usar deleteItem y showLoading en cualquier parte de tu JS principal
window.Alpine = Alpine;
Alpine.start()

window.createPopper = createPopper;
window.Tabulator = Tabulator;
window.Swal = Swal;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});

window.deleteItem = deleteItem;
window.showLoading = showLoading;
