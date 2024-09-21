<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <?php $this->load->view('admin/include-head.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>

<body>
    <div class="container mt-5" >
        <div class="row justify-content-center border border-black p-2" id="generatePDf">
            <div class="col-lg-12 py-4">
                <h2 class="text-center font-weight-bold">Payment Receipt</h2>
            </div>
            <div class="col-lg-12 pb-2">
                <div class="bg-gray-light">
                    <table class="table border-none">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <p class="font-weight-bold p-2 m-0"><?php echo ($seller) ? "Retail Shop Name" : "Happycrop Name" ?></p>

                                </td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold">Address : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"> <?= $manufacture['plot_no'] . ' ' . $manufacture['street_locality'] . ' ' . $manufacture['landmark'] . ' ' . $manufacture['city'] . ' ' . $manufacture['state'] . ' ' . $manufacture['pin'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold">Contact : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['mobile'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold">Email : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['email'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold">GSTIN : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['gst_no'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-1 text-left font-weight-bold">State : -</td>
                                <td class="border-top-0 py-1 text-left pl-2"><?= $manufacture['state'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bg-gray-light">
                    <table class="table border-none">
                        <p class="font-weight-bold p-2 m-0"><?php echo ($seller) ? "Happycrop Name" : "Manufacturer/ Service Provider Name" ?></p>

                        <tbody>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Address : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]['billing_address'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Contact : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]['mobile'] ?></td>
                            </tr>
                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Email : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]['email'] ?></td>
                            </tr>
                            <!-- <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">GSTIN : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]['gstin'] ?></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-gray-light">
                    <table class="table h-100 border-none">
                        <tbody>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Receipt Number : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?php echo $order[0]['order_items'][0]["receipt_no"]; ?></td>
                            </tr>
                            <tr class="p-2 ">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">Date : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= date('d M Y H:i', strtotime($order[0]['date_added'])); ?></td>
                            </tr>

                            <tr class="p-2">
                                <td class="border-top-0 py-2 w-50 font-weight-bold">State : -</td>
                                <td class="border-top-0 py-2 w-50 pl-2"><?= $order[0]['state'] ?></td>
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
                    <?php include(APPPATH . 'views/front-end/happycrop/authsignature.php'); ?>


                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('admin/include-script.php'); ?>
    <script>
        $(document).ready(function() {
            generatePDF();
        });

        function generatePDF() {
            const element = document.getElementById('generatePDf');
            html2pdf().from(element).set({
                margin: [5, 5],
                filename: 'Invoice.pdf',
                html2canvas: {
                    scale: 4
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'legal',
                    orientation: 'portrait'
                }
            }).save().then(function() {
                history.back();
                
            });

        }
    </script>
</body>

</html>