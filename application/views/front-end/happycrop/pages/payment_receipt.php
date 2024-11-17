<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <?php $this->load->view('admin/headcustom.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        .pagebreak {
            display: block;
            clear: both;
            page-break-after: always;
        }
        body{
            font-size: 13px !important;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center border border-black p-2" id="generatePDf">
            <div class="col-lg-12 py-4">
                <h2 class="text-center font-weight-bold">Payment Receipt</h2>
            </div>
            <div class="col-lg-8 pb-2">
                <div class="bg-gray-light h-100">
                    <table class="table border-none">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <p class="font-weight-bold p-2 m-0">Retail Shop Name : <?php echo $order[0]["retailer_company_name"] ?></p>

                                </td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Address : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"><?= $order[0]['billing_address'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Contact : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"><?= $order[0]['mobile'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Email : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"><?= $order[0]['email'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">GSTIN : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"><?= $order[0]['retailer_gst_no'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">State : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"><?= $order[0]['state'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">

            </div>
            <div class="col-lg-6 pb-2 d-none">
                <div class="bg-gray-light h-100">
                    <table class="table border-none">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <p class="font-weight-bold p-2 m-0">Manufacturer/ Service Provider </p>

                                </td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Name : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"><?= $manufacture['company_name'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Address : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"> <?php
                                                                                    if (isset($manufacture['address'])) {
                                                                                        echo $manufacture['addresss'];
                                                                                    } else {
                                                                                        echo $manufacture['plot_no'] . ' ' . $manufacture['street_locality'] . ' ' . $manufacture['landmark'] . ' ' . $manufacture['city'] . ' ' . $manufacture['state'] . ' ' . $manufacture['pin'];
                                                                                    }
                                                                                    ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Contact : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"><?= $manufacture['mobile'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Email : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"><?= $manufacture['email'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">GSTIN : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"><?= $manufacture['gst_no'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">State : -</td>
                                <td class="border-top-0 py-1 text-left pl-2 w-75"><?= $manufacture['state'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-12">
                <p class="font-weight-bold p-2 m-0">Paid To</p>

            </div>
            <div class="col-lg-6">
                <div class="bg-gray-light h-100">
                    <table class="table border-none">
                        <p class="font-weight-bold p-2 m-0"><?= $this->config->item('happycrop_name'); ?></p>

                        <tbody>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 w-25 font-weight-bold">Address : -</td>
                                <td class="border-top-0 py-1 w-75 pl-2"><?= $this->config->item('address'); ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 w-25 font-weight-bold">Contact : -</td>
                                <td class="border-top-0 py-1 w-75 pl-2"><?= $this->config->item('mobile'); ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 w-25 font-weight-bold">Email : -</td>
                                <td class="border-top-0 py-1 w-75 pl-2"><?= $this->config->item('support'); ?></td>
                            </tr>
                            <!-- <tr class="p-2">
                                <td class="border-top-0 py-1 w-25 font-weight-bold">GSTIN : -</td>
                                <td class="border-top-0 py-1 w-25 pl-2"><?= $order[0]['gstin'] ?></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-gray-light h-100">
                    <table class="table  border-none">
                        <tbody>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-1 w-25 font-weight-bold">Receipt Number : -</td>
                                <td class="border-top-0 py-1 w-75 pl-2"><?php echo $order[0]['order_items'][0]["receipt_no"]; ?></td>
                            </tr>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-1 w-25 font-weight-bold">Order Id : -</td>
                                <td class="border-top-0 py-1 w-75 pl-2"><?php echo $order[0]['order_items'][0]["order_id"]; ?></td>
                            </tr>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-1 w-25 font-weight-bold">Date : -</td>
                                <td class="border-top-0 py-1 w-75 pl-2"><?= date('d M Y H:i', strtotime($order[0]['date_added'])); ?></td>
                            </tr>

                            <tr class="p-2">
                                <td class="border-top-0 py-1 w-25 font-weight-bold">State : -</td>
                                <td class="border-top-0 py-1 w-75 pl-2"><?= $order[0]['state'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-12 py-3">
                <div class="row">
                    <div class="col-lg-6 py-2">
                        <div class="form-group ">
                            <label for="receipt"> <strong>Transaction Number</strong> </label>
                            <div class="bg-gray-light p-2 w-100"><?php echo $order[0]['order_items'][0]["transaction_id"] ?></div>
                        </div>
                    </div>
                    <div class="col-lg-6 py-2">
                        <div class="form-group ">
                            <label for="receipt"> <strong>Received</strong> </label>
                            <div class="bg-gray-light p-2 w-100"><?php echo number_format($order[0]["total_payable"], 2); ?></div>
                        </div>
                    </div>
                    <div class="col-lg-12 py-2">
                        <div class="form-group ">
                            <label for="receipt"> <strong>Invoice Amount in Words</strong> </label>
                            <div class="bg-gray-light p-2 w-100"><?php echo convertNumberToWords($order[0]["total_payable"]) ?></div>
                        </div>
                    </div>
                    <div class="pagebreak pb-5"></div>

                    <?php include(APPPATH . 'views/front-end/happycrop/authsignature.php'); ?>
                    <div class="col-lg-12 pt-5">

                        <?php include(APPPATH . 'views/front-end/happycrop/exportfooter.php'); ?>
                    </div>

                </div>
            </div>
        </div>
        <?php if ($view == "view") { ?>

            <div class="row justify-content-center">
                <button class="btn btn-primary my-3" onclick="generatePDF();">Download</button>
                <button class="btn btn-primary my-3 ml-2" onclick="printDiv();">Print</button>
            </div>
        <?php } ?>
    </div>
    <?php $this->load->view('admin/include-script.php'); ?>
    <script>
        baseUrl = '<?php echo base_url(); ?>';

        $(document).ready(function() {
            <?php if ($view != "view") { ?>
                // generatePDF();
            <?php } ?>

        });

        function printDiv() {
            var printContents = document.getElementById('generatePDf').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }

        // function generatePDF() {
        //     const element = document.getElementById('generatePDf');
        //     html2pdf().from(element).set({
        //         margin: [5, 5],
        //         filename: 'Invoice.pdf',
        //         html2canvas: {
        //             scale: 2,
        //             scrollY: 0
        //         },
        //         jsPDF: {
        //             unit: 'mm',
        //             format: 'a4',
        //             orientation: 'portrait'
        //         }
        //     }).save().then(function() {
        //         history.back();

        //     });

        // }
        async function generatePDF() {
            const element = document.getElementById('generatePDf');
            const footer = document.getElementById('pdffooter');
            footer.style.display = 'none';
            const imageURL = baseUrl + 'assets/footer_img.png'; 

            const options = {
                margin: [5, 5],
                filename: 'Invoice.pdf',
                html2canvas: {
                    scale: 2,
                    scrollY: 0
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'A4',
                    orientation: 'portrait'
                }
            };

            try {
                const pdfInstance = await html2pdf().from(element).set(options).toPdf().get('pdf');

                const totalPages = pdfInstance.internal.getNumberOfPages();
                const pageWidth = pdfInstance.internal.pageSize.getWidth();
                const pageHeight = pdfInstance.internal.pageSize.getHeight();

                const img = new Image();
                img.src = imageURL;

                img.onload = () => {
                    const imgWidth = pageWidth * 0.9; 
                    const imgHeight = (img.height / img.width) * imgWidth;
                    const x = (pageWidth - imgWidth) / 2; 
                    const y = pageHeight - imgHeight - 10; 

                    pdfInstance.setPage(totalPages);
                    pdfInstance.addImage(img, 'PNG', x, y, imgWidth, imgHeight);

                    pdfInstance.save('Invoice_with_Footer.pdf');
                };

                img.onerror = () => {
                    console.error('Failed to load footer image.');
                    pdfInstance.save('Receipt.pdf'); 
                };

            } catch (error) {
                console.error('Error generating PDF:', error);
            }
        }
    </script>
</body>

</html>