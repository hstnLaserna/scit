<?php 
    include_once('../backend/session.php');
    unset($_SESSION['compound_dosage_recent']);
    unset($_SESSION['max_basis_weekly']);
    unset($_SESSION['max_basis_monthly']);
    unset($_SESSION['osca_id']);
    unset($_SESSION['sr_full_name']);
    unset($_SESSION['unregistered_drugs']);
    unset($_SESSION['nfc_data']);
    unset($_SESSION['qr_data']);
    unset($_SESSION['transaction_from_pos']);
    unset($_SESSION['transaction']);