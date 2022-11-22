const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const storyId = urlParams.get("storyId");
const chapter = urlParams.get("chapter");

const bg = document.querySelector(".bg");
const loadText = document.querySelector(".loading-text");

let load = 0;
// let int = setInterval(blurring, 30);

// function blurring() {
//   load++;
//   if (load > 99) {
//     clearInterval(int);
//   }
//   loadText.innerText = `${load}%`;
//   loadText.style.opacity = scale(load, 0, 100, 1, 0);
//   bg.style.filter = `blur(${scale(load, 0, 100, 30, 0)}px)`;
// }
// const scale = (num, in_min, in_max, out_min, out_max) => {
//   return ((num - in_min) * (out_max - out_min)) / (in_max - in_min) + out_min;
// };

// const bg = document.querySelector(".bg");
// const loadText = document.querySelector(".loading-text");

// let load = 0;
// let int = setInterval(blurring, 30);

var sheetData = {
  dialogues: null,
  totalRows: null,
  sheetName: null,
};

var charsData = {
  totalChars: null,
  charsProperties: {},
};

var sheetConfigs = {
  showPointer: false,
  showTyping: false,
  extendedView: false,
};

var chaptersData = {
  totalChapters: null,
  chaptersDetail: {},
};
var dialogueInCellBefore = null;

