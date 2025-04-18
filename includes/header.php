<?php
$fetchSystemNameQuery = "SELECT system_name FROM settings WHERE id = 1";
$fetchSystemNameResult = $conn->query($fetchSystemNameQuery);

$systemName = ($fetchSystemNameResult->num_rows > 0) ? $fetchSystemNameResult->fetch_assoc()['system_name'] : 'Membership System';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $systemName; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome (CDN version) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Ionicons (CDN version) -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- overlayScrollbars (CDN version) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OverlayScrollbars/1.13.1/css/OverlayScrollbars.min.css">
  <!-- DataTables (CDN version) -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
  <!-- Theme style (CDN version) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
