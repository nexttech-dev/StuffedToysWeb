function audioRecordingMode() {
  console.log("Button Triggered!");
  let currentUrl = window.location.href;
  console.log(currentUrl);
  window.location.href = currentUrl + "&rec=active";
}

$(".cellRec").click(function () {
  $(".cellRecPanel").animate(
    {
      width: "90%",
      height: "60%",
    },
    {
      duration: 1500,
      complete: function () {
        $(".cellPanelClose").show();
        // update the content here
        // this is the item being animated
      },
    }
  );
});

// $(".cellRec").click(function () {
//   $(".cellRecPanel").animate(
//     {
//       width: "12%",
//       height: "60%",
//     },
//     {
//       duration: 1500,
//       complete: function () {
//         // update the content here
//         // this is the item being animated
//       },
//     }
//   );
// });
