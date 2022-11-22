function send(charId, action, msg, aF) {
  // $(".loadingScreen").addClass("model-open");
  var [rowId, colId] = getRowCol(document.querySelector(".cell-row .selected"));
  var selectedCell = $(".selected");

  console.log("SELECTEDDD");
  console.log("ROW AND COL", rowId, colId);
  if (msg == null) {
    var allChilds = $(".input-cell-container").children();
    var rowNode = $(allChilds[rowId - 1]).children();
    msg = $(rowNode[colId]).children("textarea").val();
  }
  if (colId == charId) {
    //send message
    let xhrSend = new XMLHttpRequest();
    xhrSend.open("POST", "./php/sendReplace.php", true);
    xhrSend.onload = () => {
      if (xhrSend.readyState === XMLHttpRequest.DONE) {
        if (xhrSend.status === 200) {
          document.querySelector(".popUpMsg").value = null;
          //MOVING THE CURSOR
          dialogueInCellBefore = null;

          $(selectedCell).css("transform", "none");
          $(selectedCell).data("clicked", false);
          $(selectedCell).removeClass("selected");
          $(selectedCell).children("textarea").val("");
          // $(selectedCell).children("textarea").focus();
          // $("#row-" + rowId + "-col-" + colId).css("transform", "none");
          // $("#row-" + rowId + "-col-" + colId).data("clicked", false);
          // $(`#row-${rowId}-col-${colId}`).removeClass("selected");

          // rowNode.empty();
          // let rowNum = $(
          //   `<div class="rowNum" id="rowId-${totalRowsInDb}"><div class="row-name">${totalRowsInDb}</div></div>`
          // );
          // let row = $(
          //   `<div class="cell-row rowNo_` + totalRowsInDb + `"></div>`
          // );
          // dia = "";
          // shape = "nullShape";
          // for (let j = 1; j <= charsData.totalChars; j++) {
          //   let colCode = $(`.colId-${j}`).attr("id").split("-")[1];
          //   column = $(
          //     `<div class="input-cell" style="background:` +
          //       charsData.charsProperties[j].charColor +
          //       `;opacity :0.9; min-width: ` +
          //       minWidthCell +
          //       `" contenteditable="false" id = "col-${j}" data="code-${colCode}"><div contenteditable="false" class="box shapesLegend ` +
          //       shape +
          //       `"></div><textarea id="textId-` +
          //       totalRowsInDb +
          //       `-` +
          //       j +
          //       `" rows="1" class="msgBody textRow-` +
          //       totalRowsInDb +
          //       `" onInput="autoResize(this)">` +
          //       dia +
          //       `</textarea></div>`
          //   );
          //   row.append(column);
          // }
          // $(
          //   ".input-cell-container > div:nth-child(" + totalRowsInDb + ")"
          // ).after(row);

          // console.log("ROW NODE", rowNode);
          if (action == "act") {
            var allChilds = $(".input-cell-container").children();
            console.log("All Childs TOtal", totalRowsInDb);
            var rowNode = $(allChilds[rowId + 1]);
            rowNode = $(rowNode).children("#col-" + colId);
            $(rowNode).addClass("selected");
            $(rowNode).css("transform", "scale(1.1)");
            $(rowNode).children("textarea").focus();
            //   $(`#row-${rowId + 2}-col-${colId}`).addClass("selected");
            //   $(`#row-${rowId + 2}-col-${colId}`).css("transform", "scale(1.1)");
            //   $(`#row-${rowId + 2}-col-${colId}`)
            //     .children("textarea")
            //     .focus();
          } else {
            var allChilds = $(".input-cell-container").children();
            console.log("All Childs TOtal", totalRowsInDb);
            var rowNode = $(allChilds[rowId]);
            rowNode = $(rowNode).children("#col-" + colId);
            $(rowNode).addClass("selected");
            $(rowNode).css("transform", "scale(1.1)");
            $(rowNode).children("textarea").focus();
            //   $(`#row-${rowId + 1}-col-${colId}`).addClass("selected");
            //   $(`#row-${rowId + 1}-col-${colId}`).css("transform", "scale(1.1)");
            //   $(`#row-${rowId + 1}-col-${colId}`)
            //     .children("textarea")
            //     .focus();
          }
        }
      }
    };
    xhrSend.setRequestHeader(
      "Content-type",
      "application/x-www-form-urlencoded"
    );
    xhrSend.send(
      "sendAs=" +
        action +
        "&msg=" +
        msg +
        "&storyId=" +
        storyId +
        "&col=" +
        colId +
        "&row=" +
        rowId +
        "&chapter=" +
        chapter +
        "&autoFeed=" +
        aF
    );
  } else {
    document.querySelector(".errorHead").innerHTML = "Error!";
    document.querySelector(".errorText").innerHTML =
      "Please use '" + action + "' button of respected character!";
    document.querySelector(".errorBtns").style.display = "none";
    $(".loadingScreen").removeClass("model-open");
    $(".error").addClass("model-open");
  }
  // var allChilds = $(".input-cell-container").children();
  // console.log("All Childs", allChilds);
  // for (let i = 0; i < allChilds.length; i++) {
  //   console.log("Child " + i + " : " + $(allChilds[i]));
  //   var clssList = $(allChilds[i]).attr("class").split(/\s+/);
  //   console.log("Class List", clssList);
  // }
}

function formSend(ele, action, del) {
  var [rowId, colId] = getRowCol(document.querySelector(".cell-row .selected"));
  var msg;
  if (del) {
    msg = "";
  } else {
    msg = document.querySelector(".popUpMsg").value;
  }
  const charId = colId;
  send(charId, action, msg, true);
}
