// Importing necessary libraries
import './bootstrap';
import 'bootstrap'; // Bootstrap JS load
import * as bootstrap from 'bootstrap'; // Import Bootstrap object
import jQuery from 'jquery';
import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.css";

import moment from 'moment-timezone';

// Make libraries available globally
window.TomSelect = TomSelect;
window.bootstrap = bootstrap;
window.$ = window.jQuery = jQuery;

window.moment = moment; // Make sure moment is available globally
moment().tz("Asia/Dhaka").format();

import "daterangepicker/daterangepicker.css";
import "daterangepicker/daterangepicker.js";

// Initialize TomSelect
window.initTomSelect = function () {
    document.querySelectorAll(".tom-select").forEach((el) => {
        if (!el.tomSelect) {
            new TomSelect(el, {
                allowEmptyOption: true,
                create: true,
            });
        }
    });
};

// Slick carousel and its styles
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';
import 'slick-carousel/slick/slick.js';

// Import ApexCharts and ApexTree
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;
import ApexTree from 'apextree';
window.ApexTree = ApexTree;

// Import JSZip for handling zip files
import JSZip from 'jszip';
window.JSZip = JSZip;

// Import pdfmake and related files
import pdfmake from 'pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
pdfmake.addVirtualFileSystem(pdfFonts);
window.pdfmake = pdfmake;

// DataTables imports
import DataTable from 'datatables.net-bs5';
window.DataTable = DataTable;
import 'datatables.net-autofill-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-colreorder-bs5';
import DateTime from 'datatables.net-datetime';
window.DateTime = DateTime;
import 'datatables.net-fixedcolumns-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-keytable-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-rowgroup-bs5';
import 'datatables.net-rowreorder-bs5';
import 'datatables.net-scroller-bs5';
import 'datatables.net-searchbuilder-bs5';
import 'datatables.net-searchpanes-bs5';
import 'datatables.net-select-bs5';
import 'datatables.net-staterestore-bs5';

// Optional CKEditor (commented out if not used)
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
// window.ClassicEditor = ClassicEditor;
