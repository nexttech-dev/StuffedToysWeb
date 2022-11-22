function updatePointer(rowIdOnClick, colIdOnClick, comment) {
  let xhrPointer = new XMLHttpRequest();
  xhrPointer.open("POST", "./php/updatePointer.php", true);
  xhrPointer.onload = () => {
    if (xhrPointer.readyState === XMLHttpRequest.DONE) {
      if (xhrPointer.status === 200) {
        let data = xhrPointer.response;
        var jsonPointer = JSON.parse(xhrPointer.responseText);
        // console.log("Pointer", jsonPointer);
      } else {
        console.log("Error");
      }
    }
  };
  xhrPointer.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  xhrPointer.send(
    "storyId=" +
      storyId +
      "&chapter=" +
      chapter +
      "&row=" +
      rowIdOnClick +
      "&col=" +
      colIdOnClick +
      "&text=&commentStatus=" +
      comment
  );
}

function updateTyping() {
  $(".selected").keyup(function () {
    var [rowIdOnClick, colIdOnClick] = getRowCol(this);
    var allChilds = $(".input-cell-container").children();
    var rowNode = $(allChilds[rowIdOnClick - 1]).children();
    msg = $(rowNode[colIdOnClick]).children("textarea").val();
    // msg = document.querySelector(".cell-row .selected");

    // if (msg.querySelector(".msgBody")) {
    //   msg = msg.querySelector(".msgBody").innerHTML;
    // } else {
    //   msg = document.querySelector(".cell-row .selected").innerHTML;
    // }

    var comment = checkingComment(this);
    let xhrPointer = new XMLHttpRequest();
    xhrPointer.open("POST", "./php/updatePointer.php", true);
    xhrPointer.onload = () => {
      if (xhrPointer.readyState === XMLHttpRequest.DONE) {
        if (xhrPointer.status === 200) {
          let data = xhrPointer.response;
          var jsonPointer = JSON.parse(xhrPointer.responseText);
          // console.log("Pointer", jsonPointer);
        } else {
          console.log("Error");
        }
      }
    };
    xhrPointer.setRequestHeader(
      "Content-type",
      "application/x-www-form-urlencoded"
    );
    xhrPointer.send(
      "storyId=" +
        storyId +
        "&chapter=" +
        chapter +
        "&row=" +
        rowIdOnClick +
        "&col=" +
        colIdOnClick +
        "&text=" +
        msg +
        "&commentStatus=" +
        comment
    );
    // console.log("Typed Text", $(this).text());
  });
}

function loadPointer() {
  setInterval(() => {
    let xhrLoadPointer = new XMLHttpRequest();
    xhrLoadPointer.open("POST", "./php/loadPointer.php", true);
    xhrLoadPointer.onload = () => {
      if (xhrLoadPointer.readyState === XMLHttpRequest.DONE) {
        if (xhrLoadPointer.status === 200) {
          var jsonLoadPointer = JSON.parse(xhrLoadPointer.responseText);
          jsonLoadPointer = JSON.parse(jsonLoadPointer.data);
          // console.log("Pointer", parseInt(jsonLoadPointer.comment));
          if (sheetConfigs.showPointer) {
            var allPointers = Object.keys(jsonLoadPointer);

            allPointers.forEach(function (i, index) {
              $(".input-cell.othSelected").removeClass("othSelected");
              if (parseInt(jsonLoadPointer[i].comment) == 1) {
                // document
                //   .getElementById(
                //     "row-" +
                //       jsonLoadPointer[i].row +
                //       "-col-" +
                //       jsonLoadPointer[i].col +
                //       "-comm"
                //   )
                //   .classList.add("othSelected");
              } else if (parseInt(jsonLoadPointer[i].comment) == 0) {
                var allChilds = $(".input-cell-container").children();
                var rowNode = $(allChilds[jsonLoadPointer[i].row - 1]);
                var pointerLoc = $(rowNode).find(
                  "#col-" + jsonLoadPointer[i].col
                );
                $(pointerLoc).addClass("othSelected");
              }
            });
          }
          if (sheetConfigs.showTyping) {
            var allTexts = Object.keys(jsonLoadPointer);
            // console.log("ALL TEXTS", allTexts);
            allTexts.forEach(function (i, index) {
              if (parseInt(jsonLoadPointer[i].comment) == 1) {
                // document.getElementById(
                //   "row-" +
                //     jsonLoadPointer[i].row +
                //     "-col-" +
                //     jsonLoadPointer[i].col +
                //     "-comm"
                // ).innerHTML = jsonLoadPointer[i].text;
              } else if (parseInt(jsonLoadPointer[i].comment) == 0) {
                var allChilds = $(".input-cell-container").children();
                var rowNode = $(allChilds[jsonLoadPointer[i].row - 1]);
                var pointerLoc = $(rowNode).find(
                  "#col-" + jsonLoadPointer[i].col
                );
                var typingLoc = $(pointerLoc).find("textarea");
                $(typingLoc).val(jsonLoadPointer[i].text);
              }
            });
          }
        } else {
          console.log("Error");
        }
      }
    };
    xhrLoadPointer.setRequestHeader(
      "Content-type",
      "application/x-www-form-urlencoded"
    );
    xhrLoadPointer.send("storyId=" + storyId + "&chapter=" + chapter);
  }, 500);
}

// function loadText() {
//   setInterval(() => {
//     let xhrLoadPointer = new XMLHttpRequest();
//     xhrLoadPointer.open("POST", "./php/loadPointer.php", true);
//     xhrLoadPointer.onload = () => {
//       if (xhrLoadPointer.readyState === XMLHttpRequest.DONE) {
//         if (xhrLoadPointer.status === 200) {
//           let data = xhrLoadPointer.response;
//           var jsonLoadPointer = JSON.parse(xhrLoadPointer.responseText);
//           // console.log("Pointer", jsonLoadPointer);
//           if (jsonLoadPointer.result) {
//             if (sheetConfigs.showTyping) {
//               // $(".input-cell.othSelected").removeClass("othSelected");
//               if (parseInt(jsonLoadPointer.comment) == 1) {
//                 document.getElementById(
//                   "row-" +
//                     jsonLoadPointer.row +
//                     "-col-" +
//                     jsonLoadPointer.col +
//                     "-comm"
//                 ).innerHTML = jsonLoadPointer.text;
//               } else if (parseInt(jsonLoadPointer.comment) == 0) {
//                 document.getElementById(
//                   "row-" + jsonLoadPointer.row + "-col-" + jsonLoadPointer.col
//                 ).innerHTML = jsonLoadPointer.text;
//               }
//             }
//           }
//         } else {
//           console.log("Error");
//         }
//       }
//     };
//     xhrLoadPointer.setRequestHeader(
//       "Content-type",
//       "application/x-www-form-urlencoded"
//     );
//     xhrLoadPointer.send("storyId=" + storyId + "&chapter=" + chapter);
//   }, 500);
// }