var clientWid = 0;
var viewEnabled = null;
var minWidthCell = "450px";
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//+++++++++++++++++++LOADING DATA START++++++++++++++++++++++++
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
let xhrData = new XMLHttpRequest();
xhrData.open("POST", "./php/loadingData.php", true);
xhrData.onload = () => {
  if (xhrData.readyState === XMLHttpRequest.DONE) {
    if (xhrData.status === 200) {
      let data = JSON.parse(xhrData.responseText);
      let dialogues = JSON.parse(data.dialogues);
      let configs = JSON.parse(data.config);
      let chapDetails = JSON.parse(data.chapDetails);

      let charInfo = JSON.parse(data.charInfo);
      //Sheet Dialogues
      sheetData.dialogues = dialogues;
      sheetData.totalRows = data.totalRows;
      sheetData.sheetName = data.storyName;
      console.log("SheetData", sheetData);
      //Chars Data
      charsData.charsProperties = charInfo;
      //Configs
      sheetConfigs.showPointer = configs.sheetConfigs.pointer;
      sheetConfigs.showTyping = configs.sheetConfigs.typing;
      sheetConfigs.extendedView = configs.sheetConfigs.extendedView;
      chaptersData.chaptersDetail = chapDetails;
      charsData.totalChars = parseInt(configs.totalChars.totalChars);
      loadSheet();
    } else {
      location.reload();
    }
  }
};
xhrData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhrData.send("storyId=" + storyId + "&chapter=" + chapter);
//-----------------------------------------------------------
//--------------------LOADING DATA END-------------------------
//-----------------------------------------------------------

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++CREATING AND DEPLOYING DATA INTO CELLS+++++++++++++++++++
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function loadSheet() {
  $(document).ready(function () {
    //////////////////////////////////////////////////////////
    ////////////Col Names And Row Names - START//////////////
    ////////////////////////////////////////////////////////

    for (let i = 1; i <= charsData.totalChars; i++) {
      if (i == 0) {
        let column = $(`<div class="colId-${i}" id="colCod-${i}"></div>`);
        $(".column-name-container").append(column);
      } else {
        ans = String.fromCharCode(64 + i);
        let column = $(
          `<div class="column-name colId-${i}" id="colCod-${ans}">${ans}</div>`
        );
        $(".column-name-container").append(column);
      }
    }

    for (let i = 1; i <= 1000; i++) {}

    //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX//
    //XXXXXXXXXXXXXXXXXXXXXXXXXXPUSING DATA INTO ROWSXXXXXXXXXXXXXXXXXXXXXX//
    //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX//

    for (let i = 1; i <= 1000; i++) {
      let rowNum = $(
        `<div class="rowNum" id="rowId-${i}"><div class="row-name">${i}</div></div>`
      );
      let row = $(`<div class="cell-row rowNo_` + i + `"></div>`);
      row.append(rowNum);
      for (let j = 1; j <= charsData.totalChars; j++) {
        let colCode = $(`.colId-${j}`).attr("id").split("-")[1];
        let column = $(
          `<div class="input-cell" style="background:` +
            charsData.charsProperties[j].charColor +
            `;opacity :0.9; min-width: ` +
            minWidthCell +
            `" contenteditable="false" id = "col-${j}" data="code-${colCode}"><div contenteditable="false" class="box shapesLegend nullShape"></div><textarea id="textId-` +
            i +
            `-` +
            j +
            `" rows="1" class="msgBody textRow-` +
            i +
            `" onInput="autoResize(this)"></textarea></div>`
        );
        row.append(column);
      }
      $(".input-cell-container").append(row);
    }

    pushingDataIntoCells(sheetData.dialogues, charsData.totalChars);

    /////////////////////////////////////////////////////////
    ////////////Col Names And Row Names - END///////////////
    ///////////////////////////////////////////////////////

    let row = $(`<div class="cell-row"></div>`);
    let charConfigPopUp,
      configRow = "";
    for (let j = 1; j <= charsData.totalChars; j++) {
      charConfigPopUp = charConfiguration(j);

      ////////////////////////////////////////////////////////////////////////////////
      ////////////Control Buttons in First Row - START///////////////////////////////
      //////////////////////////////////////////////////////////////////////////////
      let controlBtns;
      let colCode = $(`.colId-${j}`).attr("id").split("-")[1];
      if (charsData.charsProperties[j]["charCat"] == "mainChar") {
        controlBtns =
          `<button class="controlBtn actorBtn" onclick="send(` +
          charsData.charsProperties[j]["charCode"] +
          `,'act',null,false)">Actor</button>
            <button class="controlBtn innerVoiceBtn" onclick="send(` +
          charsData.charsProperties[j]["charCode"] +
          `,'iv',null,false)">Inner Voice</button>
            <button class="controlBtn directorBtn" onclick="send(` +
          charsData.charsProperties[j]["charCode"] +
          `,'dir',null,false)">Director</button>
          <button class="controlBtn otherBtn" onclick="send(` +
          charsData.charsProperties[j]["charCode"] +
          `,'oth',null,false)">Other</button>`;
      } else if (charsData.charsProperties[j]["charCat"] == "auxChar") {
        controlBtns =
          `<button class="controlBtn auxBtn" onclick="send(` +
          charsData.charsProperties[j]["charCode"] +
          `,'aux',null,false)">AA</button>`;
      } else if (charsData.charsProperties[j]["charCat"] == "stuffedChar") {
        controlBtns =
          `<button class="controlBtn stuffedBtn" onclick="send(` +
          charsData.charsProperties[j]["charCode"] +
          `,'sa',null,false)">SA</button>`;
      }
      ////////////////////////////////////////////////////////////////////////////////
      ////////////Control Buttons in First Row - END///////////////////////////////
      //////////////////////////////////////////////////////////////////////////////

      /////////////////////////////////////////////////////////////////////////////////
      ////////////First Row with control buttons and char name- START/////////////////
      ///////////////////////////////////////////////////////////////////////////////
      let headerRow =
        `<div class=" firstRow" style="background-color: ` +
        charsData.charsProperties[j]["charColor"] +
        `" id = "row-0-col-${j}" data="code-${colCode}"><div class="firstRowCharName">
              <input class="charNameInput" onkeyup="updateCharName(event,this,` +
        charsData.charsProperties[j]["charCode"] +
        `)" placeholder="` +
        charsData.charsProperties[j]["charName"] +
        `">
            </div>
            <div class="controlBtns">
            ` +
        controlBtns +
        `
            </div></div>`;
      /////////////////////////////////////////////////////////////////////////////////
      ////////////First Row with control buttons and char name- END///////////////////
      ///////////////////////////////////////////////////////////////////////////////

      /////////////////////////////////////////////////////////////////////////
      ////////////Extended View (Coomments Coloumns)- START///////////////////
      ///////////////////////////////////////////////////////////////////////
      if (sheetConfigs.extendedView) {
        headerRow =
          `<div class="input-cell firstRow" style="background-color: #90ee90" id = "row-0-col-${j}-comm" data="code-${colCode}-comm"> Comments </div>` +
          headerRow;
      }
      /////////////////////////////////////////////////////////////////////////
      ////////////Extended View (Coomments Coloumns)- END/////////////////////
      ///////////////////////////////////////////////////////////////////////
      let column = $(headerRow);
      configRow = configRow + charConfigPopUp;
      $(".column-name-container-1").append(column);
    }
    document.querySelector(".configPopUp").innerHTML = configRow;
    $(".input-cell-container").append(row);

    //[[[[[[[[[[[[[[[[[[[[[[[[[[SIDE FUNCTIONS OF SHEET]]]]]]]]]]]]]]]]]]]]]]]]]]
    $(".align-icon").click(function () {
      $(".align-icon.selected").removeClass("selected");
      $(this).addClass("selected");
    });

    $(".style-icon").click(function () {
      $(this).toggleClass("selected");
    });

    function changeHeader(ele) {
      let [rowId, colId] = getRowCol(ele);
      let cellInfo = defaultProperties;
      if (
        cellData[selectedSheet][rowId] &&
        cellData[selectedSheet][rowId][colId]
      ) {
        cellInfo = cellData[selectedSheet][rowId][colId];
      }
      cellInfo["font-weight"]
        ? $(".icon-bold").addClass("selected")
        : $(".icon-bold").removeClass("selected");
      cellInfo["font-style"]
        ? $(".icon-italic").addClass("selected")
        : $(".icon-italic").removeClass("selected");
      cellInfo["text-decoration"]
        ? $(".icon-underline").addClass("selected")
        : $(".icon-underline").removeClass("selected");
      let alignment = cellInfo["text-align"];
      $(".align-icon.selected").removeClass("selected");
      $(".icon-align-" + alignment).addClass("selected");
      $(".background-color-picker").val(cellInfo["background-color"]);
      $(".text-color-picker").val(cellInfo["color"]);
      $(".font-family-selector").val(cellInfo["font-family"]);
      $(".font-family-selector").css("font-family", cellInfo["font-family"]);
      $(".font-size-selector").val(cellInfo["font-size"]);
    }
    // $("selector").click(function (event) {
    //   alert($(this).index());
    // });

    $("body").on("click", ".input-cell", function (e) {
      var parentRow = $(this).parent();
      // console.log($(parentRow).index());
      // // var nodes = Array.prototype.slice.call(
      // //   document.querySelector("input-cell-container").children
      // // );
      // console.log("N O D E S  :  ", nodes);
      if ($(e.target).attr("class") != "firstRow") {
        $(this).children("textarea").focus();

        if (!$(this).data("clicked")) {
          $(this).data("clicked", true);
          $(this).css("zIndex", "60");
          $(this).css("color", "white");
          $(this).css("font-weight", "normal");
          $(this).css("transform", "scale(1.1)");

          //SIDE LEGENDS DISPLAY NONE

          document.querySelector(".legends").style.display = "none";

          //SEARCH RESULTS DISPLAY NONE

          var elementsExactMatches =
            document.querySelectorAll("body .exactMatches");
          if (elementsExactMatches.length >> 0) {
            for (let i = 0; i < elementsExactMatches.length; i++) {
              elementsExactMatches[i].classList.remove("exactMatches");
            }
          }
          var elementsPartialMatches = document.querySelectorAll(
            "body .partialMatches"
          );
          if (elementsPartialMatches.length >> 0) {
            for (let i = 0; i < elementsPartialMatches.length; i++) {
              elementsPartialMatches[i].classList.remove("partialMatches");
            }
          }

          //STORING PROPERTIES OF LAST SELECTED CELL

          if ($(".input-cell.selected").length) {
            var [rowOfSelectedCell, colOfSelectedCell] = getRowCol(
              document.querySelector(".cell-row .selected")
            );
            $(".input-cell.selected").data("clicked", false);
            $(".wordCounter").remove();
          }

          // STORING PROPERTIES OF CLICKED CELL

          var [rowIdOnClick, colIdOnClick] = getRowCol(this);
          console.log("ROWW ANND COOLL", rowIdOnClick, colIdOnClick);

          //CONFIGURING FLOATING FORM

          document.querySelector(".navigation .toggle").style.background =
            charsData.charsProperties[colIdOnClick].charColor;
          document.querySelector(".navigation").style.background =
            charsData.charsProperties[colIdOnClick].charColor;
          document.querySelector(".charsNameForm").innerHTML =
            charsData.charsProperties[colIdOnClick].charName;
          if (charsData.charsProperties[colIdOnClick].charCat == "mainChar") {
            $(".aaBtn").hide();
            $(".saBtn").hide();
            $(".actBtn").show();
            $(".ivBtn").show();
            $(".dirBtn").show();
            $(".othBtn").show();
            $(".splitBtn").show();
          } else if (
            charsData.charsProperties[colIdOnClick].charCat == "auxChar"
          ) {
            $(".aaBtn").show();
            $(".actBtn").hide();
            $(".ivBtn").hide();
            $(".dirBtn").hide();
            $(".othBtn").hide();
            $(".splitBtn").hide();
            $(".saBtn").hide();
          } else if (
            charsData.charsProperties[colIdOnClick].charCat == "stuffedChar"
          ) {
            $(".saBtn").show();
            $(".actBtn").hide();
            $(".ivBtn").hide();
            $(".dirBtn").hide();
            $(".othBtn").hide();
            $(".aaBtn").hide();
            $(".splitBtn").hide();
          }

          //REMOVING CURSOR FROM LAST SELECTED CELL
          $(".input-cell.selected").css("transform", "none");
          $(".input-cell.selected").css("zIndex", "auto");
          $(".input-cell.selected").removeClass("selected");
          $(this).removeClass("othSelected");
          $(this).addClass("selected");

          //ADDING CURSOR PROPERTIES ON CLICKED CELL

          // UPDATING POINTER AND CELL DETAILS IN REAL TIME
          if (sheetConfigs.showPointer) {
            updatePointer(rowIdOnClick, colIdOnClick, checkingComment(this));
          }
          if (sheetConfigs.showTyping) {
            updateTyping();
          }
        }
      }
    });

    $(".input-cell").blur(function () {
      $(".input-cell.selected").attr("contenteditable", "false");
      updateCell("text", $(this).text());
    });

    $(".input-cell-container").scroll(function () {
      $(".column-name-container").scrollLeft(this.scrollLeft);
      $(".column-name-container-1").scrollLeft(this.scrollLeft);
      $(".row-name-container").scrollTop(this.scrollTop);
    });
    $(".commentCell").keypress(function (event) {
      var keycode = event.keyCode ? event.keyCode : event.which;
      if (keycode == "13") {
        event.preventDefault();
        for (let i = 0; i < this.classList.length; i++) {
          var requiredClass = this.classList[i].split("_")[0];
          if (requiredClass == "commentId") {
            let [rowId, colId] = getRowCol(this);
            addComment(colId, $(this).text(), this.classList[i].split("_")[1]);
            break;
          }
          if (i == this.classList.length - 1) {
            console.log(
              "No Dialogue here so you cant add any comments unfortunately!"
            );
          }
        }
      }
    });
    //{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}

    //_____________________________________________________________________//
    //XXXXXXXXXXXXXXXXXXXXXXXXXXPUSING DATA INTO ROWSXXXXXXXXXXXXXXXXXXXXXX//
    //_____________________________________________________________________//
  });
  sheetTabs();
  if (sheetConfigs.showPointer || sheetConfigs.showTyping) {
    if (typeof loadPointer === "function") {
      loadPointer();
    }
  }
}

