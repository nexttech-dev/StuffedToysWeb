let xhrOnLoad = new XMLHttpRequest();
xhrOnLoad.open("POST", "./php/onLoad.php", true);
xhrOnLoad.onload = () => {
  if (xhrOnLoad.readyState === XMLHttpRequest.DONE) {
    if (xhrOnLoad.status === 200) {
      let data = xhrOnLoad.response;
      var jsonOnLoad = JSON.parse(xhrOnLoad.responseText);
      if (jsonOnLoad.result) {
        console.log("Website Loaded!");
        ////////////////////////////////////////////////////////////////////
        ////////////Adjusting the view acc to total chars - START///////////
        ///////////////////////////////////////////////////////////////////
        var clientWidth = document.querySelector(".data-container").clientWidth;
        clientWid = clientWidth;
        // if (charsData.totalChars <= 6) {
        //   var clientWidth =
        //     document.querySelector(".data-container").clientWidth;
        //   console.log("Executedsdd", clientWidth);

        //   document
        //     .querySelectorAll(".column-name")
        //     .forEach(
        //       (elem) =>
        //         (elem.style.minWidth =
        //           clientWidth / charsData.totalChars + "px")
        //     );
        //   document
        //     .querySelectorAll(".input-cell")
        //     .forEach(
        //       (elem) =>
        //         (elem.style.minWidth =
        //           clientWidth / charsData.totalChars + "px")
        //     );
        //   if (charsData.totalChars <= 2) {
        //     document
        //       .querySelectorAll(".firstRow")
        //       .forEach((elem) => (elem.style.height = "100px"));
        //   } else if (charsData.totalChars > 4) {
        //     document
        //       .querySelectorAll(".firstRow")
        //       .forEach((elem) => (elem.style.height = "150px"));
        //   }
        // }

        /////////////////////////////////////////////////////////////////////
        ////////////Adjusting the view acc to total chars - END/////////////
        ///////////////////////////////////////////////////////////////////
      }
    }
  }
};
xhrOnLoad.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhrOnLoad.send("storyId=" + storyId);

function pushingDataIntoCells(data, chars) {
  if (data) {
    rows = Object.keys(data);
    var totalRows = rows.length;

    rows.forEach((row, index) => {
      cols = Object.keys(data[row]);
      cols.forEach((col, index) => {
        if (
          data[row][col]["details"]["category"] == "human" ||
          data[row][col]["details"]["category"] == "sa" ||
          data[row][col]["details"]["category"] == "aux"
        ) {
          var shape = data[row][col]["details"]["category"] + "Emo";
          for (let i = 1; i <= chars; i++) {
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
          if (
            data[row][col]["details"]["dialogue"].length > 40 ||
            /\r|\n/.exec(data[row][col]["details"]["dialogue"])
          ) {
            // console.log("Executing OnInput");
            // $(inputCell).trigger("input");
            document.querySelector(".textRow-" + row).oninput();
          }
        } else {
          var shape = data[row][col]["details"]["category"] + "Emo";
          var inputRow = $("#textId-" + row + "-" + col);
          var inputCellShape = $(inputRow).parent();
          var inputCellDia = data[row][col]["details"]["dialogue"];
          if (data[row][col]["details"]["dialogue"].length >> 0) {
            inputCellDia =
              charsData.charsProperties[col]["charName"].substring(0, 4) +
              " : " +
              data[row][col]["details"]["dialogue"];
            $(inputCellShape)
              .children(".shapesLegend")
              .removeClass("nullShape");
            $(inputCellShape).children(".shapesLegend").addClass(shape);
          }
          inputRow.val(inputCellDia);
          if (
            data[row][col]["details"]["dialogue"].length > 50 ||
            /\r|\n/.exec(data[row][col]["details"]["dialogue"])
          ) {
            $(inputRow).trigger("input");
          }
        }
      });
    });
  }
}
