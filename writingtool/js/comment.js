function addComment(colId, comment, msgId) {
  let xhrAddComment = new XMLHttpRequest();
  xhrAddComment.open("POST", "./php/updateComments.php", true);
  xhrAddComment.onload = () => {
    if (xhrAddComment.readyState === XMLHttpRequest.DONE) {
      if (xhrAddComment.status === 200) {
        let data = xhrAddComment.response;
        var jsonAddComment = JSON.parse(xhrAddComment.responseText);
        if (jsonAddComment.result) {
          console.log("JSON", jsonAddComment);
          console.log("Comment Added Successfully!");
        } else {
          console.log("Aux not added!", jsonAddComment);
        }
        console.log(jsonAddComment);
      }
    }
  };
  xhrAddComment.setRequestHeader(
    "Content-type",
    "application/x-www-form-urlencoded"
  );
  xhrAddComment.send(
    "storyId=" +
      storyId +
      "&chapter=" +
      chapter +
      "&charCode=" +
      colId +
      "&comment=" +
      comment +
      "&msgId=" +
      msgId
  );
}
