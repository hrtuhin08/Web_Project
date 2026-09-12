<?php


declare(strict_types=1);
require_once __DIR__ . '/functions.php';

$pageTitle = isset($page_title) ? e($page_title) . ' | Hostel Seat & Mess System' : 'Hostel Seat & Mess Management System';
$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="<?= $csrfToken ?>">
  <title><?= $pageTitle ?></title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Mono:ital,wght@0,300;0,400;0,500;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Core Design System CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forms.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/tables.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/responsive.css">

  <!-- Expose BASE_URL to JavaScript -->
  <script>window.BASE_URL = '<?= BASE_URL ?>';</script>

  <!-- Chart.js & SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
