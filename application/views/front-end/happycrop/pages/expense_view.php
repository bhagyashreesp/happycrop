<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?php $this->load->view('admin/headcustom.php'); ?>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script> -->

    <style>
        body {
            font-size: 13px;
        }

        .signatureimg {
            width: 228px;
            height: 143px;
            margin-left: 100px;
            position: absolute;
            right: 0;
        }

        .pagebreak {
            display: block;
            clear: both;
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center border border-black p-2" id="generatePDf">
            <div class="col-lg-12 py-4">
                <h2 class="text-center font-weight-bold">Expense</h2>
            </div>
            <div class="col-lg-12 pb-2">
                <div class="bg-gray-light">
                    <table class="table border-none">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <p class="font-weight-bold p-2 m-0"> <?php echo $expenseDetails[0]['retailer']["company_name"]; ?></p>

                                </td>
                            </tr>

                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Address : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"> <?php echo $expenseDetails[0]['retailer']['plot_no'] . ' ' . $expenseDetails[0]['retailer']['street_locality'] . ' ' . $expenseDetails[0]['retailer']['landmark'] . ' ' . $expenseDetails[0]['retailer']['city'] . ' ' . $expenseDetails[0]['retailer']['state'] . ' ' . $expenseDetails[0]['retailer']['pin']; ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Contact : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $this->session->userdata('mobile') ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Email : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $this->session->userdata('email') ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">GSTIN : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $expenseDetails[0]['retailer']['gst_no'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-12 py-3">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Qty</th>
                                <th>Price/Unit</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $total_price = 0;
                            $total_amt = 0;
                            $total_qty = 0;
                            foreach ($expenseDetails[0]['items'] as $key => $item) {
                                $total_amt += $item["amount"];
                                $total_price += $item["price_unit"];
                                $total_qty += ($item['quantity']) ;

                            ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?php echo $item['name']; ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo $item["price_unit"] ?></td>
                                        <td><?php echo $item["amount"] ?></td>
                                    </tr>
                            <?php
                                
                                $i++;
                            }
                            ?>
                            <tr>
                                <th class="text-left" colspan="2">Total Amount</th>
                                <th class="text-left"><?= $total_qty; ?></th>
                                <th class="text-left"><?= number_format($total_price, 2); ?></th>
                                <th class="text-left"><?php echo  number_format(($total_amt), 2); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-between">
                    <div class="col-lg-6 py-2">
                        <div class="form-group ">
                            <label for="receipt"> <strong>Invoice Amount in Words</strong> </label>
                            <div class="bg-gray-light p-2 w-100"><?php echo convertNumberToWords($total_amt) ?></div>
                        </div>
                        <div class="border border-dark p-2">
                            <h5 class="font-weight-bold">Description</h5>
                            <div>
                                <?php echo $expenseDetails[0]['description']; ?>
                            </div>
                            

                        </div>
                        
                    </div>
                    <div class="col-lg-4 pt-2 mt-4">
                        <div class="bg-gray-light">
                            <table class="table h-100 border-none">
                                <tbody>
                                    <tr class="p-2 pt-2 ">
                                        <td class="border-top-0 w-50 font-weight-bold">Total : -</td>
                                        <td class="border-top-0 w-50 pl-2"><?php echo $settings['currency'] . ' ' . number_format(($total_amt), 2); ?></td>
                                    </tr>
                                    <hr>
                                    <tr class="p-2 pt-2 border-top">
                                        <td class="border-top-0 w-50 font-weight-bold">Paid : -</td>
                                        <td class="border-top-0 w-50 pl-2"><?php echo $settings['currency'] . ' ' . number_format(($expenseDetails[0]["paid_amount"]), 2); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- <p class="pb-2 text-right pr-5"><?= $this->config->item('happycrop_name'); ?></p>
                        <div class="position-relative">
                            <img src="<?= base_url('assets/signature-img.jpeg') ?>" class="signatureimg">
                        </div>
                        <p class="py-2 text-right pr-5">Authorized Signatory</p> -->
                    </div>
                </div>

                <?php include(APPPATH . 'views/front-end/happycrop/exportfooter.php'); ?>


            </div>
        </div>

    </div>
        <div class="row justify-content-center">
            <button class="btn btn-primary my-3" onclick="generatePDF();">Download</button>
            <button class="btn btn-primary my-3 ml-2" onclick="printDiv();">Print</button>
        </div>
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
                    pdfInstance.save('Invoice.pdf');
                };

            } catch (error) {
                console.error('Error generating PDF:', error);
            }
        }
    </script>
</body>

</html>