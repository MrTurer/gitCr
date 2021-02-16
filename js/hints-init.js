BX.ready(function () {
  BX.bind(document, "readystatechange", function () {
    setTimeout(() => {
      let hints = new rnsHintsView();
      hints.render();
    });
  });
});
