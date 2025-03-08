<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <meta name="keywords" content='<?= $keywords ?>'>
    <meta name="description" content='<?= $description ?>'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .fixed-table-toolbar {
            position: absolute;
            top: 20px;
            right: 30px;
            display: block !important;
        }

        .fixed-table-toolbar .btn-secondary {
            background-color: #007bff !important;
        }

        .float-right.pagination {
            display: block !important;
        }
    </style>

    <?php $cookie_lang = $this->input->cookie('language', TRUE);
    $path = $is_rtl = "";
    if (!empty($cookie_lang)) {
        $language = get_languages(0, $cookie_lang, 0, 1);
        if (!empty($language)) {
            $path = ($language[0]['is_rtl'] == 1) ? 'rtl/' : "";
            $is_rtl =  ($language[0]['is_rtl'] == 1) ? true : false;
        }
    } else {
        /* read the default language */
        $lang = $this->config->item('language');
        $language = get_languages(0, $lang, 0, 1);
        if (!empty($language)) {
            $path = ($language[0]['is_rtl'] == 1) ? 'rtl/' : "";
            $is_rtl =  ($language[0]['is_rtl'] == 1) ? true : false;
        }
    }
    $data['is_rtl'] = $is_rtl;
    ?>
    <?php $this->load->view('front-end/' . THEME . '/include-css', $data); ?>
</head>

<body id="body" data-is-rtl='<?= $is_rtl ?>'>

    <h1 class="d-none">Happy Crop</h1>
    <?php $this->load->view('front-end/' . THEME . '/header'); ?>
    <?php $this->load->view('front-end/' . THEME . '/pages/' . $main_page); ?>
    <?php $this->load->view('front-end/' . THEME . '/footer'); ?>

    <?php $this->load->view('front-end/' . THEME . '/include-script'); ?>

    <!-- Bootstrap Table CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.css">

    <!-- Bootstrap Table JS -->
    <script src="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>

    <!-- Export Extension -->
    <script src="https://unpkg.com/bootstrap-table@1.22.1/dist/extensions/export/bootstrap-table-export.min.js"></script>

    <!-- Table Export Plugin -->
    <script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        function printPDF(divid) {
            var printContents = document.getElementById(divid).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function generatePDF(divid) {
            // window.jsPDF = window.jspdf.jsPDF;
            const element = document.getElementById(divid);
            html2pdf().from(element).set({
                margin: [5, 5],
                filename: 'Invoice.pdf',
                html2canvas: {
                    scale: 2,
                    scrollY: 0

                },
                jsPDF: {
                    unit: 'mm',
                    format: 'legal',
                    orientation: 'portrait'
                }
            }).save();

        }
        $('.table-resp').bootstrapTable({
            showExport: true,
            exportDataType: 'all', // Options: 'basic', 'all', 'selected'
            exportTypes: ['pdf', 'excel', 'csv'], // Customize buttons
            exportOptions: {
                fileName: 'reports' // Set the exported file name
            }
        });

        function external_orders_query_params(p) {

            return {
                "start_date": $('#ext_start_date').val(),
                "end_date": $('#ext_end_date').val(),
                "order_status": $('#order_status').val(),
                "search_field": $("#ext_search_field").val(),
                "user_id": $('#order_user_id').val(),
                limit: p.limit,
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                search: p.search
            };
        }
    </script>
</body>

</html>