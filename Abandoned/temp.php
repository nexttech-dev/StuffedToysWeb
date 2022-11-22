<html class="hydrated">

<head>
    <style data-styles="">
        ion-icon {
            visibility: hidden
        }

        .hydrated {
            visibility: inherit
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

</head>

<body data-new-gr-c-s-check-loaded="14.1068.0" data-gr-ext-installed="" _c_t_j1="1" cz-shortcut-listen="true">
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="logo-apple" role="img" class="md hydrated" aria-label="logo apple"></ion-icon>
                        </span>
                        <span class="title">Stuffed Toys</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="loadUsers()">
                        <span class="icon">
                            <ion-icon name="home-outline" role="img" class="md hydrated" aria-label="home outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="loadUsers()">
                        <span class="icon">
                            <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
                        </span>
                        <span class="title">Create New Story</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="loadStories()">
                        <span class="icon">
                            <ion-icon name="newspaper-outline" role="img" class="md hydrated" aria-label="newspaper outline"></ion-icon>
                        </span>
                        <span class="title">Stories</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="popUp()">
                        <span class="icon">
                            <ion-icon name="chatbubble-outline" role="img" class="md hydrated" aria-label="chatbubble outline"></ion-icon>
                        </span>
                        <span class="title">Message</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="help-outline" role="img" class="md hydrated" aria-label="help outline"></ion-icon>
                        </span>
                        <span class="title">Help</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="settings-outline" role="img" class="md hydrated" aria-label="settings outline"></ion-icon>
                        </span>
                        <span class="title">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="lock-closed-outline" role="img" class="md hydrated" aria-label="lock closed outline"></ion-icon>
                        </span>
                        <span class="title">Password</span>
                    </a>
                </li>
                <li>
                    <a href="php/signOut/signOut.php?" onclick="signOut()">
                        <span class="icon">
                            <ion-icon name="log-out-outline" role="img" class="md hydrated" aria-label="log out outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- main -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline" role="img" class="md hydrated" aria-label="menu outline"></ion-icon>
                </div>
                <!-- search -->
                <div class="search">
                    <label>
                        <input type="text" placeholder="Search here">
                        <ion-icon name="search-outline" role="img" class="md hydrated" aria-label="search outline"></ion-icon>
                    </label>
                </div>
                <!-- notfication -->
                <div class="notifi">
                    <div class="icon" onclick="toggleNotifi()">
                        <img src="images/bell.png" alt=""> <span>0</span>
                    </div>
                    <div class="notifi-box" id="box">
                        <h2>Notifications <span>0</span></h2>
                        <div class="notifi-item">
                            <div class="text">
                                <h4>Muhammad Zia Ur Rehman</h4>
                                <p>Request has been approved to start story on Story 1</p>

                            </div>
                        </div>
                        <div class="notifi-item">
                            <div class="text">
                                <h4>Muhammad Zia Ur Rehman</h4>
                                <p>Request has been approved to start story on Story 2</p>

                            </div>
                        </div>
                        <div class="notifi-item">
                            <div class="text">
                                <h4>Muhammad Zia Ur Rehman</h4>
                                <p>Request has been approved to start story on Story 3</p>

                            </div>
                        </div>
                        <div class="notifi-item">
                            <div class="text">
                                <h4>Muhammad Zia Ur Rehman</h4>
                                <p>Request has been approved to start story on Story 1</p>

                            </div>
                        </div>
                        <div class="notifi-item">
                            <div class="text">
                                <h4>Muhammad Zia Ur Rehman</h4>
                                <p>Request has been approved to start story on Story 4</p>

                            </div>
                        </div>
                        <div class="notifi-item">
                            <div class="text">
                                <h4>Muhammad Zia Ur Rehman</h4>
                                <p>Request has been approved to start story on Story 5</p>

                            </div>
                        </div>
                        <div class="notifi-item">
                            <div class="text">
                                <h4>Muhammad Zia Ur Rehman</h4>
                                <p>Request has been approved to start story on Story 6</p>

                            </div>
                        </div>
                        <div class="notifi-item">
                            <div class="text">
                                <h4>Muhammad Zia Ur Rehman</h4>
                                <p>Request has been approved to start story on Story 8</p>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- userImg -->
                <div class="user">
                    <img src="images/user.jpg">
                </div>
            </div>

            <!-- cards -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="totalStories numbers">5</div>
                        <div class="cardName">Total Stories</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="eye outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="totalCompletedStories numbers">0</div>
                        <div class="cardName">Completed Stories</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cart-outline" role="img" class="md hydrated" aria-label="cart outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="totalWords numbers">0</div>
                        <div class="cardName">Words</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="chatbubbles-outline" role="img" class="md hydrated" aria-label="chatbubbles outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="totalEarnings numbers">$0</div>
                        <div class="cardName">Earning</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cash-outline" role="img" class="md hydrated" aria-label="cash outline"></ion-icon>
                    </div>
                </div>
            </div>


            <div class="details allUsers">
                <!-- order details list -->
                <div class="recentOrders">

                    <!-- search -->
                    <div class="search">
                        <label>
                            <input type="text" placeholder="Search for users" class="searchInput">
                            <ion-icon name="search-outline" role="img" class="md hydrated" aria-label="search outline"></ion-icon>
                        </label>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Rank</td>
                                <td>Status</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody class="storyTable">
                            <tr>
                                <td>Muhammad Zia Ur Rehman</td>
                                <td>muhammad</td>
                                <td><span class="status delivered">Availible</span></td>
                                <td><button class="createStoryButton" id="1" onclick="createStoryButton(this)">Create Story</button></td>
                            </tr>
                            <tr class="storyInfo" id="storyInfo1" style="display: contents;">
                                <td><input type="text" placeholder="Story Name" id="storyName1"></td>
                                <td><input type="numbers" placeholder="Total Number of Characters" id="totalChars1"></td>
                                <td><button class="createStory" onclick="sendReq(336719793,1)">Send Request</button></td>
                                <td></td>
                            </tr>
                        </tbody>

                    </table>

                </div>
            </div>


            <div class="details">
                <div class="storiesList" style="display: none;">
                    <!-- order details list -->
                    <div class="recentOrders">

                        <!-- search -->
                        <div class="search">
                            <label>
                                <input type="text" placeholder="Search for users" class="searchInput">
                                <ion-icon name="search-outline" role="img" class="md hydrated" aria-label="search outline"></ion-icon>
                            </label>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Status</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody class="storiesTable">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="custom-model-main sendBtnSelPrompt">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap">
                    <div class="devicer"></div>
                    <div class="popUpMessage">
                        <p>Yay! Now you can start creating story</p>
                    </div>
                    <button class="popUpConfirm" onclick="loadStories()">Start Story!</button>
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script>
        // MenuToggle
        let toggle = document.querySelector('.toggle');
        let navigation = document.querySelector('.navigation');
        let main = document.querySelector('.main');

        toggle.onclick = function() {
            navigation.classList.toggle('active');
            main.classList.toggle('active');
        }

        // add hovered class in selected list item
        let list = document.querySelectorAll('.navigation li');

        function activeLink() {
            list.forEach((item) =>
                item.classList.remove('hovered'));
            this.classList.add('hovered');
        }
        list.forEach((item) =>
            item.addEventListener('mouseover', activeLink));

        function createStoryButton(data) {
            console.log("Button Clicked!")
            selfId = 'storyInfo' + $(data).attr('id');
            const storyInfo = document.getElementById(selfId)
            if (storyInfo.style.display == "none") {
                storyInfo.style.display = "contents";

            } else {
                storyInfo.style.display = "none"
            }
        }

        function sendReq(userId, count) {
            let storyName = document.getElementById('storyName' + count).value;
            let totalChars = document.getElementById('totalChars' + count).value;

            if (storyName.length >> 0 && totalChars.length >> 0) {
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "php/sendReq.php", true);
                xhr.onload = () => {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            let data = xhr.response;
                            console.log("data comming", data);
                        }
                    }
                };
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send("userId=" + userId + "&storyName=" + storyName + "&totalChars=" + totalChars);
            } else {
                console.log("Please enter story Name and total chars");
            }

        }

        //propmt
        // function popUp() {
        // 	$(".sendBtnSelPrompt").addClass('model-open');
        // };
        $(".close-btn, .bg-overlay").click(function() {
            $(".custom-model-main").removeClass('model-open');
        });
    </script>

    <script src="js/signOut/signOut.js"></script>
    <script src="js/search/search.js"></script>
    <script src="js/notification//loadNotifications.js"></script>
    <script src="js/notification/notification.js"></script>
    <script src="js/controlPanel/stories.js"></script>
    <script src="js/controlPanel/dashboardStatistics.js"></script>




</body>
<grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration>

</html>