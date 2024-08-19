<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edu system</title>
    <link rel="stylesheet" href="payment.css" />
</head>

<body>
    <div class="card-container">
        <div class="middle">
            <h3>pay something  </h3>
            <div class="extra-detail">
                <div class="detail">
                    <p id="dark">Amount to Pay</p>
                </div>
                <div class="detail-price">
                    <p id="dark">Ksh. 1</p>
                </div>
            </div>
            <div class="border"></div>
        </div>

        <div class="form-box">
            <div class="card-box">
                <div class="main-content">
                    <div class="pay-instruction">
                        <h3>Activation Instruction</h3>
                    </div>
                   
                    </div>

                    <?php if (isset($_GET['error']) && $_GET['error'] != '') { ?>
                        <p class="error"><?php echo $_GET['error']; ?></p>

                    <?php unset($_GET['error']);
                    } ?>

                </div>
                <div class="name-area">
                    <form class="" action="stk_initiate.php" method="POST">
                        <!--<form class="ajaxx" action="Ajax/getters.php" method="POST">-->
                        <div class="name">
                            <h3>Phone: Start with 254.......</h3>
                            <input type="text" name="phone" placeholder="254700000000" class="input-no" required />
                        </div>
                        <div class="payment-buttons">
                            <input type="submit" name="submit" value="Pay" class="pay-btn">
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <!--<script src='functions.js'></script>-->
    <script>

    </script>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</html>

