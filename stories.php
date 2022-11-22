<?php
session_start();
if (!isset($_SESSION['cid'])) {
    header("location: welcome/");
}
?>
<?php include_once "chatBox/header.php"; ?>

<body>
    <div class="wrapper">
        <section class="users">
            <header>
                <div class="content">

                </div>
                <a href="#" class="logout">Logout</a>
            </header>
            <div class="search">
                <span class="text">Select an user to start chat</span>
                <input type="text" placeholder="Enter name to search...">
                <button><i class="fas fa-search"></i></button>
            </div>
            <div class="users-list">

            </div>
        </section>
    </div>

    <script src="js/stories/stories.js"></script>

</body>

</html>