//---------------------------------------------------------------------
//-------------CREATING AND DEPLOYING DATA INTO CELLS------------------
//---------------------------------------------------------------------

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//+++++++++++++++++++CONFIG POP START++++++++++++++++++++++++
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function charConfiguration(charCode) {
  document.querySelector("#cursor").checked = sheetConfigs.showPointer;
  document.querySelector("#typing").checked = sheetConfigs.showTyping;
  document.querySelector("#extendedView").checked = sheetConfigs.extendedView;
  let charConfigPopUp =
    `<hr width="100%" />
    <div class="fontStyling code_` +
    charCode +
    `">
      <div class="nameOfChar">
        <p>` +
    charsData.charsProperties[charCode]["charName"] +
    `</p>
      </div>
      <div class="color-dropdown">
      <button onclick="changeColor(this,` +
    charCode +
    `)" class="js-link charColorDropDown_` +
    charCode +
    `" style="background : ` +
    charsData.charsProperties[charCode]["charColor"] +
    `" id="` +
    charsData.charsProperties[charCode]["charColor"] +
    `">Color for '` +
    charsData.charsProperties[charCode]["charName"] +
    `'<i class="fa fa-chevron-down"></i></button>
        <ul class="js-dropdown-list charCode_` +
    charCode +
    `">
            <li style="background : #340459" class="#340459">340459</li>
            <li style="background : #200563" class="#200563">200563</li>
            <li style="background : #051C63" class="#051C63">051C63</li>
            <li style="background : #043059" class="#043059">043059</li>
            <li style="background : #5E0505" class="#5E0505">5E0505</li>
            <li style="background : #690535" class="#690535">690535</li>
            <li style="background : #004759" class="#004759">004759</li>
            <li style="background : #2E055E" class="#2E055E">2E055E</li>
            <li style="background : #872C07" class="#872C07">872C07</li>
            <li style="background : #911B07" class="#911B07">911B07</li>
            <li style="background : #91074C" class="#91074C">91074C</li>
            <li style="background : #870787" class="#870787">870787</li>
            <li style="background : #B03A00" class="#B03A00">B03A00</li>
            <li style="background : #4D3F00" class="#4D3F00">4D3F00</li>
            <li style="background : #A6003E" class="#A6003E">A6003E</li>
            <li style="background : #A88400" class="#A88400">A88400</li>
            <li style="background : #593904" class="#593904">593904</li>
            <li style="background : #A83100" class="#A83100">A83100</li>
        </ul>
      </div>
      <div class="font-dropdown">
        <select class="selector font-family-selector charFontFamily_` +
    charCode +
    `">
          <option
            style="font-family: Noto Sans"
            value="Noto Sans"
            selected=""
          >
            Noto Sans
          </option>
          <option style="font-family: Arial" value="Arial">
            Arial
          </option>
          <option style="font-family: Calibri" value="Calibri">
            Calibri
          </option>
          <option
            style="font-family: Comic Sans MS"
            value="Comic Sans MS"
          >
            Comic Sans MS
          </option>
          <option
            style="font-family: Courier New"
            value="Courier New"
          >
            Courier New
          </option>
          <option style="font-family: Impact" value="Impact">
            Impact
          </option>
          <option style="font-family: Georgia" value="Georgia">
            Georgia
          </option>
          <option style="font-family: Garamond" value="Garamond">
            Garamond
          </option>
          <option style="font-family: Lato" value="Lato">Lato</option>
          <option style="font-family: Open Sans" value="Open Sans">
            Open Sans
          </option>
          <option style="font-family: Palatino" value="Palatino">
            Palatino
          </option>
          <option style="font-family: Verdana" value="Verdana">
            Verdana
          </option>
        </select>
        <select class="selector font-size-selector charFontSize_` +
    charCode +
    `">
          <option value="10">10</option>
          <option value="12">12</option>
          <option value="14" selected="">14</option>
          <option value="16">16</option>
          <option value="18">18</option>
          <option value="20">20</option>
          <option value="22">22</option>
          <option value="24">24</option>
          <option value="26">26</option>
          <option value="30">30</option>
          <option value="32">32</option>
        </select>
        <button class="material-icons menu-icon icon-bold style-icon fontBold_` +
    charsData.charsProperties[charCode]["fontStyle"]["bold"] +
    ` charFontBold_` +
    charCode +
    `" onclick="changingLabelsFontStyle(this,'bold',` +
    charCode +
    `)">
          format_bold
        </button>
        <button class="material-icons menu-icon icon-italic style-icon fontItalic_` +
    charsData.charsProperties[charCode]["fontStyle"]["italic"] +
    ` charFontItalic_` +
    charCode +
    `" onclick="changingLabelsFontStyle(this,'italic',` +
    charCode +
    `)">
          format_italic
        </button>
        <button
          class="material-icons menu-icon icon-underline style-icon fontUnderline_` +
    charsData.charsProperties[charCode]["fontStyle"]["underline"] +
    ` charFontUnderline_` +
    charCode +
    `"
    onclick="changingLabelsFontStyle(this,'underline',` +
    charCode +
    `)">
          format_underline
        </button>
        <div class="menu-icon icon-color-text">
          <input
            class="color-picker text-color-picker"
            type="color"
          />
          <div class="material-icons color-fill-text">
            format_color_text
          </div>
        </div>
      </div>
      <div class="charOptBtns">
        <button>Clear Dialogues</button>
        <button>Delete Character</button>
      </div>
    </div>`;

  return charConfigPopUp;
}

