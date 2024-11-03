<!DOCTYPE html>
<html lang="en" id="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($purchase_order != "" && $purchase_order == "1" ? "Purchase Order" : "Sale Invoice"); ?></title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?php $this->load->view('admin/headcustom.php'); ?>


    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script> -->

    <style>
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
                <h2 class="text-center font-weight-bold"><?php echo ($purchase_order != "" && $purchase_order == "1" ? "Purchase Order" : "Sale Invoice"); ?></h2>
            </div>
            <div class="col-lg-8 pb-2">
                <div class="bg-gray-light">
                    <p class="font-weight-bold p-2 m-0">Manufacturer / Service provider Name</p>
                    <table class="table border-none">
                        <tbody>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">Address : -</td>
                                <td class="border-top-0 py-1 pl-2"> <?= $manufacture['plot_no'] . ' ' . $manufacture['street_locality'] . ' ' . $manufacture['landmark'] . ' ' . $manufacture['city'] . ' ' . $manufacture['state'] . ' ' . $manufacture['pin'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">Contact : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $manufacture['mobile'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">Email : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $manufacture['email'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">GSTIN : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $manufacture['gst_no'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">State : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $manufacture['state'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4 pb-2">
                <!-- <img src="http://localhost/happy_crop/uploads/media/2023/happycrop-logo1.png"> -->

            </div>
            <div class="col-lg-6">
                <div class="bg-gray-light h-100">
                    <table class="table border-none">
                        <tbody>
                            <tr class="p-2">
                                <td class="border-top-0 py-1  font-weight-bold">Seller Name : -</td>
                                <td class="border-top-0 py-1  pl-2"><?= $order['retailer_company_name'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1  font-weight-bold">Address : -</td>
                                <td class="border-top-0 py-1  pl-2"><?= $order['billing_address'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1  font-weight-bold">Phone Number : -</td>
                                <td class="border-top-0 py-1  pl-2"><?= $order['mobile'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1  font-weight-bold">Email : -</td>
                                <td class="border-top-0 py-1  pl-2"><?= $order['email'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1  font-weight-bold">State of Supply : -</td>
                                <td class="border-top-0 py-1  pl-2"><?= $order['state'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-gray-light h-100">
                    <table class="table  border-none">
                        <tbody>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-1  font-weight-bold">Order Number : -</td>
                                <td class="border-top-0 py-1  pl-2"><?php echo 'HC-A' . $order['id']; ?></td>
                            </tr>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-1  font-weight-bold">Order Date : -</td>
                                <td class="border-top-0 py-1  pl-2"><?= date('d M Y H:i', strtotime($order['date_added'])); ?></td>
                            </tr>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-1  font-weight-bold">Due Date : -</td>
                                <td class="border-top-0 py-1  pl-2"><?= date('d M Y H:i', strtotime($order['schedule_delivery_date'])) ?></td>
                            </tr>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-1  font-weight-bold">GSTIN : -</td>
                                <td class="border-top-0 py-1  pl-2"><?= $order['retailer_gst_no']; ?></td>
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
                                <th>HSN No</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Price/Unit</th>
                                <th>GST</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $total_amt = 0;
                            $total_qty = 0;
                            $total_gst = 0;
                            $sub_total = 0;
                            foreach ($order['order_items'] as $key => $item) {
                                $total_amt += $item["price"] * (($item['quantity']) ? $item['quantity'] : $item['quantity']);
                                $total_qty += (($item['quantity']) ? $item['quantity'] : $item['quantity']);
                                $total_gst += ($item["price"] * $item['tax_percentage'] / 100);
                                $sub_total += ($total_amt - $total_gst);
                                if ($manufacture['seller_id'] == $item['seller_id']) {
                            ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?php echo $item['product_name']; ?></td>
                                        <td><?php echo $item['hsn_no']; ?></td>
                                        <td><?php echo (($item['quantity']) ? $item['quantity'] : $item['quantity']) ?></td>
                                        <td><?php echo (($item['unit'] != '') ? $item['unit'] : $item['pv_unit']) ?></td>
                                        <td><?php echo $item["price"] ?></td>
                                        <td><?= isset($item['tax_percentage']) && !empty($item['tax_percentage']) ? ($item["price"] * $item['tax_percentage'] / 100) . '(' . $item['tax_percentage'] . ' %)' : '-' ?></td>
                                        <td><?= ($item["price"] * (($item['quantity']) ? $item['quantity'] : $item['quantity'])) ?></td>
                                    </tr>
                            <?php
                                }
                                $i++;
                            }
                            ?>
                            <tr>
                                <th class="text-left" colspan="2">Total Amount</th>
                                <th class="text-left"><?= $total_qty; ?></th>
                                <th class="text-left" colspan="2"></th>
                                <th class="text-left"><?= $settings['currency'] . '' . number_format($total_gst, 2); ?></th>
                                <th class="text-left"><?php echo $settings['currency'] . '' . number_format(($total_amt), 2); ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-6 py-2">
                        <div class="form-group ">
                            <label for="receipt"> <strong>Invoice Amount in Words</strong> </label>
                            <div class="bg-gray-light p-2 w-100"><?php echo convertNumberToWords($total_amt) ?></div>
                        </div>
                        <div class="border border-dark p-2">
                            <h5 class="font-weight-bold">Terms and conditions</h5>
                            <ul class="list-none">
                                <?php
                                foreach ($getterms as $key => $value) { ?>
                                    <li><?php echo ($key + 1) . ". " . $value['terms_conditions']; ?></li>

                                <?php
                                }
                                ?>
                                <li>Thanks for doing business with us!</li>
                            </ul>

                        </div>
                    </div>
                    <div class="col-lg-6 pt-4 mt-3">
                        <div class="bg-gray-light h-100">
                            <table class="table  border-none">
                                <tbody>
                                    <tr class="p-2 ">
                                        <td class="border-top-0 font-weight-bold">Sub Total : -</td>
                                        <td class="border-top-0 pl-2"><?php echo $settings['currency'] . '' . number_format(($sub_total), 2); ?></td>
                                    </tr>
                                    <tr class="p-2 ">
                                        <td class="border-top-0 font-weight-bold">Total GST : -</td>
                                        <td class="border-top-0 pl-2"><?php echo $settings['currency'] . '' . number_format(($total_gst), 2); ?></td>
                                    </tr>
                                    <tr class="p-2 border-top">
                                        <td class="border-top-0 font-weight-bold">Total : -</td>
                                        <td class="border-top-0 pl-2"><?php echo $settings['currency'] . '' . number_format(($total_amt), 2); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php include(APPPATH . 'views/front-end/happycrop/exportfooter.php'); ?>


                </div>
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


        function downloadpdf() {
            const element = $('#generatePDf').html();
            htmlData = {
                "html": element
            };

            $.ajax({
                url: baseUrl + 'my_account/generatepdf',
                method: "POST",
                data: {
                    json_data: JSON.stringify(htmlData)
                },
                dataType: 'text',
                success: function(encodedPdfData) {
                    if (encodedPdfData.trim() === "") {
                        alert("No records found");
                    } else {
                        var byteCharacters = atob(encodedPdfData);
                        var byteNumbers = new Uint8Array(byteCharacters.length);
                        for (var i = 0; i < byteCharacters.length; i++) {
                            byteNumbers[i] = byteCharacters.charCodeAt(i);
                        }
                        var pdfBlob = new Blob([byteNumbers], {
                            type: 'application/pdf'
                        });
                        var timestamp = new Date().getTime();
                        var pdfFileName = 'tax_invoice_' + timestamp + '.pdf';
                        saveAs(pdfBlob, pdfFileName);
                    }
                }
            })
        }

        // function generatePDF() {
        //     const element = document.getElementById('generatePDf');
        //     console.log(element.innerHTML);
        //     html2pdf().from(element).set({
        //         margin: [5, 5],
        //         filename: 'Invoice.pdf',
        //         html2canvas: {
        //             scale: 2,
        //             scrollY: 0
        //         },
        //         jsPDF: {
        //             unit: 'mm',
        //             format: 'A4',
        //             orientation: 'portrait'
        //         }
        //     }).save();


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

                    pdfInstance.save('Invoice.pdf');
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