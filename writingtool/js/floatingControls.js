// $(".input-cell").click(function (e) {});

var inputCellContainer = document.querySelector(".popUpMsg");
inputCellContainer.addEventListener("keydown", function (event) {
  if ($(".input-cell.selected").length) {
    var [rowOfSelectedCell, colOfSelectedCell] = getRowCol(
      document.querySelector(".cell-row .selected")
    );
    // Number 13 is the "Enter" key on the keyboard
    if (event.shiftKey && event.keyCode === 37) {
      event.preventDefault();

      if (colOfSelectedCell - 1 > 0) {
        $(`#row-${rowOfSelectedCell}-col-${colOfSelectedCell}`).removeClass(
          "selected"
        );

        $(`#row-${rowOfSelectedCell}-col-${colOfSelectedCell - 1}`).addClass(
          "selected"
        );
        if (
          charsData.charsProperties[colOfSelectedCell - 1]["charCat"] ==
          "mainChar"
        ) {
          $(".aaBtn").hide();
          $(".saBtn").hide();

          $(".actBtn").show();
          $(".ivBtn").show();
          $(".dirBtn").show();
          $(".othBtn").show();
        } else if (
          charsData.charsProperties[colOfSelectedCell - 1]["charCat"] ==
          "auxChar"
        ) {
          $(".aaBtn").show();

          $(".actBtn").hide();
          $(".ivBtn").hide();
          $(".dirBtn").hide();
          $(".othBtn").hide();
          $(".saBtn").hide();
        } else if (
          charsData.charsProperties[colOfSelectedCell - 1]["charCat"] ==
          "stuffedChar"
        ) {
          $(".saBtn").show();

          $(".actBtn").hide();
          $(".ivBtn").hide();
          $(".dirBtn").hide();
          $(".othBtn").hide();
          $(".aaBtn").hide();
        }
      }
    }
    if (event.shiftKey && event.keyCode === 38) {
      event.preventDefault();
      if (rowOfSelectedCell - 1 > 0) {
        $(`#row-${rowOfSelectedCell}-col-${colOfSelectedCell}`).removeClass(
          "selected"
        );

        $(`#row-${rowOfSelectedCell - 1}-col-${colOfSelectedCell}`).addClass(
          "selected"
        );
      }
    }
    if (event.shiftKey && event.keyCode === 39) {
      event.preventDefault();
      if (colOfSelectedCell + 1 <= charsData.totalChars) {
        $(`#row-${rowOfSelectedCell}-col-${colOfSelectedCell}`).removeClass(
          "selected"
        );

        $(`#row-${rowOfSelectedCell}-col-${colOfSelectedCell + 1}`).addClass(
          "selected"
        );
        if (
          charsData.charsProperties[colOfSelectedCell + 1]["charCat"] ==
          "mainChar"
        ) {
          $(".aaBtn").hide();
          $(".saBtn").hide();

          $(".actBtn").show();
          $(".ivBtn").show();
          $(".dirBtn").show();
          $(".othBtn").show();
        } else if (
          charsData.charsProperties[colOfSelectedCell + 1]["charCat"] ==
          "auxChar"
        ) {
          $(".aaBtn").show();

          $(".actBtn").hide();
          $(".ivBtn").hide();
          $(".dirBtn").hide();
          $(".othBtn").hide();
          $(".saBtn").hide();
        } else if (
          charsData.charsProperties[colOfSelectedCell + 1]["charCat"] ==
          "stuffedChar"
        ) {
          $(".saBtn").show();

          $(".actBtn").hide();
          $(".ivBtn").hide();
          $(".dirBtn").hide();
          $(".othBtn").hide();
          $(".aaBtn").hide();
        }
      }
    }
    if (event.shiftKey && event.keyCode === 40) {
      event.preventDefault();
      if (rowOfSelectedCell + 1 <= 1000) {
        $(`#row-${rowOfSelectedCell}-col-${colOfSelectedCell}`).removeClass(
          "selected"
        );

        $(`#row-${rowOfSelectedCell + 1}-col-${colOfSelectedCell}`).addClass(
          "selected"
        );
      }
    }
  }
});