//-----------------------------------------------------------
//--------------------CONFIG POP END-------------------------
//-----------------------------------------------------------

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//+++++++++++++++++++SHEET TAB START++++++++++++++++++++++++
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function sheetTabs() {
  //###### UPDATING SHEET NAME
  document.querySelector(".title-bar").innerHTML = sheetData.sheetName;

  //###### ADDING SHEET TABS _START
  let chapRow = "";
  for (let i = 0; i < chaptersData.chaptersDetail.length; i++) {
    chapName = chaptersData.chaptersDetail[i].split("_")[2];
    if (chapter == i + 1) {
      chapRow =
        chapRow +
        '<div class="sheet-tab selected"><button onclick="sheetChange(this,' +
        (i + 1) +
        ')" class="sheetBtns sheet_' +
        (i + 1) +
        '">' +
        chapName +
        "</button></div>";
    } else {
      chapRow =
        chapRow +
        '<div class="sheet-tab"><button onclick="sheetChange(this,' +
        (i + 1) +
        ')" class="sheetBtns sheet_' +
        (i + 1) +
        '">' +
        chapName +
        " </button></div>";
    }
  }
  $(".sheet-tab-container").append(chapRow);
  //****** ADDING SHEET TABS _END

  //###### SHEET TABS CONTEXT MENU _START
  $(".sheetBtns").on("contextmenu", function (e) {
    sheetName = e.delegateTarget.classList[1].split("_")[1];
    let heightOfContextMenu =
      document.getElementById("contextMenu").offsetHeight;
    if (heightOfContextMenu == 0) {
      heightOfContextMenu = 120;
    }

    $("#contextMenu").css({
      display: "block",
      left: e.pageX,
      top: e.pageY - heightOfContextMenu,
    });

    $(".renameSheet").on("click", function (e) {
      $(".renameSheetPopUp").addClass("model-open");
      document.querySelector(".newNameOfSheet").focus();
      $(".renameSheetButton").on("click", function (e) {
        renameSheet(sheetName, document.querySelector(".newNameOfSheet").value);
      });
    });
    $(".clearSheet").on("click", function (e) {
      document.querySelector(".confirmClearSheet").disabled = false;
      var sheetPopUp = document.querySelector(".clearSheetPopUp");
      sheetPopUp.querySelector("p").innerHTML =
        "Are you sure want to delete all the text from this sheet?";
      $(".clearSheetPopUp").addClass("model-open");
      $(".confirmClearSheet").on("click", function (e) {
        clearSheet(sheetName);
      });
      $(".declineClearSheet").on("click", function (e) {
        $(".clearSheetPopUp").removeClass("model-open");
      });
    });
    $(".duplicateSheet").on("click", function (e) {
      document.querySelector(".confirmClearSheet").disabled = false;
      var sheetPopUp = document.querySelector(".clearSheetPopUp");
      sheetPopUp.querySelector("p").innerHTML =
        "Are you sure want to duplicate data of current sheet and make a new sheet!";
      $(".clearSheetPopUp").addClass("model-open");
      $(".confirmClearSheet").on("click", function (e) {
        duplicateSheet(sheetName);
      });
      $(".declineClearSheet").on("click", function (e) {
        $(".clearSheetPopUp").removeClass("model-open");
      });
    });
    $(".deleteSheet").on("click", function (e) {
      document.querySelector(".confirmClearSheet").disabled = false;
      var sheetPopUp = document.querySelector(".clearSheetPopUp");
      sheetPopUp.querySelector("p").innerHTML =
        "Are you sure want to delete current sheet! 😣";
      $(".clearSheetPopUp").addClass("model-open");
      $(".confirmClearSheet").on("click", function (e) {
        deleteSheet(sheetName);
      });
      $(".declineClearSheet").on("click", function (e) {
        $(".clearSheetPopUp").removeClass("model-open");
      });
    });
    return false;
  });
}

