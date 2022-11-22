<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Story Creation</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/addAux.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
</head>

<body _c_t_common="1" data-new-gr-c-s-check-loaded="14.1065.0" data-gr-ext-installed="" cz-shortcut-listen="true">
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <div class="details">
                    <span>Shahab Lund</span>
                    <p>Active now</p>
                </div>
            </header>

            <div class="chat-box" id="chatBox">

            </div>
            <div class="typing-area">
                <input type="text" class="incoming_id" name="incoming_id" value="987709432" hidden="">
                <input type="text" name="message" class="input-field msgToSend" placeholder="Type a message here..." autocomplete="off">
                <button class="Click-here"><i class="fab fa-telegram-plane"></i></button>
            </div>
            <div class="custom-model-main sendBtnSelPrompt">
                <div class="custom-model-inner">
                    <div class="close-btn">×</div>
                    <div class="custom-model-wrap">
                        <div class="pop-up-content-wrap">
                            <button class="actor">Actor</button>
                            <button class="innerThoughts">Inner Thoughts</button>
                            <button class="director">Director</button>
                            <div class="devicer"></div>
                            <div class="auxCharsShow">
                                <p>No auxilary characters</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-overlay"></div>
            </div>
            <div class="custom-model-main addAuxActPrompt">
                <div class="custom-model-inner">
                    <div class="close-btn">×</div>
                    <div class="custom-model-wrap">
                        <div class="pop-up-content-wrap">
                            <input type="text" class="auxName">
                            <button class="auxAddButton">Add</button>
                        </div>
                    </div>
                </div>
                <div class="bg-overlay"></div>
            </div>
        </section>
    </div>
    <nav class="menu">
        <ul>
            <li class="addAux"><span>Auxilary</span></li>
            <li><span>Character</span></li>
            <li><span>Writer</span></li>
        </ul>
    </nav>

    <script>
        function textAreaAdjust(element, event) {

            console.log("Key pressed!", event.keyCode)
            let chatDetails = element.parentNode.parentNode
            if (event.keyCode == 13) {
                let chatText = chatDetails.querySelector(".chatText").value;
                let msgIdToUpdate = $(element).attr("id")
                msgIdToUpdate = msgIdToUpdate.split('Id').pop();
                updateMsg(msgIdToUpdate, chatText)
            }
            chatDetails.style.height = "5px";
            chatDetails.style.height = (element.scrollHeight) + "px";
        }

        function reConstructChatTextBox(element, toDo, chatText) {
            let chatDetails = element.parentNode;
            let chatTextLeft = chatDetails.querySelector(chatText);
            let moreOptions = chatDetails.querySelector(".moreOptions")
            if (toDo) {
                moreOptions.style.display = "block";
                let chatDetailNewHeight = chatDetails.scrollHeight + 28;
                let chatTextLeftNewHeight = chatDetails.scrollHeight - 28;
                chatDetails.style.height = chatDetailNewHeight + "px";
                chatTextLeft.style.height = chatTextLeftNewHeight + "px";
                window.setTimeout(function() {
                    moreOptions.style.opacity = 1;
                    // moreOptions.style.transform = 'scale(1)';
                }, 0);
            } else {
                moreOptions.style.display = "none"
                let chatDetailNewHeight = chatDetails.scrollHeight - 93;
                let chatTextLeftNewHeight = chatDetails.scrollHeight + 28;
                chatDetails.style.height = chatDetailNewHeight + "px";
                chatTextLeft.style.height = "100%";
                moreOptions.style.opacity = 0;
                // moreOptions.style.transform = 'scale(0)';
                window.setTimeout(function() {
                    moreOptions.style.display = 'none';
                }, 700);
            }
        }

        $(".Click-here").on('click', function() {
            $(".sendBtnSelPrompt").addClass('model-open');
        });
        $(".close-btn, .bg-overlay").click(function() {
            $(".custom-model-main").removeClass('model-open');
        });
    </script>
    <script src="./js/index.js"></script>
    <script src="./js/loadStories.js"></script>
    <script src="./js/fetchingAllAuxChars.js"></script>
    <script src="./js/sendMsg.js"></script>
    <script src="./js/addAuxAct.js"></script>
</body>

</html>