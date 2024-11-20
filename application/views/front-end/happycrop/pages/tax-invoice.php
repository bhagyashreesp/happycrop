<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($dchallan != "" && $dchallan == "1" ? "Delivery Challan" : "Tax Invoice"); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script> -->

    <style>
        body {
            font-size: 13px;
        }

        .signatureimg {
            width: 228px;
            height: 143px;
            /* margin-left: 75px; */
            /* position: absolute; */
            right: 0;
        }

        .pagebreak {
            display: block;
            clear: both;
            page-break-after: always;
        }

        .text-head {
            color: #78ab37;
        }

        .bg-primary {
            background-color: #78ab37 !important;
        }

        .border-top-green {
            border-top: 4px solid #78ab37;
            padding-bottom: 1rem;
        }

        .image-border {
            border: 4px solid #78ab37;
            border-radius: 12px;
            padding: 0.5rem;
        }

        tbody,
        td,
        tfoot,
        th,
        thead,
        tr {
            border-color: #5c9513 !important;
        }
        .w-17{
            width: 17%!important;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center border border-black p-2" id="generatePDf">
            <div class="col-lg-12 py-4">
                <h2 class="text-center fw-bold text-head"><?php echo ($dchallan != "" && $dchallan == "1" ? "Delivery Challan" : "Tax Invoice"); ?></h2>
            </div>
            <div class="border-top-green"></div>

            <div class="col-lg-8 pb-2">
                <div class="bg-gray-light">
                    <table class="table border-none">
                        <tbody>
                            <tr>
                                <td colspan="2" class="border-0">
                                    <p class="fw-bold p-2 m-0">Manufacturer/ Service Provider : <?php echo $manufacture['company_name']; ?></p>

                                </td>
                            </tr>

                            <tr class="p-2">
                                <td class="border-0 py-0 text-left fw-bold ">Address : -</td>
                                <td class="border-0 py-0 text-left pl-2"> <?php echo $manufacture['plot_no'] . ' ' . $manufacture['street_locality'] . ' ' . $manufacture['landmark'] . ' ' . $manufacture['city'] . ' ' . $manufacture['state'] . ' ' . $manufacture['pin']; ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 text-left fw-bold ">Contact : -</td>
                                <td class="border-0 py-0 text-left pl-2"><?= $manufacture['mobile'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 text-left fw-bold ">Email : -</td>
                                <td class="border-0 py-0 text-left pl-2"><?= $manufacture['email'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 text-left fw-bold ">GSTIN : -</td>
                                <td class="border-0 py-0 text-left pl-2"><?= $manufacture['gst_no'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 text-left fw-bold ">State : -</td>
                                <td class="border-0 py-0 text-left pl-2"><?= $manufacture['state'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4 pb-2">
                <?php if (isset($order[0]["logo"])) { ?>
                    <img src="<?php echo base_url() . $order[0]["logo"]; ?>" class="h-100 w-100 image-border">
                <?php } ?>
            </div>
            <div class="border-top-green"></div>

            <!-- <div class="col-lg-12 py-2">
                <div class="bg-gray-light h-100">
                    <table class="table  border-none">
                        <p class="fw-bold p-2 m-0">Place to supply : <?php echo ($order[0]['state'] ? $order[0]['state'] : "Maharashtra") ?></p>
                        <tbody>
                            <tr class="p-2 ">
                                <td class="border-0 py-0  fw-bold w-17">Invoice No : -</td>
                                <td class="border-0 py-0 w-75 pl-2"><?php echo $order[0]["id"]; ?></td>
                            </tr>
                            <tr class="p-2 ">
                                <td class="border-0 py-0  fw-bold w-17">Date : -</td>
                                <td class="border-0 py-0 w-75 pl-2"><?= (!empty($order_item_stages) && $order_item_stages[0]["status"] === "send_invoice" ? date('d M Y ', strtotime($order_item_stages[0]["created_date"])) : date('d M Y', strtotime(date('d-m-y')))); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> -->
            <div class="col-lg-8">
                <div class="bg-gray-light h-100">

                    <table class="table border-none">

                        <p class="fw-bold p-2 m-0">Bill To : <?php echo $order[0]['retailer_company_name']; ?></p>

                        <tbody>
                            <tr class="p-2">
                                <td class="border-0 py-0   w-100"><b>Address : </b> <?= $order[0]["billing_address"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 w-100"><b>Contact : </b> <?= $order[0]["mobile"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 w-100"><b>Email : </b> <?= $order[0]["email"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 w-100"><b>GSTIN : </b> <?= $order[0]['retailer_gst_no'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 w-100"><b>Place to supply : </b> <?php echo ($order[0]['state'] ? $order[0]['state'] : "Maharashtra") ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="bg-gray-light h-100">
                    <table class="table border-none">
                        <p class="fw-bold p-2 m-0">Invoice Details:</p>

                        <tbody>
                            <tr class="p-2 ">
                                <td class="border-0 py-0  w-100"><b>Invoice No  : </b><?php echo $order[0]["id"]; ?></td>
                            </tr>
                            <tr class="p-2 ">
                                <td class="border-0 py-0  w-100"><b>Date :</b> <?= (!empty($order_item_stages) && $order_item_stages[0]["status"] === "send_invoice" ? date('d M Y ', strtotime($order_item_stages[0]["created_date"])) : date('d M Y', strtotime(date('d-m-y')))); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="col-lg-6">
                <div class="bg-gray-light h-100">
                    <table class="table border-none">
                        <p class="fw-bold p-2 m-0">Ship To:</p>
                        <p class="fw-bold p-2 m-0"><?php echo $order[0]['retailer_company_name']; ?></p>

                        <tbody>
                            <tr class="p-2">
                                <td class="border-0 py-0 fw-bold">Address : -</td>
                                <td class="border-0 py-0 pl-2"><?= $order[0]["billing_address"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 fw-bold">Contact : -</td>
                                <td class="border-0 py-0 pl-2"><?= $order[0]["mobile"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 fw-bold">Email : -</td>
                                <td class="border-0 py-0 pl-2"><?= $order[0]["email"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-0 py-0 fw-bold">State : -</td>
                                <td class="border-0 py-0 pl-2"><?= $order[0]['retailer_gst_no'] ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div> -->

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
                            foreach ($order[0]['order_items'] as $key => $item) {
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
                                <th class="text-left" colspan="3">Total Amount</th>
                                <th class="text-left"><?= $total_qty; ?></th>
                                <th class="text-left" colspan="2"></th>
                                <th class="text-left"><?= $settings['currency'] . '' . number_format($total_gst, 2); ?></th>
                                <th class="text-left"><?php echo $settings['currency'] . '' . number_format(($total_amt), 2); ?></th>
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
                            <h5 class="fw-bold">Terms and conditions</h5>
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
                        <?php if ($dchallan == "") { ?>
                            <div class=" p-2 mb-3">
                                <h6 class="mb-1">Pay To</h6>
                                <span>Acct Name: HAPPYCROP AGRI AND BIOTECH LLP<?php //echo $manufacture['company_name'] 
                                                                                ?></span> <br />
                                <span>Acct No: 40616444587<?php //echo $manufacture['account_number'] 
                                                            ?></span> <br />
                                <span>Bank Name: State Bank Of India<?php //echo $manufacture['bank_name'] 
                                                                    ?></span> <br />
                                <span>Bank IFSC: SBIN0000570<?php //echo $manufacture['bank_code'] 
                                                            ?></span> <br />
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-4 pt-2 mt-4">
                        <div class="bg-gray-light">
                            <table class="table h-100 border-none">
                                <tbody>
                                    <tr class="p-2 ">
                                        <td class="border-0 fw-bold">Sub Total : </td>
                                        <td class="border-0 pl-2"><?php echo $settings['currency'] . '' . number_format(($sub_total), 2); ?></td>
                                    </tr>
                                    <tr class="p-2 ">
                                        <td class="border-0 fw-bold">CGST@2.5% : </td>
                                        <td class="border-0 pl-2"><?php echo $settings['currency'] . '' . number_format(($total_gst), 2); ?></td>
                                    </tr>
                                    <tr class="p-2 ">
                                        <td class="border-0 fw-bold">SGST@2.5% : </td>
                                        <td class="border-0 pl-2"><?php echo $settings['currency'] . '' . number_format(($total_gst), 2); ?></td>
                                    </tr>
                                    <tr class="p-2 border-top bg-primary text-white">
                                        <td class="border-0 fw-bold">Total : </td>
                                        <td class="border-0 pl-2"><?php echo $settings['currency'] . '' . number_format(($total_amt), 2); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <p class="pb-2 text-left pr-5"><?= $this->config->item('happycrop_name'); ?></p>
                            <div class="position-relative">
                                <img src="<?= base_url('assets/signature-img.jpeg') ?>" class="signatureimg">
                            </div>
                        </div>
                    </div>
                </div>

                <?php include(APPPATH . 'views/front-end/happycrop/exportfooter.php'); ?>


            </div>
            
        </div>

    </div>
    <?php if ($view == "view") { ?>
        <div class="row justify-content-center">
            <div class="col-lg-2 align-content-center">
                <button class="btn btn-primary my-3" onclick="generatePDF();">Download</button>
                <button class="btn btn-primary my-3 ml-2" onclick="printDiv();">Print</button>
            </div>
        </div>
    <?php } ?>
    </div>
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
        //     // const element = document.getElementById('generatePDf');
        //     // html2pdf().from(element).set({
        //     //     margin: [5, 5],
        //     //     filename: 'Invoice.pdf',
        //     //     html2canvas: {
        //     //         scale: 2
        //     //     },
        //     //     jsPDF: {
        //     //         unit: 'mm',
        //     //         format: 'legal',
        //     //         orientation: 'portrait'
        //     //     }
        //     // }).save().then(function() {
        //     //     history.back();

        //     // });
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
                margin: [2, 2],
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