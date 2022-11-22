<?php
session_start();
if (!isset($_SESSION['cid'])) {
	header("location: welcome/");
}
include_once("php/connections/main.php");
?>
<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

</head>

<body>
	<div class="container">
		<div class="navigation">
			<ul>
				<li>
					<a href="#">
						<span class="icon">
							<ion-icon name="logo-apple"></ion-icon>
						</span>
						<span class="title">Stuffed Toys</span>
					</a>
				</li>
				<li>
					<a href="#" onclick="loadUsers()">
						<span class="icon">
							<ion-icon name="home-outline"></ion-icon>
						</span>
						<span class="title">Dashboard</span>
					</a>
				</li>
				<li>
					<a href="#" onclick="popUpCreateStory()">
						<span class="icon">
							<ion-icon name="people-outline"></ion-icon>
						</span>
						<span class="title">Create New Story</span>
					</a>
				</li>
				<li>
					<a href="#" onclick="loadStories()">
						<span class="icon">
							<ion-icon name="newspaper-outline"></ion-icon>
						</span>
						<span class="title">Stories</span>
					</a>
				</li>
				<li>
					<a href="#" onclick="popUp()">
						<span class="icon">
							<ion-icon name="chatbubble-outline"></ion-icon>
						</span>
						<span class="title">Message</span>
					</a>
				</li>
                <li>
					<a href="#" onclick="loadCommunity()">
						<span class="icon">
							<ion-icon name="chatbubble-outline"></ion-icon>
						</span>
						<span class="title">Community</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="icon">
							<ion-icon name="help-outline"></ion-icon>
						</span>
						<span class="title">Help</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="icon">
							<ion-icon name="settings-outline"></ion-icon>
						</span>
						<span class="title">Settings</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="icon">
							<ion-icon name="lock-closed-outline"></ion-icon>
						</span>
						<span class="title">Password</span>
					</a>
				</li>
				<li>
					<a href="php/signOut/signOut.php?" onclick="signOut()">
						<span class="icon">
							<ion-icon name="log-out-outline"></ion-icon>
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
					<ion-icon name="menu-outline"></ion-icon>
				</div>
				<!-- search -->
				<div class="search">
					<label>
						<input type="text" placeholder="Search here">
						<ion-icon name="search-outline"></ion-icon>
					</label>
				</div>
				<!-- notfication -->
				<div class="notifi">
					<div class="icon" onclick="toggleNotifi()">
						<img src="images/bell.png" alt=""> <span>0</span>
					</div>
					<div class="notifi-box" id="box">
						<h2>Notifications <span>17</span></h2>
					</div>
				</div>
				<!-- userImg -->
				<div class="user">
					<img src="images/user.jpg">
				</div>
			</div>

			<!-- cards -->
			<!-- <div class="cardBox">
				<div class="card">
					<div>
						<div class="totalStories numbers"></div>
						<div class="cardName">Total Stories</div>
					</div>
					<div class="iconBx">
						<ion-icon name="eye-outline"></ion-icon>
					</div>
				</div>
				<div class="card">
					<div>
						<div class="totalCompletedStories numbers"></div>
						<div class="cardName">Completed Stories</div>
					</div>
					<div class="iconBx">
						<ion-icon name="cart-outline"></ion-icon>
					</div>
				</div>
				<div class="card">
					<div>
						<div class="totalWords numbers"></div>
						<div class="cardName">Words</div>
					</div>
					<div class="iconBx">
						<ion-icon name="chatbubbles-outline"></ion-icon>
					</div>
				</div>
				<div class="card">
					<div>
						<div class="totalEarnings numbers"></div>
						<div class="cardName">Earning</div>
					</div>
					<div class="iconBx">
						<ion-icon name="cash-outline"></ion-icon>
					</div>
				</div>
			</div>
 -->

			<div class="details allUsers">
				<!-- order details list -->
				<div class="recentOrders">

					<!-- search -->
					<div class="search">
						<label>
							<input type="text" placeholder="Search for users" class="searchInput">
							<ion-icon name="search-outline"></ion-icon>
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

						</tbody>

					</table>

				</div>
			</div>


			<div class="details">
				<div class="communityList" style="display: none;">

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
								<ion-icon name="search-outline"></ion-icon>
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
	<div class="custom-model-main createTopicPopUp">
		<div class="custom-model-inner">
			<div class="close-btn">×</div>
			<div class="custom-model-wrap">
				<div class="pop-up-content-wrap">
					<div class="devicer"></div>
					<div class="popUpMessage">
						<h1>New Topic!</h1>
					</div>
                    <div class="container-fluid">
                        <form action="" id="manage-topic">
                            <input type="hidden" name="action" value="save_topic">
                            <div class="row form-group">
                                <div class="col-md-8">
                                    <label class="control-label">Title</label>
                                    <input type="text" name="title" id="create_title" class="n-form-control title" value="<?php echo isset($title) ? $title:'' ?>">
                                </div>
                            </div>
