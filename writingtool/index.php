<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/floatingControls.css">
    <link rel="stylesheet" href="css/configSliders.css">
    <link rel="stylesheet" href="css/contextMenu.css">

    <link href="style.css" rel="stylesheet" />
    <link href="css/floatingControls.css" rel="stylesheet">
    <title>Story Foolery</title>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999999;
            background: url('./assets/images/loading.gif') 50% 50% no-repeat rgb(249, 249, 249);
        }
    </style>
    <script>
        $(window).load(function() {
            $(".loader").fadeOut("slow");
        })
    </script>
</head>

<body>
    <div class="loader">

    </div>
    <div class="container">
        <div class="title-bar">Loading...</div>
        <div class="RecBtn"><button onclick="audioRecordingMode()"><img src="assets/images/recButton.png" alt="" class="RecBtnImg"></button></div>

        <div class="menu-bar">
            <div class="menu-file menu-item">File</div>
            <div class="fileDropDown">
                <div class="fileViewList">
                    <button class="dropdown__link configurations">Configurations</button>
                    <button class="dropdown__link save">Save</button>
                    <button class="dropdown__link saveAs">Save As</button>
                    <button class="dropdown__link loadOlderVersion">Load Older Version</button>

                </div>
                <!-- <div class="fileViewList">
                </div>
                <div class="fileViewList">
                </div>
                <div class="fileViewList">
                </div> -->
            </div>
            <div class="menu-home menu-item selected">Home</div>
            <div class="menu-insert menu-item">Insert</div>
            <div class="insertDropDown">
                <div class="insertViewList">
                    <button class="dropdown__link addNewChar">Add New Character</button>
                    <!-- <button class="dropdown__link" onclick="loadingScreen()">Void</button> -->
                    <!-- <button class="dropdown__link">Void</button> -->
                </div>
            </div>
            <div class="menu-layout menu-item">Layout</div>
            <div class="menu-help menu-item">Help</div>
            <div class="menu-view menu-item">View</div>
            <div class="viewDropDown">
                <div class="writersViewList">
                    <button class="dropdown__link twoView">2 Writers View</button>
                    <button class="dropdown__link fourView">4 Writers View</button>
                    <button class="dropdown__link sixView">6 Writers View</button>
                    <button class="dropdown__link configurations">Configurations</button>
                </div>
            </div>
            <div class="menu-share menu-item">Share</div>
            <div class="commonCtrlBtns">
                <button class="insertRowMenu" onclick="formInsertRowBtn(this)">
                    Insert Row
                </button>
                <button class="deleteCellMenu" onclick="formSend(this,'iv',true)">
                    Delete Cell
                </button>
                <button class="deleteRowMenu" onclick="formDeleteRowBtn(this)">
                    Delete Row
                </button>
            </div>

        </div>


        <div class="data-container">
            <div class="select-all"></div>
            <div class="column-name-container-0">
                <div class="column-name-container">

                </div>
                <div class="column-name-container-1">

                </div>
            </div>
            <div class="input-cell-container">

            </div>
        </div>
        <div class="sheet-bar">
            <div class="scroller">
                <div class="material-icons icon-left-scroll">arrow_left</div>
                <div class="material-icons icon-right-scroll">arrow_right</div>
            </div>
            <div class="material-icons icon-add">add_circle</div>
            <div class="sheet-tab-container">
                <!-- <div class="sheet-tab selected">
                    <button class="sheetBtns">Sheet1</button>
                </div> -->
            </div>
        </div>

    </div>
    <div class="navigation">
        <div class="toggle">
            <div class="charsNameForm">Welcome!</div>
            <div class="closeFormBtn">X</div>

        </div>
        <!-- <div class="charactersNameInForm">Hello</div> -->
        <textarea class="popUpMsg" name="" id="" cols="30" rows="3"></textarea>
        <div class="popUpBtns">
            <button class="popUpBtn actBtn" onclick="formSend(this,'act',false)">ACT</button>
            <button class="popUpBtn ivBtn" onclick="formSend(this,'iv',false)">IV</button>
            <button class="popUpBtn dirBtn" onclick="formSend(this,'dir',false)">DIR</button>
            <button class="popUpBtn othBtn" onclick="formSend(this,'oth',false)">OTH</button>
            <button class="popUpBtn aaBtn" onclick="formSend(this,'aux',false)">AA</button>
            <button class="popUpBtn saBtn" onclick="formSend(this,'sa',false)">SA</button>
        </div>
        <div class="popUpBtns">
            <button class="popUpBtn refreshBtn" onclick="formRefreshBtn(this)">EDIT</button>
            <button class="popUpBtn findBtn" onclick="formFindBtn(this)">FIND</button>
            <button class="popUpBtn replaceBtn" onclick="formReplaceBtn(this)">REPLACE</button>
            <!-- </div>
        <div class="popUpBtns"> -->
            <button class="popUpBtn splitBtn" onclick="formSplitBtn(this)">SPLIT LINE</button>
            <button class="popUpBtn insertBtn" onclick="formInsertRowBtn(this)">INSERT ROW</button>
            <button class="popUpBtn deleteBtn" onclick="formDeleteRowBtn(this)">DELETE ROW</button>
        </div>
        <div class="searchOptions">
            <div class="searchCtrls">
                <div class="searchChoices">
                    <button class="exactSearch">Exact Searches</button>
                    <button class="partialSearch">Partial Searches</button>
                </div>
                <div class="searchJump">
                    <button class="arrowPrev">
                        <img src="assets/images/arrowPrev.png" alt="" />
                    </button>
                    <button class="arrowNext">
                        <img src="assets/images/arrowNext.png" alt="" />
                    </button>
                </div>
            </div>
            <div class="totalSearchResults">
                <p>Total Matches :</p>
            </div>
        </div>
    </div>

    <div class="custom-model-main addChar">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap">
                    <input type="text" class="charName">
                    <button class="addCharBtns" onclick="addChar('mainChar')">Character</button>
                    <button class="addCharBtns" onclick="addChar('auxChar')">Auxiliary Character</button>
                    <button class="addCharBtns" onclick="addChar('stuffedChar')">Stuffed Toy</button>

                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
    <div class="custom-model-main error">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap">
                    <h1 class="errorHead">Error!</h1>
                    <p class="errorText">Error</p>
                    <div class="errorBtns">
                        <button class="popUpReplace" onclick="replaceAction(this)">Replace</button>
                        <button class="popUpNot">Not Now</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>

    <div class="custom-model-main shareStory">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap">
                    <input type="email" class="emailOfOthUser" placeholder="Enter email of the user" autofocus>
                    <button class="addCharBtns" onclick="shareStory()">Share</button>
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>

    <div class="custom-model-main renameSheetPopUp">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap">
                    <input type="text" class="sheetPopElements newNameOfSheet" placeholder="New name for sheet" autofocus>
                    <button class="addCharBtns renameSheetButton">Rename</button>
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
    <div class="custom-model-main saveAsPopUp">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap">
                    <input type="text" class="sheetPopElements nameOfSave" placeholder="Please enter name for saving story" autofocus>
                    <button class="addCharBtns saveCurrentSheet" onclick="save(true,false,'saveAs')">Save Current Sheet</button>
                    <button class="addCharBtns saveFullSheet" onclick="save(true,true,'saveAs')">Save Full Story</button>

                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
    <div class="custom-model-main clearSheetPopUp">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap">
                    <p>Invalid Operation! Please Refresh the website.</p>
                    <button class="addCharBtns popUpNot confirmClearSheet">Confirm</button>
                    <button class="addCharBtns popUpReplace declineClearSheet">Not Now</button>
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
    <div class="custom-model-main sharingConfirmations">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap">
                    <p>Loading...</p>
                    <!-- <button class="addCharBtns popUpReplace confirmClearSheet">Perfect!</button> -->
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
    <div class="custom-model-main config">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap configPop">
                    <div class="toggle-container autoFeedContainer">
                        <input type="checkbox" id="autoFeed" class="slider">
                        <label for="autoFeed" class="label">
                            <div class="ball"></div>
                        </label>
                        <span>Auto Feed</span>

                    </div>

                    <div class="toggle-container">
                        <input type="checkbox" id="cursor" class="slider">
                        <label for="cursor" class="label">
                            <div class="ball"></div>
                        </label>
                        <span>Cursor Positioning (Multiple writer cursor positions are visible)</span>
                    </div>
                    <div class="toggle-container">
                        <input type="checkbox" id="extendedView" class="slider">
                        <label for="extendedView" class="label">
                            <div class="ball"></div>
                        </label>
                        <span>Extended View (Comments Columns Open)</span>
                    </div>
                    <div class="toggle-container">
                        <input type="checkbox" id="typing" class="slider">
                        <label for="typing" class="label">
                            <div class="ball"></div>
                        </label>
                        <span>Typing Text (View Multiple writer's text when it is written)</span>
                    </div>


                    <div class="configPopUp">

                    </div>

                    <div class="errorBtns">
                        <button class="saveConfigButton" onclick="updateConfigs(this)">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>


    <!-- LOAD OLDER VERSIONS - START -->
    <div class="custom-model-main loadOlderVersions">
        <div class="custom-model-inner">
            <div class="close-btn">×</div>
            <div class="custom-model-wrap">
                <div class="pop-up-content-wrap loadOlderPopUp">
                    <h1>Chapters</h1>
                    <hr>
                    <div class="chaptersOlderVersions">

                    </div>
                    <h1>Full Story</h1>
                    <hr>
                    <div class="storyOlderVersions">

                    </div>

                </div>
            </div>
        </div>
        <div class="bg-overlay"></div>
    </div>
    <!-- LOAD OLDER VERSIONS - END -->

    <!-- LOADING SCREEN ONLOAD - START -->

    <!-- <div class="onLoadLoadingScreen">
        <div class="bg"></div>
        <div class="loading-text">Loading 0% </div>

    </div> -->
    <!-- LOADING SCREEN ONLOAD - END -->


    <!-- Context Menu - START -->
    <div id="contextMenu" class="dropdown clearfix">
        <div class="contextMenuDropDown">
            <button class="renameSheet">
                Rename
            </button>
            <button class="duplicateSheet">
                Duplicate Sheet
            </button>
            <button class="clearSheet">
                Clear Sheet
            </button>
            <button class="deleteSheet">
                Delete
            </button>
        </div>
    </div>
    <!-- Context Menu - END -->

    <!-- Context Menu - START -->
    <div id="popUpForRemeberence" class="dropdown clearfix">
        <div class="arrow">
            <div class="outer"></div>
            <div class="inner"></div>
        </div>
        <div class="popUpForRemeberenceContent">
            <p>You have forget to send dialogue!</p>
        </div>
    </div>
    <!-- Context Menu - END -->

    <!-- <div id="sheetPopUp" class="dropdown clearfix">
        <div class="sheetPopElements">
            <input type="text">
            <button class="renameButton">RENAME</button>
        </div>
    </div> -->


    <div class="custom-model-main loadingScreen">
        <div class="custom-model-inner">
            <img src="./assets/images/loading.gif" alt="">
        </div>
        <div class="bg-overlay"></div>
    </div>

    <div class="helpPopUp">
        <div class="arrow">
            <div class="outer"></div>
            <div class="inner"></div>
        </div>
        <div class="message-body">
            <p>
                Hello world! This is a test message to show how to make an arrow on
                the side of the box.
            </p>
        </div>
    </div>
    <div class="legends">
        <div class="legendsDetails">
            <div class=""><span class="squareEmo"></span><label>Director</label></div>
            <div class=""><span class="circleEmo"></span><label>Aux Actor</label></div>
            <div class=""><span class="heartEmo"></span><label>Inner Thoughts</label></div>
            <div class=""><span class="triUpEmo"></span><label>Actor</label></div>
            <div class=""><span class="triDownEmo"></span><label>Human</label></div>
            <div class=""><span class="cloverEmo"></span><label>Non-Actor Toy</label></div>
        </div>
    </div>
    <script>
        // document.querySelector(".square").onclick = function() {
        //     var offsets = $(".square").offset();
        //     var top = offsets.top;
        //     var left = offsets.left;
        //     console.log("TOP", top);
        //     console.log("LEFT", left);

        //     $(".helpPopUp").css({
        //         display: "block",
        //         left: left - 28,
        //         top: top + 30,
        //     });
        // };
    </script>
    <script>
        const navigation = document.querySelector('.navigation');
        document.querySelector(".toggle").addEventListener('mouseup', function() {
            if ($('.navigation').is('.ui-draggable-dragging')) {} else {

                if (document.querySelector(".charsNameForm").style.display == "block") {
                    document.querySelector(".charsNameForm").style.display = "none"
                    document.querySelector(".closeFormBtn").style.display = "none";
                } else {
                    document.querySelector(".charsNameForm").style.display = "block"
                    document.querySelector(".closeFormBtn").style.display = "block";
                }
                document.querySelector(".searchOptions").style.display = "none";
                // document.querySelector(".searchOptions").style.display = "none";


                this.classList.toggle('active');
                navigation.classList.toggle('active')
                document.querySelector(".popUpMsg").focus();

            }
        })

        $(function() {
            $(".navigation").draggable();
        });
    </script>

    <script src="script.js"></script>
    <script src="menuItems.js"></script>
    <script src="js/sheetCtrllers.js"></script>
    <script src="js/addChar.js"></script>
    <script src="js/updateCharName.js"></script>
    <script src="js/formBtns.js"></script>
    <script src="js/shareStory.js"></script>
    <script src="js/send.js"></script>
    <script src="js/config.js"></script>
    <script src="js/sheetChange.js"></script>
    <script src="js/comment.js"></script>
    <script src="js/onLoad.js"></script>
    <script src="js/contextMenu.js"></script>
    <script src="js/renameSheet.js"></script>
    <script src="js/recording.js"></script>
    <script src="js/floatingControls.js"></script>
    <script src="js/updatingDialogues.js"></script>
    <script src="js/saveAndLoadSheet.js"></script>
    <script src="js/loading.js"></script>
    <script src="js/load&UpdatePoinndText.js"></script>


</body>

</html>