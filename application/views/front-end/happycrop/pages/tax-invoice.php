<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($dchallan != "" && $dchallan == "1" ? "Delivery Challan" : "Tax Invoice"); ?></title>
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
                <h2 class="text-center font-weight-bold"><?php echo ($dchallan != "" && $dchallan == "1" ? "Delivery Challan" : "Tax Invoice"); ?></h2>
            </div>
            <div class="col-lg-8 pb-2">
                <div class="bg-gray-light">
                    <table class="table border-none">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <p class="font-weight-bold p-2 m-0">Manufacturer/ Service Provider Name</p>

                                </td>
                            </tr>

                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Name : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"> <?php echo $manufacture['company_name']; ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Address : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"> <?php echo $manufacture['plot_no'] . ' ' . $manufacture['street_locality'] . ' ' . $manufacture['landmark'] . ' ' . $manufacture['city'] . ' ' . $manufacture['state'] . ' ' . $manufacture['pin']; ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Contact : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['mobile'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">Email : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['email'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">GSTIN : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['gst_no'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold w-25">State : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['state'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4 pb-2">
                <?php if (isset($order[0]["logo"])) { ?>
                    <img src="<?php echo base_url() . $order[0]["logo"]; ?>" class="h-100 w-100">
                <?php } ?>
            </div>
            <div class="col-lg-12 py-2">
                <div class="bg-gray-light h-100">
                    <table class="table  border-none">
                        <p class="font-weight-bold p-2 m-0">Place to supply : <?php echo ($order[0]['state'] ? $order[0]['state'] : "Maharashtra") ?></p>
                        <tbody>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-1 w-25 font-weight-bold">Invoice No : -</td>
                                <td class="border-top-0 py-1 w-75 pl-2"><?php echo $order[0]["id"]; ?></td>
                            </tr>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-1 w-25 font-weight-bold">Date : -</td>
                                <td class="border-top-0 py-1 w-75 pl-2"><?= (!empty($order_item_stages) && $order_item_stages[0]["status"] === "send_invoice" ? date('d M Y ', strtotime($order_item_stages[0]["created_date"])) : date('d M Y', strtotime(date('d-m-y')))); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-gray-light h-100">

                    <table class="table border-none">

                        <p class="font-weight-bold p-2 m-0">Bill To : <?php echo $order[0]['retailer_company_name']; ?></p>

                        <tbody>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">Address : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $order[0]["billing_address"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">Contact : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $order[0]["mobile"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">Email : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $order[0]["email"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">GSTIN : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $order[0]['retailer_gst_no'] ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-gray-light h-100">
                    <table class="table border-none">
                        <p class="font-weight-bold p-2 m-0">Ship To:</p>
                        <p class="font-weight-bold p-2 m-0"><?php echo $order[0]['retailer_company_name']; ?></p>

                        <tbody>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">Address : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $order[0]["billing_address"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">Contact : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $order[0]["mobile"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">Email : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $order[0]["email"] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 font-weight-bold">State : -</td>
                                <td class="border-top-0 py-1 pl-2"><?= $order[0]['retailer_gst_no'] ?></td>
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
                            foreach ($order[0]['order_items'] as $key => $item) {
                                $total_amt += $item["price"] * (($item['quantity']) ? $item['quantity'] : $item['quantity']);
                                $total_qty += (($item['quantity']) ? $item['quantity'] : $item['quantity']);
                                $total_gst += ($item["price"] * $item['tax_percentage'] / 100);

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
                <div class="pagebreak"></div>
                <div class="row justify-content-between">
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
                        <?php if($dchallan == ""){ ?>
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
                                    <tr class="p-2 pt-2 ">
                                        <td class="border-top-0 w-50 font-weight-bold">Total : -</td>
                                        <td class="border-top-0 w-50 pl-2"><?php echo $settings['currency'] . '' . number_format(($total_amt), 2); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="pb-2 text-right pr-5"><?= $this->config->item('happycrop_name'); ?></p>
                        <div class="position-relative">
                            <img src="<?= base_url('assets/signature-img.jpeg') ?>" class="signatureimg">
                        </div>
                        <p class="py-2 text-right pr-5">Authorized Signatory</p>
                    </div>
                </div>

                <?php include(APPPATH . 'views/front-end/happycrop/exportfooter.php'); ?>


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