import './bootstrap';
import ApexCharts from 'apexcharts'
window.ApexCharts = ApexCharts

import JSZip from 'jszip';
window.JSZip = JSZip;

import pdfmake from 'pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
import pdfMake from "pdfmake/build/pdfmake";
pdfMake.addVirtualFileSystem(pdfFonts);

// pdfmake.vfs = pdfFonts.pdfMake.vfs;
window.pdfmake = pdfmake;


import moment from 'moment';
window.moment = moment;

// Other DataTables imports
import DataTable from 'datatables.net-bs5';
window.DataTable = DataTable;
import 'datatables.net-autofill-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
// import 'datatables.net-plugins/dataRender/datetime.mjs';
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

// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
// window.ClassicEditor = ClassicEditor;


// for datatable import

// import jszip from 'jszip';
// import pdfmake from 'pdfmake';
// import DataTable from 'datatables.net-bs5';
// import 'datatables.net-autofill-bs5';
// import 'datatables.net-buttons-bs5';
// import 'datatables.net-buttons/js/buttons.colVis.mjs';
// import 'datatables.net-buttons/js/buttons.html5.mjs';
// import 'datatables.net-buttons/js/buttons.print.mjs';
// import 'datatables.net-colreorder-bs5';
// import DateTime from 'datatables.net-datetime';
// import 'datatables.net-fixedcolumns-bs5';
// import 'datatables.net-fixedheader-bs5';
// import 'datatables.net-keytable-bs5';
// import 'datatables.net-responsive-bs5';
// import 'datatables.net-rowgroup-bs5';
// import 'datatables.net-rowreorder-bs5';
// import 'datatables.net-scroller-bs5';
// import 'datatables.net-searchbuilder-bs5';
// import 'datatables.net-searchpanes-bs5';
// import 'datatables.net-select-bs5';
// import 'datatables.net-staterestore-bs5';
