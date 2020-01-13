$(function() {
    $('#treeTerm').tree({
      dragAndDrop: false,
      autoEscape: false
  });
});

function PopTermsWrite (term, target)
{
  var data = { term:term, index:target};
  window.opener.postMessage(data, "*");
}