//****** SHEET TABS CONTEXT MENU _END

//-----------------------------------------------------------
//--------------------SHEET TAB END-------------------------
//-----------------------------------------------------------

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//+++++++++++++++++++SUPPORTING FUNCTIONS START++++++++++++++++++++++++
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function generateRow(row, params, i, extendedView, flag) {
  let sameRow = false;
  let activeChar = null;
  // let firstCol = $(`<div class="row-name" id="rowId-${i}">${i}</div>`);
  //   $(".row-name-container").append(row);
  // row.append(firstCol);

  for (let j = 1; j <= charsData.totalChars; j++) {
    if (
      params[i] &&
      params[i][j] &&
      (params[i][j].details.category == "human" ||
        params[i][j].details.category == "sa" ||
        params[i][j].details.category == "aux" ||
        params[i][j].details.category == null)
    ) {
      sameRow = true;
      activeChar = j;
      break;
    }
  }

  if (sameRow) {
    //Same Properties
    let dialogueText = params[i][activeChar].details.dialogue;
    let shape = generateShape(params[i][activeChar].details.category);
    let comment = params[i][activeChar].details.comment;
    let font = {
      color:
        charsData.charsProperties[activeChar].fontStyle.color[
          params[i][activeChar].details.category
        ],
      weight: charsData.charsProperties[activeChar].fontStyle.bold,
      size: charsData.charsProperties[activeChar].fontStyle.size,
      italic: charsData.charsProperties[activeChar].fontStyle.italic,
      underline: charsData.charsProperties[activeChar].fontStyle.underline,
      family: charsData.charsProperties[activeChar].fontStyle.family,
    };
    for (let j = 1; j <= charsData.totalChars; j++) {
      let colCode = $(`.colId-${j}`).attr("id").split("-")[1];

      var cellToAdd = generateCell(i, j, colCode, dialogueText, shape, font);
      if (extendedView) {
        var commentCellToAdd = generateCommentCell(row, j, colCode, comment);
        row.append(commentCellToAdd);
      }
      row.append(cellToAdd);
      // resizingTextArea(i, j);
    }
  } else {
    //New Properties
    for (let j = 1; j <= charsData.totalChars; j++) {
      let colCode = $(`.colId-${j}`).attr("id").split("-")[1];

      let dialogueText;
      let shape;
      let comment;
      let font;
      if (params[i][j]) {
        dialogueText = params[i][j].details.dialogue;
        shape = generateShape(params[i][j].details.category);
        comment = params[i][j].details.comment;
        font = {
          color:
            charsData.charsProperties[j].fontStyle.color[
              params[i][j].details.category
            ],
          weight: charsData.charsProperties[j].fontStyle.bold,
          size: charsData.charsProperties[j].fontStyle.size,
          italic: charsData.charsProperties[j].fontStyle.italic,
          underline: charsData.charsProperties[j].fontStyle.underline,
          family: charsData.charsProperties[j].fontStyle.family,
        };
      } else {
        dialogueText = "";
        shape = generateShape(null);
        comment = null;
        font = {
          color: charsData.charsProperties[j].fontStyle.color.act,
          weight: charsData.charsProperties[j].fontStyle.bold,
          size: charsData.charsProperties[j].fontStyle.size,
          italic: charsData.charsProperties[j].fontStyle.italic,
          underline: charsData.charsProperties[j].fontStyle.underline,
          family: charsData.charsProperties[j].fontStyle.family,
        };
      }

      var cellToAdd = generateCell(i, j, colCode, dialogueText, shape, font);
      if (extendedView) {
        var commentCellToAdd = generateCommentCell(row, j, colCode, comment);
        row.append(commentCellToAdd);
      }
      row.append(cellToAdd);
      // resizingTextArea(i, j);
    }
  }

  $(".input-cell-container").append(row);
  if (flag) {
  }
}

