<header class="u-clearfix u-grey-5 u-header u-valign-middle u-header" id="sec-b906">
    <div class="u-clearfix u-group-elements u-group-elements-1">
        <a href="./Home.php" data-page-id="55768739" class="u-image u-logo u-image-1" data-image-width="987" data-image-height="851">
            <img src="images/logo.png" class="u-logo-image u-logo-image-1" style="width: 5.5rem; ">
        </a>
        <h3 class="u-headline u-text u-text-default u-text-1">
            <a href="./Home.php">BITEK<br>
            </a>
        </h3>
    </div>
    <nav class="u-menu u-menu-dropdown u-offcanvas u-menu-1">
        <div class="menu-collapse">
            <a class="u-button-style u-nav-link" href="#">
                <svg class="u-svg-link" viewBox="0 0 24 24">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-ed1e"></use>
                </svg>
                <svg class="u-svg-content" version="1.1" id="svg-ed1e" viewBox="0 0 16 16" x="0px" y="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <rect y="1" width="16" height="2"></rect>
                        <rect y="7" width="16" height="2"></rect>
                        <rect y="13" width="16" height="2"></rect>
                    </g>
                </svg>
            </a>
        </div>
        <div class="u-custom-menu u-nav-container">
            <ul class="u-nav u-unstyled">
                <?php
                // Check if user is logged in to change the menu
                require_once('db.inc.php');
                @session_start();
                $query = "SELECT * FROM activation";
                $stmt_activation = $conn->prepare($query);
                $stmt_activation->execute();
                $status_result = $stmt_activation->fetchAll(PDO::FETCH_ASSOC);

                if (isset($_SESSION['userid'])) {
                    // User is already logged in
                    echo '<li class="u-nav-item"><a class="u-button-style u-nav-link" href="Main.php">Dashboard</a>
                          </li>
                          <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Teams.php">Teams</a>
                          </li>';
                    if ($status_result[0]['activation_status'] == 0 || $status_result[0]['activation_status'] == -1) {
                        echo '<li class="u-nav-item"><a class="u-button-style u-nav-link" href="Results.php">Results</a></li>';
                    }
                    echo '<li class="u-nav-item"><a class="u-button-style u-nav-link" href="Logout.php">Logout</a></li>';
                } else {
                    echo '<li class="u-nav-item"><a class="u-button-style u-nav-link" href="home.php">Home</a></li>
                          <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Login.php">Login</a></li>';
                }
                ?>
            </ul>
        </div>
        <div class="u-custom-menu u-nav-container-collapse">
            <div class="u-black u-container-style u-inner-container-layout u-opacity u-opacity-95 u-sidenav">
                <div class="u-inner-container-layout u-sidenav-overflow">
                    <div class="u-menu-close"></div>
                    <ul class="u-align-center u-nav u-popupmenu-items u-unstyled u-nav-2">
                        <?php
                        // Check if user is logged in to change the menu
                        require_once('db.inc.php');
                        @session_start();
                        try {
                            $query = "SELECT activation_status FROM activation";
                            $stmt_activation = $conn->prepare($query);
                            $stmt_activation->execute();
                            $status_result = $stmt_activation->fetchAll(PDO::FETCH_ASSOC);
                        } catch (Exception $ex) {
                            echo 'Query Error!' . $ex->getMessage();
                        }
                        if (isset($_SESSION['userid'])) {
                            // User is already logged in
                            echo '<li class="u-nav-item"><a class="u-button-style u-nav-link" href="Main.php">Dashboard</a>
                                    </li>
                                    <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Teams.php">Teams</a>
                                    </li>';
                            if ($status_result[0]['activation_status'] == 0 || $status_result[0]['activation_status'] == -1) {
                                echo '<li class="u-nav-item"><a class="u-button-style u-nav-link" href="Results.php">Results</a></li>';
                            }
                            echo '<li class="u-nav-item"><a class="u-button-style u-nav-link" href="Logout.php">Logout</a></li>';
                        } else {
                            echo '<li class="u-nav-item"><a class="u-button-style u-nav-link" href="home.php">Home</a></li>
                          <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Login.php">Login</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="u-black u-menu-overlay u-opacity u-opacity-70"></div>
        </div>
    </nav>
</header>