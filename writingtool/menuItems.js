const viewBtn = document.querySelector(".menu-view");
const fileBtn = document.querySelector(".menu-file");
const twoView = document.querySelector(".twoView");
const fourView = document.querySelector(".fourView");
const sixView = document.querySelector(".sixView");
const configurations = document.querySelector(".configurations");
const saveBtn = document.querySelector(".save");
const saveAsBtn = document.querySelector(".saveAs");
const loadOldVersBtn = document.querySelector(".loadOlderVersion");

const shareBtn = document.querySelector(".menu-share");

const insertBtn = document.querySelector(".menu-insert");
const addNewChar = document.querySelector(".addNewChar");

viewBtn.onclick = (e) => {
  e.stopPropagation();

  if (document.querySelector(".writersViewList").style.display == "none") {
    document.querySelector(".writersViewList").style.display = "block";
    document.querySelector(".fileViewList").style.display = "none";
    document.querySelector(".insertViewList").style.display = "none";
    // $(".custom-model-main").addClass("model-open");
  } else {
    document.querySelector(".writersViewList").style.display = "none";
    // hideDropDown();
  }
};
fileBtn.onclick = (e) => {
  e.stopPropagation();

  if (document.querySelector(".fileViewList").style.display == "none") {
    document.querySelector(".fileViewList").style.display = "block";
    document.querySelector(".writersViewList").style.display = "none";
    document.querySelector(".insertViewList").style.display = "none";
    // $(".custom-model-main").addClass("model-open");
  } else {
    document.querySelector(".fileViewList").style.display = "none";
    // hideDropDown();
  }
};
twoView.onclick = () => {
  var clientWidth = document.querySelector(".data-container").clientWidth;
  document
    .querySelectorAll(".column-name")
    .forEach((elem) => (elem.style.minWidth = clientWidth / 2 + "px"));
  document
    .querySelectorAll(".input-cell")
    .forEach((elem) => (elem.style.minWidth = clientWidth / 2 + "px"));
  document
    .querySelectorAll(".firstRow")
    .forEach((elem) => (elem.style.minWidth = clientWidth / 2 + "px"));
  document
    .querySelectorAll(".firstRow")
    .forEach((elem) => (elem.style.height = "100px"));

  document.querySelector(".writersViewList").style.display = "none";
  viewEnabled = 2;
  minWidthCell = clientWidth / 2 + "px";
};
fourView.onclick = () => {
  var clientWidth = document.querySelector(".data-container").clientWidth;
  document
    .querySelectorAll(".column-name")
    .forEach((elem) => (elem.style.minWidth = clientWidth / 4 + "px"));
  document
    .querySelectorAll(".input-cell")
    .forEach((elem) => (elem.style.minWidth = clientWidth / 4 + "px"));
  document
    .querySelectorAll(".firstRow")
    .forEach((elem) => (elem.style.minWidth = clientWidth / 4 + "px"));
  document

    .querySelectorAll(".firstRow")
    .forEach((elem) => (elem.style.height = "130px"));
  document.querySelector(".writersViewList").style.display = "none";
  // console.log(document.querySelector(".data-container").clientHeight);
  // document.querySelector(".data-container").clientHeight =
  //   document.querySelector(".data-container").clientHeight - 100 + "px";
  viewEnabled = 4;
  minWidthCell = clientWidth / 4 + "px";
};
sixView.onclick = () => {
  var clientWidth = document.querySelector(".data-container").clientWidth;
  document
    .querySelectorAll(".firstRow")
    .forEach((elem) => (elem.style.height = "130px"));
  document
    .querySelectorAll(".column-name")
    .forEach((elem) => (elem.style.minWidth = clientWidth / 6 + "px"));
  document
    .querySelectorAll(".input-cell")
    .forEach((elem) => (elem.style.minWidth = clientWidth / 6 + "px"));
  document
    .querySelectorAll(".firstRow")
    .forEach((elem) => (elem.style.minWidth = clientWidth / 6 + "px"));

  document.querySelector(".writersViewList").style.display = "none";
  viewEnabled = 6;
  minWidthCell = clientWidth / 6 + "px";
};

function insertBtnDropDown(e) {
  console.log("Executed");
  if (document.querySelector(".insertViewList").style.display == "none") {
    console.log("We are here!");
    document.querySelector(".insertViewList").style.display = "block";
    document.querySelector(".writersViewList").style.display = "none";
    document.querySelector(".fileViewList").style.display = "none";
  } else {
    document.querySelector(".insertViewList").style.display = "none";
  }
}
insertBtn.onclick = (e) => {
  // console.log("Hello");
  e.stopPropagation();

  if (document.querySelector(".insertViewList").style.display == "none") {
    console.log("We are here!");
    document.querySelector(".insertViewList").style.display = "block";
    document.querySelector(".writersViewList").style.display = "none";
    document.querySelector(".fileViewList").style.display = "none";
    // $(".custom-model-main").addClass("model-open");
  } else {
    document.querySelector(".insertViewList").style.display = "none";
  }
};
addNewChar.onclick = () => {
  console.log("Hello");
  $(".addChar").addClass("model-open");
  document.querySelector(".insertViewList").style.display = "none";
};
shareBtn.onclick = () => {
  console.log("Hello");
  $(".shareStory").addClass("model-open");
  document.querySelector(".insertViewList").style.display = "none";
};
// $(".menu-view").on("click", function () {
//   console.log("View Btn Clicked!");
//   // var writersViewList =
//   //   ;

// });
$(".close-btn,.bg-overlay").click(function () {
  // console.log("Removed!");
  document.querySelector(".writersViewList").style.display = "none";
  $(".addChar").removeClass("model-open");
  $(".clearSheetPopUp").removeClass("model-open");
  $(".error").removeClass("model-open");
  $(".config").removeClass("model-open");
  $(".shareStory").removeClass("model-open");
  $(".renameSheetPopUp").removeClass("model-open");
  $(".loadOlderVersions").removeClass("model-open");
  $(".saveAsPopUp").removeClass("model-open");
  $(".sharingConfirmations").removeClass("model-open");

  const removingUnneccClasses = document
    .querySelector(".popUpReplace")
    .className.split(/\s+/);
  document
    .querySelector(".popUpReplace")
    .classList.remove(
      removingUnneccClasses[1],
      removingUnneccClasses[2],
      removingUnneccClasses[3],
      removingUnneccClasses[4]
    );
  //you need to remove classes of replace button
});
$("body").click(function () {
  // do something here
  if (
    document.querySelector(".writersViewList").style.display != "none" ||
    document.querySelector(".fileViewList").style.display != "none" ||
    document.querySelector(".insertViewList").style.display != "none"
  ) {
    document.querySelector(".writersViewList").style.display = "none";
    document.querySelector(".fileViewList").style.display = "none";
    document.querySelector(".insertViewList").style.display = "none";
  }
});
configurations.onclick = () => {
  document.querySelector(".writersViewList").style.display = "none";
  $(".config").addClass("model-open");
};
saveBtn.onclick = () => {
  document.querySelector(".fileViewList").style.display = "none";
  save(false, true, "save");
};
saveAsBtn.onclick = () => {
  document.querySelector(".fileViewList").style.display = "none";
  $(".saveAsPopUp").addClass("model-open");
};
loadOldVersBtn.onclick = () => {
  document.querySelector(".fileViewList").style.display = "none";
  $(".loadOlderVersions").addClass("model-open");
  loadOldVersions();
};
