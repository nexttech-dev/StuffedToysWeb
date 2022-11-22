function formDeleteRowBtn(params) {
  var [rowId, colId] = getRowCol(document.querySelector(".cell-row .selected"));
  let xhrInsertRow = new XMLHttpRequest();
  xhrInsertRow.open("POST", "./php/formDeleteRowBtn.php", true);
  xhrInsertRow.onload = () => {
    if (xhrInsertRow.readyState === XMLHttpRequest.DONE) {
      if (xhrInsertRow.status === 200) {
        // var jsonInsertRow = JSON.parse(xhrInsertRow.responseText);
        // console.log("JSON DIR", jsonInsertRow);

        console.log("JSON DIR", xhrInsertRow.responseText);
      }
    }
  };
  xhrInsertRow.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  xhrInsertRow.send(
    "storyId=" +
      storyId +
      "&charCode=" +
      colId +
      "&chapter=" +
      chapter +
      "&lineNumber=" +
      rowId
  );
}
function formFindBtn(e) {
  // e.stopPropagation();

  const [rowId, colId] = getRowCol(
    document.querySelector(".cell-row .selected")
  );
  const msg = document.querySelector(".popUpMsg").value;
  //   const charId = colId;
  //   console.log("Full Sheet Data", fullSheetData);
  //   console.log("Full Sheet Data", Object.keys(fullSheetData).length);

  // console.log("Full Sheet Data", rowId);

  let exactMatches = [];
  let partialMatches = [];
  let countEM = 0;
  let countPM = 0;
  let exactMatchesBtn = false;
  let partialMatchesBtn = false;
  for (let i = 1; i <= Object.keys(sheetData.dialogues).length; i++) {
    for (let j = 1; j <= charsData.totalChars; j++) {
      if (
        sheetData.dialogues[i][j] &&
        sheetData.dialogues[i][j]["details"]["dialogue"]
      ) {
        if (
          sheetData.dialogues[i][j]["details"]["dialogue"].toLowerCase() ==
          msg.toLowerCase()
        ) {
          exactMatches.push({ row: i, col: j });
        } else {
          let partialRslSearch = partialSearch(
            msg.toLowerCase(),
            sheetData.dialogues[i][j]["details"]["dialogue"].toLowerCase()
          );
          if (partialRslSearch) {
            partialMatches.push({ row: i, col: j });
          }
        }
      }
    }
  }
  console.log("Exact Matches", exactMatches);
  console.log("Partial Matches", partialMatches);

  // for (let i = 0; i < exactMatches.length; i++) {
  //   document
  //     .getElementById(
  //       "row-" + exactMatches[i]["row"] + "-col-" + exactMatches[i]["col"]
  //     )
  //     .classList.add("exactMatches");
  // }
  // for (let i = 0; i < partialMatches.length; i++) {
  //   document
  //     .getElementById(
  //       "row-" + partialMatches[i]["row"] + "-col-" + partialMatches[i]["col"]
  //     )
  //     .classList.add("partialMatches");
  // }

  $(".exactSearch").click(function (e) {
    exactMatchesBtn = true;
    partialMatchesBtn = false;
    var elements = document.querySelectorAll("body .partialMatches");

    if (elements.length >> 0) {
      for (let i = 0; i < elements.length; i++) {
        elements[i].classList.remove("partialMatches");
      }
    }
    for (let i = 0; i < exactMatches.length; i++) {
      document
        .getElementById(
          "row-" + exactMatches[i]["row"] + "-col-" + exactMatches[i]["col"]
        )
        .classList.add("exactMatches");
    }
  });
  $(".partialSearch").click(function (e) {
    partialMatchesBtn = true;
    exactMatchesBtn = false;
    var elements = document.querySelectorAll("body .exactMatches");

    if (elements.length >> 0) {
      for (let i = 0; i < elements.length; i++) {
        elements[i].classList.remove("exactMatches");
      }
    }
    for (let i = 0; i < partialMatches.length; i++) {
      document
        .getElementById(
          "row-" + partialMatches[i]["row"] + "-col-" + partialMatches[i]["col"]
        )
        .classList.add("partialMatches");
    }
  });

  $(".arrowPrev").click(function (e) {
    var elementsExactMatches = document.querySelectorAll("body .exactMatches");

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

    if (exactMatchesBtn && countEM >> 0) {
      countEM--;
      document
        .getElementById(
          "row-" +
            exactMatches[countEM]["row"] +
            "-col-" +
            exactMatches[countEM]["col"]
        )
        .classList.add("exactMatches");
      document
        .getElementById(
          "row-" +
            exactMatches[countEM]["row"] +
            "-col-" +
            exactMatches[countEM]["col"]
        )
        .scrollIntoView();
    } else if (partialMatchesBtn && countPM >> 0) {
      countPM--;
      document
        .getElementById(
          "row-" +
            partialMatches[countPM]["row"] +
            "-col-" +
            partialMatches[countPM]["col"]
        )
        .classList.add("partialMatches");
      document
        .getElementById(
          "row-" +
            partialMatches[countPM]["row"] +
            "-col-" +
            partialMatches[countPM]["col"]
        )
        .scrollIntoView();
    } else if (countEM >> 0) {
      countEM--;
      document
        .getElementById(
          "row-" +
            exactMatches[countEM]["row"] +
            "-col-" +
            exactMatches[countEM]["col"]
        )
        .classList.add("exactMatches");

      document
        .getElementById(
          "row-" +
            exactMatches[countEM]["row"] +
            "-col-" +
            exactMatches[countEM]["col"]
        )
        .scrollIntoView();
    }
  });

  $(".arrowNext").click(function (e) {
    var elementsExactMatches = document.querySelectorAll("body .exactMatches");

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

    if (exactMatchesBtn && countEM < exactMatches.length) {
      document
        .getElementById(
          "row-" +
            exactMatches[countEM]["row"] +
            "-col-" +
            exactMatches[countEM]["col"]
        )
        .classList.add("exactMatches");
      document
        .getElementById(
          "row-" +
            exactMatches[countEM]["row"] +
            "-col-" +
            exactMatches[countEM]["col"]
        )
        .scrollIntoView();
      countEM++;
    } else if (partialMatchesBtn && countPM < partialMatches.length) {
      console.log("countPM", countPM);
      document
        .getElementById(
          "row-" +
            partialMatches[countPM]["row"] +
            "-col-" +
            partialMatches[countPM]["col"]
        )
        .classList.add("partialMatches");
      document
        .getElementById(
          "row-" +
            partialMatches[countPM]["row"] +
            "-col-" +
            partialMatches[countPM]["col"]
        )
        .scrollIntoView();
      countPM++;
    } else if (countEM < exactMatches.length) {
      document
        .getElementById(
          "row-" +
            exactMatches[countEM]["row"] +
            "-col-" +
            exactMatches[countEM]["col"]
        )
        .classList.add("exactMatches");
      document
        .getElementById(
          "row-" +
            exactMatches[countEM]["row"] +
            "-col-" +
            exactMatches[countEM]["col"]
        )
        .scrollIntoView();
      countEM++;
    } else {
      console.log("Do NOthing!");
    }
  });
  document.querySelector(".searchOptions").style.display = "block";

  // $("body").click(function (e) {
  //   // do something here
  //   e.preventDefault();
  //   if (partialMatches.length >> 0) {
  //     for (let i = 0; i < partialMatches.length; i++) {
  //       document
  //         .getElementById(
  //           "row-" +
  //             partialMatches[i]["row"] +
  //             "-col-" +
  //             partialMatches[i]["col"]
  //         )
  //         .classList.remove("partialMatches");
  //     }
  //     for (let i = 0; i < exactMatches.length; i++) {
  //       document
  //         .getElementById(
  //           "row-" + exactMatches[i]["row"] + "-col-" + exactMatches[i]["col"]
  //         )
  //         .classList.remove("exactMatches");
  //     }
  //   }
  //   partialMatches = null;
  //   exactMatches = null;
  // });
}

