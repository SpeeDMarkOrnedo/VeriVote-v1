<div class="sidebar"> 
<img src="../includes/logo.png" alt="Logo ">
    <h2>VeriVote Admin</h2>

    <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
    <a href="voters.php" class="<?= basename($_SERVER['PHP_SELF']) == 'voters.php' ? 'active' : '' ?>">Manage Students</a>
    <a href="positions.php" class="<?= basename($_SERVER['PHP_SELF']) == 'positions.php' ? 'active' : '' ?>">Manage Positions</a>
    <a href="candidates.php" class="<?= basename($_SERVER['PHP_SELF']) == 'candidates.php' ? 'active' : '' ?>">Manage Candidates</a>
    <a href="results.php" class="<?= basename($_SERVER['PHP_SELF']) == 'results.php' ? 'active' : '' ?>">View Results</a>

  
    <a href="logout.php" class="logout">Logout</a>
</div>