<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay Fees</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-5">

    <!-- Pay Now Button -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
        Pay Now
    </button>

    <!-- Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="paymentModalLabel">Payment Confirmation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <p>Please confirm the following before payment:</p>
            <form id="paymentForm">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check1">
                    <label class="form-check-label" for="check1"> I have verified my details.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check2">
                    <label class="form-check-label" for="check2"> My contact information is correct.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check3">
                    <label class="form-check-label" for="check3"> I agree to the refund policy.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check4">
                    <label class="form-check-label" for="check4"> I understand transaction charges apply.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="check5">
                    <label class="form-check-label" for="check5"> I consent to the terms and conditions.</label>
                </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="payFeesBtn">Pay Fees</button>
          </div>

        </div>
      </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#payFeesBtn').click(function(){
                let allChecked = true;
                $('#paymentForm input[type="checkbox"]').each(function(){
                    if(!$(this).is(':checked')){
                        allChecked = false;
                    }
                });

                if(!allChecked){
                    alert("Please check all the boxes before proceeding to payment.");
                } else {
                    // Redirect to payment gateway
                    window.location.href = "<?= base_url('payment/gateway') ?>";
                }
            });
        });
    </script>

</body>
</html>