function generateShape(params) {
  let shape = null;
  if (params == null) {
    shape = "";
  } else if (params == "dir") {
    shape =
      '<div contenteditable="false" class="box shapesLegend square"><span class="squareEmo"></span></div>';
  } else if (params == "act") {
    shape =
      '<div contenteditable="false" class="box shapesLegend triangle"><span class="triUpEmo"></span></div>';
  } else if (params == "human") {
    shape =
      '<div contenteditable="false" class="box shapesLegend invertedTriangle"><span class="triDownEmo"></span></div>';
  } else if (params == "sa") {
    shape =
      '<div contenteditable="false" class="box shapesLegend clover"><span class="cloverEmo"></span></div>';
  } else if (params == "aux") {
    shape =
      '<div contenteditable="false" class="box shapesLegend star"><span class="starEmo"></span></div>';
  } else if (params == "iv") {
    shape =
      '<div contenteditable="false" class="box shapesLegend heart"><span class="heartEmo"></span></div>';
  } else if (params == "oth") {
    shape =
      '<div contenteditable="false" class="box shapesLegend circle"><span class="circleEmo"></span></div>';
  }
  return shape;
}

function generateCell(row, col, colCode, dialogueText, shape, font) {
  let column = $(
    `<div class="input-cell" style="background:` +
      charsData.charsProperties[col].charColor +
      `;opacity :0.9; min-width: ` +
      minWidthCell +
      `" contenteditable="false" id = "row-${row}-col-${col}" data="code-${colCode}">` +
      shape +
      `<textarea class="msgBody" style="color:` +
      font.color +
      `;font-weight:` +
      font.wieght +
      `; font-family : ` +
      font.family +
      `;font-size:` +
      font.size +
      `px;text-decoration : ` +
      font.underline +
      `;font-style : ` +
      font.italic +
      `" >` +
      dialogueText +
      `</textarea></div>`
  );

  return column;
}
function generateCommentCell(row, col, colCode, comment) {
  let commentCell = $(
    `<div class="input-cell commentCell" style="background: ` +
      charsData.charsProperties[col].charColor +
      `;opacity:.5"contenteditable="false" id = "row-${row}-col-${col}-comm" data="code-${colCode}-comm">` +
      comment +
      `</div>`
  );

  return commentCell;
}