function partialSearch(word, str) {
  return str.split(" ").some(function (w) {
    return w === word;
  });
}

function formInsertRowBtn(params) {
  var [rowId, colId] = getRowCol(document.querySelector(".cell-row .selected"));
  let xhrInsertRow = new XMLHttpRequest();
  xhrInsertRow.open("POST", "./php/formInsertRowBtn.php", true);
  xhrInsertRow.onload = () => {
    if (xhrInsertRow.readyState === XMLHttpRequest.DONE) {
      if (xhrInsertRow.status === 200) {
        // var jsonInsertRow = JSON.parse(xhrInsertRow.responseText);
        // console.log("JSON DIR", jsonInsertRow);

        console.log("JSON DIR", xhrInsertRow.responseText);
      }
    }
  };
  xhrInsertRow.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  xhrInsertRow.send(
    "storyId=" +
      storyId +
      "&charCode=" +
      colId +
      "&chapter=" +
      chapter +
      "&lineNumber=" +
      rowId
  );
}
function formRefreshBtn(params) {
  const [rowId, colId] = getRowCol(
    document.querySelector(".cell-row .selected")
  );
  if (sheetData.dialogues[rowId]) {
    cols = Object.keys(sheetData.dialogues[rowId]);
    var flag = true;
    cols.forEach(function (item, index) {
      var charCat = sheetData.dialogues[rowId][item].details.category;
      if (charCat == "human" || charCat == "sa" || charCat == "aux") {
        document.querySelector(".popUpMsg").value =
          sheetData.dialogues[rowId][item].details.dialogue;
        flag = false;
      }
    });

    if (flag && sheetData.dialogues[rowId][colId]) {
      document.querySelector(".popUpMsg").value =
        sheetData.dialogues[rowId][colId].details.dialogue;
    }
  }
}
function formSplitBtn(params) {
  let secondHalf = highlightedText(0);
  console.log("Second Hald", secondHalf);
  var [rowId, colId] = getRowCol(document.querySelector(".cell-row .selected"));
  if (
    sheetData.dialogues[rowId][colId] &&
    sheetData.dialogues[rowId][colId].details.category == "act" &&
    secondHalf &&
    rowId &&
    colId
  ) {
    let originalDialogue = sheetData.dialogues[rowId][colId].details.dialogue;
    console.log("Original ", originalDialogue.includes(secondHalf));
    if (originalDialogue.includes(secondHalf)) {
      let firstHalf = originalDialogue.replace(secondHalf, "");
      let xhrInsertRow = new XMLHttpRequest();
      xhrInsertRow.open("POST", "./php/formSplitBtn.php", true);
      xhrInsertRow.onload = () => {
        if (xhrInsertRow.readyState === XMLHttpRequest.DONE) {
          if (xhrInsertRow.status === 200) {
            // var jsonInsertRow = JSON.parse(xhrInsertRow.responseText);
            console.log("JSON DIR", xhrInsertRow.responseText);
            // if (jsonInsertRow.result) {
            //   triggerNewLine.row = rowId + 1;
            //   triggerNewLine.trigger = true;
            //   console.log("New row added!");
            // }
          }
        }
      };
      xhrInsertRow.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded"
      );
      xhrInsertRow.send(
        "storyId=" +
          storyId +
          "&charCode=" +
          colId +
          "&firstHalf=" +
          firstHalf +
          "&secondHalf=" +
          secondHalf +
          "&chapter=" +
          chapter +
          "&lineNumber=" +
          rowId
      );
    } else {
      console.log("Nothign");
    }
  }
}

function highlightedText(isStart) {
  var text = null;
  var parentEl = null,
    sel;
  if (window.getSelection) {
    sel = window.getSelection();
    if (sel.rangeCount) {
      parentEl = sel.getRangeAt(0).commonAncestorContainer;
      if (parentEl.nodeType != 1) {
        parentEl = parentEl.parentNode;
      }
    }
  } else if ((sel = document.selection) && sel.type != "Control") {
    parentEl = sel.createRange().parentElement();
  }

  classNameContainingSelectedText = parentEl.className.split(" ");
  if (classNameContainingSelectedText[0] == "navigation") {
    text = window.getSelection().toString();
  }
  return text;
}