<!--                            <div class="row form-group">-->
<!--                                <div class="col-md-8">-->
<!--                                    <label class="control-label">Tags/Category</label>-->
<!--                                    <select name="category_ids[]" id="category_ids" multiple="multiple" class="custom-select storyNameInput select2">-->
<!--                                        <option value=""></option>-->
<!--                                        --><?php
//                                        $tag = $conn->query("SELECT * FROM categories order by name asc");
//                                        while($row= $tag->fetch_assoc()):
//                                            ?>
<!--                                            <option value="--><?php //echo $row['id'] ?><!--" --><?php //echo isset($category_ids) && in_array($row['id'], explode(",",$category_ids)) ? "selected" : '' ?><!-- > --><?php //echo $row['name'] ?><!--</option>-->
<!--                                        --><?php //endwhile; ?>
<!--                                    </select>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label class="control-label">Content</label>
                                    <textarea name="content" id="create_content" class="text-jqte n-form-control content"><?php echo isset($content) ? $content : '' ?></textarea>
                                </div>
                            </div>
                        </form>
                    </div>

<!--					<input type="text" placeholder="Enter story name..." class="storyNameInput">-->
					<button class="popUpCreateStory" onclick="saveTopic();">Create New Topic!</button>
				</div>
			</div>
		</div>
		<div class="bg-overlay"></div>
	</div>
    <div class="custom-model-main createStoryPopUp">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap">
                    <div class="devicer"></div>
                    <div class="popUpMessage">
                        <h1>New Story!</h1>
                    </div>
                    <input type="text" placeholder="Enter story name..." class="storyNameInput">
                    <button class="popUpCreateStory" onclick="createStory()">Create Story!</button>
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link type="text/css" rel="stylesheet" href="jquery-te-1.4.0.css">
	<script>
		// MenuToggle
		let toggle = document.querySelector('.toggle');
		let navigation = document.querySelector('.navigation');
		let main = document.querySelector('.main');
		// loadStories()
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

		function popUpCreateStory() {
			$(document).ready(function() {
				$(".createStoryPopUp").addClass("model-open");
			});
		}
		$(".close-btn, .bg-overlay").click(function() {
			$(".custom-model-main").removeClass('model-open');
		});
	</script>
    <script type="text/javascript" src="js/jquery-te-1.4.0.min.js" charset="utf-8"></script>
	<script src="js/signOut/signOut.js"></script>
	<script src="js/search/search.js"></script>
	<script src="js/notification/loadNotifications.js"></script>
	<script src="js/controlPanel/community.js"></script>
	<script src="js/notification/notification.js"></script>
	<script src="js/controlPanel/stories.js"></script>
	<script>
        // $(document).ready(function() {
            loadUsers();
            loadStories();

        // });
	</script>
    <script>
        $('.text-jqte').jqte();
    </script>
    <script>
        function popUpCreateTopic() {
            $(document).ready(function() {
                $(".createTopicPopUp").addClass("model-open");
                document.getElementById('create_title').value='';
                document.getElementById('create_content').value='';
                $('.text-jqte').jqte();
            });
        }
    </script>
	<!-- <script src="js/controlPanel/dashboardStatistics.js"></script> -->
</body>


</html>