let prevDialogues, prevRowIds, prevRowNos;
let count = false;
let prevDiaFlag = false;
let totalRowsInDb = 0;
setInterval(() => {
  let xhrUpdate = new XMLHttpRequest();
  xhrUpdate.open("POST", "./php/loadSheet.php", true);
  xhrUpdate.onload = () => {
    if (xhrUpdate.readyState === XMLHttpRequest.DONE) {
      if (xhrUpdate.status === 200) {
        let data = JSON.parse(xhrUpdate.responseText);
        let newDialogues = JSON.parse(data.dialogues);
        let newRowIds = data.rowIds;
        let newRowNos = data.rowNos;
        sheetData.dialogues = newDialogues;
        sheetData.totalRows = data.totalRows;
        sheetData.sheetName = data.storyName;
        // console.log(newDialogues);
        if (
          prevDiaFlag &&
          JSON.stringify(prevDialogues) != JSON.stringify(newDialogues)
        ) {
          // console.log("Executed!");
          deepComparison(
            prevDialogues,
            newDialogues,
            prevRowIds,
            newRowIds,
            prevRowNos,
            newRowNos
          );
        }

        prevDiaFlag = true;
        count = true;
        prevDialogues = newDialogues;
        prevRowIds = newRowIds;
        prevRowNos = newRowNos;
        totalRowsInDb = JSON.parse(data.totalRows);
        // console.log(totalRowsInDb);
      }
    }
  };
  xhrUpdate.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  xhrUpdate.send("storyId=" + storyId + "&chapter=" + chapter);
}, 1000);

function arr_diff(a1, a2) {
  var a = [],
    diff = [];

  for (var i = 0; i < a1.length; i++) {
    a[a1[i]] = true;
  }

  for (var i = 0; i < a2.length; i++) {
    if (a[a2[i]]) {
      delete a[a2[i]];
    } else {
      a[a2[i]] = true;
    }
  }

  for (var k in a) {
    diff.push(k);
  }

  return diff;
}
function deepComparison(
  prevDialogues,
  newDialogues,
  pRowIds,
  nRowIds,
  pRowNos,
  nRowNos
) {
  let newLineIds = Object.keys(nRowNos);
  let prevLineIds = Object.keys(pRowNos);
  // rowMatch = [];
  // let prevTotalDias = prevDialogues.length;
  // let newTotalDias = newDialogues.length;

  var prevLength = 0;
  var newLength = 0;
  prevLength = Object.keys(prevLineIds).length;
  newLength = Object.keys(newLineIds).length;

  if (prevLength > newLength) {
    let updateRows = {};
    prevLineIds.forEach((ele, index) => {
      if (!newLineIds.includes(ele)) {
        updateRows[pRowNos[ele]] = ele;
      }
    });
    deletingNode(Object.keys(updateRows));
    reAssigningRowNums();

    console.log("Modified Rows", updateRows);
  } else if (prevLength == newLength) {
    let updateRows = {};
    newLineIds.forEach((ele, index) => {
      if (!prevLineIds.includes(ele)) {
        updateRows[nRowNos[ele]] = ele;
      }
    });

    deletingNode(Object.keys(updateRows));
    insertingNode(Object.keys(updateRows), newDialogues);
  } else {
    let updateRows = {};
    newLineIds.forEach((ele, index) => {
      if (!prevLineIds.includes(ele)) {
        updateRows[nRowNos[ele]] = ele;
      }
    });

    let linesToModify = Object.keys(updateRows);
    linesToModify = linesToModify.sort();
    if (linesToModify[linesToModify.length - 1] > prevLength) {
      console.log(
        "Modified Rows Done",
        updateRows,
        linesToModify[linesToModify.length - 1],
        newLength
      );
      deletingNode(Object.keys(updateRows));
      insertingNode(Object.keys(updateRows), newDialogues);
    } else {
      let toDelete = Object.keys(updateRows);
      toDelete = toDelete.sort();
      toDelete.pop();
      let toInsert = Object.keys(updateRows);
      toInsert = toInsert.sort();
      console.log("Modified Rows hehe", updateRows, toDelete, toInsert);
      deletingNode(toDelete);
      insertingNode(toInsert, newDialogues);
      reAssigningRowNums();
    }
  }

  // var diff = arr_diff(pRowIds, nRowIds);
  // let added = [];
  // let deleted = [];
  // diff.forEach((element, index) => {
  //   if (nRowNos[element]) {
  //     added.push(parseInt(nRowNos[element]));
  //   } else if (pRowNos[element]) {
  //     console.log("P rows", pRowNos);
  //     deleted.push(parseInt(pRowNos[element]));
  //   }
  // });
  // added = added.sort();
  // deleted = deleted.sort();
  // console.log("Added", added);
  // console.log("Deleted", deleted);
  // console.log("Diff", diff);
  // console.log("Deleted Leng", deleted.length);
  // // var rows = null;
  // // if (prevLength == 0 && newLength > prevLength) {
  // //   insertingFirstNode(newDialogues, added);
  // // } else
  // if (prevLength > newLength) {
  //   rows = -1;
  //   if (deleted.length >> 0 && added.length >> 0) {
  //     deletingNode(deleted, added, rows);
  //     insertingNode(added, newDialogues, deleted, rows);
  //   } else if (deleted.length >> 0) {
  //     deletingNode(deleted, added, rows);
  //   }
  // } else if (prevLength < newLength) {
  //   rows = 1;
  //   if (deleted.length == 0 && added.length >> 1) {
  //     deleted.push(added[0]);
  //   }
  //   if (deleted.length) {
  //     deletingNode(deleted, added, rows);
  //   }
  //   if (added.length) {
  //     insertingNode(added, newDialogues, deleted, rows);
  //   }
  // } else {
  //   if (deleted.length) {
  //     deletingNode(deleted, added, rows);
  //   }
  //   if (added.length) {
  //     insertingNode(added, newDialogues, deleted, rows);
  //   }
  //   rows = 0;
  // }
  // reAssigningRowNums();
}

