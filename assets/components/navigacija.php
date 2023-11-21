<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
            <?php
                if(isset($_SESSION["user"])){
                    /* @var User $user */
                    $user = unserialize($_SESSION["user"]);
                    echo $user->getRole();
                }
            ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                  width="20" height="20" viewBox="0 0 1200 1200" enable-background="new 0 0 1200 1200"
                  xml:space="preserve">
                <path d="M0,0v240h1200V0H0z M0,480v240h1200V480H0z M0,960v240h1200V960H0z"></path>
            </svg>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <?php
                if(isset($_SESSION["user"])){
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="assets/php/logout.php">logout</a>
                    </li>
                    <?php
                }else{
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login">login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register">register</a>
                    </li>
                    <?php
                }
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>