function getRowCol(ele) {
  // let idArray = $(ele).attr("id").split("-");
  // let colId = parseInt(idArray[1]);
  // var parent = $(ele).parent();
  // var rowNode = $(parent).children()[0];
  // let rowArray = $(rowNode).attr("id").split("-");
  // let rowId = parseInt(rowArray[1]);
  // return [rowId, colId];

  var parent = $(ele).parent();
  var rowNode = $(parent).children()[0];
  // console.log("getRowColParent", rowNode);
  let idArray = $(rowNode).attr("id").split("-");
  let rowId = parseInt(idArray[1]);
  let arr = $(ele).attr("id").split("-");
  let colId = parseInt(arr[1]);
  return [rowId, colId];
}

function getRowColParent(ele) {
  var parent = $(ele).parent();
  var rowNode = $(parent).children()[0];

  console.log("getRowColParent", rowNode);

  let idArray = $(rowNode).attr("id").split("-");
  let rowId = parseInt(idArray[1]);

  let arr = $(ele).attr("id").split("-");
  let colId = parseInt(arr[3]);
  return [rowId, colId];
}
Array.prototype.max = function () {
  return Math.max.apply(null, this);
};
$(".icon-add").click(function () {
  newSheet(this);
});
function checkingComment(ele) {
  let idArray = $(ele).attr("id").split("-");
  if (idArray[4]) {
    return true;
  } else {
    return false;
  }
}

