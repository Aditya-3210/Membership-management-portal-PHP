<?php
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index2.php");
    exit();
}

function getTotalMembersCount()
{
    global $conn;
    $totalMembersQuery = "SELECT COUNT(*) AS totalMembers FROM members";
    $totalMembersResult = $conn->query($totalMembersQuery);
    if ($totalMembersResult->num_rows > 0) {
        $totalMembersRow = $totalMembersResult->fetch_assoc();
        return $totalMembersRow['totalMembers'];
    } else {
        return 0;
    }
}

function getTotalMembershipTypesCount()
{
    global $conn;
    $totalMembershipTypesQuery = "SELECT COUNT(*) AS totalMembershipTypes FROM membership_types";
    $totalMembershipTypesResult = $conn->query($totalMembershipTypesQuery);
    if ($totalMembershipTypesResult->num_rows > 0) {
        $totalMembershipTypesRow = $totalMembershipTypesResult->fetch_assoc();
        return $totalMembershipTypesRow['totalMembershipTypes'];
    } else {
        return 0;
    }
}

function getExpiringSoonCount()
{
    global $conn;
    $expiringSoonQuery = "SELECT COUNT(*) AS expiringSoon FROM members WHERE expiry_date BETWEEN CURDATE() AND CURDATE() + INTERVAL 7 DAY";
    $expiringSoonResult = $conn->query($expiringSoonQuery);
    if ($expiringSoonResult->num_rows > 0) {
        $expiringSoonRow = $expiringSoonResult->fetch_assoc();
        return $expiringSoonRow['expiringSoon'];
    } else {
        return 0;
    }
}

function getTotalRevenueWithCurrency()
{
    global $conn;
    $currencyQuery = "SELECT currency FROM settings LIMIT 1";
    $currencyResult = $conn->query($currencyQuery);
    $currencySymbol = ($currencyResult->num_rows > 0) ? $currencyResult->fetch_assoc()['currency'] : '$';
    $totalRevenueQuery = "SELECT SUM(total_amount) AS totalRevenue FROM renew";
    $totalRevenueResult = $conn->query($totalRevenueQuery);
    $totalRevenue = ($totalRevenueResult->num_rows > 0) ? $totalRevenueResult->fetch_assoc()['totalRevenue'] : 0;
    return $currencySymbol . number_format($totalRevenue, 2);
}

function getNewMembersCount() {
    global $conn;
    $twentyFourHoursAgo = time() - (24 * 60 * 60);
    $newMembersQuery = "SELECT COUNT(*) AS newMembersCount FROM members WHERE created_at >= FROM_UNIXTIME($twentyFourHoursAgo)";
    $newMembersResult = $conn->query($newMembersQuery);
    return ($newMembersResult) ? $newMembersResult->fetch_assoc()['newMembersCount'] : 0;
}

function displayNewMembersCount() {
    echo "<span class='info-box-number'>" . getNewMembersCount() . "</span>";
}

function getExpiredMembersCount() {
    global $conn;
    $expiredMembersQuery = "SELECT COUNT(*) AS expiredMembersCount FROM members WHERE (expiry_date IS NULL OR expiry_date < NOW())";
    $expiredMembersResult = $conn->query($expiredMembersQuery);
    return ($expiredMembersResult) ? $expiredMembersResult->fetch_assoc()['expiredMembersCount'] : 0;
}

function displayExpiredMembersCount() {
    echo "<span class='info-box-number'>" . getExpiredMembersCount() . "</span>";
}

$fetchLogoQuery = "SELECT logo FROM settings WHERE id = 1";
$fetchLogoResult = $conn->query($fetchLogoQuery);
$logoPath = ($fetchLogoResult->num_rows > 0) ? $fetchLogoResult->fetch_assoc()['logo'] : 'dist/img/default-logo.png';
?>

<?php include('includes/header.php'); // Uses CDN plugins now ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php include('includes/nav.php');?>
  <?php include('includes/sidebar.php');?>

  <div class="content-wrapper">
    <?php include('includes/pagetitle.php');?>

    <section class="content">
      <div class="container-fluid">

        <div class="row">
          <!-- Total Members -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Members</span>
                <span class="info-box-number"><?php echo getTotalMembersCount(); ?></span>
              </div>
            </div>
          </div>

          <!-- Membership Types -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Membership Types</span>
                <span class="info-box-number"><?php echo getTotalMembershipTypesCount(); ?></span>
              </div>
            </div>
          </div>

          <!-- Expiring Soon -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hourglass-half"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Expiring Soon</span>
                <span class="info-box-number"><?php echo getExpiringSoonCount(); ?></span>
              </div>
            </div>
          </div>

          <!-- Total Revenue -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-coins"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Revenue</span>
                <span class="info-box-number"><?php echo getTotalRevenueWithCurrency(); ?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- New & Expired Members -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">New Members</span>
                <?php displayNewMembersCount(); ?>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-maroon elevation-1"><i class="fas fa-times"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Expired Membership</span>
                <?php displayExpiredMembersCount(); ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Recently Joined Members -->
        <div class="row">
          <div class="col-md-12">
            <?php
            $recentMembersQuery = "SELECT * FROM members ORDER BY created_at DESC LIMIT 4";
            $recentMembersResult = $conn->query($recentMembersQuery);
            ?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recently Joined Members</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
              </div>
              <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  <?php while ($row = $recentMembersResult->fetch_assoc()): ?>
                    <li class="item">
                      <div class="product-img">
                        <?php
                          $photoPath = !empty($row['photo']) ? 'uploads/member_photos/' . $row['photo'] : 'uploads/member_photos/default.jpg';
                        ?>
                        <img src="<?= $photoPath ?>" alt="Member Photo" class="img-size-50">
                      </div>
                      <div class="product-info">
                        <a href="javascript:void(0)" class="product-title"><?= $row['fullname'] ?></a>
                        <span class="product-description">
                          <span class="badge badge-dark float-right"><?= getMembershipTypeName($row['membership_type']) ?></span>
                          Membership Number: <?= $row['membership_number'] ?>
                        </span>
                      </div>
                    </li>
                  <?php endwhile; ?>
                </ul>
              </div>
              <div class="card-footer text-center">
                <a href="manage_members.php" class="uppercase">View All Members</a>
              </div>
            </div>

            <?php
            function getMembershipTypeName($membershipTypeId)
            {
                global $conn;
                $query = "SELECT type FROM membership_types WHERE id = $membershipTypeId";
                $result = $conn->query($query);
                return ($result->num_rows > 0) ? $result->fetch_assoc()['type'] : 'Unknown';
            }
            ?>
          </div>
        </div>
      </div>
    </section>
  </div>

  <aside class="control-sidebar control-sidebar-dark"></aside>

  <footer class="main-footer">
    <strong>&copy; <?= date('Y') ?> Memberly</strong> - All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