function updatingNodes(added, deleted, dialogues, rows) {
  if (rows == -1) {
    if (deleted.length >> 0) {
      deletingNode(deleted, added, rows);
    }
  }
  if (added.length >> 0) {
    insertingNode(added, dialogues, deleted, rows);
  }
  reAssigningRowNums();
}

function deletingNode(deleted) {
  // console.log("Deleted Rows", deleted);
  var allChilds = $(".input-cell-container").children();
  deleted.forEach((element, index) => {
    $(allChilds[element - 1]).remove();
  });
}

function insertingNode(added, dialogues) {
  added.forEach((element, index) => {
    var totalWordsCountRow = [];
    var totalWordsCountCell = [];

    element = parseInt(element);
    let rowNum = $(
      `<div class="rowNum" id="rowId-${element}"><div class="row-name">${element}</div></div>`
    );
    let row = $(`<div class="cell-row rowNo_` + element + `"></div>`);
    row.append(rowNum);
    for (let j = 1; j <= charsData.totalChars; j++) {
      let column;
      if (dialogues[element][j]) {
        if (dialogues[element][j]["details"]["category"] == "human") {
          row.empty();
          row.append(rowNum);
          totalWordsCountCell = [];

          dia =
            charsData.charsProperties[j]["charName"].substring(0, 4) +
            " : " +
            dialogues[element][j]["details"]["dialogue"];
          // totalWordsCount.push()
          shape = dialogues[element][j]["details"]["category"] + "Emo";
          for (let j = 1; j <= charsData.totalChars; j++) {
            let colCode = $(`.colId-${j}`).attr("id").split("-")[1];
            column = $(
              `<div class="input-cell" style="background:` +
                charsData.charsProperties[j].charColor +
                `;opacity :0.9; min-width: ` +
                minWidthCell +
                `" contenteditable="false" id = "col-${j}" data="code-${colCode}"><div contenteditable="false" class="box shapesLegend ` +
                shape +
                `"></div><textarea id="textId-` +
                element +
                `-` +
                j +
                `" rows="1" class="msgBody textRow-` +
                element +
                `" onInput="autoResize(this)">` +
                dia +
                `</textarea></div>`
            );

            row.append(column);
          }
          totalWordsCountRow.push(dia.length);
          break;
        } else if (
          dialogues[element][j]["details"]["category"] == "sa" ||
          dialogues[element][j]["details"]["category"] == "aux"
        ) {
          row.empty();
          row.append(rowNum);
          totalWordsCountCell = [];

          dia =
            charsData.charsProperties[j]["charName"].substring(0, 4) +
            " : " +
            dialogues[element][j]["details"]["dialogue"];
          shape = dialogues[element][j]["details"]["category"] + "Emo";
          for (let j = 1; j <= charsData.totalChars; j++) {
            let colCode = $(`.colId-${j}`).attr("id").split("-")[1];
            column = $(
              `<div class="input-cell" style="background:` +
                charsData.charsProperties[j].charColor +
                `;opacity :0.9; min-width: ` +
                minWidthCell +
                `" contenteditable="false" id = "col-${j}" data="code-${colCode}"><div contenteditable="false" class="box shapesLegend ` +
                shape +
                `"></div><textarea id="textId-` +
                element +
                `-` +
                j +
                `" rows="1" class="msgBody textRow-` +
                element +
                `" onInput="autoResize(this)">` +
                dia +
                `</textarea></div>`
            );
            row.append(column);
          }
          totalWordsCountRow.push(dia.length);
          break;
        } else {
          dia = dialogues[element][j]["details"]["dialogue"];
          if (dia.length >> 0) {
            dia =
              charsData.charsProperties[j]["charName"].substring(0, 4) +
              " : " +
              dia;
            shape = dialogues[element][j]["details"]["category"] + "Emo";
          } else {
            shape = "nullShape";
          }
          let colCode = $(`.colId-${j}`).attr("id").split("-")[1];
          column = $(
            `<div class="input-cell" style="background:` +
              charsData.charsProperties[j].charColor +
              `;opacity :0.9; min-width: ` +
              minWidthCell +
              `" contenteditable="false" id = "col-${j}" data="code-${colCode}"><div contenteditable="false" class="box shapesLegend ` +
              shape +
              `"></div><textarea id="textId-` +
              element +
              `-` +
              j +
              `" rows="1" class="msgBody textRow-` +
              element +
              `" onInput="autoResize(this)">` +
              dia +
              `</textarea></div>`
          );
          totalWordsCountCell[dia.length] = { row: element, col: j };
        }
      } else if (Object.keys(dialogues[element]).length == 0) {
        // console.log("Its Empty");
        row.empty();
        row.append(rowNum);
        totalWordsCountCell = [];

        dia = "";
        shape = "nullShape";
        for (let j = 1; j <= charsData.totalChars; j++) {
          let colCode = $(`.colId-${j}`).attr("id").split("-")[1];
          column = $(
            `<div class="input-cell" style="background:` +
              charsData.charsProperties[j].charColor +
              `;opacity :0.9; min-width: ` +
              minWidthCell +
              `" contenteditable="false" id = "col-${j}" data="code-${colCode}"><div contenteditable="false" class="box shapesLegend ` +
              shape +
              `"></div><textarea id="textId-` +
              element +
              `-` +
              j +
              `" rows="1" class="msgBody textRow-` +
              element +
              `" onInput="autoResize(this)">` +
              dia +
              `</textarea></div>`
          );
          row.append(column);
        }
        break;
      } else {
        var dia = "";
        var shape = "nullShape";
        let colCode = $(`.colId-${j}`).attr("id").split("-")[1];
        column = $(
          `<div class="input-cell" style="background:` +
            charsData.charsProperties[j].charColor +
            `;opacity :0.9; min-width: ` +
            minWidthCell +
            `" contenteditable="false" id = "col-${j}" data="code-${colCode}"><div contenteditable="false" class="box shapesLegend nullShape"></div><textarea id="textId-` +
            element +
            `-` +
            j +
            `" rows="1" class="msgBody textRow-` +
            element +
            `" onInput="autoResize(this)"></textarea></div>`
        );
      }
      row.append(column);
    }
    if (element - 1 == 0) {
      $(".input-cell-container").prepend(row);
    } else {
      $(".input-cell-container > div:nth-child(" + (element - 1) + ")").after(
        row
      );
    }

    if (totalWordsCountRow.length != 0) {
      // console.log("totalWordsCountRow[0]", totalWordsCountRow[0]);
      if (totalWordsCountRow[0] > 50) {
        var textAreaToRealign = $(row).find("#col-1");
        textAreaToRealign = $(textAreaToRealign).find("textarea");
        // console.log("ROW TO ALIGN ", $(row).find("#col-1"), textAreaToRealign);
        $(textAreaToRealign).trigger("input");
      } else {
        // console.log("Nothing TO Change");
      }
    }
    if (totalWordsCountCell.length != 0) {
      var allCounts = Object.keys(totalWordsCountCell);
      var biggestCount = allCounts.max();
      var textAreaToRealign = $(
        "#textId-" +
          totalWordsCountCell[biggestCount]["row"] +
          "-" +
          totalWordsCountCell[biggestCount]["col"]
      );
      $(textAreaToRealign).trigger("input");
      console.log(totalWordsCountCell[biggestCount], textAreaToRealign);
    }
  });
}

