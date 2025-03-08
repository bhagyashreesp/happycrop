<!DOCTYPE html>
<html>
<?php $this->load->view('admin/include-head.php'); ?>
<style>
    .fixed-table-toolbar {
        position: absolute;
        top: 134px;
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
<div id="loading">
    <div class="lds-ring">
        <div></div>
    </div>
</div>

<body class="hold-transition sidebar-mini layout-fixed ">
    <div class=" wrapper ">
        <?php $this->load->view('admin/include-navbar.php') ?>
        <?php $this->load->view('admin/include-sidebar.php'); ?>
        <?php $this->load->view('admin/pages/' . $main_page); ?>
        <?php $this->load->view('admin/include-footer.php'); ?>
    </div>
    <?php $this->load->view('admin/include-script.php'); ?>

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
        function shop_sys_orders_query_params(p) {
            return {
                "start_date": $('#shop_start_date').val(),
                "end_date": $('#shop_end_date').val(),
                "order_status": $('#order_status').val(),
                "search_field": $("#shop_search_field").val(),
                "user_id": $('#order_user_id').val(),
                limit: p.limit,
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                search: p.search
            };
        }
        function shop_ext_orders_query_params(p) {
            return {
                "start_date": $('#shopext_start_date').val(),
                "end_date": $('#shopext_end_date').val(),
                "order_status": $('#order_status').val(),
                "search_field": $("#shopext_search_field").val(),
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