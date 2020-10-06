<?php
    include('../backend/session.php');
    
    ?>
    <script>
        alert("called transaction.php");
    </script>
    <?php
    if(isset($_SESSION['osca_id'])) {
        ?>
        <script>
            alert("OSCA_ID is set: <?php echo $_SESSION['osca_id']?>" );
        </script>
        <?php
        include_once('../backend/php_functions.php');
        include_once('../backend/terminal_scripts.php');
<<<<<<< HEAD
=======
            ?>
            <script>
                console.log(<?php echo json_decode($_SESSION['transaction_from_pos'], true)?>);
                alert("Transaction has been read (see console");
                //alert("Serial has been read: < ?php var_dump($_SESSION['transaction_from_pos']);?>" );
            </script>
            <?php
>>>>>>> a9f5761bc5f1e8c543307371c7ece297b3e06aca
        $formatter = new NumberFormatter("fil-PH", \NumberFormatter::CURRENCY);
        $total_discount = 0;
        $total_amount_to_pay = 0;
        $trans_date = "";
        $clerk = "";
        $flagged_items = [];
        $business_type = $_SESSION['business_type'];
        ?>
        <div class="trans-title">
            TRANSACTION
        </div>
        <div class="trans-title user">
            <?php echo $_SESSION['sr_full_name']; ?>
        </div>
        <div class="transaction scrollbar-black" id="trans123">
            <?php
<<<<<<< HEAD

            
            // this will control the creation of unknown drugs.
            if(isset($unregistered_drugs['drugs']) && (count($unregistered_drugs['drugs']) > 0)) {
                ?>
                <script>
                    alert("Called create_drugs.php");
                </script>
                <?php
                include("../backend/create_drugs.php");
            }
            
            $counter = 0;

            include('../backend/new_transaction.php');
            if(isset($_SESSION['transaction_from_pos']) || !empty($_SESSION['transaction_from_pos'])){
                $_SESSION['transaction'] = json_decode(json_encode($transaction),true);
=======
            
            $counter = 0;
            include('../backend/new_transaction.php');
            if(isset($_SESSION['transaction_from_pos '])){
                $_SESSION['transaction'] = $transaction;
>>>>>>> a9f5761bc5f1e8c543307371c7ece297b3e06aca
                
                ?>
                    <script> console.log(<?php echo json_encode($_SESSION); ?>); </script>
                <?php
                
                // get total of ingredient dosage in this transaction
                if($business_type == "pharmacy"){

                    if(count($unregistered_drugs['drugs']) > 0){
                        ?>
                        <script>
                        alert("displaying invalid_drugs in console");
                        console.log(<?php echo json_encode($unregistered_drugs);?>);
                        alert("DISPLAYED invalid_drugs in console");
                        </script>
                        <?php
                        serial_invalid_drug();

                        $_SESSION['unregistered_drugs'] = true;
                        
                        // PROGRESS REMAINING:
                        // send $unregistered_drugs_json to POS using serial
                        // the $unregistered_drugs_json will be converted to json and 
                        // use the containing details to be the generic name, brand, dose&unit
                        // the only editable text in POS will be the is_otc, max_wkly, & max_monthly
                        // if the SCIT receives the $_POST['unregisted_drugs'],
                        // load the transaction.php  to the #body

                        // PHARMACIST OVERRIDE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        // PHARMACIST OVERRIDE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        // PHARMACIST OVERRIDE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        // PHARMACIST OVERRIDE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    } else {
                        unset($_SESSION['unregistered_drugs']);
                    }

                    $compound_dosage_transaction = [];
                    $max_basis_weekly = [];
                    $max_basis_monthly = [];
                    foreach($transaction['items'] as $row => $item){
                        if (!isset($item['desc'])) {
                            $brand = ucwords($item['brand']);
                            $dose = $item['dose'];
                            $quantity = $item['quantity'];
                            $total_dosage = $dose * $quantity;

                            $max_monthly = $item['max_monthly'];
                            $max_weekly = $item['max_weekly'];

                            $generic_name_string = $item['generic_name'];
                            
                            if(isset($max_basis_weekly[$generic_name_string])) {
                                if($max_basis_weekly[$generic_name_string] > $max_weekly){
                                    $max_basis_weekly[$generic_name_string] = $max_weekly;
                                }
                            } else {
                                $max_basis_weekly[$generic_name_string] = $max_weekly;
                            }
                            
                            if(isset($max_basis_monthly[$generic_name_string])) {
                                if($max_basis_monthly[$generic_name_string] > $max_monthly){
                                    $max_basis_monthly[$generic_name_string] = $max_monthly;
                                }
                            } else {
                                $max_basis_monthly[$generic_name_string] = $max_monthly;
                            }
                            
                            if (array_key_exists($generic_name_string, $compound_dosage_transaction)){
                                $compound_dosage_transaction[$generic_name_string] += $total_dosage;
                            } else {
                                $compound_dosage_transaction[$generic_name_string] = $total_dosage;
                            }
                        }
                    }
                }

                // Populate displayed Transactions list from $transactions array
                foreach($transaction['items'] as $row => $item){
                    $counter++;
                    $vat_exempt_price = $formatter->format($item['vat_exempt_price']);
                    $discount_price = $formatter->format($item['discount_price']);
                    $payable_price = $formatter->format($item['payable_price']);
                    
                    $total_discount += (double)$item['discount_price'];
                    $total_amount_to_pay += (double)$item['payable_price'];
                    
                    if($business_type == "pharmacy"){
                        if (!isset($item['desc'])) {
                            $brand = ucwords($item['brand']);
                            $dose = $item['dose'];
                            $unit = $item['unit'];
                            $quantity = $item['quantity'];
                            $total_dosage = $dose * $quantity;

                            $is_otc = ($item['is_otc'] == '1') ? true: false;
                            $max_monthly = $item['max_monthly'];
                            $max_weekly = $item['max_weekly'];
                            $generic_name_string = $item['generic_name'];

                            $compound_total = 0;


                            // check if item[compound] exists in session[compounds_recent]
                            if (array_key_exists($generic_name_string, $_SESSION['compound_dosage_recent'])){
                                $compound_total = $compound_dosage_transaction[$generic_name_string] + $_SESSION['compound_dosage_recent'][$generic_name_string];
                                $max_basis = ($is_otc)? $_SESSION['max_basis_weekly'][$generic_name_string]: $_SESSION['max_basis_monthly'][$generic_name_string];
                            } else {
                                $compound_total = $compound_dosage_transaction[$generic_name_string];
                                $max_basis = ($is_otc)? $max_basis_weekly[$generic_name_string]: $max_basis_monthly[$generic_name_string];
                            }
                            
                            if($compound_total > $max_basis){
                                $maxed = "flagged";
                                ?>
                                <script>console.log("<?php echo "flagged: ($compound_total > $max_basis) == true; Item:" . $item['brand']?>")</script>
                                <?php
                                $flagged_items[] = $item;
                            } else {
                                $maxed = "";
                            }
                        
                            // end of conditions
                            ?>
                            <div class="row _transaction-record collapse-header<?php echo " $maxed";?>" data-toggle="collapse" data-target="#collapse_<?php echo $counter?>" aria-expanded="true" aria-controls="collapse_<?php echo $counter?>">
                                <!--div class="col col-12">
                                    <?php /*echo "<b>(Dosage on this purchase: $total_dosage)</b>"; ?>
                                    <?php echo "<br><b>($generic_name_string: ".$_SESSION['compound_dosage_recent'][$generic_name_string].")</b>"; ?>
                                    <?php echo "<br><b>(Compound Total: ".$compound_total.")</b>"; ?>
                                    <?php echo "<br><b>(Max: ".$max_basis.")</b>"; */?>
                                </div-->
                                <div class="col col-12">
                                    <b><?php echo "$brand $dose"."$unit @ $quantity pcs"?></b>
                                </div>
                                <div class="col col-12">
                                    [ <?php echo ucwords($generic_name_string)?> ]<br>
                                </div>
                            <?php 
                        } else {
                            $desc = ucwords($item['desc']);
                            ?>
                            <div class="row _transaction-record collapse-header" data-toggle="collapse" data-target="#collapse_<?php echo $counter?>" aria-expanded="true" aria-controls="collapse_<?php echo $counter?>">
                                <div class="col col-12">
                                    <b><?php echo $desc?></b>
                                </div>
                            <?php
                            
                        }?>

                        
                                <div id="collapse_<?php echo $counter?>" class="col col-12 collapse" aria-labelledby="heading<?php echo $counter?>">
                                    <div class="row pl-3">
                                        <div class="col col-6">
                                            VAT Exempt Price
                                        </div>
                                        <div class="col col-6 _transaction-record-right">
                                            <?php echo $vat_exempt_price?>
                                        </div>
                                        <div class="col col-6">
                                            Discounted Price
                                        </div>
                                        <div class="col col-6 _transaction-record-right">
                                            (<?php echo $discount_price ?>)
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-12">
                                    <div class="row pl-3">
                                        <div class="col col-6">
                                            Amount to pay
                                        </div>
                                        <div class="col col-6 _transaction-record-right">
                                            <?php echo $payable_price ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                    if($business_type == "food" || $business_type == "transportation" ){
                        $desc = $item['desc'];
                        ?>
                        <div class="row _transaction-record collapse-header" data-toggle="collapse" data-target="#collapse_<?php echo $counter?>" aria-expanded="true" aria-controls="collapse_<?php echo $counter?>">
                                <div class="col col-12">
                                <?php echo $desc ?>
                            </div>
                            <div id="collapse_<?php echo $counter?>" class="col col-12 collapse" aria-labelledby="heading<?php echo $counter?>">
                                <div class="row">
                                    <div class="col col-6">
                                        VAT Exempt Price
                                    </div>
                                    <div class="col col-6 _transaction-record-right">
                                        <?php echo $vat_exempt_price?>
                                    </div>
                                    <div class="col col-6">
                                        Discounted Price
                                    </div>
                                    <div class="col col-6 _transaction-record-right">
                                        (<?php echo $discount_price ?>)
                                    </div>
                                </div>
                            </div>
                            <div class="col col-12">
                                <div class="row">
                                    <div class="col col-6">
                                        Amount to pay
                                    </div>
                                    <div class="col col-6 _transaction-record-right">
                                        <?php echo $payable_price ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            } else {
                echo "<div class='eol'>Transaction has not been set</div>";
                $flagged = true;
            }
            var_dump($transaction_from_pos);
            ?>
        </div>
        <div class="transaction-summary">
            <div class="col col-12">
                <div class="row">
                    <div class="col col-md-8">
                    Total Discount <small>(After VAT Exempt)</small>
                    </div>
                    <div class="col col-md-4 _transaction-record-right">
                        <?php echo $formatter->format($total_discount);?>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-8">
                    Amount to Pay
                    </div>
                    <div class="col col-md-4 _transaction-record-right">
                        <b><?php echo $formatter->format($total_amount_to_pay);?></b>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-12">
                        <?php echo $trans_date ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-12">
                        Cashier: <?php echo $clerk ?>
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD
        <?php
=======
        <?php 
            // this will control the creation of unknown drugs.
            if(isset($_SESSION['unregistered_drugs']) && $_SESSION['unregistered_drugs']) {
                ?>
                <script>
                    alert("The invalid drugs has been sent to the POS, the ACCEPT button will be inactive.\r\n Then the SCIT will display trans details while waiting for POS\r\n to send the [transaction details] + [new drug details]. \r\n Only then the ACCEPT TRANSACTION button will be active.");
                </script>
                <?php
                include("../backend/create_drugs.php");
            }
>>>>>>> a9f5761bc5f1e8c543307371c7ece297b3e06aca
            if(count($flagged_items) > 0) {
                $flagged = true;
                $counter = 0;
                ?>
                <div id="msg_modal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body">
                            <p>Some items are invalid:</p>
                            <ul class="invalid-list scrollbar-black">
                                <?php
                                    foreach($flagged_items as $row => $item){
                                        $counter++;
                                        $brand = ucwords($item['brand']);
                                        $generic_name = ucwords($item['generic_name']);
                                        $dose = $item['dose'];
                                        $unit = $item['unit'];
                                        $quantity = $item['quantity'];
                                        echo "<li>Item #$counter: $brand [ $generic_name ] $dose"."$unit @ $quantity pcs</li>";
                                    }
                                ?>
                            </ul>
                            <button class="btn btn-lg btn-dark btn-block" id="new_trans_2">OK</button>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div> 
                </div>
                <?php
                
                if(!isset($_SESSION['unregistered_drugs']) || !$_SESSION['unregistered_drugs']) {
                    serial_invalid_dosage();
                }
            } else {
                $flagged = false;
                
            } ?>
            
        <div class="foot">
            <button type="button" class="btn btn-block btn-light btn-lg" id="accept" <?php echo ($flagged)? "disabled": "";?>>Accept</button>
                <button type="button" class="btn btn-block btn-exit btn-lg" id="exit">Exit</button>
        </div>
        <script>
<<<<<<< HEAD
=======
            var modal = document.getElementById("msg_modal");
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

>>>>>>> a9f5761bc5f1e8c543307371c7ece297b3e06aca
            function CreateTransaction(message) {
                $('<div></div>').appendTo('body')
                    .html('<div><h6>' + message + '?</h6></div>')
                    .dialog({
                        modal: true,
                        title: 'Accept Transaction',
                        zIndex: 10000,
                        autoOpen: true,
                        width: 'auto',
                        resizable: false,
                        buttons: {
                            Accept: function() {
                                var trans = JSON.stringify(<?php echo json_encode($transaction); ?>);
                                $.post("../backend/create_transaction.php", { accepted: true, transaction: trans}, function(d){
                                    if(d="true") {
                                        alert("Transaction success!");
                                        $('#body').load("../frontend/home.php #home");
                                    } else {
                                        alert("Transaction error!!");
                                    }
                                });
                                $(this).dialog("close");
                            },
                            Cancel: function() {
                                $(this).remove();
                            }
                        },
                        close: function(event, ui) {
                            $(this).remove();
                        }
                    });
            };

            $(document).ready(function(){
                $("#accept").click(function(){
                    CreateTransaction('Are transaction details correct?');
<<<<<<< HEAD
                });
                
                $("body").on('click', "#new_trans_2", function () {
                    modal.style.display = "none";
                    $('#response').load("../backend/read_serial.php", function(read_serial_response){
                        //alert(read_serial_response);
                        if(read_serial_response.trim() != "false"){
                            $('#body').load("../frontend/transaction.php", function(d){
                                if(d.trim() == "false"){
                                    MsgBox_Invalid("Data received is invalid!", "Invalid Serial Read");
                                }
                            });
                        } else {
                            MsgBox_Invalid("No transaction received!", "Invalid Serial Read");
                        }
                    });
=======
>>>>>>> a9f5761bc5f1e8c543307371c7ece297b3e06aca
                });

            });
            console.log("Session vars after encode:");
            console.log(<?php echo json_encode($_SESSION); ?>);
            console.log("transaction:");
            console.log(<?php echo json_encode($transaction); ?>);
        </script>
        <?php
    } else {
        echo "false";
    }
?>