function rowNumAlignment(row, rowNum, onLoad) {
  // console.log("ROW ALIGNING", row);
  // console.log("ROW ALIGNING MAIN", $(row).height());
  // console.log("ROW ALIGNING PARENT", $(row).parent().height());
  var msgBodyRow;
  if (onLoad) {
    msgBodyRow = $(row).height();
  } else {
    msgBodyRow = $(row).height();
  }

  var msgBodyRowNum = $("#rowId-" + rowNum).height();
  // console.log("FUCKING HERE!", msgBodyRowNum);
  if (msgBodyRow != msgBodyRowNum) {
    // $("#rowId-" + rowNum).height(msgBodyRow);
    $("#rowId-" + rowNum).css(
      "cssText",
      "height: " + msgBodyRow + "px !important;"
    );
    // var newHe = $("#rowId-" + rowNum).height();
    // if (onLoad) {
    //   // $(row).height(newHe);
    //   $(row).css("cssText", "height: " + newHe + "px !important;");
    // } else {
    //   $(row)
    //     .parent()
    //     .css("cssText", "height: " + newHe + "px !important;");
    //   // $(row).parent().height(newHe);
    // }
  }

  // console.log("It did Executed", msgBodyRow, msgBodyRowNum);
}
function blurring() {
  load++;
  if (load > 99) {
    clearInterval(int);
  }
  loadText.innerText = `${load}%`;
  loadText.style.opacity = scale(load, 0, 100, 1, 0);
  bg.style.filter = `blur(${scale(load, 0, 100, 30, 0)}px)`;
}
const scale = (num, in_min, in_max, out_min, out_max) => {
  return ((num - in_min) * (out_max - out_min)) / (in_max - in_min) + out_min;
};

function autoResize(element) {
  element.style.height = "5px";
  element.style.height = element.scrollHeight + "px";
}
// const txHeight = 25;

// const tx = document.getElementsByTagName("textarea");

// tx.addEventListener("input", autoResize, false);

// function OnInput(e) {
//   this.style.height = 0;
//   this.style.height = this.scrollHeight + "px";
// }
//---------------------------------------------------------------------
//--------------------SUPPORTING FUNCTIONS END-------------------------
//---------------------------------------------------------------------
