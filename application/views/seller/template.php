<!DOCTYPE html>
<html>
<?php $this->load->view('seller/include-head.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div id="loading">
    <div class="lds-ring">
        <div></div>
    </div>
</div>

<body class="hold-transition sidebar-mini layout-fixed ">
    <div class=" wrapper ">
        <?php $this->load->view('seller/include-navbar.php') ?>
        <?php $this->load->view('seller/include-sidebar.php'); ?>
        <?php $this->load->view('seller/pages/' . $main_page); ?>
        <?php $this->load->view('seller/include-footer.php'); ?>
    </div>
    <?php $this->load->view('seller/include-script.php'); ?>
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
    </script>
</body>

</html>