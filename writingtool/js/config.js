const slider = document.querySelectorAll(".slider");
const autoFeed = document.querySelector("#autoFeed");
const typing = document.querySelector("#typing");
const pointer = document.querySelector("#cursor");
const extended = document.querySelector("#extendedView");
const fontStlyingBtns = document.getElementById("fontStlyingBtns");
function updateConfigs(params, charCode) {
  var charsNewColors = "";
  let charsNewConfigs = {};
  charsNewConfigs["storyId"] = storyId;
  charsNewConfigs["chapter"] = chapter;
  charsNewConfigs["pointer"] = pointer.checked;
  charsNewConfigs["typing"] = typing.checked;
  charsNewConfigs["extended"] = extended.checked;
  charsNewConfigs["totalChars"] = charsData.totalChars;

  for (let i = 1; i <= charsData.totalChars; i++) {
    var bold = false;
    var italic = false;
    var underline = false;
    var fontFamily, fontSize, charColor;

    var element = document.querySelector(".code_" + i);

    charColor = element.querySelector(".charColorDropDown_" + i).id;
    fontFamily = element.querySelector(".charFontFamily_" + i).value;
    fontSize = element.querySelector(".charFontSize_" + i).value;
    if (
      element
        .querySelector(".charFontBold_" + i)
        .classList.contains("fontBold_bold")
    ) {
      bold = "bold";
    } else {
      bold = "normal";
    }
    if (
      element
        .querySelector(".charFontItalic_" + i)
        .classList.contains("fontItalic_italic")
    ) {
      italic = "italic";
    } else {
      italic = "normal";
    }
    if (
      element
        .querySelector(".charFontUnderline_" + i)
        .classList.contains("fontUnderline_underline")
    ) {
      underline = "underline";
    } else {
      underline = "none";
    }

    charsNewConfigs[i] = {
      bold: bold,
      italic: italic,
      underline: underline,
      fontFamily: fontFamily,
      fontSize: fontSize,
      charColor: charColor,
    };
  }

  let xhrSaveConfig = new XMLHttpRequest();
  xhrSaveConfig.open("POST", "./php/saveConfig.php", true);
  xhrSaveConfig.onload = () => {
    if (xhrSaveConfig.readyState === XMLHttpRequest.DONE) {
      if (xhrSaveConfig.status === 200) {
        var jsonSaveConfig = JSON.parse(xhrSaveConfig.responseText);

        if (jsonSaveConfig.result) {
          location.reload();
        } else {
          console.log("json", jsonSaveConfig);
        }
      }
    }
  };
  xhrSaveConfig.setRequestHeader("Content-type", "application/json");
  xhrSaveConfig.send(JSON.stringify(charsNewConfigs));
}

function changeColor(ele, charCode) {
  var list = $(".charCode_" + charCode);
  var link = $(".charColorDropDown_" + charCode);

  list.slideToggle(200);
  list.find("li").click(function () {
    var text = $(this).html();
    link.css("background-color", "#" + text);
    var icon = '<i class="fa fa-chevron-down"></i>';
    // link.html("Char" + icon);
    link.attr("id", "#" + text);
    // list.slideToggle(200);
    if (text === "* Reset") {
      link.html("Select one option" + icon);
    }
  });
}

// fontStlyingBtns.onclick = function (params) {
//   console.log("Hellosd");
// };

function changingLabelsFontStyle(params, property, charCode) {
  // console.log(params.classList);

  if (property == "bold") {
    if (params.classList.contains("fontBold_bold")) {
      params.classList.remove("fontBold_bold");
      params.classList.add("fontBold_normal");
    } else if (params.classList.contains("fontBold_normal")) {
      params.classList.remove("fontBold_normal");
      params.classList.add("fontBold_bold");
    }
  } else if (property == "italic") {
    if (params.classList.contains("fontItalic_italic")) {
      params.classList.remove("fontItalic_italic");
      params.classList.add("fontItalic_normal");
    } else if (params.classList.contains("fontItalic_normal")) {
      params.classList.remove("fontItalic_normal");
      params.classList.add("fontItalic_italic");
    }
  } else if (property == "underline") {
    if (params.classList.contains("fontUnderline_underline")) {
      params.classList.remove("fontUnderline_underline");
      params.classList.add("fontUnderline_none");
    } else if (params.classList.contains("fontUnderline_none")) {
      params.classList.remove("fontUnderline_none");
      params.classList.add("fontUnderline_underline");
    }
  }
}