function reAssigningRowNums() {
  var allChilds = $(".input-cell-container").children();
  for (let i = 0; i < allChilds.length - 1; i++) {
    var rowNode = $(allChilds[i]);
    var childNode = $(rowNode).children()[0];
    $(childNode).attr("id", "rowId-" + (i + 1));
    var rowNum = $(childNode).children()[0];
    $(rowNum).html(i + 1);
  }
}

function insertingFirstNode(data, rows) {
  rows = Object.keys(data);
  rows.forEach((row, index) => {
    cols = Object.keys(data[row]);
    cols.forEach((col, index) => {
      if (
        data[row][col]["details"]["category"] == "human" ||
        data[row][col]["details"]["category"] == "sa" ||
        data[row][col]["details"]["category"] == "aux"
      ) {
        var shape = data[row][col]["details"]["category"] + "Emo";
        for (let i = 1; i <= charsData.totalChars; i++) {
          var inputCell = $("#textId-" + row + "-" + i);
          var inputCellShape = $(inputCell).parent();
          if (data[row][col]["details"]["dialogue"].length >> 0) {
            $(inputCellShape)
              .children(".shapesLegend")
              .removeClass("nullShape");
            $(inputCellShape).children(".shapesLegend").addClass(shape);
          }
          var inputCellDia =
            charsData.charsProperties[col]["charName"].substring(0, 4) +
            " : " +
            data[row][col]["details"]["dialogue"];
          inputCell.val(inputCellDia);
        }
        if (data[row][col]["details"]["dialogue"].length > 40) {
          document.querySelector(".textRow-" + row).oninput();
        }
      } else {
        var shape = data[row][col]["details"]["category"] + "Emo";
        var inputRow = $("#textId-" + row + "-" + col);
        var inputCellShape = $(inputRow).parent();
        var inputCellDia = data[row][col]["details"]["dialogue"];
        if (data[row][col]["details"]["dialogue"].length >> 0) {
          $(inputCellShape).children(".shapesLegend").removeClass("nullShape");
          $(inputCellShape).children(".shapesLegend").addClass(shape);
          inputCellDia =
            charsData.charsProperties[col]["charName"].substring(0, 4) +
            " : " +
            data[row][col]["details"]["dialogue"];
        }
        inputRow.val(inputCellDia);
        if (data[row][col]["details"]["dialogue"].length > 50) {
          $(inputRow).trigger("input");
        }
      }
    });
  });
}
