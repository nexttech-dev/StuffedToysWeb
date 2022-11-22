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

<body _c_t_common="1" data-new-gr-c-s-check-loaded="14.1069.0" data-gr-ext-installed="" cz-shortcut-listen="true">
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <div class="details">
                    <span>Story 9</span>
                    <p>Active now</p>
                </div>
            </header>

            <div class="chat-box" id="chatBox">
                <div class="chatSelection"><input type="text" name="" onkeyup="updateChar(event,this)" id="Char1" placeholder="Char1" style="background : #d21174;"><input type="text" name="" onkeyup="updateChar(event,this)" id="Char2" placeholder="Char2" style="background : #26bc72;"></div>
                <div class="chatRow">
                    <div class="chat chatLeft">
                        <div class="chatDetails">
                            <div class="textAndOptions">
                                <textarea oninput="textAreaAdjust(this,event)" class="chatText chatTextLeft chatText1" type="text">Hey</textarea>
                                <button class="delete"><i class="fa-solid fa-trash"></i></button>
                            </div>
                            <div class="userInfo">Char4 : Actor : 1658475244</div>
                        </div>
                    </div>
                    <div class="chat chatRight greyedOut">
                        <div class="chatDetails">
                            <div class="msgId685897398 textAndOptions">
                                <textarea onkeyup="textAreaAdjust(this)" class="chatText chatTextRight chatText1 " type="text" placeholder="Char 2 is listening to his Actor. You might want to add someting to listen in the meantime."></textarea>
                            </div>
                            <div class="menu__item--meatball rightOpt" tabindex="5" onclick="moreOptions(this)">
                                <div class="circle"></div>
                                <div class="circle"></div>
                                <div class="circle"></div>
                            </div>
                            <div class="moreOptions" style="display: none;">
                                <div>
                                    <button>Delete</button>
                                </div>
                                <div>
                                    <button>Record</button>
                                </div>
                            </div>
                            <div class="userName">Char1 : Actor : 1658475244</div>
                        </div>
                    </div>
                </div>
                <div class="chatRow">
                    <div class="chat chatRight">
                        <div class="chatDetails">
                            <div class="msgId907887170 textAndOptions">
                                <textarea onkeyup="textAreaAdjust(this)" class="chatText chatTextRight chatText1" type="text">Hey</textarea>
                                <button class="delete"><i class="fa-solid fa-trash"></i></button>
                            </div>
                            <div class="userInfo">Char2 : Actor : 1658475244</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="typing-area">
                <input type="text" class="incoming_id" name="incoming_id" value="987709432" hidden="">
                <input type="text" name="message" class="input-field msgToSend" placeholder="Type a message here..." autocomplete="off">
                <button class="Click-here active"><i class="fab fa-telegram-plane"></i></button>
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
            let chatDetails = element.parentNode.parentNode
            // element = element.parentNode;
            // if (event.keyCode == 13) {
            //     //     let chatText = chatDetails.querySelector(".chatText").value;
            //     //     let msgIdToUpdate = $(element).attr("id")
            //     //     msgIdToUpdate = msgIdToUpdate.split('Id').pop();
            //     //     updateMsg(msgIdToUpdate, chatText)
            // } else {
            // reConstructChatTextBox(element.parentNode, 0)
            chatDetails.style.height = "5px";
            chatDetails.style.height = (element.scrollHeight) + "px";

            // }
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
    <!-- <script src="./js/index.js"></script>
    <script src="./js/loadStories.js"></script>
    <script src="./js/fetchingAllAuxChars.js"></script>
    <script src="./js/sendMsg.js"></script>
    <script src="./js/addAuxAct.js"></script> -->


</body>
<grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration>

</html>